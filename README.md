# Fluent Traversable

[![Build Status](https://travis-ci.org/psliwa/fluent-traversable.svg?branch=master)](https://travis-ci.org/psliwa/fluent-traversable)

**FluentTraversable** is very small utility library that adds a bit of **functional programming** to php, especially for arrays 
and collections. This library is inspired by java8 stream framework, guava FluentIterable and Scala functional features.
To fully enjoy of this library, you should be familiar with basic patterns of functional programming.

# ToC

1. [Installation](#installation)
1. [FluentTraversable](#fluent)
1. [TraversableComposer](#composer)
    1. [TraversableComposer as predicate / mapping function](#composerAsPredicate)
1. [Predicates](#predicates)
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

Thanks to `FluentTraversable` class you can operate on arrays and collection in declarative and readable way. There is a
simple example.

*We want to get emails of male authors of books that have been released before 2007.*

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
    use FluentTraversable\Semantics\get;

    $books = array(/* some books */);
    
    $emails = FluentTraversable::from($books)
        ->filter(is::lt('releaseDate', 2007))
        ->flatMap(get::value('authors'))
        ->filter(is::eq('sex', 'male'))
        ->map(get::value('email')))
        ->filter(is::notNull())
        ->toArray();       

```

> **IMPORTANT**
>
> In examples `toMap` and `toArray` functions are used to convert elements to array. The difference between those
> two functions is `toArray` **re-indexes elements**, `toMap` **preserves indexes**. You should use `toArray` method
> when indexes in your use case are not important, otherwise you should use `toMap`.

There are no loops, if statements, it looks straightforward, flow is clear and explicit (when you now what `filter`, 
`flatMap`, `map` etc methods are doing - as I said before the basics functional programming patters are needed ;)).

`is` class (alias to `Predicates` class) is factory for closures that have one argument and evaluate it to boolean 
value. There are `lt`, `gt`, `eq`, `not` etc methods. Closures in php are very lengthy, you have to write `function` 
keyword, curly braces, return statement, semicolon etc - a lot of syntax noise. Closure is multiline (yes, I now it can 
be written in single line, but it would be unreadable), so it is no very compact. To handle simple predicate cases, you 
might use `is` class. More about predicates you can read in [Predicates](#predicates) section.

`get::value('authors')` also is a shortcut for closures, this is semantic equivalent to:

```php

    function($object){
        return $object->getAuthors();
    }

```

Nested paths in predicates and `get::value` function are supported, so this code works as expected: 
`get::value('address.city.name')`.

In the most of functions (where make it sense) to predicate/mapping function are provided two arguments: element value
and index:

```php
    FluentTraversable::from(array('a' => 'A', 'b' => 'B'))
        ->map(function($value, $index){
            return $value.$index;
        })
        ->toMap();
        //result will be: array('a' => 'Aa', 'b' => 'Bb')
```

When you won't index to be passed as second argument, you could use `call::func($func)` function. It is very helpful
especially when you want to use php build-in function that has optional second argument with different meaning, for 
example `str_split`:

```php
    FluentTraversable::from(array('some', 'values'))
        ->flatMap(call::func('str_split'))
        ->toArray();
        //result will be: array('s', 'o', 'm', 'e', 'v', 'a', 'l', 'u', 'e')
```

`FluentTraversable` has a lot of useful methods: `map`, `flatMap`, `filter`, `unique`, `groupBy`, `orderBy`, `allMatch`,
`anyMatch`, `noneMatch`, `firstMatch`, `maxBy`, `minBy`, `reduce`, `toArray`, `toMap` and more. List, description and examples
of all those methods are available in [TraversableFlow][3] interface. Each method belongs to one of two groups: 
intermediate or terminate operations. Intermediate operation does some work on input array, modifies it and returns 
`FluentTraversable` object for further processing, so you can chain another operation. Terminate operation does some 
calculation on each element of array and returns result of this calculation. For example `size` operation returns 
integer that is length of input array, so you can not chain operation anymore.

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
object is a wrapper for value, it can contain value, but it haven't to. You should threat `Option` as collection with 0 or 1
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

Properly used, option is very powerful and it integrates with `FluentTraversable` perfectly. `Option::map` method is
very inconspicuous, but it is also very useful. Thanks to `Option::map` you can execute piece of code when value is 
available without using `if` statement:

```php

    FluentTraversable::from($books)
        ->maxBy(get::value('rating'))
        ->map(function(Book $book){
            $this->takeToBackpack($book);
        });

```

<a name="composer"></a>
## TraversableComposer

`TraversableComposer` is a tool to compose complex operations on arrays. You can define one complex operation thanks to
composer, and apply it multiple times on any array. `TraversableComposer` has very similar interface to `FluentTraversable`
(those two classes implements the same interface: `TraversableFlow`).

There is an example:

```php

    $maxEvenPrinter = TraversableComposer::forArray();

    //very important is, to not chain directly from `forArray()` method, first you should assign created object
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

As I said, `TraversableComposer` has almost the same methods as `FluentTraversable`. The difference between those two classes
is that, `FluentTraversable` needs input array when object is created and it should be used once, `TraversableComposer`
doesn't need array when object is created and can be invoked multiple times with different input arrays. Internally
`TraversableComposer` uses `FluentTraversable` instance ;) You should threat `TraversableComposer` as tool to compose functions.

**`TraversableComposer` has three factory methods that differ in arguments that are accepted by created function:**

* `TraversableComposer::forArray()` - created function accepts one array/traversable argument
 
    ```php

        $func = TraversableComposer::forArray();
        $func-> /* some chaining methods */;
            
        $func(array('value1', 'value2', 'value3'));

    ```

* `TraversableComposer::forVarargs()` - created function accepts variable number of arguments (varargs):

    ```php
    
        $func = TraversableComposer::forVarargs();
        $func-> /* some chaining methods */;
        
        $func('value1', 'value2', 'value3');
    
    ```
    
* `TraversableComposer::forValue()` - created function accepts one argument that will be threaten as only element of array.
This method is similar to `TraversableComposer::forVarargs()`, the difference is all arguments are ignored except the first.

    ```php
    
        $func = TraversableComposer::forValue();
        $func-> /* some chaining methods */;
    
        $func('value1', 'this value will be ignored')
    
    ```

> **IMPORTANT**
>
> There is also `compose` factory class that contains that three mentioned methods. It adds semantic value to your code, reduces
> syntax noise and makes it more readable. `compose` class is recommended way of creating `TraversableComposer` instances
> and in next examples that class will be used.

<a name="composerAsPredicate"></a>
### TraversableComposer as predicate / mapping function

You can use `compose` (factory for `TraversableComposer`) to create predicate or mapping function for `FluentTraversable`,
especially after functions that transforms single value to array of values (`groupBy`, `partition` etc.).

Example:

*We have an array of patients and we want to know percentage of female patients grouped by blood type.*

```php

    $patients = array(...);
    
    $info = FluentTraversable::from($patients)
        ->groupBy(get::value('bloodType'))
        ->map(
            compose::forArray()
                ->partition(is::eq('sex', 'female'))
                ->map(call::func('count'))
                ->collect(function($elements){
                    list($femalesCount, $malesCount) = $elements;
                    return $femalesCount / ($femalesCount + $malesCount) * 100;
                })
        )
        ->toMap();
```

> **IMPORTANT**
>
> Directly chaining from `compose::forArray()` (and other factory methods) is not always safe, some methods does not 
> return `TraversableComposer`, but `Option` object. Methods that returns `Option` are: `reduce`, `firstMatch`, `max`, 
> `min`, `first`, `last`. When you after all want to chain directly from `compose::forArray()` and use terminal 
> operation that returns `Option`, you can apply a trick:
>
> ```php
>
>   ->map(
>       $f = compose::forArray(), $f
>            ->firstMatch(is::eq('name', 'Stefan'))
>            ->getOrElse('Not found')
>   )
>
> ```

There is also `compose::forValue()` method to create function with one argument that contains single value. It might be 
useful to create predicates or mapping functions for single value.

Example:

*We want to find doctors that all patients are women (gynecologists?).*

```php
    
    $doctors = array(/* some doctors */);

    $doctors = FluentTraversable::from($doctors)
        ->filter(
            compose::forValue()
                ->flatMap(get::value('patients'))
                ->allMatch(is::eq('sex', 'female'))
        )
        ->toArray();

```

<a name="predicates"></a>
## Predicates

Predicate is a function that evaluates single value to boolean. Predefined predicates are available in `is` and `Predicates`
classes. Those classes are the same, `is` is an alias to `Predicates`, so you can choose witch one to use (`is` gives more
expressiveness to code). Predicates are perfect to use in `filter`, `firstMatch`, `partition`, `allMatch`, `noneMatch`,
`anyMatch` methods of `FluentTraversable`.

The most of predicates (for example: `eq`, `gt`, `qte`, `identical`, `in`, `contains`) have two versions:

* unary: `predicate($valueToCompare)`

    ```php
    
        $gt25 = is::gt(25);    
        $gt25(26);//evaluates to true
    
    ```

* binary - `predicate($property, $valueToCompare)`

    ```php
    
        $ageGt25 = is::gt('age', 25);
        $gt25(array('age', 26));//evaluates to true
    
    ```

Few predicates (`null`, `notNull`, `false`, `true`) have also two, but different versions:

* not argument: `predicate()`

    ```php
    
        $true = is::true();    
        $true(true);//evaluates to true
    
    ```
    
* unary: `predicate($property)`

    ```php
    
        $true = is::true('awesome');    
        $true(array('awesome' => true));//evaluates to true
    
    ```
    
There are also logical predicates (`not`, `andX`, `orX`), but when you need to create complex predicate maybe the
better and more readable way is just to use closure.

<a name="puppet"></a>
## Puppet

Puppet is a very small (less than 100 lines of code) class, but it is also very powerful. What is a Puppet? Thanks to 
Puppet you can "record" some behaviour and execute this behaviour multiple times on various objects.

Example:

```php

    $book = ...;
    $puppet = Puppet::record()->getPublisher()->getName();
    
    echo $puppet($book);//$book->getPublisher()->getName() will be invoked

```

`Puppet` supports property access, array access and method calls with arguments. Originally it was created to simplify `map` and
`flatMap` operations in `FluentTraversable`. It is is also used internally by `TraversableComposer`, but maybe you will find 
another use case for `Puppet`.

Puppet has two factory methods: `record` and `object` - those methods are the same, `object` method was created only for 
semantic purpose. You can use `Puppet` to create mapping function for `map`, `flatMap` etc. functions, but `get::value()`
is recommended for this purpose.

`the` class is alias to `Puppet`, it only adds semantic meaning to using `Puppet` in `FluentTraversable` context:
`->map(the::object()->getName())` is much more readable than `->map(Puppet::record()->getName())`.

<a name="contri"></a>
## Contribution

Any suggestions, PR, bug reports etc. are welcome ;)

<a name="license"></a>
## License

**MIT** - details in [LICENSE](LICENSE) file

[1]: http://www.nurkiewicz.com/2013/08/optional-in-java-8-cheat-sheet.html
[2]: https://github.com/schmittjoh/php-option
[3]: https://github.com/psliwa/fluent-traversable/blob/master/src/FluentTraversable/TraversableFlow.php