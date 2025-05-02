<?php
require_once 'db.php';
require_once 'functions.php';
/**
 * Реализовать возможность входа с паролем и логином с использованием
 * сессии для изменения отправленных данных в предыдущей задаче,
 * пароль и логин генерируются автоматически при первоначальной отправке формы.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
$allowed_lang=getLangs($db);
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
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Выводим сообщение пользователю.
    $messages[] = strip_tags('Спасибо, результаты сохранены.');
    // Если в куках есть пароль, то выводим сообщение.
    if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !=  adminlog($db) || !password_check(adminlog($db), $_SERVER['PHP_AUTH_PW'], $db))
    {
      if (!empty($_COOKIE['pass'])) {
        $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
          и паролем <strong>%s</strong> для изменения данных.',
          strip_tags($_COOKIE['login']),
          strip_tags($_COOKIE['pass']));//XSS
      }
    }
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['field-tel'] = !empty($_COOKIE['field-tel_error']);
  $errors['field-email'] = !empty($_COOKIE['field-email_error']);
  $errors['field-date'] = !empty($_COOKIE['field-date_error']);
  $errors['radio-group-1'] = !empty($_COOKIE['radio-group-1_error']);
  $errors['languages'] = !empty($_COOKIE['languages_error']);
  $errors['check-1'] = !empty($_COOKIE['check-1_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);


  // Выдаем сообщения об ошибках.
  if ($errors['fio'] AND $_COOKIE['fio_error']==1) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    setcookie('fio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Заполните имя.</div>';
  }

  if ($errors['fio'] AND $_COOKIE['fio_error']==2) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    setcookie('fio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>ФИО должно содержать не более 150 символов.</div>';
  }

  if ($errors['fio'] AND $_COOKIE['fio_error']==3) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    setcookie('fio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>ФИО должно содержать только буквы (русские и английские) и пробелы.</div>';
  }

  if ($errors['field-tel']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('field-tel_error', '', 100000);
    setcookie('field-tel_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Телефон должен содержать только цифры и знак +</div>';
  }

  if ($errors['field-email'] AND  $_COOKIE['field-email_error']==1) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('field-email_error', '', 100000);
    setcookie('field-email_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Email введен некорректно</div>';
  }

  if ($errors['field-email'] AND  $_COOKIE['field-email_error']==2) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('field-email_error', '', 100000);
    setcookie('field-email_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Такой email уже зарегестрирован</div>';
  }

  if ($errors['field-date']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('field-date_error', '', 100000);
    setcookie('field-date_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Заполните дату</div>';
  }

  
  if ($errors['radio-group-1']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('radio-group-1_error', '', 100000);
    setcookie('radio-group-1_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Выберите пол</div>';
  }

  if ($errors['check-1']) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('check-1_error', '', 100000);
    setcookie('check-1_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Ознакомьтесь с контрактом</div>';
  }

  if ($errors['languages']) {
    if($_COOKIE['languages_error']=='1'){
      $messages[] = '<div>Укажите любимый(ые) язык(и) программирования.</div>';
    }
    elseif($_COOKIE['languages_error']=='2'){
      $messages[] = '<div>Указан недопустимый язык.</div>';
    }
    setcookie('languages_error', '', 100000);
    setcookie('languages_value', '', 100000);
  }

  if ($errors['bio'] AND  $_COOKIE['bio_error']==1) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('bio_error', '', 100000);
    setcookie('bio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Заполните биографию.</div>';
  }

  if ($errors['bio'] AND  $_COOKIE['bio_error']==2) {
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('bio_error', '', 100000);
    setcookie('bio_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div>Используйте только допустимые символы: буквы, цифры, знаки препинания.</div>';
  }


  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : strip_tags($_COOKIE['fio_value']);
  $values['field-tel'] = empty($_COOKIE['field-tel_value']) ? '' : strip_tags($_COOKIE['field-tel_value']);
  $values['field-email'] = empty($_COOKIE['field-email_value']) ? '' : strip_tags($_COOKIE['field-email_value']);
  $values['field-date'] = empty($_COOKIE['field-date_value']) ? '' : strip_tags($_COOKIE['field-date_value']);
  $values['radio-group-1'] = empty($_COOKIE['radio-group-1_value']) ? '' : strip_tags($_COOKIE['radio-group-1_value']);
  $values['check-1'] = empty($_COOKIE['check-1_value']) ? '' : strip_tags($_COOKIE['check-1_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
  $values['languages'] = empty($_COOKIE['languages_value']) ? '' : strip_tags($_COOKIE['languages_value']);

  //вставка для админа
  if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_USER'] ==  adminlog($db) && password_check(adminlog($db), $_SERVER['PHP_AUTH_PW'], $db))
    {
      if(!empty($_GET['uid']))
      {
        $update_id = strip_tags($_GET['uid']);//XSS
        $doplog=findLoginByUid($update_id, $db);
        $values=insertData($doplog, $db);
        $values['uid']=$update_id;
      }
  }
  //вставка для ползователя
  if (isset($_COOKIE[session_name()]) && session_start() &&!empty($_SESSION['login'])) {
        $values=insertData(strip_tags($_SESSION['login']), $db);//XSS
        $messages[] = "<div>Вход с логином " . htmlspecialchars(strip_tags($_SESSION['login'])) . ", uid " . (int)strip_tags($_SESSION['uid']) . "</div>";//XSS

  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в базе данных.
else {
  $fav_languages = ($_POST['languages']) ?? [];
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty(strip_tags($_POST['fio']))) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1');
    $errors = TRUE;
  }

  if(!empty(strip_tags($_POST['fio'])) && strip_tags(strlen($_POST['fio']))>150) {//XSS
    setcookie('fio_error', '2');
    $errors = TRUE;
  }
  
  if(!empty(strip_tags($_POST['fio'])) && !preg_match('/^[а-яА-Яa-zA-Z ]+$/u', strip_tags($_POST['fio']))) {
    setcookie('fio_error', '3');
    $errors = TRUE;
  }

  // Сохраняем ранее введенное в форму значение на год.
  setcookie('fio_value', strip_tags($_POST['fio']), time() + 365 * 24 * 60 * 60);

  // $_POST['field-tel']=trim($_POST['field-tel']);
  $_POST['field-tel']=strip_tags(trim($_POST['field-tel']));//XSS
  if(!preg_match('/^[0-9+]+$/', $_POST['field-tel'])) {
    setcookie('field-tel_error', '1');
    $errors = TRUE;
  }
  setcookie('field-tel_value', strip_tags($_POST['field-tel']), time() + 365 * 24 * 60 * 60);

  if(!isset($_POST['radio-group-1']) || empty($_POST['radio-group-1'])) {
    setcookie('radio-group-1_error', '1');
    $errors = TRUE;
  }
  setcookie('radio-group-1_value', strip_tags($_POST['radio-group-1']), time() + 365 * 24 * 60 * 60);

  $email=strip_tags($_POST['field-email']);
  if(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/u', $email)) {
    setcookie('field-email_error', '1');
    $errors = TRUE;
  }
  if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !=  adminlog($db) || !password_check(adminlog($db), $_SERVER['PHP_AUTH_PW'], $db))
  {
    if (emailExists($email, $db) && session_start()) {
            $id = null;
       try {
           $dp = $db->prepare("SELECT id FROM person WHERE email = ?");
           $dp->execute([$email]);
           $id = strip_tags($dp->fetchColumn());
       } catch (PDOException $e) {
           echo "Database error: " . $e->getMessage(); // Выводим ошибку на экран
           exit();
       }
       if ((int)$id !== (int)strip_tags($_SESSION['uid'])) {
           setcookie('field-email_error', '2');
           $errors = TRUE;
       }
    }
  }
  else {
    if (emailExists($email, $db)) {
       $id = null;
       try {
           $dp = $db->prepare("SELECT id FROM person WHERE email = ?");
           $dp->execute([$email]);
           $id = $dp->fetchColumn();
       } catch (PDOException $e) {
           echo "Database error: " . $e->getMessage(); // Выводим ошибку на экран
           exit();
       }
       if ((int)$id !== (int)$_POST['uid']) {
           setcookie('field-email_error', '2');
           $errors = TRUE;
       }
    }
  }

  setcookie('field-email_value', strip_tags($_POST['field-email']), time() + 365 * 24 * 60 * 60);

  if(empty($fav_languages)) {
    setcookie('languages_error', '1');
    $errors = TRUE;
  } else {
    foreach ($fav_languages as $lang) {
      if (!in_array($lang, $allowed_lang)) {
          setcookie('languages_error', '2');
          $errors = TRUE;
      }
    }
  }
  $langs_value =strip_tags(implode(",", $fav_languages));
  setcookie('languages_value', $langs_value, time() + 365 * 24 * 60 * 60);

  if (empty($_POST['field-date'])) {
    setcookie('field-date_error', '1');
    $errors = TRUE;
  }
  setcookie('field-date_value', strip_tags($_POST['field-date']), time() + 365 * 24 * 60 * 60);//XSS

  if(!isset($_POST['check-1']) || empty($_POST['check-1'])) {
    setcookie('check-1_error', '1');
    $errors = TRUE;
  }
  setcookie('check-1_value', strip_tags($_POST['check-1']), time() + 365 * 24 * 60 * 60);

  if (empty($_POST['bio'])) {
    setcookie('bio_error', '1');
    $errors = TRUE;
  }

  if (!empty($_POST['bio']) && !preg_match('/^[а-яА-Яa-zA-Z1-9.,?!:() ]+$/u', $_POST['bio'])) {
    setcookie('bio_error', '2');
    $errors = TRUE;
  }
  setcookie('bio_value', strip_tags($_POST['bio']), time() + 365 * 24 * 60 * 60);


  if ($errors) {
    if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_USER'] ==  adminlog($db) && password_check(adminlog($db), $_SERVER['PHP_AUTH_PW'], $db))
  {
    header('Location: index.php?=' . $_POST['uid'] . '');
    exit();
  }
else{
  header('Location: index.php');
    exit();
}
    
  }
  else {
    setcookie('fio_error', '', 100000);
    setcookie('field-tel_error', '', 100000);
    setcookie('field-email_error', '', 100000);
    setcookie('field-date_error', '', 100000);
    setcookie('radio-group-1_error', '', 100000);
    setcookie('check-1_error', '', 100000);
    setcookie('languages_error', '', 100000);
    setcookie('bio_error', '', 100000);
  }

  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.

  if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_USER'] ==  adminlog($db) && password_check(adminlog($db), $_SERVER['PHP_AUTH_PW'], $db))
  {
    if(!empty($_POST['uid']))
    {
      try{
      $update_id = ($_POST['uid']);//XSS
      $doplog=findLoginByUid($update_id, $db);
      updateDB($doplog, $db);
      header('Location: adm_page.php');
      exit();
      }
      catch(PDOException $e){
        header('Location:adm_page.php');
        exit();
      }
    }
    else{
      print('Вы не выбрали пользователя для изменения');
      exit();
    }
  }
  else{
  if (isset($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    try {
          updateDB(($_SESSION['login']), $db);//XSS
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
  }
  else {
    $login = generate_pass(7);
    while(check_login($login, $db)>0)
    {
      $login = generate_pass(7);
    }
    $pass = generate_pass();
    // Сохраняем в Cookies.
    $hash_pass=password_hash($pass, PASSWORD_DEFAULT);
    setcookie('login', $login);
    setcookie('pass', $pass);
    try {
          insertDB($db, $login, $hash_pass);
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
  }
}

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: ./');
}
