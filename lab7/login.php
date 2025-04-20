<?php
require_once 'db.php';
require_once 'functions.php';
/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');


// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
$session_started = false;
$p=1;
$l=true;
if (isset($_COOKIE[session_name()]) && session_start()) {
  $session_started = true;
  if (!empty($_SESSION['login'])) {
    if(isset($_POST['logout'])){
      session_unset();
      session_destroy();
      header('Location: login.php');
      exit();
    }
    header('Location: ./');
    exit();
  }
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  include 'htmlcssmodules.php';
?>

            
            <div class="content container-fluid mt-sm-0" >
              <div class="log">
                <form action="" method="post">
                  <label> 
                    Логин:<br />
                    <input name="login" />
                  </label> <br />
                  <label>
                    Пароль:<br />
                    <input name="pass" />
                  </label>
                  <br />
                  <input type="submit" value="Войти" />
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                </form>
                <a href="adm_page.php">Вход для администратора</a>
              </div>
            </div>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  if (!validateCsrfToken()) {
    http_response_code(403);
    die('CSRF token validation failed.');
}
  $login = $_POST['login'];
  $password = $_POST['pass'];
  if (!$session_started) {
    session_start();
  }
  if(!isValid($login, $db))
  {
    $l=false;
  }
  if(!password_check($login, $password, $db))
  {
    $p=0;
  }
  if (isValid($login, $db) && password_check($login, $password, $db)){
    $_SESSION['login'] = $_POST['login'];

    $_SESSION['uid'];
    try {
        $stmt_select = $db->prepare("SELECT id FROM person_LOGIN WHERE login=?");
        $stmt_select->execute([$_SESSION['login']]);
        $_SESSION['uid']  = $stmt_select->fetchColumn();
    } catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }

    // Делаем перенаправление.
    header('Location: index.php');
  }
  else {
    print('Неверный логин или пароль');
  }
}
