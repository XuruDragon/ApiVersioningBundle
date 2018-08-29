<?php

namespace XuruDragon\ApiVersioningBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use XuruDragon\ApiVersioningBundle\Factory\ChangesFactory;

/**
 * Class ChangesFactoryTest.
 *
 * @internal
 * @coversNothing
 */
final class ChangesFactoryTest extends TestCase
{
    /**
     * @var array
     */
    protected $versions;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var ChangesFactory
     */
    protected $changesFactory;

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        $this->requestStack = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')
            ->disableOriginalConstructor()
            ->getMock();

        $this->versions = [
            '0.0.0' => 'XuruDragon\ApiVersioningBundle\Changes\Changes000',
        ];

        $this->changesFactory = new ChangesFactory($this->requestStack, $this->versions);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->requestStack = null;
        $this->versions = null;
        $this->changesFactory = null;
    }

    /**
     * Test getHistory() when version 0.0.0.
     */
    public function testGetHistoryWithVersion000()
    {
        $history = $this->changesFactory->getHistory('0.0.0');

        $this->assertCount(1, $history);
        $this->assertArrayHasKey("0.0.0", $history);
        $this->assertInstanceOf('XuruDragon\ApiVersioningBundle\Changes\Changes000', $history["0.0.0"]);
        $this->assertInstanceOf('XuruDragon\ApiVersioningBundle\Changes\AbstractChanges', $history["0.0.0"]);
        $this->assertInstanceOf('XuruDragon\ApiVersioningBundle\Changes\ChangesInterface', $history["0.0.0"]);
    }
}
