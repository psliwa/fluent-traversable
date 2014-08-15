<?php


namespace FluentTraversable;

use FluentTraversable\Stub\Book;
use FluentTraversable\Stub\BookBuilder;
use FluentTraversable\Stub\Publisher;

class PuppetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function givenEmptyPuppet_doNothing()
    {
        //given

        $puppet = new Puppet();

        $book = BookBuilder::create()
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame($book, $actual);
    }

    /**
     * @test
     */
    public function givenSingleMethodCall_expectThisCallOnObject()
    {
        //given

        $puppet = Puppet::record()->getPublisher();

        $book = BookBuilder::create()
            ->publisher("O'rly")
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame($book->getPublisher(), $actual);
    }

    /**
     * @test
     */
    public function givenSinglePropertyAccess_expectAccessThisProperty()
    {
        //given

        $puppet = Puppet::record()->name;

        $publisher = new Publisher('adison');

        //when

        $actual = $puppet->play($publisher);

        //then

        $this->assertSame($publisher->name, $actual);
    }

    /**
     * @test
     */
    public function givenChainedPropertyAccessAndMethodCall_dontWorry_beHappy()
    {
        //given

        $puppet = Puppet::record()->getPublisher()->name;

        $book = BookBuilder::create()
            ->publisher('halion')
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame($book->getPublisher()->name, $actual);
    }

    /**
     * @test
     */
    public function givenMethodCallWithArgument_yesThisAlsoWorks()
    {
        //given

        $puppet = Puppet::record()->getTitle(Book::SHORT);

        $book = BookBuilder::create()
            ->title('title')
            ->shortTitle('short title')
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertSame('short title', $actual);
    }

    /**
     * @test
     */
    public function givenChainedMethodCalls_secondCallIsOnNull_nullShouldBeReturned()
    {
        //given

        $puppet = Puppet::record()->getPublisher()->getName();

        $book = BookBuilder::create()
            ->publisher(null)
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function givenMethodCall_methodReturnsFalse_falseShouldBeReturned()
    {
        //given

        $puppet = Puppet::record()->isCool();

        $book = BookBuilder::create()
            ->cool(false)
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertFalse($actual);
    }

    /**
     * @test
     */
    public function givenPropertyAndArrayAccess_yesArrayAccessShouldWorkPerfectly()
    {
        //given

        $puppet = Puppet::record()->authors[1]->getName();

        $book = BookBuilder::create()
            ->author('Eddy')
            ->author('psliwa')
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertEquals($book->authors[1]->getName(), $actual);
    }

    /**
     * @test
     */
    public function givenPropertyAndArrayAccess_offsetDoesNotExist_ooohReturnNull()
    {
        //given

        $puppet = Puppet::record()->authors[1]->getName();

        $book = BookBuilder::create()
            ->getBook();

        //when

        $actual = $puppet($book);

        //then

        $this->assertNull($actual);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function givenUnexistedMethodCall_itsToMuch_throwException()
    {
        //given

        $puppet = Puppet::record()->unexistedMethod();

        $book = BookBuilder::create()
            ->getBook();

        //when

        $puppet($book);
    }
}


