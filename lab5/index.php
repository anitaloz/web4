<?php
/**
 * Реализовать возможность входа с паролем и логином с использованием
 * сессии для изменения отправленных данных в предыдущей задаче,
 * пароль и логин генерируются автоматически при первоначальной отправке формы.
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
    try {
    $dp=$pdo->prepare("SELECT id from person where email=:email");
    $dp->bindParam(':email', $email);
    $dp->execute();
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
    $id = $dp->fetchColumn();
    if(is_null($id)) {
        $id=0;
    }
    print('ghjhghjhghjhgjghjg');
    $check=$pdo->prepare("SELECT login from person_LOGIN where id=:id");
    $check->bindParam(':id', $id);
    $check->execute();
    $login=$check->fetchColumn();
    
    if($login==$_COOKIE['login'] && !is_null($login)) {
        $count = 0;
    }
    // 5. Закрытие курсора (необязательно, но рекомендуется)
    $stmt->closeCursor();

    // 6. Возврат true, если email найден в базе, иначе false.
    return $count > 0;
}//функция для проверки почты



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
    $messages[] = 'Спасибо, результаты сохранены.';
    // Если в куках есть пароль, то выводим сообщение.
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
    // Удаляем куки, указывая время устаревания в прошлом.
    setcookie('languages_error', '', 100000);
    setcookie('languages_value', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="messages">Отметьте любимый язык программирования.</div>';
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




  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (isset($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
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
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в базе данных.
else {
  $user = 'u68598'; // Заменить на ваш логин uXXXXX
  $pass = '8795249'; // Заменить на пароль
  $db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

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
  if(!preg_match('/^[a-zA-Z1-9._@]+$/u', $email) || !preg_match('/@.*\./', $email)) {
    setcookie('field-email_error', '1');
    $errors = TRUE;
  }
  if (emailExists($email, $db)) { 
    setcookie('field-email_error', '2');
    $errors = TRUE;
  }
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

  if (empty($_POST['bio'])) {
    setcookie('bio_error', '1');
    $errors = TRUE;
  }

  if (!empty($_POST['bio']) && !preg_match('/^[а-яА-Яa-zA-Z1-9.,?!:()]+$/u', $_POST['bio'])) {
    setcookie('bio_error', '2');
    $errors = TRUE;
  }
  setcookie('bio_value', $_POST['bio'], time() + 365 * 24 * 60 * 60);


  if ($errors) {
    header('Location: index.php');
    exit();
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

  if (isset($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    try {
    $dop=$db->prepare("SELECT id from person_LOGIN where login=:login");
    $dop->bindParam(':login', $_SESSION['login']);
    $dop->execute();
    $stmt = $db->prepare("UPDATE person set fio=:fio, tel=:tel, email=:email, bdate=:bdate, gender=:gender, biography=:biography where id=:id");
    $stmt->bindParam(':fio', $_POST['fio']);
    $stmt->bindParam(':id', $dop->fetchColumn());
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':bdate', $bdate);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':biography', $biography);
    $tel = ($_POST['field-tel']);
    $email = ($_POST['field-email']);
    $bdate = ($_POST['field-date']);
    $gender = ($_POST['radio-group-1']);
    $biography = ($_POST['bio']);
    $stmt->execute();
    $lastInsertId = $db->prepare("SELECT id from person_LOGIN where login=:login");
    $lastInsertId->bindParam(':login', $_SESSION['login']);
    $lastInsertId->execute();
    $pers_id=$lastInsertId->fetchColumn();
    $erasure=$db->prepare("DELETE from personlang where pers_id=:pers_id");
    $erasure->bindParam(':pers_id', $pers_id, PDO::PARAM_INT);
    $erasure->execute();
    foreach($_POST['languages'] as $lang) {
    $stmt1 = $db->prepare("INSERT INTO personlang (pers_id, lang_id) VALUES (:pers_id, :lang_id)");
    $stmt1->bindParam(':pers_id', $pers_id, PDO::PARAM_INT);
    $stmt1->bindParam(':lang_id', $lang);
    $stmt1->execute();
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
  }
}
  else {
    $login = rand()%10000000;
    $pass = rand()%10000000000;
    // Сохраняем в Cookies.
    $hash_pass=md5($pass);
    setcookie('login', $login);
    setcookie('pass', $pass);
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
        $stmt = $db->prepare("INSERT INTO LOGIN (login, pass) VALUES (:login, :pass)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':pass', $hash_pass);
        $stmt->execute();
        $stmt = $db->prepare("INSERT INTO person_LOGIN (id, login) VALUES (:id, :login)");
        $stmt->bindParam(':id', $lastInsertId);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
}

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: ./');
}
