<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
function emailExists($email, $pdo) {
  // 1. Подготовка SQL запроса для проверки существования email.
  // Используем подготовленные выражения для предотвращения SQL-инъекций.
  $sql = "SELECT COUNT(*) FROM person WHERE email = :email"; // Используем именованный плейсхолдер
  $stmt = $pdo->prepare($sql);

  // Проверка, успешно ли подготовлен запрос.
  if ($stmt === false) {
    // Обработка ошибки подготовки запроса.  Важно для отладки.
    error_log("Ошибка подготовки запроса: " . $pdo->errorInfo()[2]); // Получаем сообщение об ошибке PDO
    return true; // Или false, в зависимости от того, как вы хотите обрабатывать ошибки БД.
  }

  // 2. Привязка параметра к запросу. Используем bindValue()
  $stmt->bindValue(':email', $email, PDO::PARAM_STR); // Явно указываем тип данных

  // 3. Выполнение запроса.
  if (!$stmt->execute()) {
    // Обработка ошибки выполнения запроса. Важно для отладки.
    error_log("Ошибка выполнения запроса: " . $stmt->errorInfo()[2]); // Получаем сообщение об ошибке PDO
    return true; // Или false, в зависимости от того, как вы хотите обрабатывать ошибки БД.
  }

  // 4. Получение результата запроса.
  $count = $stmt->fetchColumn(); // Получаем сразу значение COUNT(*)

  // 5. Закрытие курсора (необязательно, но рекомендуется)
  $stmt->closeCursor();

  // 6. Возврат true, если email найден в базе, иначе false.
  return $count > 0;
}
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  // if (!empty($_GET['save'])) {
  //   // Если есть параметр save, то выводим сообщение пользователю.
  //   print('Данные сохранены!');
  // }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в БД.
$user = 'u68598'; // Заменить на ваш логин uXXXXX
$pass = '8795249'; // Заменить на пароль
$db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

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

if(!preg_match('/^[а-яА-Яa-zA-Z]+$/u', $_POST['field-name-1'])) {
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
$email=trim($_POST['field-email']);
if(!preg_match('/^[a-zA-Z1-9._@]+$/u', $email) && preg_match('/^[@.]+$/u', $email)) {
  print('Email введен некорректно.<br/>');
  $errors=TRUE;
}
if (emailExists($email, $db)) { // Используйте ваше соединение с БД!
  print("Этот email уже зарегистрирован.<br/>");
  $errors = TRUE;
}

if(empty($_POST['field-name-4']))
{
  print('Выберите хотя бы один язык программирования.<br/>');
  $errors=TRUE;
}

if(empty($_POST['field-name-2']))
{
  print('Заполните биографию.<br/>');
  $errors=TRUE;
}

if(!preg_match('/^[а-яА-Яa-zA-Z1-9.,?!:()]+$/u', $_POST['field-name-2']))
{
  print('Используйте только допустимые символы: буквы, цифры, знаки препинания.<br/>');
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
header('Location: p12.html');