<?php namespace Ammonkc\Basket\Facades;

use Illuminate\Support\Facades\Facade;

class BasketFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'basket';
    }
}
