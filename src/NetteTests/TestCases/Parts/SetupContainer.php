<?php declare(strict_types = 1);

namespace Wavevision\NetteTests\TestCases\Parts;

use Nette\Configurator;
use Nette\DI\Container;
use Nette\Http\Session;
use PHPUnit\Framework\TestCase;
use Wavevision\NetteTests\Setup\ConfigureContainer;

trait SetupContainer
{

	/**
	 * @var Container
	 */
	private static $container;

	protected function setupContainer(Configurator $configurator, TestCase $testCase): void
	{
	    if (!static::$container) {
            static::$container = (new ConfigureContainer())->process($configurator, $testCase);
            // start session on setup
            /** @var Session $session */
            $session = static::$container->getService('session.session');
            if (!$session->isStarted()) {
                $session->start();
            }
        }
	}

	protected function getContainer(): Container
	{
		return static::$container;
	}
}
