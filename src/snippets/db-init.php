<?php

$user = 'root';
$pass = 'root';
$iterations = 10000;

$db = new PDO('mysql:host=127.0.0.1', $user, $pass);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);

$query = <<<SQL
CREATE DATABASE test;
CREATE TABLE test.users (
   id INTEGER NOT NULL AUTO_INCREMENT,
   name VARCHAR(255),
   PRIMARY KEY (id)
)
SQL;
$db->exec($query);

for ($i = 0; $i < $iterations; $i++) {
    $db->exec("INSERT INTO test.users (id, name) VALUES ($i, 'john doe')");
}