<?php namespace Ammonkc\Basket;

use PhilipBrown\Basket\Basket as BasketBase;
use PhilipBrown\Basket\Product;
use Luxbus\Basket\Jurisdictions\HiState as Jurisdiction;
use Illuminate\Support\Collection;
use Closure;
use Money\Money;

class Basket
{

    /**
     * the basket
     *
     * @var
     */
    protected $basket;

    /**
     * the item storage
     *
     * @var
     */
    protected $store;

    /**
     * the event dispatcher
     *
     * @var
     */
    protected $events;

    /**
     * the cart session key
     *
     * @var
     */
    protected $instanceName;

    /**
     * the session key use as storage
     *
     * @var
     */
    protected $sessionKey;

    /**
     * the session key use to persist cart items
     *
     * @var
     */
    protected $sessionKeyCartItems;

    /**
     * our object constructor
     *
     * @param $store
     * @param $events
     * @param $instanceName
     * @param $session_key
     */
    public function __construct($instanceName, $events, $session_key, StorageInterface $store)
    {
        $this->events = $events;
        $this->store = $store;
        $this->instanceName = $instanceName;
        $this->sessionKey = $session_key;
        $this->sessionKeyCartItems = $session_key.'_cart_items';
        $this->basket = $this->setBasket();

        $this->events->fire($this->getInstanceName().'.created', array($this));
    }

    /**
     * get instance name of the cart
     *
     * @return string
     */
    public function getInstanceName()
    {
        return $this->instanceName;
    }

    /**
     * Get the products from the basket
     *
     * @return Collection
     */
    public function products()
    {
        return $this->basket->products();
    }

    /**
     * Count the items in the basket
     *
     * @return int
     */
    public function count()
    {
        return $this->basket->products()->count();
    }

    /**
     * Pick a product from the basket
     *
     * @param string $sku
     * @return Product
     */
    public function pick($sku)
    {
        return $this->basket->products()->get($sku);
    }

    /**
     * Add a product to the basket
     *
     * @param string $sku
     * @param string $name
     * @param Money $price
     * @param Closure $action
     * @return void
     */
    public function add($sku, $name, Money $price, Closure $action = null)
    {
        $basket = $this->getBasket();

        $product = new Product($sku, $name, $price, $basket->rate());

        if ($action) {
          $product->action($action);
        }

        $id = $this->genId($sku, $price);

        $this->events->fire($this->getInstanceName().'.adding', array($product, $this));

        $basket->products()->add($id, $product);

        $this->events->fire($this->getInstanceName().'.added', array($product, $this));

        $this->save($basket);
    }

    /**
     * Update a product that is already in the basket
     *
     * @param string $id
     * @param Closure $action
     * @return void
     */
    public function update($id, Closure $action)
    {
        $basket = $this->getBasket();

        $product = $basket->products()->get($id);
        $product->action($action);

        $basket->products()->put($id, $product);

        $this->save($basket);
    }

    /**
     * Remove a product from the basket
     *
     * @param string $id
     * @return void
     */
    public function remove($id)
    {
        $basket = $this->getBasket();

        $this->events->fire($this->getInstanceName().'.removing', array($id, $this));

        $basket->products->remove($id);

        $this->save($basket);

        $this->events->fire($this->getInstanceName().'.removed', array($id, $this));
    }

    /**
     * clear basket
     */
    public function clear()
    {
        $this->events->fire($this->getInstanceName().'.clearing', array($this));

        $this->store->put($this->sessionKeyCartItems, array());

        $this->events->fire($this->getInstanceName().'.cleared', array($this));
    }

    /**
     * get the basket
     *
     * @return CartCollection
     */
    public function getBasket()
    {
        return $this->store->get($this->sessionKeyCartItems);
    }

    /**
     * set the basket
     *
     * @return CartCollection
     */
    public function setBasket()
    {
        if ( ! $this->store->has($this->sessionKeyCartItems))
        {
            $basket = new BasketBase(new Jurisdiction);
            $this->save($basket);
        }

        return $this->getBasket();
    }

    /**
     * check if basket is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        $basket = $this->store->get($this->sessionKeyCartItems);

        return $basket->isEmpty();
    }

    /**
     * save the basket
     *
     * @param $basket CartCollection
     */
    protected function save($basket)
    {
        $this->store->put($this->sessionKeyCartItems, $basket);
    }

    /**
     * Generate a product ID
     *
     * @param $cart CartCollection
     */
    protected function genId($id, $price)
    {
        return md5($id.$price->getAmount());
    }

}
