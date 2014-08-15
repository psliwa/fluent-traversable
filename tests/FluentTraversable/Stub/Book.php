<?php

namespace FluentTraversable\Stub;

class Book
{
    const FULL = 'full';
    const SHORT = 'short';

    public $authors = array();
    public $publisher;
    private $titles = array(
        self::FULL => null,
        self::SHORT => null,
    );
    private $coolness = true;

    function __construct($title, $authors, $publisherName, $shortTitle = null, $coolness = true)
    {
        $this->authors = $authors;
        $this->publisher = $publisherName ? new Publisher($publisherName) : null;

        $this->titles = array(
            self::FULL => $title,
            self::SHORT => $shortTitle ?: $title,
        );
        $this->coolness = (boolean) $coolness;
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function getTitle($type = self::FULL)
    {
        return $this->titles[$type];
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function isCool()
    {
        return $this->coolness;
    }
}