<?php
    require_once("connect.php");
    require_once("functions.php");

    // получение данных из файла
    $str = file_get_contents("Z:\home\MyMainProject\www\\".$_SESSION['user']['login'].'.txt');
    $data = unpacking($str);

    // чтение пользователея
    $users = readUsers();
    
    // константы
    define('LINE', 0); //000 00
    define('TRIANGLE', 1); //000 01
    define('RECTANGLE', 2); //000 10
    define('ELLIPSE', 3); //000 11
    define('BLACK', 0); //000 00
    define('BLUE', 4); //001 00
    define('GREEN', 8); //010 00
    define('YELLOW', 12); //011 00
    define('ORANGE', 16); //100 00
    define('RED', 20); //101 00
    define('WHITE', 24); //110 00
    define('VIOLET', 28); //111 00

    // отрисовка характеристики пользователя
    $colors = ['black', 'blue', 'green', 'yellow', 'orange', 'red', 'white', 'violet'];
    $figs = ['line', 'triangle', 'rectangle', 'ellipse'];
    $angle = 45 << 5; // угол поворота на 45 градусов сдвигаем на 5 бит
    $heigth = 30 << 14; // высота 30 сдвигаем на 14 бит
    $width = 30 << 23; //ширина 30 сдвигаем на 23 бита

    $chislo = $data['day'].$data['month'].$data['year'].$data['predmet']['toch']['math'].$data['predmet']['toch']['informatika']; // делаем 11-значное  число для характеристики
    $fig = $chislo & 3;
    $color = (($chislo & 28) >> 2);
    $a = (($chislo & 16352) >> 5);
    $h = (($chislo & 8372224) >> 14);
    $w = (($chislo & 4286378688) >> 23);
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
                <div class="first">
                    <p><span>Имя: </span><?php echo $data['name']?></p>
                    <p><span>Фамилия: </span><?php echo $data['surname']?></p>
                    <p><span>Отчество: </span><?php echo $data['otchestvo']?></p>
                    <p><span>Дата рождения: </span><?php echo $data['day'].".".$data['month'].".".$data['year'];?></p>
                    <p><span>Пол: </span><?php echo $data['gender']?></p>
                    <p><span>Увлечения: </span><?php echo $data['hobby']?></p>
                    <p><span>E-mail: </span><?php echo $users[$_POST['login']]['email']?></p>
                    <p><span>Логин: </span><?php echo $_SESSION['user']['login']?></p>
                    <label>Характеристика пользователя по заполненной анкете.</label>
                    <form method="POST" action="anketa.php" autocomplete="off" name="reg">
                        <div class="char">
                            <div class="<?=$figs[$fig];?>" style="width:<?=30+$w;?>px; transform: rotate(<?=$a;?>deg); height:<?=$h;?>px; background-color: <?=$colors[$color];?>;">
                            </div>
                        </div>
                    </form>
                </div>
            </article>
            <?php
                require_once("footer.php");
            ?>
        </div>
    </body>
</html> 