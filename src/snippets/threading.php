<?php

function step1() {
    $f = fopen(__DIR__.'/file.txt', 'r');
    while ($line = fgets($f)) {
        //processLine($line);
        echo 'processing line: '.$line;
        yield true;
    }
}

function step2() {
    $f = fopen(__DIR__.'/file2.txt', 'r');
    while ($line = fgets($f)) {
        //processLine($line);
        echo 'processing line: '.$line;
        yield true;
    }
}

function step3() {
    $f = fsockopen('www.example.com', 80);
    stream_set_blocking($f, false);
    $headers = "GET / HTTP/1.1\r\n";
    $headers .= "Host: www.example.com\r\n";
    $headers .= "Connection: Close\r\n\r\n";
    fwrite($f, $headers);
    $body = '';
    while (!feof($f)) {
        $body .= fread($f, 8192);
        yield true;
    }

    //processBody($body);
    echo 'processing body: '.$body;
}

function runner(array $steps) {
    while (true) {
        foreach ($steps as $key => $step) {
            $step->next();
            if (!$step->valid()) {
                unset($steps[$key]);
            }
        }

        if (empty($steps)) {
            return;
        }
    }
}

runner(array(step1(), step2(), step3()));