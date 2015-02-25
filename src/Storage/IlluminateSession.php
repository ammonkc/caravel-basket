<?php namespace Ammonkc\Basket\Storage;

use Illuminate\Session\Store as SessionStore;

class IlluminateSession implements StorageInterface
{
    /**
     * Session store object.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Creates a new Illuminate based Session driver for Alerts.
     *
     * @param  \Illuminate\Session\Store  $session
     * @param  string  $key
     * @return void
     */
    public function __construct(SessionStore $session)
    {
        $this->session = $session;
    }

    /**
     * Retrieve the saved state for a cart instance.
     *
     * @param string $basketKey
     *
     * @return string
     */
    public function get($key, $default = null)
    {
        return $this->session->get($key, $default);
    }

    /**
     * Save the state for a cart instance.
     *
     * @param string $key
     * @param string $data
     *
     * @return void
     */
    public function put($key, $data)
    {
        return $this->session->put($key, $data);
    }

    /**
     * Flush the saved state for a cart instance.
     *
     * @param string $key
     *
     * @return void
     */
    public function flush($key)
    {
        return $this->session->flush($key);
    }
}
