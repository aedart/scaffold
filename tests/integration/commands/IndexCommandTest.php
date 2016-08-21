<?php
use Aedart\Scaffold\Indexes\IndexBuilder;
use Codeception\Util\Debug;

/**
 * IndexCommandTest
 *
 * @group commands
 * @group indexCommand
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class IndexCommandTest extends BaseIntegrationTest
{
    protected function _after()
    {
        $this->emptyPath($this->outputPath());

        parent::_after();
    }

    public function outputPath()
    {
        return parent::outputPath() . 'index/';
    }

    public function dataPath()
    {
        return parent::dataPath() . 'index/';
    }

    public function getVendorsList()
    {
        return [
            $this->dataPath() . 'vendorA/',
            $this->dataPath() . 'vendorB/',
            $this->dataPath() . 'vendorC/',
        ];
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     */
    public function canObtainCommandHelpText()
    {
        $command = $this->getCommandFromApp('index');

        $txt = $command->getHelp();

        $this->assertNotEmpty($txt);
    }

    /**
     * @test
     */
    public function canMakeIndexFile()
    {
        $command = $this->getCommandFromApp('index');

        $commandTester = $this->makeCommandTester($command);

        $commandTester->execute([
            'command'       => $command->getName(),
            '--directories' => $this->getVendorsList(),
            '--output'      => $this->outputPath()
        ]);

        // Index file exists
        $this->assertPathsOrFilesExist(['index.json'], 'No index file generated');
    }

    /**
     * @test
     */
    public function indexFileContainsAllVendors()
    {
        $command = $this->getCommandFromApp('index');

        $commandTester = $this->makeCommandTester($command);

        $commandTester->execute([
            'command'       => $command->getName(),
            '--directories' => $this->getVendorsList(),
            '--output'      => $this->outputPath()
        ]);

        // The easiest way to ensure that all vendors are in the index,
        // is by loading the index, using the index-builder...
        $builder = new IndexBuilder();
        $builder->setDirectory($this->outputPath());
        $index = $builder->load();

        $expectedVendors = [
            'acme',
            'umbrella',
            'globex',
            'soylent',
        ];
        $vendors = $index->getVendors();

        $this->assertCount(4, $vendors, 'Incorrect amount of vendors');
        $this->assertArraySubset($vendors, $expectedVendors, 'Incorrect vendors');
    }

    /**
     * @test
     */
    public function containsAllScaffoldFilePaths()
    {
        $command = $this->getCommandFromApp('index');

        $commandTester = $this->makeCommandTester($command);

        $commandTester->execute([
            'command'       => $command->getName(),
            '--directories' => $this->getVendorsList(),
            '--output'      => $this->outputPath()
        ]);

        // Same as previous test... just load the index
        $builder = new IndexBuilder();
        $builder->setDirectory($this->outputPath());
        $index = $builder->load();

        $expectedFilePaths = [
            $this->dataPath() . 'vendorA/acme/foo/foo.scaffold.php',
            $this->dataPath() . 'vendorA/umbrella/bar/bar.scaffold.php',
            $this->dataPath() . 'vendorB/globex/baz/baz.scaffold.php',
            $this->dataPath() . 'vendorC/soylent/util/utilA.scaffold.php',
            $this->dataPath() . 'vendorC/soylent/util/utilB.scaffold.php',
        ];

        $locations = $index->all();

        $this->assertCount(count($expectedFilePaths), $locations, 'Incorrect amount of scaffolds indexed');

        foreach ($locations as $location){
            $this->assertTrue(in_array($location->getFilePath(), $expectedFilePaths), sprintf('%s" is an incorrect scaffold file path', $location->getFilePath()));
        }
    }

    /**
     * @test
     */
    public function indexHasExpirationDate()
    {
        $command = $this->getCommandFromApp('index');

        $commandTester = $this->makeCommandTester($command);

        $commandTester->execute([
            'command'       => $command->getName(),
            '--directories' => $this->getVendorsList(),
            '--output'      => $this->outputPath()
        ]);

        // Same as previous test... just load the index
        $builder = new IndexBuilder();
        $builder->setDirectory($this->outputPath());
        $index = $builder->load();

        $expiresAt = $index->expiresAt();

        $this->assertNotNull($expiresAt, 'No expiration date set');
    }

    /**
     * @test
     */
    public function doesNotReIndexIfExistingNotYetExpired()
    {
        $command = $this->getCommandFromApp('index');
        $commandTester = $this->makeCommandTester($command);

        $args = [
            'command'       => $command->getName(),
            '--directories' => $this->getVendorsList(),
            '--output'      => $this->outputPath()
        ];

        // 1st execution will build an index
        $commandTester->execute($args);

        // We load in the index, and obtain the expiration date
        $builder = new IndexBuilder();
        $builder->setDirectory($this->outputPath());
        $firstIndex = $builder->load();

        // 2nd execution should not create a new index
        $commandTester->execute($args);

        // Load in a new index
        $secondIndex = $builder->load();

        // If the expiration date is the same, then we can assume
        // that a new index file was not generated; the first one
        // was just loaded!
        $this->assertSame((string) $firstIndex->expiresAt(), (string) $secondIndex->expiresAt(), 'Should not have re-indexed!');
    }

    /**
     * @test
     */
    public function makesNewIndexIfExistingHasExpired()
    {
        $command = $this->getCommandFromApp('index');
        $commandTester = $this->makeCommandTester($command);

        $args = [
            'command'       => $command->getName(),
            '--directories' => $this->getVendorsList(),
            '--output'      => $this->outputPath(),
            '--expire'      => -5
        ];

        // 1st execution will build an expired index
        $commandTester->execute($args);

        // Load in the expired index
        $builder = new IndexBuilder();
        $builder->setDirectory($this->outputPath());
        $expiredIndex = $builder->load();

        Debug::debug('Expired date: ' . $expiredIndex->expiresAt());

        // 2nd execution should create a new index
        unset($args['--expire']);
        $commandTester->execute($args);

        // Load in a new index
        $secondIndex = $builder->load();

        Debug::debug('New expiration date: ' . $secondIndex->expiresAt());

        // The second index should contain a higher expiration date
        // than the first - thus a new index file was created.
        $result = $secondIndex->expiresAt()->gt($expiredIndex->expiresAt());
        $this->assertTrue($result, 'No new index was built');
    }

    /**
     * @test
     */
    public function makesNewIndexIfForced()
    {
        $command = $this->getCommandFromApp('index');
        $commandTester = $this->makeCommandTester($command);

        $args = [
            'command'       => $command->getName(),
            '--directories' => $this->getVendorsList(),
            '--output'      => $this->outputPath(),
        ];

        // 1st execution will build an expired index
        $commandTester->execute($args);

        // Load in the existing index
        $builder = new IndexBuilder();
        $builder->setDirectory($this->outputPath());
        $index = $builder->load();

        Debug::debug('Expired date: ' . $index->expiresAt());

        // Sleep - or the expiration date is the exact same and the
        // test becomes invalid. Sadly, there isn't other was to
        // ensure that the force option actually works
        Debug::debug('Sleeping for 1 sec.');
        sleep(1);

        // 2nd execution should create a new index - force it!
        $args['--force'] = true;
        $commandTester->execute($args);

        // Load the new index
        $secondIndex = $builder->load();

        Debug::debug('New expiration date: ' . $secondIndex->expiresAt());

        // If the second index' expiration date is higher than
        // the first, it means that a new index was made.
        $result = $secondIndex->expiresAt()->gt($index->expiresAt());
        $this->assertTrue($result, 'No new index was built');
    }

    /**
     * @test
     */
    public function skipsBadScaffolds()
    {
        $command = $this->getCommandFromApp('index');

        $commandTester = $this->makeCommandTester($command);

        $goodVendors = $this->getVendorsList();
        $badVendors = [
            $this->dataPath() . 'vendorD/',
        ];
        $vendors = array_merge($goodVendors, $badVendors);

        $commandTester->execute([
            'command'       => $command->getName(),
            '--directories' => $vendors,
            '--output'      => $this->outputPath()
        ]);

        // Same as previous test... just load the index
        $builder = new IndexBuilder();
        $builder->setDirectory($this->outputPath());
        $index = $builder->load();

        $shouldNotBeInIndex = [
            $this->dataPath() . 'vendorD/badcom/bad/bad.scaffold.php',
            $this->dataPath() . 'vendorD/badcom/bad/anotherBad.scaffold.php',
        ];

        $this->assertCount(5, $index, 'Incorrect amount of scaffolds indexed');

        $locations = $index->all();
        foreach ($locations as $location){
            $this->assertFalse(in_array($location->getFilePath(), $shouldNotBeInIndex), sprintf('%s" should NOT be indexed', $location->getFilePath()));
        }
    }
}