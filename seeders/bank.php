<?php

require __DIR__ . '/../bootstrap.php';


$host = getSetting('host');
$db   = getSetting('db');
$user = getSetting('user');
$pass = getSetting('pass');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES, false,
];

$pdo = new PDO($dsn, $user, $pass, $options);


$sql = "DROP TABLE IF EXISTS
customers, users;
";
$pdo->query($sql);


$sql = "CREATE TABLE 
users (
    id    smallint PRIMARY KEY AUTO_INCREMENT,
    user  varchar(20) not null,
    email varchar(40) not null,
    pass  varchar(60) not null,
    UNIQUE KEY unique_user (user),
    UNIQUE KEY unique_email (email)
);
";
$pdo->query($sql);


$sql = "CREATE TABLE 
customers (
    id                      smallint PRIMARY KEY AUTO_INCREMENT,
    `name`	                varchar(70),
    surname	                varchar(70),
    identification_number   char(11),
    account_number          varchar(20),
    balance	                decimal(6,2) not null default 0
);
";
$pdo->query($sql);


$users = [
    ['Jonas', 'jonas@jo.com', 123]
];
$user = $users[0][0];
$email = $users[0][1];
$pass = password_hash($users[0][2], PASSWORD_BCRYPT);

$sql = "INSERT INTO
    users
    (user, email, pass)
    VALUES ( '$user', '$email', '$pass' )
    ";
$pdo->query($sql);

$customers = [
    ['Albinas', 'Lapys', '31708148616', 'LT704423654896521780'],
    ['Kristina', 'Jones', '40007068972', 'LT705823652566521362'],
    ['Giedrė', 'Bosas', '44006029487', 'LT705824652566527323',],
    ['Martynas', 'Liepa', '50312019270', 'LT70582365256652567']
];


foreach ($customers as $customer) {
    $n = $customer[0];
    $s = $customer[1];
    $pin = $customer[2];
    $accNumber = $customer[3];
    $sql = "INSERT INTO
    customers
    (`name`, surname, identification_number, account_number)
    VALUES ( '$n', '$s', '$pin', '$accNumber' )
    ";
    $pdo->query($sql);
}
