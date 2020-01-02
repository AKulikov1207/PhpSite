<?php
    require_once("connect.php");
    require_once("functions.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $anketa = $_POST;

        define('SALT', 'FIFA'); // константа для пароля

        // обработка логина пароля и мыла
        if(isset($_POST['send'])) {
            trim($anketa['login']);
            trim($anketa['email']);
            trim($anketa['password']);
            mb_strtolower($anketa['login']);
            ucfirst($anketa['login']);
            // проверки на ошибки ввода
            $error['email'] = checkEmail($anketa['email']);
            $error['password'] = checkPassword($anketa['password']);
            $error['login'] = checkLogin($anketa['login']);
            $pass = md5($_POST['password'].SALT); // кодирование пароля 
        }

        $reg = $anketa['email'].'; '.$anketa['login'].'; '.$pass."\r\n"; // строка для сохранения
        
        $users = readUsers();

        if (is_array($users[$_POST['login']])) {
            $error['users'] = "Есть такой пользователь! Авторизуйтесь!";
        }
        else {
            $res = fopen('users.csv', 'ab+'); // сохраняем данные в файл
            fwrite($res, $reg);
            fclose($res);
        }

        // создаем куки и устанавливаем сессию
        setcookie('login', $_POST['login'], time() + 60*60*24*30);
        setcookie('email', $users[$_POST['login']]['email'], time() + 60*60*24*30);
        $_SESSION['user'] = ['login' => $_POST['login']];
    }
?>
<!DOCTYPE html>
<html>
     <head>
	     <meta charset="utf-8">
	     <title>Cайт-регистрация</title>
	     <link rel="stylesheet" type="text/css" href="css/style.css">
         <link rel="shortcut icon" type="image/x-icon" href="img/favicon-16x16.png">
    </head>
    <body>
        <div class="container">
            <?php
                require_once("header.php");
                require_once("menu.php");
            ?>
            <article>
             	<p><a href="index.php">Главная</a> \ Регистрация (1/2)</p>
                <?php if(!$_POST || ($_POST && check_error($error))): ?>
                <p>Заполните данные поля для регистрации.</p>
                <form method="POST" action="registration.php" autocomplete="off" name="reg">
                    <div class="first">
                        <label for="loginField"><span class="s">E-mail:</span><span class='error'><?=$error['email'];?></span></label><br>
                        <input type="text" id="loginField" name="email" maxlength="20" value="" required=""><br>

                        <label for="loginField"><span class="s">Логин:</span><span class='error'><?=$error['login'];$error['users'];?></span></label>
                        <br>
                        <input type="text" id="loginField1" name="login" maxlength="20" value="" required=""><br>

                        <label for="passwordField"><span class="s">Пароль:</span><span class='error'><?=$error['password'];?></span></label><br>
                        <input type="password" id="passwordField" name="password" maxlength="20" value="" required=""><br>

                        <input type="submit" name="send" value = "Зарегестрироваться" class="btn1">
                        <input type="reset" name="reset" id="btn-reset" value="Очистить" class="btn1">
                    </div>
                </form>
                <?php else:?> 
                    <div class="down">
                        <label>Регистрация прошла успешна!</label><br>
                        <a href="anketa.php">Заполнить анкету</a>
                    </div> 
                <? endif;?>
            </article>
            <?php
                require_once("footer.php");
            ?>
        </div>
    </body>
</html> 