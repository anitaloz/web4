<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');
function emailExists($email, $pdo) {

  $sql = "SELECT COUNT(*) FROM person WHERE email = :email"; 
  $stmt = $pdo->prepare($sql);


  if ($stmt === false) {
    error_log("Ошибка подготовки запроса: " . $pdo->errorInfo()[2]);
    return true; 
  }


  $stmt->bindValue(':email', $email, PDO::PARAM_STR);


  if (!$stmt->execute()) {

    error_log("Ошибка выполнения запроса: " . $stmt->errorInfo()[2]); 
    return true; 
  }

  // 4. Получение результата запроса.
  $count = $stmt->fetchColumn(); // Получаем сразу значение COUNT(*)

  // 5. Закрытие курсора (необязательно, но рекомендуется)
  $stmt->closeCursor();

  // 6. Возврат true, если email найден в базе, иначе false.
  return $count > 0;
}
  $user = 'u68598'; // Заменить на ваш логин uXXXXX
  $pass = '8795249'; // Заменить на пароль
  $db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

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
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
    if (!empty($_COOKIE['pass'])) {
        $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
          и паролем <strong>%s</strong> для изменения данных.',
          strip_tags($_COOKIE['login']),
          strip_tags($_COOKIE['pass']));
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
  // TODO: аналогично все поля.

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
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('languages_error', '', 100000);
    setcookie('languages_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="messages">Отметьте любимый язык программирования.</div>';
  }

  // TODO: тут выдать сообщения об ошибках в других полях.

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['field-tel'] = empty($_COOKIE['field-tel_value']) ? '' : $_COOKIE['field-tel_value'];
  $values['field-email'] = empty($_COOKIE['field-email_value']) ? '' : $_COOKIE['field-email_value'];
  $values['field-date'] = empty($_COOKIE['field-date_value']) ? '' : $_COOKIE['field-date_value'];
  $values['radio-group-1'] = empty($_COOKIE['radio-group-1_value']) ? '' : $_COOKIE['radio-group-1_value'];
  $values['check-1'] = empty($_COOKIE['check-1']) ? '' : $_COOKIE['check-1_value'];
  $values['bio'] = empty($_COOKIE['bio']) ? '' : $_COOKIE['bio_value'];
  $values['languages'] = empty($_COOKIE['languages_value']) ? '' : $_COOKIE['languages_value'];
  // TODO: аналогично все поля.
    if (empty($errors) && !empty($_COOKIE[session_name()]) &&
    session_start() && !empty($_SESSION['login'])) {
        $sql = "SELECT fio FROM person join person_LOGIN using(id) WHERE login = :login"; 
        $stmt = $pdo->prepare($sql);
        if ($stmt === false) {
            error_log("Ошибка подготовки запроса: " . $pdo->errorInfo()[2]);
            return true; 
        }
        $stmt->bindValue(':login', $_SESSION['login'], PDO::PARAM_STR);
        if (!$stmt->execute()) {

            error_log("Ошибка выполнения запроса: " . $stmt->errorInfo()[2]); 
            return true; 
        }
        // 4. Получение результата запроса.
        $count = $stmt->fetchColumn(); // Получаем сразу значение COUNT(*)

        // 5. Закрытие курсора (необязательно, но рекомендуется)
        $stmt->closeCursor();
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
    // Для загрузки данных из БД делаем запрос SELECT и вызываем метод PDO fetchArray(), fetchObject() или fetchAll() 
    // См. https://www.php.net/manual/en/pdostatement.fetchall.php
        printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    }
  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  $fav_languages = $_POST['languages'] ?? [];
  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['fio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1');
    $errors = TRUE;
  }

  if(!empty($_POST['fio']) && strlen($_POST['fio'])>150) {
    setcookie('fio_error', '2');
    $errors = TRUE;
  }
  
  if(!empty($_POST['fio']) && !preg_match('/^[а-яА-Яa-zA-Z ]+$/u', $_POST['fio'])) {
    setcookie('fio_error', '3');
    $errors = TRUE;
  }

  // Сохраняем ранее введенное в форму значение на год.
  setcookie('fio_value', $_POST['fio'], time() + 365 * 24 * 60 * 60);

  // $_POST['field-tel']=trim($_POST['field-tel']);
  //$_POST['field-tel']=trim($_POST['field-tel']);
  if(!preg_match('/^[0-9+]+$/', $_POST['field-tel'])) {
    setcookie('field-tel_error', '1');
    $errors = TRUE;
  }
  setcookie('field-tel_value', $_POST['field-tel'], time() + 365 * 24 * 60 * 60);

  if(!isset($_POST['radio-group-1']) || empty($_POST['radio-group-1'])) {
    setcookie('radio-group-1_error', '1');
    $errors = TRUE;
  }
  setcookie('radio-group-1_value', $_POST['radio-group-1'], time() + 365 * 24 * 60 * 60);

  $email=trim($_POST['field-email']);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setcookie('field-email_error', '1');
    $errors = TRUE;
  }
