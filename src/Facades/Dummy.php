<?php

namespace Laravel\Dummy\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Dummy class
 *
 * @package     Laravel\Dummy\Facades
 * @author      Oanh Nguyen <oanhnn.bk@gmail.com>
 * @license     The MIT license
 */
class Dummy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return \Laravel\Dummy\Dummy::class;
    }
}
