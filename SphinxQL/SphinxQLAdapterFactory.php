<?php
/**
 * Sphinx Search Bundle
 *
 * @link        https://github.com/ripaclub/sphinxsearch
 * @copyright   Copyright (c) 2015 RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace SphinxSearch\SphinxSearchBundle\SphinxQL;

use SphinxSearch\Db\Adapter\Exception\UnsupportedDriverException;
use SphinxSearch\Db\Adapter\Platform\SphinxQL;
use Zend\Db\Adapter\Adapter as ZendDbAdapter;
use Zend\Db\Adapter\Driver\Mysqli\Mysqli as ZendMysqliDriver;
use Zend\Db\Adapter\Driver\Pdo\Pdo as ZendPdoDriver;
use SphinxSearch\Db\Adapter\Driver\Pdo\Statement as PdoStatement;

class SphinxQLAdapterFactory
{

    protected $driver;

    protected $hostName;

    protected $port;

    protected $charset;

    public function __construct($driver, $hostName, $port, $charset)
    {
        $this->driver = $driver;
        $this->hostName = $hostName;
        $this->port = $port;
        $this->charset = $charset;
    }

    public function createSphinxQLAdapter()
    {
        $sphinxQL = new SphinxQL();

        $adapter = new ZendDbAdapter($this->getConfigArray(), $sphinxQL);
        $driver = $adapter->getDriver();

        if ($driver instanceof ZendPdoDriver &&
            $driver->getDatabasePlatformName(ZendPdoDriver::NAME_FORMAT_CAMELCASE) == 'Mysql'
        ) {
            $driver->registerStatementPrototype(new PdoStatement());
        } elseif (!$driver instanceof ZendMysqliDriver) {
            throw new UnsupportedDriverException(
                sprintf(
                    '%s not supported. Use Zend\Db\Adapter\Driver\Pdo\Pdo or Zend\Db\Adapter\Driver\Mysqli\Mysqli',
                    get_class($driver)
                )
            );
        }
        $sphinxQL->setDriver($adapter->getDriver());
        return $adapter;
    }

    protected function getConfigArray()
    {
        return [
            'driver' => $this->driver,
            'hostname' => $this->hostName,
            'port' => $this->port,
            'charset' => $this->charset
        ];
    }
}
