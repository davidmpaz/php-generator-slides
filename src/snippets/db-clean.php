<?php

$user = 'root';
$pass = 'root';

$db = new PDO('mysql:host=localhost', $user, $pass);
$query = 'DROP DATABASE test';
$db->exec($query);
