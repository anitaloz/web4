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
  function insertData($login, $db)
  {
        $sql = "SELECT fio FROM person join person_LOGIN using(id) WHERE login = :login"; 
        $stmt = $db->prepare($sql);
        if ($stmt === false) {
            error_log("Ошибка подготовки запроса: " . $db->errorInfo()[2]);
        }
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            error_log("Ошибка выполнения запроса: " . $stmt->errorInfo()[2]); 
        }
        $fio = $stmt->fetchColumn();
        $values['fio']=$fio;



        $sql = "SELECT email FROM person join person_LOGIN using(id) WHERE login = :login"; 
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $em = $stmt->fetchColumn();
            $values['field-email']=$em;
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }

        $sql = "SELECT tel FROM person join person_LOGIN using(id) WHERE login = :login"; 
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $tel = $stmt->fetchColumn();
            $values['field-tel']=$tel;
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }
        $sql = "SELECT bdate FROM person join person_LOGIN using(id) WHERE login = :login"; 
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $date = $stmt->fetchColumn();
            $values['field-date']=$date;
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }

        $sql = "SELECT gender FROM person join person_LOGIN using(id) WHERE login = :login"; 
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $gen = $stmt->fetchColumn();
            $values['radio-group-1']=$gen;
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }

        $sql = "SELECT biography FROM person join person_LOGIN using(id) WHERE login = :login"; 
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $bio = $stmt->fetchColumn();
            $values['bio']=$bio;
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }

        $sql = "SELECT lang.namelang
        FROM personlang pl
        JOIN person_LOGIN l ON pl.pers_id = l.id
        JOIN languages lang ON pl.lang_id = lang.id
        WHERE l.login = :login;";
        try{
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $lang = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            $langs_value1 =(implode(",", $lang));
            $values['languages']=$langs_value1;
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }
  }

function adminlog($db)
{
    $query = "SELECT login FROM LOGIN where role='ADMIN'"; // Запрос с параметром

    $stmt = $db->prepare($query); // Подготавливаем запрос
    $stmt->execute();// Выполняем запрос с параметром
    $adminlogin = $stmt->fetchColumn();
    return $adminlog;
}