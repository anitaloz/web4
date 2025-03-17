
<?php
if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_USER'] != 'admin' || md5($_SERVER['PHP_AUTH_PW']) != md5('123')) 
{
    ?>
    <html>
    <h1>Требуется авторизация<h1>
    <?php
}
else
{
    $user = 'u68598'; // Заменить на ваш логин uXXXXX
    $pass = '8795249'; // Заменить на пароль
    $db = new PDO('mysql:host=localhost;dbname=u68598', $user, $pass,
      [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $query = "SELECT id, fio, tel, email, bdate, gender, biography FROM person"; // Запрос с параметром

        $stmt = $db->prepare($query); // Подготавливаем запрос
        $stmt->execute();// Выполняем запрос с параметром
        //$result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        echo "<table border='1'>"; // Начало HTML-таблицы
        echo "<tr>
                <th>ID</th>
                <th>FIO</th>
                <th>Tel</th>
                <th>email</th>
                <th>bdate</th>
                <th>gender</th>
                <th>biography</th>
            </tr>"; // Заголовки столбцов

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fio']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tel']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['bdate']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
            echo "<td>" . htmlspecialchars($row['biography']) . "</td>";
            echo '<td> <form class="update_form" action="index.php" method="POST">
            <input type="submit" name="update" value="Изменить"/> </form> </td>';
            echo '<td> <form class="delete_form" action="index.php" method="POST">
            <input type="submit" name="delete" value="Удалить"/> </form> </td>';
            echo "</tr>";
            }

        echo "</table>"; // Конец HTML-таблицы
    }
}