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