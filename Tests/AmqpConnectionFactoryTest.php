<?php

namespace Enqueue\AmqpExt\Tests;

use Enqueue\AmqpExt\AmqpConnectionFactory;
use Enqueue\AmqpExt\AmqpContext;
use Enqueue\Psr\ConnectionFactory;
use Enqueue\Test\ClassExtensionTrait;

class AmqpConnectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    use ClassExtensionTrait;

    public function testShouldImplementConnectionFactoryInterface()
    {
        $this->assertClassImplements(ConnectionFactory::class, AmqpConnectionFactory::class);
    }

    public function testCouldBeConstructedWithEmptyConfiguration()
    {
        $factory = new AmqpConnectionFactory([]);

        $this->assertAttributeEquals([
            'host' => null,
            'port' => null,
            'vhost' => null,
            'login' => null,
            'password' => null,
            'read_timeout' => null,
            'write_timeout' => null,
            'connect_timeout' => null,
            'persisted' => false,
            'lazy' => true,
        ], 'config', $factory);
    }

    public function testCouldBeConstructedWithCustomConfiguration()
    {
        $factory = new AmqpConnectionFactory(['host' => 'theCustomHost']);

        $this->assertAttributeEquals([
            'host' => 'theCustomHost',
            'port' => null,
            'vhost' => null,
            'login' => null,
            'password' => null,
            'read_timeout' => null,
            'write_timeout' => null,
            'connect_timeout' => null,
            'persisted' => false,
            'lazy' => true,
        ], 'config', $factory);
    }

    public function testShouldCreateLazyContext()
    {
        $factory = new AmqpConnectionFactory(['lazy' => true]);

        $context = $factory->createContext();

        $this->assertInstanceOf(AmqpContext::class, $context);

        $this->assertAttributeEquals(null, 'extChannel', $context);
        $this->assertTrue(is_callable($this->readAttribute($context, 'extChannelFactory')));
    }
}
