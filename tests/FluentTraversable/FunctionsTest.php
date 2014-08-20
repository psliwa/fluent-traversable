<?php


namespace FluentTraversable;


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
        );
    }
}
 