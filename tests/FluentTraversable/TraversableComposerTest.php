<?php


namespace FluentTraversable;

use PhpOption\Option;

class TraversableComposerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function defineComposer_invokeItOnSomeArrays_itShouldProceedInput()
    {
        //given

        $composer = TraversableComposer::forArray()
            ->map(function($value){
                return strtoupper($value);
            })
            ->filter(function($value){
                return $value[0] === 'A';
            })
            ->unique()
            ->toArray()
            ;

        //when

        $actual = $composer(array('Asia', 'Apple', 'php', 'android', 'Android', 'tv', 'php'));

        //then

        $this->assertSame(array('ASIA', 'APPLE', 'ANDROID'), $actual);
    }

    /**
     * @test
     */
    public function defineComposerAsVarargs_everyArgumentShouldBeOneElementOfArray()
    {
        //given

        $composer = TraversableComposer::forVarargs()
            ->map(function($value){
                return strtoupper($value);
            })
            ->toArray();

        //when

        $actual = $composer('a', 'b', 'C');

        //then

        $this->assertSame(array('A', 'B', 'C'), $actual);
    }

    /**
     * @test
     */
    public function defineComposerWithOneSingleValueArg_acceptsOnlyOneArg()
    {
        //given

        $composer = TraversableComposer::forValue()
            ->map(function($value){
                return strtoupper($value);
            })
            ->toArray();

        //when

        $actual = $composer('a', 'b', 'C');

        //then

        $this->assertSame(array('A'), $actual);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function defineComposer_executeTwoTerminalOperations_throwException()
    {
        TraversableComposer::forArray()
            ->toArray()
            ->toMap();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function defineComposer_executeIntermediateOpAfterTerminalOp_throwException()
    {
        TraversableComposer::forArray()
            ->toArray()
            ->map(function($value){
                return strtolower($value);
            });
    }

    /**
     * @test
     */
    public function defineComposer_executeTerminalOpWithOptionAsResult()
    {
        $max = TraversableComposer::forArray();
        $max
            ->max()
            ->map(function($value){
                return 'max: '.$value;
            })
            ->orElse(Option::fromValue('max not found'))
            ->get();

        $this->assertEquals('max: 5', $max->apply(array(1, 5, 3)));
        $this->assertEquals('max not found', $max->apply(array()));
    }
}
 