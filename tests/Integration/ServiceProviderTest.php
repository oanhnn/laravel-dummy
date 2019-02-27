<?php

namespace Tests\Integration;

use Illuminate\Filesystem\Filesystem;
use Laravel\Dummy\ServiceProvider;
use Tests\TestCase;

/**
 * Class ServiceProviderTest
 *
 * @package     Tests\Integration
 * @author      Oanh Nguyen <oanhnn.bk@gmail.com>
 * @license     The MIT license
 */
class ServiceProviderTest extends TestCase
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Set up before test
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->files = new Filesystem();
    }

    /**
     * Clear up after test
     */
    protected function tearDown(): void
    {
        $this->files->delete([
            $this->app->configPath('dummy.php'),
        ]);

        parent::tearDown();
    }

    /**
     * Tests config file is existed in config directory after run
     *
     * php artisan vendor:publish --provider="Laravel\\Dummy\\ServiceProvider" --tag=laravel-dummy-config
     *
     * @test
     */
    public function it_will_publish_vendor_config()
    {
        $sourceFile = dirname(dirname(__DIR__)) . '/config/dummy.php';
        $targetFile = base_path('config/dummy.php');

        $this->assertFileNotExists($targetFile);

        $this->artisan('vendor:publish', [
            '--provider' => 'Laravel\\Dummy\\ServiceProvider',
            '--tag' => 'laravel-dummy-config',
        ]);

        $this->assertFileExists($targetFile);
        $this->assertEquals(file_get_contents($sourceFile), file_get_contents($targetFile));
    }

    /**
     * Test config values are merged
     *
     * @test
     */
    public function it_provides_default_config()
    {
        $config = config('dummy');

        $this->assertTrue(is_array($config));

        // TODO: assert default config values
    }

    /**
     * Test manager is bound in application container
     *
     * @test
     */
    public function it_bound_some_services()
    {
        $classes = (new ServiceProvider($this->app))->provides();

        foreach ($classes as $class) {
            $this->assertTrue($this->app->bound($class));
            if (class_exists($class)) {
                //$this->assertInstanceOf($class, $this->app->make($class));
            }
        }
    }
}
