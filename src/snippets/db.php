<?php

$user = 'root';
$pass = 'root';

$db = new PDO('mysql:host=127.0.0.1', $user, $pass);

$sth = $db->query('SELECT * FROM test.users');

function getUsers($pdo)
{
    $users = array();
    /** @var PDOStatement $pdo */
    foreach ($pdo as $user) {
        $users[] = $user;
    }

    return $users;
}

echo count(getUsers($sth))."\n";