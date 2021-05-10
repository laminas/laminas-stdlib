<?php

namespace LaminasTest\Stdlib;

use Laminas\Stdlib\Hydrator\ClassMethods;
use Laminas\Stdlib\Hydrator\HydratorInterface;
use Laminas\Stdlib\Hydrator\Strategy\StrategyInterface;
use PHPUnit\Framework\TestCase;

class HydratorStrategyTest extends TestCase
{
    /**
     * The hydrator that is used during testing.
     *
     * @var HydratorInterface
     */
    private $hydrator;

    public function setUp()
    {
        $this->hydrator = new ClassMethods();
    }

    public function testAddingStrategy()
    {
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);

        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());

        $this->assertAttributeCount(1, 'strategies', $this->hydrator);
    }

    public function testCheckStrategyEmpty()
    {
        $this->assertFalse($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testCheckStrategyNotEmpty()
    {
        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());

        $this->assertTrue($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testRemovingStrategy()
    {
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);

        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());
        $this->assertAttributeCount(1, 'strategies', $this->hydrator);

        $this->hydrator->removeStrategy('myStrategy');
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);
    }

    public function testRetrieveStrategy()
    {
        $strategy = new TestAsset\HydratorStrategy();
        $this->hydrator->addStrategy('myStrategy', $strategy);

        $this->assertEquals($strategy, $this->hydrator->getStrategy('myStrategy'));
    }

    public function testExtractingObjects()
    {
        $this->hydrator->addStrategy('entities', new TestAsset\HydratorStrategy());

        $entityA = new TestAsset\HydratorStrategyEntityA();
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(111, 'AAA'));
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(222, 'BBB'));

        $attributes = $this->hydrator->extract($entityA);

        $this->assertContains(111, $attributes['entities']);
        $this->assertContains(222, $attributes['entities']);
    }

    public function testHydratingObjects()
    {
        $this->hydrator->addStrategy('entities', new TestAsset\HydratorStrategy());

        $entityA = new TestAsset\HydratorStrategyEntityA();
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(111, 'AAA'));
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(222, 'BBB'));

        $attributes = $this->hydrator->extract($entityA);
        $attributes['entities'][] = 333;

        $this->hydrator->hydrate($attributes, $entityA);
        $entities = $entityA->getEntities();

        $this->assertCount(3, $entities);
    }

    /**
     * @dataProvider underscoreHandlingDataProvider
     */
    public function testWhenUsingUnderscoreSeparatedKeysHydratorStrategyIsAlwaysConsideredUnderscoreSeparatedToo($underscoreSeparatedKeys, $formFieldKey)
    {
        $hydrator = new ClassMethods($underscoreSeparatedKeys);

        $strategy = $this->createMock(StrategyInterface::class);

        $entity = new TestAsset\ClassMethodsUnderscore();
        $value = $entity->getFooBar();

        $hydrator->addStrategy($formFieldKey, $strategy);

        $strategy
            ->expects($this->once())
            ->method('extract')
            ->with($this->identicalTo($value))
            ->will($this->returnValue($value))
        ;

        $attributes = $hydrator->extract($entity);

        $strategy
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->identicalTo($value))
            ->will($this->returnValue($value))
        ;

        $hydrator->hydrate($attributes, $entity);
    }

    public function underscoreHandlingDataProvider()
    {
        return [
            [true, 'foo_bar'],
            [false, 'fooBar'],
        ];
    }

    public function testContextAwarenessExtract()
    {
        $strategy = new TestAsset\HydratorStrategyContextAware();
        $this->hydrator->addStrategy('field2', $strategy);

        $entityB = new TestAsset\HydratorStrategyEntityB('X', 'Y');
        $attributes = $this->hydrator->extract($entityB);

        $this->assertEquals($entityB, $strategy->object);
    }

    public function testContextAwarenessHydrate()
    {
        $strategy = new TestAsset\HydratorStrategyContextAware();
        $this->hydrator->addStrategy('field2', $strategy);

        $entityB = new TestAsset\HydratorStrategyEntityB('X', 'Y');
        $data = ['field1' => 'A', 'field2' => 'B'];
        $attributes = $this->hydrator->hydrate($data, $entityB);

        $this->assertEquals($data, $strategy->data);
    }
}
