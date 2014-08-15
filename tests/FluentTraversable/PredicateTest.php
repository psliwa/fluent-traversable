<?php


namespace FluentTraversable;


class PredicateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function testPredicates($predicate, $object, $expectedValue)
    {
        $this->assertEquals($expectedValue, $predicate($object));
    }

    public function dataProvider()
    {
        return array(
            array(
                Predicate::eq('name', 'Piotr'),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicate::eq('name', 'Piotra'),
                array('name' => 'Piotr'),
                false,
            ),
            array(
                Predicate::not(Predicate::eq('name', 'Piotra')),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicate::gt('age', 25),
                array('age' => 22),
                false,
            ),
            array(
                Predicate::gt('age', 25),
                array('age' => 25),
                false,
            ),
            array(
                Predicate::gt('age', 25),
                array('age' => 27),
                true,
            ),
            array(
                Predicate::lt('age', 25),
                array('age' => 22),
                true,
            ),
            array(
                Predicate::lt('age', 25),
                array('age' => 25),
                false,
            ),
            array(
                Predicate::lt('age', 25),
                array('age' => 27),
                false,
            ),
            array(
                Predicate::gte('age', 25),
                array('age' => 22),
                false,
            ),
            array(
                Predicate::gte('age', 25),
                array('age' => 25),
                true,
            ),
            array(
                Predicate::gte('age', 25),
                array('age' => 27),
                true,
            ),
            array(
                Predicate::lte('age', 25),
                array('age' => 22),
                true,
            ),
            array(
                Predicate::lte('age', 25),
                array('age' => 25),
                true,
            ),
            array(
                Predicate::lte('age', 25),
                array('age' => 27),
                false,
            ),
            array(
                Predicate::andX(Predicate::eq('name', 'Piotr'), Predicate::eq('age', 100)),
                array('name' => 'Piotr', 'age' => 100),
                true,
            ),
            array(
                Predicate::andX(Predicate::eq('name', 'Piotr'), Predicate::eq('age', 101)),
                array('name' => 'Piotr', 'age' => 100),
                false,
            ),
            array(
                Predicate::orX(Predicate::eq('name', 'Piotr'), Predicate::eq('age', 100)),
                array('name' => 'Piotr', 'age' => 100),
                true,
            ),
            array(
                Predicate::orX(Predicate::eq('name', 'Piotr'), Predicate::eq('age', 101)),
                array('name' => 'Piotr', 'age' => 100),
                true,
            ),
            array(
                Predicate::orX(Predicate::eq('name', 'Piotr2'), Predicate::eq('age', 101)),
                array('name' => 'Piotr', 'age' => 100),
                false,
            ),
            array(
                Predicate::in('age', array(10, 15, 30)),
                array('age' => 27),
                false,
            ),
            array(
                Predicate::in('age', array(10, 15, 30)),
                array('age' => 10),
                true,
            ),
            array(
                Predicate::contains('name', 'iot'),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicate::contains('name', 'Piotr'),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicate::contains('name', 'iotek'),
                array('name' => 'Piotr'),
                false,
            ),
        );
    }
}
 