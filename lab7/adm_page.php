<?php
    require_once 'db.php';
    require_once 'functions.php';
    $adminlogin=adminlog($db);
    if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] !=  $adminlogin || !password_check($adminlogin, $_SERVER['PHP_AUTH_PW'], $db)) 
    {
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Требуется авторизация</h1>');
        exit();
    }
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        session_start();
        $query = "SELECT id, fio, tel, email, bdate, gender, biography FROM person"; 

        $stmt = $db->prepare($query); 
        $stmt->execute();
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
            $language_name = $row['namelang'];
            if (!isset($languages_by_person[$person_id])) {
                $languages_by_person[$person_id] = [];
            }
            $languages_by_person[$person_id][] = $language_name; 
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

            <?php
            foreach ($results as $row): ?>
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
            echo "<table class='stat'><thead> <tr class='nametb px-sm-2 pt-sm-2 pb-sm-2'><td>LANGUAGE</td><td>COUNT</td></tr></thead> ";
            $stmt = $db->prepare("SELECT l.namelang, COUNT(pl.pers_id) AS cnt
            FROM personlang pl
            JOIN languages l ON pl.lang_id = l.id
            GROUP BY l.namelang");
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                echo "<tr><td>" . htmlspecialchars($row->namelang, ENT_QUOTES, 'UTF-8'). "</td><td>" . htmlspecialchars($row->cnt, ENT_QUOTES, 'UTF-8') . "</td></tr>";
            }
            echo "</table>";
            echo"</div>";
        }
        catch (PDOException $e){
            error_log('Database error: ' . $e->getMessage());
            exit();
        }
    ?>

<?php

    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
        $delete_id = intval($_POST['delete_id']); // Преобразуем в целое число
    
        if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_USER'] ==  $adminlogin && password_check($adminlogin, $_SERVER['PHP_AUTH_PW'], $db))
        {

        if ($delete_id === false) {
            echo "<p style='color: red;'>Недопустимый ID для удаления.</p>";
            exit;
        }
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
        
            header("Location: adm_page.php");
            exit;
        
            } catch (PDOException $e) {
                error_log('Database error: ' . $e->getMessage());
            }
        }
    }
