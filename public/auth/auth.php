<?php
session_start();
require_once 'modules/database.php';
include_once 'modules/functions.php';
include_once 'classes/user.php';
//try {
//    $pdo = new PDO("mysql:host=localhost;dbname=scooters", "root", "");
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//} catch (PDOException $e) {
//    die("Database connection failed: " . $e->getMessage());
//}

function checkLogin($inputs): string
{
    global $pdo;
    $sql = 'SELECT * FROM `user` WHERE `email` = :e';

    if ($pdo === null) {
        die('Databaseverbinding is niet beschikbaar.');
    }

    $sth = $pdo->prepare($sql);
    $sth->bindParam(':e', $inputs['email']);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'User');
    $sth->execute();
    $user = $sth->fetch();

    if ($user !== false && password_verify($inputs['password'], $user->password)) {
        $_SESSION['user'] = $user;
        if ($user->role == "admin") {
            return 'ADMIN';
        }
        if ($user->role == "store_worker") {
            return 'STORE_WORKER';
        }
        return 'USER';
    }
    return 'FAILURE';
}


