<?php
    // чтение или запись пользователей
    function readUsers() {
        $flag = 0;
        $h = fopen("users.csv", 'rb+');
        if($h) {
            while ($data = fgetcsv($h, 1000, ";")) {
                if ($flag == 0) {
                    $usersColumn = $data;
                    $flag++;
                }
                else {
                    $users[$data[0]];
                    foreach ($data as $k => $v) {
                        $users[$data[0]][$usersColumn[$k]] = $v;
                    }
                }
            }
        }
        fclose($h);
        return $users;
    }

    // проверка ошибок
    function check_error($err){
        foreach ($err as $k => $v) {
            if($v !== false) 
                return true;
        }
        return false;
    }

    // проверка логина
    function checkLogin($str) {
        // Если отсутствует строка с логином, возвращаем сообщение об ошибке
        if(!$str) {
            return " Вы не ввели имя пользователя";
        }
        //Проверяем имя пользователя с помощью регулярных выражений
        if(!preg_match("/^[a-zA-Z0-9]+$/",$str)) {
            return " Логин может состоять только из букв английского алфавита и цифр";
        }
        if(mb_strlen($str) < 3 or mb_strlen($str) > 20) {
            return " Логин должен быть не меньше 3-х символов и не больше 20";
        }
        return false;
    }

    // проверка пароля
    function checkPassword($str) {
        if(!$str) {
            return " Вы не ввели пароль";
        }
        if(!preg_match("/^[a-zA-Z0-9]+$/",$str)) {
            return " Пароль может состоять только из букв английского алфавита и цифр";
        }
        if(mb_strlen($str) < 3 or mb_strlen($str) > 20) {
            return " Пароль должен быть не меньше 3-х символов и не больше 20";
        }
        return false;
    }

    // проверка мыла
    function checkEmail($str) {
        if(!$str) {
            return " Вы не ввели e-mail";
        }
        if(!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i",$str)) {
            return " E-mail введен не верно";
        }
        return false;
    }

    // сохранение анкеты через текстовый файл
    function xss($data){
        $ank = "[";
        if(is_array($data)) {
            foreach ($data as $key => $value) {
                $ank .= $key.'=>';
                if(is_array($value))
                {
                    $ank .= xss($value);
                }
                else {
                    $ank .= $value;
                    $ank .= ';';
                }
            }
        }
        else
            $ank .= $data;
        $ank .= "]";
        return $ank;
    }

    // распаковка анкеты из текстового файла
    function unpacking($str){
        $data = NULL;
        if(mb_strpos($str, '[') !== false){
            $str=mb_substr($str, 1, -1);
            while(strlen($str)){
                if(mb_strpos($str, '=>') !== false){
                    $key =  mb_substr($str, 0, mb_strpos($str, '=>'));
                    $str = mb_substr($str, mb_strpos($str, '=>') + 2);
                    if(mb_strpos($str, '[') === 0) {
                        $flag = 0;
                        $new = "";
                        for($i = 0; $i < mb_strlen($str); $i++) {
                            $new .= $str[$i];
                            if($str[$i] == "[") 
                                $flag++;
                            if($str[$i] == "]")
                                $flag--;
                            if($flag == 0)
                                break;
                        }
                        $data[$key] = unpacking($new);
                        $str = mb_substr($str, mb_strlen($new) + 1);
                    } 
                    elseif(mb_strpos($str, ';') !== false){
                        $data[$key] = mb_substr($str, 0, mb_strpos($str, ';'));
                        $str = mb_substr($str, mb_strpos($str, ';') + 1);
                    }
                    else {
                        $data[$key] = mb_substr($str, 0);
                        $str = "";
                    }
                }
            }
        }
        else echo "Ошибка!";
        return $data;
    }
?>