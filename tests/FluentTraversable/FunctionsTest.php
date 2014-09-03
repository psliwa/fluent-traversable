<?php


namespace FluentTraversable;


use PhpOption\None;
use PhpOption\Some;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider functionsProvider
     */
    public function testFunctions($func, $argument, $expectedValue)
    {
        $this->assertEquals($expectedValue, $func($argument));
    }

    public function functionsProvider()
    {
        return array(
            //it uses PropertyGetter, so more detailed tests are unnecessary
            array(Functions::getPropertyValue('name'), array('name' => 'Piotr'), 'Piotr'),
            array(Functions::getPropertyOptionValue('name'), array('name' => 'Piotr'), Some::create('Piotr')),
            array(Functions::getPropertyOptionValue('unexisted'), array('name' => 'Piotr'), None::create()),
        );
    }

    public function testOneArgFunction()
    {
        $argsNum = null;

        $innerFunction = function() use(&$argsNum){
            $argsNum = func_num_args();
        };

        $outerFunction = Functions::oneArgumentFunction($innerFunction);

        $outerFunction(1, 2, 3, 4, 5);

        $this->assertSame(1, $argsNum);
    }

    public function testCount()
    {
        $count = Functions::count('values');

        $this->assertEquals(3, $count(array('values' => array(1, 2, 3))));
    }
}
 