//   if (emailExists($email, $db)) { 
//     setcookie('field-email_error', '2');
//     $errors = TRUE;
//   }
  setcookie('field-email_value', $_POST['field-email'], time() + 365 * 24 * 60 * 60);

  if (empty($fav_languages)) {
    setcookie('languages_error', "1");
    $errors = TRUE;
  }

  $langs_value =(implode(",", $fav_languages));
  setcookie('languages_value', $langs_value, time() + 365 * 24 * 60 * 60);

  if (empty($_POST['field-date'])) {
    setcookie('field-date_error', '1');
    $errors = TRUE;
  }
  setcookie('field-date_value', $_POST['field-date'], time() + 365 * 24 * 60 * 60);

  if(!isset($_POST['check-1']) || empty($_POST['check-1'])) {
    setcookie('check-1_error', '1');
    $errors = TRUE;
  }
  setcookie('check-1_value', $_POST['check-1'], time() + 365 * 24 * 60 * 60);

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
    setcookie('field-tel_error', '', 100000);
    setcookie('field-email_error', '', 100000);
    setcookie('field-date_error', '', 100000);
    setcookie('radio-group-1_error', '', 100000);
    setcookie('check-1_error', '', 100000);
    setcookie('languages_error', '', 100000);
    //setcookie('bio_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  // Сохранение в БД.
  // ...
  // $user = 'u68598'; // Заменить на ваш логин uXXXXX
  // $pass = '8795249'; // Заменить на пароль
  // $db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
  //   [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

  //  Именованные метки.

if (!empty($_COOKIE[session_name()]) &&
session_start() && !empty($_SESSION['login'])) {
// TODO: перезаписать данные в БД новыми данными,
// кроме логина и пароля.
}
else {
        try {
            $stmt = $db->prepare("INSERT INTO person (fio, tel, email, bdate, gender, biography) VALUES (:fio, :tel, :email, :bdate, :gender, :biography)");
            $stmt->bindParam(':fio', $fio);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':bdate', $bdate);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':biography', $biography);
            $fio = ($_POST['fio']);
            $tel = ($_POST['field-tel']);
            $email = ($_POST['field-email']);
            $bdate = ($_POST['field-date']);
            $gender = ($_POST['radio-group-1']);
            $biography = ($_POST['bio']);
            $stmt->execute();
            $lastInsertId = $db->lastInsertId();
            foreach($_POST['languages'] as $lang) {
            $stmt = $db->prepare("INSERT INTO personlang (pers_id, lang_id) VALUES (:pers_id, :lang_id)");
            $stmt->bindParam(':pers_id', $lastInsertId);
            $stmt->bindParam(':lang_id', $lang);
            $stmt->execute();
            }
            // Генерируем уникальный логин и пароль.
            // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
            $login = rand()%10000000;
            $pass = rand()%10000000000;
            // Сохраняем в Cookies.
            setcookie('login', $login);
            setcookie('pass', $pass);
            $stmt = $db->prepare("INSERT INTO LOGIN (login, pass) VALUES (:login, :pass)");
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':pass', $pass);
            $stmt->execute();
            $lastInsertId = $db->lastInsertId();
            $stmt = $db->prepare("INSERT INTO person_LOGIN (id, login) VALUES (:id, :login)");
            $stmt->bindParam(':id', $lastInsertId);
            $stmt->bindParam(':login', $login);
            $stmt->execute();
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }
    // TODO: Сохранение данных формы, логина и хеш md5() пароля в базу данных.
    // ...
    }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
