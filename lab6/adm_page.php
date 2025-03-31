<?php
    require_once 'db.php';
    require_once 'functions.php';
    $query = "SELECT login FROM LOGIN where role='ADMIN'"; // Запрос с параметром

        $stmt = $db->prepare($query); // Подготавливаем запрос
        $stmt->execute();// Выполняем запрос с параметром
        $adminlogin = $stmt->fetchColumn();
    
    if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !=  $adminlogin || !password_check($adminlogin, $_SERVER['PHP_AUTH_PW'], $db)) 
    {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
    }
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $query = "SELECT id, fio, tel, email, bdate, gender, biography FROM person"; // Запрос с параметром

        $stmt = $db->prepare($query); // Подготавливаем запрос
        $stmt->execute();// Выполняем запрос с параметром
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $query_languages = "SELECT
                            pl.pers_id,
                            l.namelang
                        FROM
                            personlang pl
                        JOIN
                            languages l ON pl.lang_id = l.id";
    $stmt_languages = $db->prepare($query_languages);
    $stmt_languages->execute();
    $person_languages = $stmt_languages->fetchAll(PDO::FETCH_ASSOC);
    $languages_by_person = [];
    foreach ($person_languages as $row) {
        $person_id = $row['pers_id'];
        $language_name = $row['namelang']; // Используем language_name
        if (!isset($languages_by_person[$person_id])) {
            $languages_by_person[$person_id] = [];
        }
        $languages_by_person[$person_id][] = $language_name; // Добавляем название языка
    }
    include 'htmlcssmodules.php';
    ?>
        <div class="content container-fluid mt-sm-0" >
            <h3>Вы видите защищенные паролем данные</h3>
            <table>
            <tr class="nametb px-sm-2 pt-sm-2 pb-sm-2">
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
                <?php
                    // 3. Используем implode для объединения языков
                    $person_id = $row['id'];
                    if (isset($languages_by_person[$person_id])) {
                        $languages_string = implode(', ', $languages_by_person[$person_id]);
                        echo htmlspecialchars($languages_string);
                    } else {
                        echo "Нет данных";
                    }
                    ?>
                    </td>
                    <td>
                    <form method="post" action="">
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit">Удалить</button>
                    </form>
                    <a href="index.php?uid=<?= htmlspecialchars($row['id']) ?>">Изменить</a>
                </td>
                </tr>
        <?php endforeach; ?>
        </table>

        <?php
        try {
            echo "<table class='stat'><thead> <tr class="nametb px-sm-2 pt-sm-2 pb-sm-2"><td>LANGUAGE</td><td>COUNT</td></tr></thead> ";
            $stmt = $db->prepare("SELECT l.namelang, COUNT(pl.pers_id) AS cnt
            FROM personlang pl
            JOIN languages l ON pl.lang_id = l.id
            GROUP BY l.namelang");
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                echo "<tr><td>$row->namelang</td><td>$row->cnt</td></tr>";
            }
            echo "</table>";
            echo"</div>";
        }
        catch (PDOException $e){
            print('ERROR : ' . $e->getMessage());
            exit();
        }
    ?>

<?php

    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
        if($_SERVER['PHP_AUTH_USER'] == 'admin' || md5($_SERVER['PHP_AUTH_PW']) == md5('123'))
        {
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
    }

    // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    //     $update_id = $_POST['update_id'];
    //     $update_query = "SELECT login FROM person_LOGIN WHERE id = :id";
    //     try {
    //         $update_stmt = $db->prepare($update_query);
    //         $update_stmt->bindParam(':id', $update_id, PDO::PARAM_INT);
    //         $update_stmt->execute();
    //         $doplog=$update_stmt->fetchColumn();
    //     }
    //     catch (PDOException $e){
    //         print('Error : ' . $e->getMessage());
    //         exit();
    //     }
    //     session_start();
    //     $_SESSION['login']=$doplog;
    //     $_SESSION['uid']=$_POST['update_id'];
    //     header("Location: index.php");
    // }
