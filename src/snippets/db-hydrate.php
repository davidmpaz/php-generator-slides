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
        // add to collection
        $users[] = hydrateUser($user);
    }

    return $users;
}

function hydrateUser(array $user)
{
    $uObj = new stdClass();
    // hydrating logic for our class, for more advance requirements
    // check: https://github.com/Ocramius/GeneratedHydrator
    $uObj->id = $user['id'];
    $uObj->name = $user['name'];
    return $uObj;
}

$users = getUsers($sth);
foreach ($users as $user) {
    $count++;
}

echo $count."\n";