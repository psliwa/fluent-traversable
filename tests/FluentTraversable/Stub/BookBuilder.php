<?php


namespace FluentTraversable\Stub;

class BookBuilder
{
    private $fullTitle;
    private $shortTitle;
    private $authors = array();
    private $publisherName;
    private $cool = true;

    public static function create()
    {
        return new self();
    }

    public function title($title)
    {
        $this->fullTitle = $title;
        return $this;
    }

    public function shortTitle($title)
    {
        $this->shortTitle = $title;
        return $this;
    }

    public function author($name)
    {
        $this->authors[] = new Author($name);
        return $this;
    }

    public function publisher($name)
    {
        $this->publisherName = $name;

        return $this;
    }

    public function cool($cool)
    {
        $this->cool = $cool;

        return $this;
    }

    public function getBook()
    {
        return new Book($this->fullTitle, $this->authors, $this->publisherName, $this->shortTitle, $this->cool);
    }
} 