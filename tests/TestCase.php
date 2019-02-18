<?php

namespace Tests;

use Laravel\Dummy\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Concerns\WithFakeDateTime;

/**
 * Class TestCase
 *
 * @package     Tests
 * @author      Oanh Nguyen <oanhnn.bk@gmail.com>
 * @license     The MIT license
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[WithFakeDateTime::class])) {
            $this->setUpFakeDateTime();
        }

        return $uses;
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Define your environment setup.
    }
}
