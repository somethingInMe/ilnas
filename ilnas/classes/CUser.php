<?php
/**
 * Класс для работы с пользователями
 * При запуске каждой страницы автоматически создаётся объект этого класса $USER - данные о текущем пользователе.
 * getList Возвращает список всех пользователей с их свойствами.
 * getByID  Возвращает пользователя по его ID.
 * getByLogin   Возвращает пользователя по его логину.
 * getByEMail   Возвращает пользоватля по его E-Mail.
 * add  Создает нового пользователя.
 * update   Изменяет параметры пользователя.
 * delete   Удаляет пользователя.
 * getID  Возвращает ID текущего авторизованного пользователя.
 * getLogin   Возвращает логин текущего авторизованного пользователя.
 * getEmail   Возвращает E-Mail текущего авторизованного пользователя.
 * getParam   Возвращает один из параметров пользователя.
 * isAdmin  Проверяет принадлежность пользователя группе администраторов.
 * isAuthorized   Проверяет авторизован ли пользователь.
 * loginByHash  Авторизует пользователя по хранимому в куках хешу.
 * savePasswordHash   Сохраняет специальный хеш в куках пользователя в целях дальнейшей автоматической авторизации.
 * authorize  Непосредственно осуществляет процесс авторизации пользователя. Инициализирует необходимые сессионные переменные и переменные объекта класса CUser.
 * logout   Заканчивает сеанс авторизации пользователя.
 * register   Создает нового пользователя, авторизует его и отсылает письмо по шаблону типа NEW_USER.
 * changePassword   Изменяет пароль пользователя.
 * sendPassword   Создает почтовое событие для отправки пользователю сообщения для смены пароля.
 * 
*/

class CUser {
  private $db;
  public $ulist;
  public $isonline;
  public $cur_uid;
  public $cur_login;
  public $isAdmin;


  function __construct($db) {
    session_start(); 
    $this->db = $db;
  }
  
  public function getList() {
    $this->ulist = $this->db->getInd ('id' ,'SELECT * FROM `users`');
    return $this->ulist;
  }

  public function getByID($uid) {
    $udata = $this->db->getRow ('SELECT * FROM `users` WHERE `id`=?i', $uid); 
    return $udata;
  }

  public function getByLogin($login) {
    $udata = $this->db->getRow ('SELECT * FROM `users` WHERE `login`=?s', $login); 
    return $udata;
  }
  
    public function getByEMail($email) {
    $udata = $this->db->getRow ('SELECT * FROM `users` WHERE `email`=?s', $email); 
    return $udata;
  }

  public function add($email, $login, $pass) {
    $pass = md5($pass);
    $hash = md5(md5($pass.$login));
    $sql = $this->db->parse('INSERT INTO `users`(`email`, `login`, `password`, `hash`) VALUES (?s,?s,?s,?s)', $email, $login, $pass, $hash);
    $new = $this->db->query ($sql); 
    return $new;
  }

  public function update($parameter, $value, $uid) {
    if (is_numeric($value)) {
      $sql = $this->db->parse('UPDATE `users` SET ?n=?i WHERE `id`=?i', $parameter, $value, $uid);
    } else {
      (string) $value;
      $sql = $this->db->parse('UPDATE `users` SET ?n=?s WHERE `id`=?i', $parameter, $value, $uid);
    }
    $upd = $this->db->query ($sql); 
    return $upd;
  }

  public function delete($uid) {
    $sql = $this->db->parse('DELETE FROM `users` WHERE `id`=?i', $uid);
    $del = $this->db->query ($sql); 
    return $del;
  }
   
  public function getID() {
    if ($this->isAuthorized()) {
      $this->cur_uid = $_SESSION['uid'];
    } else {
      $this->cur_uid = NULL;
    }
    return $this->cur_uid;
  }

  public function getLogin() {
    if ($this->isAuthorized()) {
      $this->cur_login = $_SESSION['login'];
    } else {
      $this->cur_login = '';
    }
    return $this->cur_login;
  }

