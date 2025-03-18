
<?php
if ($login=='admin' && $password=='123'){
    if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] != 'admin' || md5($_SERVER['PHP_AUTH_PW']) != md5('123')) 
    {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
    }
    else {
      header('Location: adm_page.php');
    }
  //include('admin.php');
}
if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] != 'admin' || md5($_SERVER['PHP_AUTH_PW']) != md5('123')) 
{
    ?>
    <html>
    <h1>Требуется авторизация<h1>
    <?php
}
else
{
    ;
    $user = 'u68598'; // Заменить на ваш логин uXXXXX
    $pass = '8795249'; // Заменить на пароль
    $db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
      [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $query = "SELECT id, fio, tel, email, bdate, gender, biography FROM person"; // Запрос с параметром

        $stmt = $db->prepare($query); // Подготавливаем запрос
        $stmt->execute();// Выполняем запрос с параметром
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <table border='1'>
        <tr>
            <th>ID</th>
            <th>FIO</th>
            <th>Tel</th>
            <th>Email</th>
            <th>Bdate</th>
            <th>Gender</th>
            <th>Biography</th>
            <th>Languages</th>
            <th>Действия</th>
        </tr>

        <?php foreach ($results as $row): ?>
            <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['fio']) ?></td>
            <td><?= htmlspecialchars($row['tel']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['biography']) ?></td>
            <td>
                <form method="post" action="">
                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['id']) ?>">
                <button type="submit">Удалить</button>
                </form>
                <form method="post" action="">
                <input type="hidden" name="update_id" value="<?= htmlspecialchars($row['id']) ?>">
                <button type="submit">Изменить</button>
                </form>
            </td>
            </tr>
        <?php endforeach; ?>

        </table>

<?php

    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $delete_query = "DELETE FROM person WHERE id = :id";
        $delete_querylang="DELETE FROM personlang WHERE pers_id=:id";
        $delete_querylogin="DELETE FROM person_LOGIN WHERE id=:id";
        $addition_query="SELECT login FROM person_LOGIN WHERE id=:id";
        $delete_LOGIN="DELETE FROM LOGIN WHERE login=:login";
        try {
            $delete_stmt = $db->prepare($addition_query);
            $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
            $delete_stmt->execute();
            $doplog=$delete_stmt->fetchColumn();
            $delete_stmt = $db->prepare($delete_querylogin);
            $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT); 
            $delete_stmt->execute();
            $delete_stmt = $db->prepare($delete_LOGIN);
            $delete_stmt->bindParam(':login', $doplog, PDO::PARAM_STR); 
            $delete_stmt->execute();
            $delete_stmt = $db->prepare($delete_querylang);
            $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT); 
            $delete_stmt->execute();
            $delete_stmt = $db->prepare($delete_query);
            $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
            $delete_stmt->execute();
        
            //echo "<p style='color: green;'>Строка с ID " . htmlspecialchars($delete_id) . " успешно удалена.</p>";
            //$messAction[] = '<p style="color: green;">Строка с ID " . htmlspecialchars($delete_id) . " успешно удалена.</p>';
            header("Location: adm_page.php");
            exit;
        
            } catch (PDOException $e) {
            echo "<p style='color: red;'>Ошибка удаления: " . $e->getMessage() . "</p>";
            }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
        $update_id = $_POST['update_id'];
        $update_query = "SELECT login FROM person_LOGIN WHERE id = :id";
        try {
            $update_stmt = $db->prepare($update_query);
            $update_stmt->bindParam(':id', $update_id, PDO::PARAM_INT);
            $update_stmt->execute();
            $doplog=$update_stmt->fetchColumn();
        }
        catch (PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }
        session_start();
        $_SESSION['login']=$doplog;
        $_SESSION['uid']=$_POST['update_id'];
        header("Location: index.php");
    }
}