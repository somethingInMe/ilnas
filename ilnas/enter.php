<?php
  include "config.php";
  include "classes/SafeMySQL.php";
  include "classes/CUser.php";
  $db = new SafeMySQL(array('user' => USER,'pass' => PASS,'db' => DB,'charset' => 'utf8'));
  $User = new CUser($db);

// Если пользователь авторизован, редиректим на главную  
  if($User->isAuthorized())
  {
    header("Location: index.php");
    exit;
  }
// Если есть хэш в куках, авторизуемся
  if($User->loginByHash()) header("Location: index.php");

    $errors_u = array(); // инициализируем массив ошибок логина
    $errors_p = array(); // инициализируем массив ошибок пароля
    $fields = array(); // инициализируем массив значений
    $message = "";
 
    if (isset($_POST["submit"]))
    {
         $val = "";
         // подключение класса
         require("classes/Validation.php");
         $rules_u = array(); // инициализируем массив для правил логина
         $rules_p = array(); // инициализируем массив для правил пароля
       
         // устанавливаем правила валидации
         $rules_u[] = "required,user,Укажите логин";
         $rules_p[] = "required,pass,Укажите пароль";
         $rules_u[] = "is_alpha,user,Неверный логин или пароль";
         $rules_p[] = "is_alpha,pass,Неверный логин или пароль";
         $rules_u[] = "length=3-20,user,Неверный логин или пароль";
         $rules_p[] = "length=6-20,pass,Неверный логин или пароль";  
         
         $errors_u = validateFields($_POST, $rules_u);
         $errors_p = validateFields($_POST, $rules_p);
         // если есть ошибки, активируем алерты формы и выводим первые попавшиеся ошибки
         if (!empty($errors_u) || !empty($errors_p))
         {
            $fields = $_POST;
            if (!empty($errors_u)) $user_alert = 'alert';
            if (!empty($errors_p)) $pass_alert = 'alert';
            if ($errors_u[0] == $errors_p[0]) 
            {
              $user_alert = 'alert';
              $pass_alert = 'alert';
              $error = 'Неверный логин или пароль';
              $errors_u[0] = '';
              $errors_p[0] = '';
            }
         }
         // Нет ошибок валидации! Проверяем, есть ли такой логин в базе, и если есть, сверяем пароли
         else
         {
            $usrdta = $User->getByLogin($_POST["user"]);
            if(!$usrdta) 
            {
              $errors_u[0] = 'Пользователя с таким именем не зарегистрировано';
              $user_alert = 'alert';
              $val = 'value = "'.$_POST["user"].'"';
            }
            else
            {
              if (md5($_POST["pass"]) !== $usrdta['password'])
              {
                $errors_p[0] = 'Неверный логин или пароль';
                $user_alert = 'alert';
                $pass_alert = 'alert';
              } else 
              {
                if ($User->authorize($usrdta['login'])) 
                {
                  header("Location: index.php");
                  exit;
                }
              }
            }
         }
         // Если перед отправкой формы логин был введён, записать его в переменную $value
         if ($_POST["user"] !== '' && $error == NULL && $errors_u[0] == '')
         {
          $val = 'value = "'.$_POST["user"].'"';
         }
    }

  

// ВЕСЬ ВЫВОД НИЖЕ
  include "templates/auth_form.php";
?>