<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Данные сохранены!');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в БД.

// Проверяем ошибки.
$errors = FALSE;
$_POST['field-name-1']=trim($_POST['field-name-1']);
if (empty($_POST['field-name-1'])) {
  print('Заполните ФИО.<br/>');
  $errors = TRUE;
}

if(strlen($_POST['field-name-1'])>150) {
  print('Длина ФИО не должна превышать 150 символов.<br/>');
  $errors = TRUE;
}

if(!preg_match('/^[[:alpha:][:space:]]+$/u', $_POST['field-name-1'])) {
  print('ФИО должно содержать только буквы (русские и английские) и пробелы.<br/>');
  $errors =TRUE;
}
$_POST['field-tel']=trim($_POST['field-tel']);
$_POST['field-tel']=trim($_POST['field-tel']);
if(!preg_match('/^[0-9+]+$/', $_POST['field-tel'])) {
  print('Телефон должен содержать толко цифры.<br/>');
  $errors= TRUE;
}

if(!isset($_POST['radio-group-1']) || empty($_POST['radio-group-1'])) {
  print('Выберите пол.<br/>');
  $errors= TRUE;
}
$_POST['field-email']=trim($_POST['field-email']);
$_POST['field-email']=trim($_POST['field-email']);
if (!filter_var(($_POST['field-email']), FILTER_VALIDATE_EMAIL)) {
  print('Email введен некорректно.<br/>');
  $errors=TRUE;
}

if(empty($_POST['field-name-4']))
{
  print('Выберите хотя бы один язык программирования.<br/>');
  $errors=TRUE;
}

if (empty($_POST['field-date'])) {
  print('Заполните дату.<br/>');
  $errors = TRUE;
}

if(!isset($_POST['check-1']) || empty($_POST['check-1'])) {
  print('Ознакомьтесь с контрактом.<br/>');
  $errors= TRUE;
}
// *************
// Тут необходимо проверить правильность заполнения всех остальных полей.
// *************

if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u68598'; // Заменить на ваш логин uXXXXX
$pass = '8795249'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

//  Именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO person (fio, tel, email, bdate, gender, biography) VALUES (:fio, :tel, :email, :bdate, :gender, :biography)");
  $stmt->bindParam(':fio', $fio);
  $stmt->bindParam(':tel', $tel);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':bdate', $bdate);
  $stmt->bindParam(':gender', $gender);
  $stmt->bindParam(':biography', $biography);
  $fio = ($_POST['field-name-1']);
  $tel = ($_POST['field-tel']);
  $email = ($_POST['field-email']);
  $bdate = ($_POST['field-date']);
  $gender = ($_POST['radio-group-1']);
  $biography = ($_POST['field-name-2']);
  $stmt->execute();
  $lastInsertId = $db->lastInsertId();
  foreach($_POST['field-name-4'] as $lang) {
    $stmt = $db->prepare("INSERT INTO personlang (pers_id, lang_id) VALUES (:pers_id, :lang_id)");
    $stmt->bindParam(':pers_id', $lastInsertId);
    $stmt->bindParam(':lang_id', $lang);
    $stmt->execute();
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}


//  stmt - это "дескриптор состояния".
 

//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');