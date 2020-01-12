<?php declare(strict_types = 1);

namespace Wavevision\NetteTests\TestCases;

use PHPUnit\DbUnit\Operation\Factory;
use PHPUnit\DbUnit\TestCase;
use Wavevision\NetteTests\Configuration;
use Wavevision\NetteTests\TestCases\Parts\SetupContainer;

abstract class DIContainerTestCase extends TestCase
{

	use SetupContainer;

	private $connection;

	protected function setUp(): void
	{
		parent::setUp();
		$this->setupContainer(Configuration::createConfigurator(), $this);
	}

	protected function getConnection()
    {
        if (!isset($_ENV['NETTE__DB_HOST']) || !isset($_ENV['NETTE__DB_NAME']) || !isset($_ENV['NETTE__DB_USERNAME'])
            || !isset($_ENV['NETTE__DB_PASSWORD'])) {
            throw new \Exception('Missing database env variables');
        }

        if (!$this->connection) {
            $dsn = 'mysql:dbname='.$_ENV['NETTE__DB_NAME'].';host='.$_ENV['NETTE__DB_HOST'];
            $pdo = new \PDO($dsn, $_ENV['NETTE__DB_USERNAME'], $_ENV['NETTE__DB_PASSWORD']);

            $this->connection = $this->createDefaultDBConnection($pdo);
        }


        return $this->connection;
    }

    protected function getSetUpOperation()
    {
        return new \PHPUnit\DbUnit\Operation\Composite([
            new \PHPUnit\DbUnit\Operation\DeleteAll(),
            new \PHPUnit\DbUnit\Operation\Insert(),
        ]);
    }

    protected function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }

}
