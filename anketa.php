<?php
    require_once("connect.php");
    require_once("functions.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['send']) {
            $anketa = $_POST;

            // обработка ошибок
            if (!checkdate($anketa['month'], $anketa['day'], $anketa['year'])) {
                $error['DATE'] = " Дата не корректная!";
            }
            if (mb_strlen($anketa['name']) < 2){
                $error['name'] = " Имя введено не верно!";
            }
            if (mb_strlen($anketa['surname']) < 2){
                $error['surname'] = " Фамилия введена не верно!";
            }
            if($anketa['gender'] == false){
                $error['gender'] = " Пол не выбран!";
            }
            if(!is_numeric($anketa['predmet']) || $anketa['predmet'] > 5 || $anketa['predmet'] < 1){
                $error['predmet'] = "Неверный ввод оценок";
            }
            
            // работа с оценками
            foreach ($anketa['predmet']['toch'] as $key => $value) {         //общий балл по точным наукам
                $anketa['predmet']['ocenka']['toch']['total'] += (int)$value;
            }
            foreach ($anketa['predmet']['gum'] as $key => $value) {          //общий балл по гумманитарным наукам
                $anketa['predmet']['ocenka']['gum']['total'] += (int)$value;
            }

            $anketa['predmet']['ocenka']['total'] = $anketa['predmet']['ocenka']['toch']['total'] + $anketa['predmet']['ocenka']['gum']['total']; // сумма

            $anketa['predmet']['ocenka']['toch']['average'] = $anketa['predmet']['ocenka']['toch']['total'] / count($anketa['predmet']['toch']);     //средний балл по точным наукам
            $anketa['predmet']['ocenka']['gum']['average'] = $anketa['predmet']['ocenka']['gum']['total'] / count($anketa['predmet']['gum']);        //средний балл по гумманитарным наукам
            $anketa['predmet']['ocenka']['average'] = ($anketa['predmet']['ocenka']['toch']['average'] +  $anketa['predmet']['ocenka']['gum']['average']) / 2;   //средний балл по всем предметам
            
            rsort($anketa['predmet']); //сортировка предметов по убыванию оценок
            
            // сохраняем анкету в личный файл
            $res = fopen($_SESSION['user']['login'].'.txt', 'wb+');
            $str = xss($anketa); 
            fwrite($res, $str);
            fclose($res);

            header('Location: http://mymainproject/kabinet.php');
        }
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
            <div>
                <article>
                 	<p><a href="index.php">Главная</a> \ Регистрация (2/2)</p>
                    <p>Чтобы пройти регистрацию на нашем сайте заполните простую анкету.</p>
                    <div>
                        <?php if(!$_POST || ($_POST && $error)): ?>
                        <form action="anketa.php" method="POST" name="forma">
                            <div class="first">
                                <label>Имя:<span class='error'><?=$error['name'];?></span></label><br>
                                <input type="text" name="name" maxlength="20" value="" required=""><br>

                                <label>Фамилия:<span class='error'><?=$error['surname'];?></span></label><br>
                                <input type="text" name="surname" maxlength="20" value="" required=""><br>

                                <label>Отчество:</label><br>
                                <input type="text" name="otchestvo" maxlength="20" value="" ><br>

                                <label>Дата рождения:<span class='error'><?=$error['DATE'];?></span></label><br>
                                <div class="select">
                                    <select name="day" required="">
                                        <option disabled selected>Выберите день</option>
                                        <?php 
                                            for ($i = 1; $i <= 31; $i++){
                                                echo "<option value = \"".($i < 10 ? "0{$i}" : $i)."\">".($i < 10 ? "0{$i}" : $i)."";
                                            }
                                        ?>
                                    </select>
                                    <select name="month" required="">
                                        <option disabled selected>Выберите месяц</option>
                                        <?php 
                                            $month = array('01' => "Январь", '02' => "Февраль", '03' => "Март", '04' => "Апрель", '05' => "Май", '06' => "Июнь", '07' => "Июль", '08' => "Август", '09' => "Сентябрь", '10' => "Октябрь", '11' => "Ноябрь", '12' =>"Декабрь");
                                            foreach($month as $num => $name_m) {
                                                echo "<option value = \"$num\" >$name_m";
                                            }
                                        ?>
                                    </select>
                                    <select name="year" required="">
                                        <option disabled selected>Выберите год</option>
                                        <?php 
                                            for ($i = ((int)date('Y')-5); $i >= ((int)date('Y')-100); $i--){
                                                echo "<option value = \"$i\">$i";
                                            }
                                        ?>
                                    </select><br>
                                </div>
                            </div>
                            <label>Пол:<span class='error'><?=$error['gender'];?></span></label><br>
                            <input class="radio" type="radio" value="Мужской" name="gender"/><label for="radio">М</label>
                            <input class="radio" type="radio" value="Женский" name="gender"/><label for="radio">Ж</label>
                            <div class="hobby">
                                <label>Увлечения:</label><br>
                                <input type="text" name="hobby" maxlength="30" value="" required=""><br>
                            </div>
                            <label>Любимые жанры:</label><br>
                            <div class="ganrs">
                                <label>Комедия</label>
                                <input type="checkbox" name="ganre[]" value="comedy">
                                <label>Боевик</label>
                                <input type="checkbox" name="ganre[]" value="action">
                                <label>Триллер</label>
                                <input type="checkbox" name="ganre[]" value="thriller">
                                <label>Детектив</label>
                                <input type="checkbox" name="ganre[]" value="detective">
                                <label>Ужасы</label>
                                <input type="checkbox" name="ganre[]" value="horrors"><br>
                                <label>Документальный</label>
                                <input type="checkbox" name="ganre[]" value="documentary">
                                <label>Драма</label>
                                <input type="checkbox" name="ganre[]" value="drama">
                                <label>Мультфильм</label>
                                <input type="checkbox" name="ganre[]" value="cartoon">
                                <label>Романтик</label>
                                <input type="checkbox" name="ganre[]" value="romantic"><br>
                            </div>
                            <label>Внесите данные об оценках в школе:<span class='error'><?=$error['predmet'];?></span></label><br>
                            <div class="subjects">
                                <label>Математика:</label>
                                <input type="text" name="predmet[toch][math]" size="1" value="" >
                                <label>Русский язык:</label>
                                <input type="text" name="predmet[gum][russian]" size="1" value="" >
                                <label>Информатика:</label>
                                <input type="text" name="predmet[toch][informatika]" size="1" value="" >
                                <label>Физика:</label>
                                <input type="text" name="predmet[toch][physics]" size="1" value="" ><br>
                                <label>Английский:</label>
                                <input type="text" name="predmet[gum][english]" size="1" value="" >
                                <label>Химия:</label>
                                <input type="text" name="predmet[toch][chemistry]" size="1" value="" >
                                <label>Биология:</label>
                                <input type="text" name="predmet[toch][biology]" size="1" value="" >
                                <label>Литература:</label>
                                <input type="text" name="predmet[gum][literature]" size="1" value="" ><br>
                            </div>
                            <input type="submit" name="send" value = "Отправить" class="btn"/><a href="kabinet.php"></a>
                            <input type="reset" name="reset" id="btn-reset" value="Очистить" class="btn"/>
                        </form>
                        <? endif;?>
                    </div>
                </article>
            </div>
            <?php
                require_once("footer.php");
            ?>
        </div>
    </body>
</html> 