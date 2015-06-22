<?php
/**
 * Sphinx Search Bundle
 *
 * @link        https://github.com/ripaclub/sphinxsearch
 * @copyright   Copyright (c) 2015 RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace SphinxSearch\SphinxSearchBundle\Tests\Sphinx;

use SphinxSearch\SphinxSearchBundle\SphinxQL\SphinxQLAdapterFactory;

class SphinxQLAdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider configurationProvider
     */
    public function testSphinxQLAdapterCreation($driver, $hostName, $port, $charset, $driverInstance)
    {
        $factory = new SphinxQLAdapterFactory($driver, $hostName, $port, $charset);
        $adapter = $factory->createSphinxQLAdapter();

        $this->assertInstanceOf('\Zend\Db\Adapter\Adapter', $adapter);
        $this->assertInstanceOf($driverInstance, $adapter->getDriver());
    }

    /**
     * @expectedException \SphinxSearch\Db\Adapter\Exception\UnsupportedDriverException
     */
    public function testSphinxQLAdapterCreationWithInvalidDriver()
    {
        $factory = new SphinxQLAdapterFactory('pdo', '127.0.0.1', 9306, 'UTF8');
        $factory->createSphinxQLAdapter();
    }

    public function configurationProvider()
    {
        return [
            ['pdo_mysql', '127.0.0.1', 9306, 'UTF8', 'Zend\Db\Adapter\Driver\Pdo\Pdo'],
            ['mysqli', '127.0.0.1', 9306, 'UTF8', 'Zend\Db\Adapter\Driver\Mysqli\Mysqli'],
        ];
    }
}
