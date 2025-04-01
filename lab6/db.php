<?php

global $db;
$user = 'u68598'; // Заменить на ваш логин uXXXXX
$pass = '8795249'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX


function findLoginByUid($update_id, $db)
{
    $update_query = "SELECT login FROM person_LOGIN WHERE id = :id";
    try {
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':id', $update_id);
        $update_stmt->execute();
        $doplog=$update_stmt->fetchColumn();
    }
    catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
    return $doplog;

}