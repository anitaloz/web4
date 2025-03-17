
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
    if($_SERVER['REQUEST_METHOD'] == 'GET')
}