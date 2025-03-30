<?php

global $db;
$user = 'u68598'; // Заменить на ваш логин uXXXXX
$pass = '8795249'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
