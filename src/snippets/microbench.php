<?php

error_reporting(E_ALL);

function xrange($start, $end, $step = 1) {
    for ($i = $start; $i < $end; $i += $step) {
        yield $i;
    }
}

class RangeIterator implements Iterator {
    protected $start;
    protected $end;
    protected $step;
    
    protected $key;
    protected $value;
    
    public function __construct($start, $end, $step = 1) {
        $this->start = $start;
        $this->end   = $end;
        $this->step  = $step;
    }
    
    public function rewind() {
        $this->key   = 0;
        $this->value = $this->start;
    }
    
    public function valid() {
        return $this->value < $this->end;
    }
    
    public function next() {
        $this->value += $this->step;
        $this->key   += 1;
    }
    
    public function current() {
        return $this->value;
    }
    
    public function key() {
        return $this->key;
    }
}

function urange($start, $end, $step = 1) {
    $result = [];
    for ($i = $start; $i < $end; $i += $step) {
        $result[] = $i;
    }
    return $result;
}

function testTraversable($name, callable $traversableFactory) {
    $startTime = microtime(true);
    $startMemory = xdebug_memory_usage(true);
    $t = $traversableFactory();
    foreach ($t as $value) {
        // noop
        $t2 = $value;
    }
    echo $name, ' took ', microtime(true) - $startTime, ' seconds.', ' memory used: ' , (xdebug_memory_usage(true) - $startMemory) / (1024*1024) , " Mib\n";
}

function testVariants($count) {
    testTraversable(
        "xrange        ($count)",
        function() use($count) { return xrange(0, $count); }
    );
    testTraversable(
        "RangeIterator ($count)",
        function() use($count) { return new RangeIterator(0, $count); }
    );
    testTraversable(
        "urange        ($count)",
        function() use($count) { return urange(0, $count); }
    );
    testTraversable(
        "range         ($count)",
        function() use($count) { return range(0, $count); }
    );
}

testVariants(1000000);
testVariants(10000);
testVariants(100);