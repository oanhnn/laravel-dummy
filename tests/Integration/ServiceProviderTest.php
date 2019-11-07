<?php

namespace Tests\Integration;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Laravel\Dummy\ServiceProvider;
use Tests\Concerns\WithFakeDateTime;
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
     * Tests config file is existed in config directory after run
     *
     * php artisan vendor:publish --provider="Laravel\\Dummy\\ServiceProvider" --tag=laravel-dummy-config
     *
     * @return void
     */
    public function testItShouldPublishVendorConfig()
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
     * Test migration class will be created after run
     *
     * php artisan vendor:publish --provider="Laravel\\Dummy\\ServiceProvider" --tag=laravel-dummy-migration
     *
     * @return void
     */
    public function testItShouldPublishVendorMigration()
    {
        $sourceFile = dirname(dirname(__DIR__)) . '/database/migrations/create_dummy_table.stub';
        $targetFile = database_path('migrations/2019_11_07_002200_create_dummy_table.php');

        $this->assertFileNotExists($targetFile);

        $this->artisan('vendor:publish', [
            '--provider' => 'Laravel\\Dummy\\ServiceProvider',
            '--tag' => 'laravel-dummy-migration',
        ]);

        $this->assertFileExists($targetFile);
        $this->assertEquals(file_get_contents($sourceFile), file_get_contents($targetFile));
    }

    /**
     * Test config values are merged
     *
     * @return void
     */
    public function testItShouldProvidesDefaultConfig()
    {
        $config = config('dummy');

        $this->assertTrue(is_array($config));

        // TODO: assert default config values
    }

    /**
     * Test manager is bound in application container
     *
     * @return void
     */
    public function testItShouldBoundSomeServices()
    {
        $classes = (new ServiceProvider($this->app))->provides();

        foreach ($classes as $class) {
            $this->assertTrue($this->app->bound($class));
            if (class_exists($class)) {
                //$this->assertInstanceOf($class, $this->app->make($class));
            }
        }
    }

    /**
     * Set up before test
     */
    protected function setUp(): void
    {
        $now = Carbon::create(2019, 11, 7, 0, 22, 0);
        Carbon::setTestNow($now);

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
            $this->app->databasePath('migrations/2019_11_07_002200_create_dummy_table.php')
        ]);

        parent::tearDown();
    }
}
