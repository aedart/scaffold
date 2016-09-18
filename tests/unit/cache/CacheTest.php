<?php
use Aedart\Scaffold\Cache\CacheHelper;
use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Providers\CacheServiceProvider;
use Aedart\Scaffold\Providers\ConfigServiceProvider;
use Aedart\Scaffold\Providers\FilesystemServiceProvider;
use Illuminate\Contracts\Cache\Repository;

/**
 * CacheTest
 *
 * @group cache
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class CacheTest extends BaseUnitTest
{

    protected function _before()
    {
        parent::_before();

        $ioc = IoC::getInstance();
        $ioc->registerServiceProviders([
            FilesystemServiceProvider::class,
            ConfigServiceProvider::class,
            CacheServiceProvider::class
        ]);

        CacheHelper::setCacheDirectory($this->outputPath());
    }

    protected function _after()
    {
        //$this->emptyPath($this->outputPath());

        parent::_after();
    }

    public function outputPath()
    {
        return parent::outputPath() . 'cache/';
    }

    /**
     * Returns instance of a cache repository
     *
     * @return Repository
     */
    public function makeCache()
    {
        return CacheHelper::make();
    }

    /******************************************************************
     * Actual tests
     *****************************************************************/

    /**
     * @test
     */
    public function canObtainInstance()
    {
        $cache = $this->makeCache();
        $this->assertNotNull($cache);
    }

    /**
     * @test
     */
    public function canSetAndRetrieveValue()
    {
        $cache = $this->makeCache();

        $key = $this->faker->word;
        $value = $this->faker->uuid;

        $cache->put($key, $value, 2);

        $cachedValue = $cache->get($key);

        $this->assertSame($value, $cachedValue, 'Invalid cached value returned');
    }

    /**
     * @test
     */
    public function canFlush()
    {
        $cache = $this->makeCache();

        $key = $this->faker->word;
        $value = $this->faker->uuid;

        $cache->put($key, $value, 2);

        /** @var \Illuminate\Contracts\Cache\Store $store */
        $store = $cache->getStore(); // Not part of Laravel's Cache Repository interface!
        $store->flush();

        $cachedValue = $cache->get($key);

        $this->assertNull($cachedValue, 'Cache dir not flushed');
    }

    /**
     * @test
     */
    public function canUseCaseViaHelpers()
    {
        $key = $this->faker->word;
        $value = $this->faker->uuid;

        scaffold_cache_forever($key, $value);

        $this->assertSame($value, scaffold_cache_get($key), 'Helper methods seem not to work correctly');
    }
}