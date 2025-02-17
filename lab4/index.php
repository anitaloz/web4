<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  // TODO: аналогично все поля.

  // Выдаем сообщения об ошибках.
  if ($errors['fio'] AND $_COOKIE['fio_error']==1) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    setcookie('fio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя.</div>';
  }

  if ($errors['fio'] AND $_COOKIE['fio_error']==3) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    setcookie('fio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">ФИО должно содержать только буквы (русские и английские) и пробелы.</div>';
  }
  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  // TODO: аналогично все поля.

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['fio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if(!empty($_POST['fio']) && strlen($_POST['field-name-1'])>150) {
    setcookie('fio_error', '2', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  
  if(!preg_match('/^[[:alpha:][:space:]]+$/u', $_POST['field-name-1'])) {
    setcookie('fio_error', '3', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  // Сохраняем ранее введенное в форму значение на месяц.
  setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);

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
// TODO: тут необходимо проверить правильность заполнения всех остальных полей.
// Сохранить в Cookie признаки ошибок и значения полей.
// *************

  if ($errors) {
    // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Сохранение в БД.
  // ...
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

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
