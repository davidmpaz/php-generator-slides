# Notes to the Slides

# Intro
Generators provide an easy, boilerplate-free way of implementing iterators.[^1]

... without the overhead or complexity of implementing the Iterator interface [^3]

# Intro²
Generators are interruptible functions, where the yield statements constitute 
the interruption points.

Sticking to the [`xrange`](http://php.net/manual/en/language.generators.overview.php#example-278) 
example, if you call xrange(1, 1000000) no code in the xrange() function is 
actually run. Instead PHP just returns an instance of the Generator class which 
implements the Iterator interface [^8].

```php
<?php
$range = xrange(1, 1000000);
var_dump($range); // object(Generator)#1
var_dump($range instanceof Iterator); // bool(true)
```

# Syntax

## On delegating for aggregating (Since PHP 7.0)
In PHP 7, generator delegation allows you to yield values from another 
generator [^5], [^7].
```php
<?php
function getEbooks()
{
    yield new Ebook();
    yield from [new Ebook(), new Ebook()];
    yield from new ArrayIterator([new Ebook(), new Ebook()]);
    yield from $this->getEbooksFromCSV();
    yield from $this->getEbooksFromDatabase();
}
```

## On execution time
Bear in mind that execution of a generator function is postponed until 
iteration over its result (the Generator object) begins [^3].

Uhm... lazy loading?

## When compared with Iterators

primary __advantage__ of generators is their simplicity [^5]. Lets take the 
following:

```php
<?php
function getLinesFromFile($fileName) {
    if (!$fileHandle = fopen($fileName, 'r')) {
        throw new RuntimeException('Couldn\'t open file "' . $fileName . '"');
    }

    while (false !== $line = fgets($fileHandle)) {
        yield $line;
    }

    fclose($fileHandle);
}

// versus...

class LineIterator implements Iterator {
    protected $fileHandle;
 
    protected $line;
    protected $i;
 
    public function __construct($fileName) {
        if (!$this->fileHandle = fopen($fileName, 'r')) {
            throw new RuntimeException('Couldn\'t open file "' . $fileName . '"');
        }
    }
 
    public function rewind() {
        fseek($this->fileHandle, 0);
        $this->line = fgets($this->fileHandle);
        $this->i = 0;
    }
 
    public function valid() {
        return false !== $this->line;
    }
 
    public function current() {
        return $this->line;
    }
 
    public function key() {
        return $this->i;
    }
 
    public function next() {
        if (false !== $this->line) {
            $this->line = fgets($this->fileHandle);
            $this->i++;
        }
    }
 
    public function __destruct() {
        fclose($this->fileHandle);
    }
}
?>
```

__disadvantage__ is that generators are forward-only iterators, and cannot be 
rewound once iteration has started. Also, same generator can't be iterated over 
multiple times: the generator will need to be rebuilt by calling the generator 
function again [^5].

## On cleaning up your mess
Close the generator! [^3]

Can be closed in two ways:
* Reaching a return statement (or the end of the function) in a generator or 
  throwing an exception from it (without catching it inside the generator).
* Removing all references to the generator object. In this case the generator 
  will be closed as part of the garbage collection process.
  
Avoid leaking... resources offcourse.
```php
<?php
function getLines($file) {
    $f = fopen($file, 'r');
    try {
        while ($line = fgets($f)) {
            yield $line;
        }
    } finally {
        fclose($f);
    }
}
```

# Test run results
Profiling was done with PHP 7.0 xdebug enabled on:
```bash
$ lshw
...
...
*-memory
     Beschreibung: Systemspeicher
     Physische ID: 0
     Größe: 7916MiB
*-cpu
     Produkt: Intel(R) Core(TM) i5-6600K CPU @ 3.50GHz
     Hersteller: Intel Corp.
     Physische ID: 1
     Bus-Informationen: cpu@0
     Größe: 800MHz
     Kapazität: 3900MHz
     Breite: 64 bits
```

## Getting a range from 0 to 1000000
### Built-in based range function [^3]
```
Showing the 20 most costly calls sorted by 'memory-own'.

                   Inclusive        Own
function   #calls  time     memory  time     memory
---------------------------------------------------
range           1  0.0172 33558608  0.0172 33558608
{main}          1  1.2081       32  1.1909 -33558576
```

### Generators based range function
```
Showing the 20 most costly calls sorted by 'memory-own'.

                      Inclusive        Own
function      #calls  time     memory  time     memory
------------------------------------------------------
{main}             1  8.8782       32  3.4404     4128
range_gen    1000002  5.4378    -4096  5.4378    -4096
```

## Several iteration methods
## Output from script [^6]
```
xrange        (1000000) took 6.3151068687439 seconds.
RangeIterator (1000000) took 20.609377145767 seconds.
urange        (1000000) took 0.085041999816895 seconds.
range         (1000000) took 0.016937971115112 seconds.

xrange        (10000) took 0.065307855606079 seconds.
RangeIterator (10000) took 0.20801401138306 seconds.
urange        (10000) took 0.00072097778320312 seconds.
range         (10000) took 0.00019407272338867 seconds.

xrange        (100) took 0.00067996978759766 seconds.
RangeIterator (100) took 0.0022540092468262 seconds.
urange        (100) took 3.2901763916016E-5 seconds.
range         (100) took 4.3869018554688E-5 seconds.
```


[^1]: https://wiki.php.net/rfc/generators
[^2]: http://www.dabeaz.com/generators/
[^3]: http://php.net/manual/en/language.generators.overview.php
[^4]: http://php.net/manual/en/language.generators.syntax.php
[^5]: http://php.net/manual/en/language.generators.comparison.php
[^6]: https://gist.github.com/nikic/2975796
[^7]: http://blog.kevingomez.fr/2016/01/20/use-cases-for-php-generators/
[^8]: https://nikic.github.io/2012/12/22/Cooperative-multitasking-using-coroutines-in-PHP.html
[^10]: http://php.budgegeria.de/trarengbe2
[^11]: http://php.budgegeria.de/trarengbe3
[^12]: http://createopen.com/php/2012/11/27/generators.html
[^13]: https://bitbucket.org/mkjpryor/async/wiki/Home
[^14]: https://github.com/jasonamyers/php-yield-presentation
[^15]: http://ocramius.github.io/blog/doctrine-orm-optimization-hydration/
[^16]: https://github.com/Ocramius/GeneratedHydrator
[^17]: https://github.com/jasonamyers/php-yield-presentation