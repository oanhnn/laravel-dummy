<?php

namespace Laravel\Dummy\Tests\Integration;

use Illuminate\Filesystem\Filesystem;
use Laravel\Dummy\ServiceProvider;
use Laravel\Dummy\Tests\NonPublicAccessibleTrait;
use Laravel\Dummy\Tests\TestCase;

/**
 * Class ServiceProviderTest
 *
 * @package     Laravel\Dummy\Tests\Integration
 * @author      Oanh Nguyen <oanhnn.bk@gmail.com>
 * @license     The MIT license
 */
class ServiceProviderTest extends TestCase
{
    use NonPublicAccessibleTrait;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Set up before test
     */
    protected function setUp()
    {
        parent::setUp();

        $this->files = new Filesystem();
    }

    /**
     * Clear up after test
     */
    protected function tearDown()
    {
        $this->files->delete([
            $this->app->configPath('dummy.php'),
        ]);

        parent::tearDown();
    }

    /**
     * Test file influxdb.php is existed in config directory after run
     *
     * php artisan vendor:publish --provider="Laravel\\Dummy\\ServiceProvider" --tag=config
     */
    public function testPublishVendorConfig()
    {
        $sourceFile = dirname(dirname(__DIR__)) . '/config/dummy.php';
        $targetFile = config_path('dummy.php');

        $this->assertFileNotExists($targetFile);

        $this->artisan('vendor:publish', [
            '--provider' => 'Laravel\\Dummy\\ServiceProvider',
            '--tag' => 'config',
        ]);

        $this->assertFileExists($targetFile);
        $this->assertEquals(file_get_contents($sourceFile), file_get_contents($targetFile));
    }

    /**
     * Test config values are merged
     */
    public function testDefaultConfigValues()
    {
        $config = config('dummy');

        $this->assertIsArray($config);

        // TODO: assert default config values
    }

    /**
     * Test manager is bound in application container
     */
    public function testBoundInstances()
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
