<?php namespace Ammonkc\Basket\Storage;

use Illuminate\Cookie\CookieJar;
use Illuminate\Session\Store as SessionStore;

class NativeSession extends IlluminateSession implements StorageInterface
{
    /**
     * Creates a new Native Session driver for Alerts.
     *
     * @param  \Illuminate\Session\Store  $session
     * @param  string  $instance
     * @param  string  $key
     * @param  array  $config
     * @return void
     */
    public function __construct(SessionStore $session, $instance = null, $key = null, $config = [])
    {
        parent::__construct($session, $instance, $key);

        // Cookie configuration
        $lifetime = isset($config['lifetime']) ? $config['lifetime'] : 120;
        $path     = isset($config['path']) ? $config['path'] : '/';
        $domain   = isset($config['domain']) ? $config['domain'] : null;
        $secure   = isset($config['secure']) ? $config['secure'] : false;
        $httpOnly = isset($config['httpOnly']) ? $config['httpOnly'] : true;

        if (isset($_COOKIE[$session->getName()])) {
            $cookieId = $_COOKIE[$session->getName()];

            $session->setId($cookieId);

            $session->setName($cookieId);
        }

        $cookie = with(new CookieJar)->make($session->getName(), $session->getId(), $lifetime, $path, $domain, $secure, $httpOnly);

        setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());

        $session->start();
    }

    /**
     * Called upon destruction of the native session handler.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->session->save();
    }
}
