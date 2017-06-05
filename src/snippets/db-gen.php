<?php

$user = 'root';
$pass = 'root';

$db = new PDO('mysql:host=127.0.0.1', $user, $pass);

$sth = $db->query('SELECT * FROM test.users');
function getUsers($pdo)
{
    /** @var PDOStatement $pdo */
    foreach ($pdo as $user) {
        yield $user;
    }
}

$count = 0;
foreach (getUsers($sth) as $user) {
    $count++;
}

echo $count."\n";