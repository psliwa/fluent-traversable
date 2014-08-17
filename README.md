**IMPORTANT**: This library is not stable yet, API can still change.

# Fluent Traversable

**FluentTraversable** is very small utility library that adds a bit of **functional programming** to php, especially for arrays 
and collections. This library is inspired by java8 stream framework, guava FluentIterable and Scala functional features.
To fully enjoy of this library, you should be familiar with basic patterns of functional programming.

# ToC

1. [Installation](#installation)
1. [FluentTraversable](#fluent)
1. [TraversableShaper](#shaper)
1. [Puppet](#puppet)
1. [Contribution](#contri)
1. [License](#license)

<a name="installation"></a>
## Installation

Installation is very easy (thanks to composer ;)):

*(add to require section in your composer.json file)*

``
    "ps/fluent-traversable": "*"
``

You should choose last stable version, wildcard char ("*") is only an example.

<a name="fluent"></a>
## FluentTraversable

Thanks to `FluentTraversable` class you can operate on arrays and collection in declarative and readable way. Ok, there is
an example.

I want to get emails of male authors of books that have been released before 2007.

```php

    $books = array(/* some books */);
    
    $emails = array();
    
    foreach($books as $book) {
        if($book->getReleaseDate() < 2007) {
            $authors = $book->getAuthors();
            foreach($authors as $author) {
                if($author->getSex() == 'male' && $author->getEmail()) {
                    $emails[] = $author->getEmail();
                }
            }
        }
    }
```

Ok, nested loops, nested if statements... It doesn't look good. If I use php array_map and array_filter functions, result
wouldn't be better, would be even worst, so I omit this example.

The same code using `FluentTraversable`:

```php

    //some imports
    use FluentTraversable\FluentTraversable;
    use FluentTraversable\Semantics\is;
    use FluentTraversable\Semantics\the;

    $books = array(/* some books */);
    
    $emails = FluentTraversable::from($books)
        ->filter(is::lt('releaseDate', 2007))
        ->flatMap(the::object()->getAuthors())
        ->filter(is::eq('sex', 'male'))
        ->map(the::object()->getEmail())
        ->filter(is::notNull())
        ->toArray();       

```

There are no loops, if statements, it looks straightforward, flow is clear and explicit (when you now what `filter`, 
`flatMap`, `map` etc methods are doing - as I said before the basics functional programming patters are needed ;)).

`is` class (alias to `Predicate` class) is factory for closures that have one argument and evaluate it to boolean 
value. There are `lt`, `gt`, `eq`, `not` etc methods. Closures in php are very lengthy, you have to write `function` 
keyword, curly braces, return statement, semicolon etc - a lot of syntax noise. Closure is multiline (yes, I now it can 
be written in single line, but it would be unreadable), so it is no very compact. To handle simple predicate cases, you 
might use `Predicate` class (or `is` class alias - it will add some semantics to your code), but you haven't to ;)

`the::object()->getAuthors()` also is a shortcut for closures, `the::object()` is as same as argument in the closure.
`the::object()->getEmail()` is semantic equivalent to closure:

```php

    function($object){
        return $object->getEmail();
    }

```

What is `the::object()`? I will tell you in [Puppet](#puppet) section ;)

`FluentTraversable` has a lot of useful methods: `map`, `flatMap`, `filter`, `unique`, `group`, `order`, `allMatch`,
`anyMatch`, `noneMatch`, `firstMatch`, `max`, `min`, `reduce`, `toArray`, `toMap` and more. All that methods belong to
one of two groups: intermediate or terminate operations. Intermediate operation does some work on input array, modifies it
and returns `FluentTraversable` object, so you can chain another operation. Terminate operation does some calculation on
each element of array and returns result of this calculation. For example `size` operation returns integer that is length
of input array, so you can not chain operation anymore.

Example:

```php

    FluentTraversable::from(array())
        ->filter(...)//intermediate operation, so I can chain
        ->map(...)//intermediate operation, so I can chain
        ->size()//terminate operation, I cannot chain - it returns integer

```

There are few terminal operations that returns `Option` value (if you don't know what is Option or Optional value pattern,
follow this links: [php-option][2], [Optional explanation in Java][1]). For example `firstMatch` method could find nothing,
so instead return null or adding second optional argument to provide default value, `Option` object is returned. `Option`
object is a wrapper for value, it can has value, but it haven't to. You should threat `Option` as collection with 0 or 1
value. `Option` class provides few familiar methods to `FluentTraversable`, for example `map` and `filter`. You can get 
value from `Option` by `getOrElse` method:

Example:

```php

    FluentTraversable::from($books)
        ->firstMatch(is::eq('author.name', 'Stephen King'))
        //there is Option instance, you can transform value (thanks to map) if this value exists
        ->map(function($book){
            return 'Found book: '.$book->getTitle();
        })
        //provide default value if book wasn't found
        ->orElse(Option::fromValue('Not found any book...'))
        //print result to stdout thanks to Option::map method
        ->map('printf')
        //or you can call "->get()" and assign to variable, it is safe because you provided default value by "orElse"
        ;

```

If Stephen King's book was found, "Found book: TITLE" will be printed, otherwise "Not found any book...".

Properly used, option is very powerful and it integrates with `FluentTraversable` perfectly.


<a name="shaper"></a>
## TraversableShaper

`TraversableShaper` is a tool to compose complex operations on arrays. You can define one complex operation thanks to
shaper, and apply it multiple times on any array. `TraversableShaper` has very similar interface to `FluentTraversable`
(those two classes implements the same interface: `TraversableFlow`).

There is an example:

```php

    $maxEvenPrinter = TraversableShaper::create();

    //very important is, to not chain directly from `create()` method, first you should assign created object
    //to variable, and then using reference to object you can compose your function

    $maxEvenPrinter
        ->filter(function($number){
            //only even numbers
            return $number % 2 === 0;
        })
        ->max()
        //"max" (as same as firstMatch) returns Option, because there is possibility given array is empty
        ->map(function($value){
            return 'max even number: '.$value;
        })
        ->orElse(Option::fromValue('max even number not found'))
        ->map('printf');

```

Ok, we have `$maxEvenPrinter` object, what's next?

```php

    $maxEvenPrinter(array(1, 3, 5, 2, 4));
    //output will be: "max even number: 4"
    
    $maxEvenPrinter(array(1, 3, 5));
    //output will be: "max even number not found"

```

As I said, `TraversableShaper` has almost the same methods as `FluentTraversable`. The difference between those two classes
is that, `FluentTraversable` needs input array when object is created and it should be used once, `TraversableShaper`
doesn't need array when object is created and can be invoked multiple times with different input arrays. Internally
`TraversableShaper` uses `FluentTraversable` instance ;) You should threat `TraversableShaper` as tool to compose functions.

<a name="puppet"></a>

## Puppet

Puppet is a very small (less than 100 lines of code) class, but it is also very powerful. We have used Puppet already in
[FluentTraversable](#fluent) section. What is a Puppet? Thanks to Puppet you can "record" some behaviour and execute
this behaviour multiple times on various objects.

Example:

```php

    $book = ...;
    $puppet = Puppet::record()->getPublisher()->getName();
    
    echo $puppet($book);//$book->getPublisher()->getName() will be invoked

```

`Puppet` supports property access, array access and method calls with arguments. It was created to simplify `map` and
`flatMap` operations in `FluentTraversable` and is also used internally by `TraversableShaper`, but maybe you will find 
another use case for `Puppet`.

Puppet has two factory methods: `record` and `object` - those methods are the same, `object` method was created only for 
semantic purpose.

`the` class is alias to `Puppet`, it only adds semantic meaning to using `Puppet` in `FluentTraversable` context:
`->map(the::object()->getName())` is much more readable than `->map(Puppet::record()->getName())`.

Puppet was inspired by
[Extractor class](https://github.com/letsdrink/ouzo-utils/blob/master/src/Ouzo/Utilities/Extractor.php) of 
[ouzo-utils](https://github.com/letsdrink/ouzo-utils) library. `FluentTraversable` doesn't use `Extractor` class, because 
in this library is a lot of stuff that would not be used by `FluentTraversable`.

<a name="contri"></a>
## Contribution

Any suggestions, PR, bug reports etc. are welcome ;)

<a name="license"></a>
## License

**MIT** - details in [LICENSE](LICENSE) file

[1]: http://www.nurkiewicz.com/2013/08/optional-in-java-8-cheat-sheet.html
[2]: https://github.com/schmittjoh/php-option