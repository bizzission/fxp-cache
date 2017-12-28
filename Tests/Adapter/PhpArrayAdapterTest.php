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

use Fxp\Component\Cache\Adapter\ArrayAdapter;
use Fxp\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * PHP Array Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PhpArrayAdapterTest extends AbstractAdapterTest
{
    /**
     * @var string
     */
    private static $file;

    /**
     * @var PhpArrayAdapter
     */
    protected $adapter;

    /**
     * @var Filesystem
     */
    protected $fs;

    public static function setupBeforeClass()
    {
        self::$file = sys_get_temp_dir().'/symfony-cache/php-array-adapter-test.php';
    }

    protected function setUp()
    {
        $this->fs = new Filesystem();
        $this->adapter = new PhpArrayAdapter(self::$file, new ArrayAdapter());
        $this->adapter->clear();

        $this->fs->remove(sys_get_temp_dir().'/symfony-cache');
    }

    protected function tearDown()
    {
        $this->fs->remove(sys_get_temp_dir().'/symfony-cache');
    }

    public function testInitialization()
    {
        $this->adapter = new PhpArrayAdapter(self::$file, new ArrayAdapter());
        $this->adapter->clearByPrefix('foo');

        $this->assertFalse($this->adapter->hasItem('foo_bar'));
    }

    public function testWarmUp()
    {
        $values = array(
            self::PREFIX_1.'foo' => 'bar1',
            self::PREFIX_2.'foo' => 'bar2',
        );

        $this->assertFileNotExists(self::$file);
        $this->adapter->warmUp($values);
        $this->assertFileExists(self::$file);

        $item1 = $this->adapter->getItem(self::PREFIX_1.'foo');
        $this->assertTrue($item1->isHit());
        $this->assertSame('bar1', $item1->get());

        $item2 = $this->adapter->getItem(self::PREFIX_2.'foo');
        $this->assertTrue($item2->isHit());
        $this->assertSame('bar2', $item2->get());

        $res = $this->adapter->clearByPrefix(self::PREFIX_1);
        $this->assertTrue($res);

        $this->assertFalse($this->adapter->hasItem(self::PREFIX_1.'foo'));
        $this->assertTrue($this->adapter->hasItem(self::PREFIX_2.'foo'));
    }
}
