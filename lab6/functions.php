<?php
function password_check($login, $password, $db) {
    $passw;
    try{
      $stmt = $db->prepare("SELECT pass FROM LOGIN WHERE login = :login");
      $stmt->bindParam(':login', $login, PDO::PARAM_STR);
      $stmt->execute();
      $passw = $stmt->fetchColumn();
      if($passw===false){
        return false;
      }
      return password_verify($password, $passw);
    } 
    catch (PDOException $e){
      print('Error : ' . $e->getMessage());
      return false;
    }
  }

  function check_login($login, $db)
    {
    try{
        $stmt = $db->prepare("SELECT COUNT(*) FROM LOGIN WHERE login = :login");
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        $fl = $stmt->fetchColumn();
    }
    catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        return false;
    }
    return $fl;
    }

    function generate_pass(int $length=9):string{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $shuff = str_shuffle($characters);
    return substr($shuff, 0, $length);
    }

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
    }//функция для проверки почты

    function isValid($login, $db) {
    $count;
    try{
      $stmt = $db->prepare("SELECT COUNT(*) FROM person_LOGIN WHERE login = ?");
      $stmt->execute([$login]);
      $count = $stmt->fetchColumn();
    } 
    catch (PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
    return $count > 0;
  }

  function getLangs($db){
    try{
      $allowed_lang=[];
      $data = $db->query("SELECT namelang FROM languages")->fetchAll();
      foreach ($data as $lang) {
        $lang_name = $lang['namelang'];
        $allowed_lang[$lang_name] = $lang_name;
      }
      return $allowed_lang;
    } catch(PDOException $e){
      print('Error: ' . $e->getMessage());
      exit();
    }
  }
  //$_SESSION['login']

function insertData($login, $db) {
    $values = []; // Локальная переменная для хранения данных

    // SQL-запросы и соответствующие ключи в массиве $values
    $queries = [
        'fio' => "SELECT fio FROM person JOIN person_LOGIN USING(id) WHERE login = :login",
        'field-email' => "SELECT email FROM person JOIN person_LOGIN USING(id) WHERE login = :login",
        'field-tel' => "SELECT tel FROM person JOIN person_LOGIN USING(id) WHERE login = :login",
        'field-date' => "SELECT bdate FROM person JOIN person_LOGIN USING(id) WHERE login = :login",
        'radio-group-1' => "SELECT gender FROM person JOIN person_LOGIN USING(id) WHERE login = :login",
        'bio' => "SELECT biography FROM person JOIN person_LOGIN USING(id) WHERE login = :login"
    ];

    // Выполняем запросы в цикле
    foreach ($queries as $key => $sql) {
        try {
            $stmt = $db->prepare($sql);
            if ($stmt === false) {
                error_log("Ошибка подготовки запроса: " . print_r($db->errorInfo(), true));
                throw new Exception("Ошибка подготовки запроса"); // Выбрасываем исключение
            }
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                error_log("Ошибка выполнения запроса: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Ошибка выполнения запроса"); // Выбрасываем исключение
            }
            $values[$key] = $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Ошибка при выполнении запроса для ключа " . $key . ": " . $e->getMessage());
            // Можно добавить дополнительную логику обработки ошибок, например, установить значение по умолчанию
            $values[$key] = null; // Или другое значение по умолчанию
        }
    }

    // Обрабатываем языки отдельно
    $sql = "SELECT lang.namelang
            FROM personlang pl
            JOIN person_LOGIN l ON pl.pers_id = l.id
            JOIN languages lang ON pl.lang_id = lang.id
            WHERE l.login = :login";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        $lang = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        $values['languages'] = implode(",", $lang);
    } catch (PDOException $e) {
        error_log("Ошибка при получении языков: " . $e->getMessage());
        $values['languages'] = null; // Или другое значение по умолчанию
    }

    return $values; // Возвращаем массив $values
}


function adminlog($db)
{
    $query = "SELECT login FROM LOGIN where role='ADMIN'"; // Запрос с параметром

    $stmt = $db->prepare($query); // Подготавливаем запрос
    $stmt->execute();// Выполняем запрос с параметром
    $adminlogin = $stmt->fetchColumn();
    return $adminlogin;
}