<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Cache\Tests\Adapter;

use Doctrine\DBAL\DriverManager;
use Fxp\Component\Cache\Adapter\PdoAdapter;

/**
 * Pdo Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class PdoAdapterTest extends AbstractAdapterTest
{
    /**
     * @var string
     */
    protected $dbFile;

    protected function setUp(): void
    {
        if (!\extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Extension pdo_sqlite required.');
        }

        $this->dbFile = tempnam(sys_get_temp_dir(), 'st_sqlite_cache');

        $this->adapter = new PdoAdapter(
            DriverManager::getConnection(
                [
                    'driver' => 'pdo_sqlite',
                    'path' => $this->dbFile,
                ]
        ),
            '',
            0
        );
        $this->adapter->createTable();
        $this->adapter->clear();
    }

    protected function tearDown(): void
    {
        @unlink($this->dbFile);
    }
}
