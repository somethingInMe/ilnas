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
    $errors_e = array(); // инициализируем массив ошибок почты 
    $errors_p = array(); // инициализируем массив ошибок пароля
    $errors_r = array(); // инициализируем массив ошибок повтора пароля
    $val_login = "";  // инициализируем value для логина
    $val_email = "";  // инициализируем value для почты
    
    if (isset($_POST["submit"]))
    {
         // подключение класса
         require("classes/Validation.php");
         $rules_u = array(); // инициализируем массив для правил логина
         $rules_e = array(); // инициализируем массив для правил почты
         $rules_p = array(); // инициализируем массив для правил пароля
         $rules_r = array(); // инициализируем массив для правил повтора пароля
       
         // устанавливаем правила валидации
         $rules_u[] = "required,user,Укажите логин";
         $rules_e[] = "required,email,Укажите электронный адрес";
         $rules_p[] = "required,pass,Укажите пароль";
         $rules_r[] = "required,rpass,Повторите пароль";
         $rules_u[] = "is_alpha,user,Логин должен состоять из латиницы или цифр";
         $rules_e[] = "valid_email,email,Некорректный адрес электронной почты";
         $rules_p[] = "is_alpha,pass,Пароль должен состоять из латиницы или цифр";
         $rules_r[] = "same_as,pass,rpass,Введённые пароли не совпадают";
         $rules_u[] = "length=3-20,user,Логин должен быть от 3 до 20 символов длиной";
         $rules_p[] = "length=6-20,pass,Пароль должен быть от 6 до 20 символов длиной";  
         
         $errors_u = validateFields($_POST, $rules_u);
         $errors_e = validateFields($_POST, $rules_e);
         $errors_p = validateFields($_POST, $rules_p);
         $errors_r = validateFields($_POST, $rules_r);
         // если есть ошибки, активируем алерты формы и выводим первые попавшиеся ошибки
         if (!empty($errors_u))
         {
            $user_alert = 'alert';
         } 
         else
         {
            $bylogin = $User->getByLogin($_POST["user"]);
            if($bylogin) 
            {
              $errors_u[0] = 'Такой логин уже занят';
              $user_alert = 'alert';
            }
         }
         if (!empty($errors_e))
         {
            $email_alert = 'alert';
         } 
         else
         {
            $byemail = $User->getByEMail($_POST["email"]);
            if($byemail) 
            {
              $errors_e[0] = 'Такой электронный адрес уже зарегистрирован';
              $email_alert = 'alert';
            }
         }
         if (!empty($errors_p)) $pass_alert = 'alert';
         if (!empty($errors_r)) $rpass_alert = 'alert';

         // Если все поля были заполнены верно, регистрируем пользователя и переходим на Главную
         if (empty($errors_u) && empty($errors_e) && empty($errors_p) && empty($errors_r))
          {
              if ($User->register($_POST["email"], $_POST["user"], $_POST["pass"])) 
              {
                include ("classes/Character.php");
                $Chr = new Character($db, $User->getID());                            // Пока тут чар создаётся с тестовой локацией 2
                $setchar = $Chr->newChar($User->getID(), $User->getLogin(), 2);
                unset($Chr);
                header("Location: index.php");
                exit;
              } 
          }
         
         // Если перед отправкой формы логин или почты были введены, записать их в переменне value
         if ($_POST["user"] !== '') $val_login = 'value = "'.$_POST["user"].'"';
         if ($_POST["email"] !== '') $val_email = 'value = "'.$_POST["email"].'"';
    }


// ВЕСЬ ВЫВОД НИЖЕ
  include "templates/reg_form.php";
  
?>