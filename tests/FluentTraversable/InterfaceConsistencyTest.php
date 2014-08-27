<?php


namespace FluentTraversable;


use FluentTraversable\Semantics\get;
use FluentTraversable\Semantics\is;

class InterfaceConsistencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider classProvider
     */
    public function givenTwoClasses_bothClassesShouldHaveTheSamePublicMethods($class1, $class2)
    {
        $this->assertEquals(
            $this->getMethodsFor($class1),
            $this->getMethodsFor($class2)
        );
    }


    private function getMethodsFor($className)
    {
        $class = new \ReflectionClass($className);

        $ignoredMethods = array(
            'apply'
        );

        return FluentTraversable::from($class->getMethods(\ReflectionMethod::IS_PUBLIC))
            ->map(get::value('name'))
            ->order()
            ->filter(is::not(is::in($ignoredMethods)))
            ->filter(is::false('static'))
            ->filter(function($value){
                return substr($value, 0, 2) !== '__';
            })
            ->toArray();
    }

    public function classProvider()
    {
        return array(
            array(
                'FluentTraversable\\TraversableFlow',
                'FluentTraversable\\FluentTraversable',
            ),
            array(
                'FluentTraversable\\TraversableFlow',
                'FluentTraversable\\TraversableComposer',
            ),
        );
    }
} 