  public function getEmail() {
    if ($this->isAuthorized()) {
      $udt = $this->getByID($this->GetID());
      $this->cur_email = $udt['email'];
      unset ($udt);
    } else {
      $this->cur_email = '';
    }
    return $this->cur_email;
  }

  public function getParam($param) {
    if ($this->isAuthorized()) {
      $udt = $this->getByID($this->GetID());
      $param = $udt[$param];
      unset ($udt);
    } else {
      $param = '';
    }
    return $param;
  }

  public function isAdmin() {
    if ($this->isAuthorized()) {
        if ($this->getLogin() == 'admin') {
          $this->isAdmin = TRUE;
        } else {
          $this->isAdmin = FALSE;
        }
    } else {
      $this->isAdmin = FALSE;
    }
    return $this->isAdmin;
  }

  public function isAuthorized() {
    if (!$_SESSION['uid']) {
      $this->isonline = FALSE;
    } else {
      $this->isonline = TRUE;
    }
    return $this->isonline;
  }

  public function savePasswordHash($login) {
    $user = $this->getByLogin($login);
    $hash = md5($user['password'].$user['login']);
    $setc = setcookie("hash",$hash, time()+60*60*24*30);
    return $setc;
  }

  public function loginByHash() {
    if (isset($_COOKIE["hash"])) {
      $hash = md5($_COOKIE["hash"]);
      if($hash !== md5('')) $userdata = $this->db->getRow('SELECT * FROM `users` WHERE `hash`=?s', $hash);
        if ($userdata !== NULL) {
          $enter = $this->authorize($userdata['login']);
          return $enter;
        } else {
          setcookie("hash");
        } 
      }
    return FALSE;
  }

  public function authorize($login) {
    $udata = $this->getByLogin($login);
    $this->isonline = TRUE;
    $this->cur_uid = $udata['id'];
    $this->cur_login = $login;
    $this->cur_email = $udata['email'];
    $_SESSION['uid'] = $udata['id'];
    $_SESSION['login'] = $login;
    $this->savePasswordHash($login);
    return $this->isonline;			
  }

  public function logout() {
    $this->isonline = FALSE;
    $this->cur_uid = '';
    $this->cur_login = '';
    $this->cur_email = '';
    unset($_SESSION['uid']);
	  unset($_SESSION['login']);
    setcookie("hash");
	  session_destroy();
    return !$this->isonline;		// Возвращает TRUE, если разлогинен успешно
  }

  
  public function register($email, $login, $pass) {
    $new = $this->add($email, $login, $pass);
	  $auth = $this->authorize($login);
				$subject = 'Ilnas. Dark Heritage. Регистрация';
        $message = "Ваша регистрация в игре ILNAS. DARK HERITAGE прошла успешно.\r\nВаш Логин - '$login'\r\nПриятной игры";
        $headers = 'From: pirlofantom@labirinth.esy.es';
        $send = mail($email, $subject, $message, $headers);			
  	if ($new && $auth && $send) {
  		$success = TRUE;
  	} else {
  		$success = FALSE;
  	}
  	return $success;
    }
  
  public function changePassword($pass, $uid) {
    $pass = md5($pass);
    $sql = $this->db->parse('UPDATE `users` SET `password`=?s WHERE `id`=?i', $pass, $uid);
    $chng = $this->db->query ($sql); 
    return $chng;
  }
   
  public function sendPassword($pass, $email) {
    $udata = $this->getByEMail($email);
	  $subject = 'Ilnas. Dark Heritage. Изменение пароля';
    $message = 'Ваш пароль был изменён. \r\n Логин - '.$udata['login'].'\r\n Новый пароль -'.$pass.'\r\n Вы можете изменить пароль в личном кабинете';
    $headers = 'From: pirlofantom@mail.ru';
    $send = mail($email, $subject, $message, $headers);
	return $send;
  } 
  
}
?>