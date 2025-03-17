
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
            echo '<form method="get action="">
            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['id']) ?>">
            <button type="submit"></button>
          </form>';
            echo '<form method="post" action="">
            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['id']) ?>">
            <button type="submit">Удалить</button>
          </form>';
            echo "</tr>";
            }


    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
      
        // Защита от SQL-инъекций с использованием подготовленного запроса
        $delete_query = "DELETE FROM person WHERE id = :id";
        try {
          $delete_stmt = $db->prepare($delete_query);
          $delete_stmt->bindParam(':id', $delete_id, PDO::PARAM_INT); // Явно указываем тип параметра
          $delete_stmt->execute();
      
          echo "<p style='color: green;'>Строка с ID " . htmlspecialchars($delete_id) . " успешно удалена.</p>";
      
          // Перенаправление на эту же страницу для обновления таблицы (необязательно)
          header("Location: ".$_SERVER['PHP_SELF']);
          exit;
      
        } catch (PDOException $e) {
          echo "<p style='color: red;'>Ошибка удаления: " . $e->getMessage() . "</p>";
        }
      }
}