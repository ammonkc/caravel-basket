<?php namespace Ammonkc\Basket\Storage;

interface StorageInterface
{
    /**
     * Retrieve the saved state for a cart instance.
     *
     * @param string $basketKey
     *
     * @return string
     */
    public function get($key);

    /**
     * Save the state for a cart instance.
     *
     * @param string $key
     * @param string $data
     *
     * @return void
     */
    public function put($key, $data);

    /**
     * Flush the saved state for a cart instance.
     *
     * @param string $key
     *
     * @return void
     */
    public function flush($key);
}
