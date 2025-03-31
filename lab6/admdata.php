<?php
require_once 'db.php';
global $adminlogin;
$query = "SELECT login FROM LOGIN where role='ADMIN'"; // Запрос с параметром

    $stmt = $db->prepare($query); // Подготавливаем запрос
    $stmt->execute();// Выполняем запрос с параметром
    $adminlogin = $stmt->fetchColumn();