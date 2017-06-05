<?php

echo 'Single digit odd numbers from range(): ';
foreach (range(0, 1000000, 2) as $number) {
    echo "$number "."\r";
}
