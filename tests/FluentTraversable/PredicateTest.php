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
                Predicate::eq('Piotr'),
                'Piotr',
                true,
            ),
            array(
                Predicate::eq('Piotra'),
                'Piotr',
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
                Predicate::gt(25),
                22,
                false,
            ),
            array(
                Predicate::gt(25),
                25,
                false,
            ),
            array(
                Predicate::gt(25),
                27,
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
                Predicate::lt(25),
                22,
                true,
            ),
            array(
                Predicate::lt(25),
                25,
                false,
            ),
            array(
                Predicate::lt(25),
                27,
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
                Predicate::gte(25),
                22,
                false,
            ),
            array(
                Predicate::gte(25),
                25,
                true,
            ),
            array(
                Predicate::gte(25),
                27,
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
                Predicate::lte(25),
                22,
                true,
            ),
            array(
                Predicate::lte(25),
                25,
                true,
            ),
            array(
                Predicate::lte(25),
                27,
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
                Predicate::in(array(10, 15, 30)),
                27,
                false,
            ),
            array(
                Predicate::in(array(10, 15, 30)),
                10,
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
            array(
                Predicate::contains('iot'),
                'Piotr',
                true,
            ),
            array(
                Predicate::contains('Piotr'),
                'Piotr',
                true,
            ),
            array(
                Predicate::contains('iotek'),
                'Piotr',
                false,
            ),
            array(
                Predicate::false('awesome'),
                array('awesome' => false),
                true,
            ),
            array(
                Predicate::false('awesome'),
                array('awesome' => 0),
                false,
            ),
            array(
                Predicate::false('awesome'),
                array('awesome' => true),
                false,
            ),
            array(
                Predicate::false(),
                false,
                true,
            ),
            array(
                Predicate::false(),
                0,
                false,
            ),
            array(
                Predicate::false(),
                true,
                false,
            ),
            array(
                Predicate::true('awesome'),
                array('awesome' => false),
                false,
            ),
            array(
                Predicate::true('awesome'),
                array('awesome' => 1),
                false,
            ),
            array(
                Predicate::true('awesome'),
                array('awesome' => true),
                true,
            ),
            array(
                Predicate::true(),
                false,
                false,
            ),
            array(
                Predicate::true(),
                1,
                false,
            ),
            array(
                Predicate::true(),
                true,
                true,
            ),
            array(
                Predicate::identical('age', 25),
                array('age' => 25),
                true,
            ),
            array(
                Predicate::identical(25),
                '25',
                false,
            ),
            array(
                Predicate::identical(25),
                25,
                true,
            ),
            array(
                Predicate::identical('age', 25),
                array('age' => '25'),
                false,
            ),
            array(
                Predicate::null('age'),
                array('age' => null),
                true,
            ),
            array(
                Predicate::null('age'),
                array('age' => 0),
                false,
            ),
            array(
                Predicate::null(),
                null,
                true,
            ),
            array(
                Predicate::null(),
                0,
                false,
            ),
            array(
                Predicate::notNull('age'),
                array('age' => null),
                false,
            ),
            array(
                Predicate::notNull('age'),
                array('age' => 0),
                true,
            ),
            array(
                Predicate::notNull(),
                null,
                false,
            ),
            array(
                Predicate::notNull(),
                0,
                true,
            ),
        );
    }
}
 