
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

        pg_prepare($db, "my_query", $query); // Подготавливаем запрос
        
        //$min_calories = 100; // Пример значения параметра
        $result = pg_execute($db, "my_query", array()); // Выполняем запрос с параметром
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

        while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['fio']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tel']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dbate']) . "</td>";
        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
        echo "<td>" . htmlspecialchars($row['biography']) . "</td>";
        echo "</tr>";
        }

        echo "</table>"; // Конец HTML-таблицы
    }
}