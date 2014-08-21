<?php


namespace FluentTraversable\Exception;


use FluentTraversable\Internal\NonCallablePuppet;
use FluentTraversable\Puppet;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function testAssertCallback($callback, $expectedException)
    {
        if($expectedException) {
            $this->setExpectedException('FluentTraversable\\Exception\\InvalidArgumentException');
        }

        InvalidArgumentException::assertCallback($callback, __FUNCTION__);
    }

    public function dataProvider()
    {
        return array(
            array('unexistedFunction', true),
            array('strtolower', false),
            array(array($this, 'testAssertCallback'), false),
            array(array($this, 'unexistedMethod'), true),
            array(new Puppet(), false),
            array($this, true),
            array(new NonCallablePuppet(), true),
        );
    }
}
