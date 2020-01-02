<?php
    require_once("connect.php");
    require_once("functions.php");
    
    if(isset($_COOKIE['login'])) {
        $_SESSION['user'] = ['login' => $_COOKIE['login']];
    }

    $users = readUsers();

    if($_POST['login'] && $users[$_POST['login']]['password'] == md5($_POST['password'].SALT)){
        setcookie('login', $_POST['login'], time() + 60*60*24*30);
        setcookie('email', $users[$_POST['login']]['email'], time() + 60*60*24*30);
        $_SESSION['user'] = ['login' => $users[$_POST['login']]];
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
             	<p><a href="index.php">Главная</a> \ Личный кабинет</p>
                <form method="POST" action="auth.php" autocomplete="off">
                    <div class="first">
                        <table>
                            <tr>
                                <td><label for="loginField" class="s">Логин</label></td>
                                <td><input id="loginField" type="text" name="login" pattern="[a-zA-Z0-9]{5,}" title="Минимум 3 букв или цифр" required=""></td>
                            </tr>
                            <tr>
                                <td><label for="passwordField" class="s">Пароль</label></td>
                                <td><input id="passwordField" type="password" name="password" pattern=".{5,}" title="Минимум 3 буквы или цифры" required=""></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center"><input class="btn" type="submit" value="Войти"></td>
                            </tr>
                        </table>
                        <?php
                            if($_SESSION['user']) {
                                echo "Вы авторизованы!";
                                exit("<html><head><meta http-equiv='Refresh' content='0; URL=kabinet.php'></head></html>");
                            }
                            else if (!(is_array($users[$_POST['login']]))) {
                                echo "Такого пользователя не существует! Зарегестрируйтесь!";
                            }
                        ?>
                    </div>
                </form>
            </article>
            <?php
                require_once("footer.php");
            ?>
        </div>
    </body>
</html> 