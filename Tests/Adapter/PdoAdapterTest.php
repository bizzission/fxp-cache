<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache\Tests\Adapter;

use Doctrine\DBAL\DriverManager;
use Sonatra\Component\Cache\Adapter\PdoAdapter;

/**
 * Pdo Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PdoAdapterTest extends AbstractAdapterTest
{
    /**
     * @var string
     */
    protected $dbFile;

    protected function setUp()
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Extension pdo_sqlite required.');
        }

        $this->dbFile = tempnam(sys_get_temp_dir(), 'st_sqlite_cache');

        $this->adapter = new PdoAdapter(DriverManager::getConnection(
            array(
                'driver' => 'pdo_sqlite',
                'path' => $this->dbFile,
            )),
            '',
            0);
        $this->adapter->createTable();
        $this->adapter->clear();
    }

    protected function tearDown()
    {
        @unlink($this->dbFile);
    }
}
