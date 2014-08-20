<?php


namespace FluentTraversable;


class PredicatesTest extends \PHPUnit_Framework_TestCase
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
                Predicates::eq('name', 'Piotr'),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicates::eq('name', 'Piotra'),
                array('name' => 'Piotr'),
                false,
            ),
            array(
                Predicates::eq('Piotr'),
                'Piotr',
                true,
            ),
            array(
                Predicates::eq('Piotra'),
                'Piotr',
                false,
            ),
            array(
                Predicates::not(Predicates::eq('name', 'Piotra')),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicates::gt('age', 25),
                array('age' => 22),
                false,
            ),
            array(
                Predicates::gt('age', 25),
                array('age' => 25),
                false,
            ),
            array(
                Predicates::gt('age', 25),
                array('age' => 27),
                true,
            ),
            array(
                Predicates::gt(25),
                22,
                false,
            ),
            array(
                Predicates::gt(25),
                25,
                false,
            ),
            array(
                Predicates::gt(25),
                27,
                true,
            ),
            array(
                Predicates::lt('age', 25),
                array('age' => 22),
                true,
            ),
            array(
                Predicates::lt('age', 25),
                array('age' => 25),
                false,
            ),
            array(
                Predicates::lt('age', 25),
                array('age' => 27),
                false,
            ),
            array(
                Predicates::lt(25),
                22,
                true,
            ),
            array(
                Predicates::lt(25),
                25,
                false,
            ),
            array(
                Predicates::lt(25),
                27,
                false,
            ),
            array(
                Predicates::gte('age', 25),
                array('age' => 22),
                false,
            ),
            array(
                Predicates::gte('age', 25),
                array('age' => 25),
                true,
            ),
            array(
                Predicates::gte('age', 25),
                array('age' => 27),
                true,
            ),
            array(
                Predicates::gte(25),
                22,
                false,
            ),
            array(
                Predicates::gte(25),
                25,
                true,
            ),
            array(
                Predicates::gte(25),
                27,
                true,
            ),
            array(
                Predicates::lte('age', 25),
                array('age' => 22),
                true,
            ),
            array(
                Predicates::lte('age', 25),
                array('age' => 25),
                true,
            ),
            array(
                Predicates::lte('age', 25),
                array('age' => 27),
                false,
            ),
            array(
                Predicates::lte(25),
                22,
                true,
            ),
            array(
                Predicates::lte(25),
                25,
                true,
            ),
            array(
                Predicates::lte(25),
                27,
                false,
            ),
            array(
                Predicates::andX(Predicates::eq('name', 'Piotr'), Predicates::eq('age', 100)),
                array('name' => 'Piotr', 'age' => 100),
                true,
            ),
            array(
                Predicates::andX(Predicates::eq('name', 'Piotr'), Predicates::eq('age', 101)),
                array('name' => 'Piotr', 'age' => 100),
                false,
            ),
            array(
                Predicates::orX(Predicates::eq('name', 'Piotr'), Predicates::eq('age', 100)),
                array('name' => 'Piotr', 'age' => 100),
                true,
            ),
            array(
                Predicates::orX(Predicates::eq('name', 'Piotr'), Predicates::eq('age', 101)),
                array('name' => 'Piotr', 'age' => 100),
                true,
            ),
            array(
                Predicates::orX(Predicates::eq('name', 'Piotr2'), Predicates::eq('age', 101)),
                array('name' => 'Piotr', 'age' => 100),
                false,
            ),
            array(
                Predicates::in('age', array(10, 15, 30)),
                array('age' => 27),
                false,
            ),
            array(
                Predicates::in('age', array(10, 15, 30)),
                array('age' => 10),
                true,
            ),
            array(
                Predicates::in(array(10, 15, 30)),
                27,
                false,
            ),
            array(
                Predicates::in(array(10, 15, 30)),
                10,
                true,
            ),
            array(
                Predicates::contains('name', 'iot'),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicates::contains('name', 'Piotr'),
                array('name' => 'Piotr'),
                true,
            ),
            array(
                Predicates::contains('name', 'iotek'),
                array('name' => 'Piotr'),
                false,
            ),
            array(
                Predicates::contains('iot'),
                'Piotr',
                true,
            ),
            array(
                Predicates::contains('Piotr'),
                'Piotr',
                true,
            ),
            array(
                Predicates::contains('iotek'),
                'Piotr',
                false,
            ),
            array(
                Predicates::false('awesome'),
                array('awesome' => false),
                true,
            ),
            array(
                Predicates::false('awesome'),
                array('awesome' => 0),
                false,
            ),
            array(
                Predicates::false('awesome'),
                array('awesome' => true),
                false,
            ),
            array(
                Predicates::false(),
                false,
                true,
            ),
            array(
                Predicates::false(),
                0,
                false,
            ),
            array(
                Predicates::false(),
                true,
                false,
            ),
            array(
                Predicates::true('awesome'),
                array('awesome' => false),
                false,
            ),
            array(
                Predicates::true('awesome'),
                array('awesome' => 1),
                false,
            ),
            array(
                Predicates::true('awesome'),
                array('awesome' => true),
                true,
            ),
            array(
                Predicates::true(),
                false,
                false,
            ),
            array(
                Predicates::true(),
                1,
                false,
            ),
            array(
                Predicates::true(),
                true,
                true,
            ),
            array(
                Predicates::identical('age', 25),
                array('age' => 25),
                true,
            ),
            array(
                Predicates::identical(25),
                '25',
                false,
            ),
            array(
                Predicates::identical(25),
                25,
                true,
            ),
            array(
                Predicates::identical('age', 25),
                array('age' => '25'),
                false,
            ),
            array(
                Predicates::null('age'),
                array('age' => null),
                true,
            ),
            array(
                Predicates::null('age'),
                array('age' => 0),
                false,
            ),
            array(
                Predicates::null(),
                null,
                true,
            ),
            array(
                Predicates::null(),
                0,
                false,
            ),
            array(
                Predicates::notNull('age'),
                array('age' => null),
                false,
            ),
            array(
                Predicates::notNull('age'),
                array('age' => 0),
                true,
            ),
            array(
                Predicates::notNull(),
                null,
                false,
            ),
            array(
                Predicates::notNull(),
                0,
                true,
            ),
        );
    }
}
 