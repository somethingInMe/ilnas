<?php
  $db = new SafeMySQL(array('user' => USER,'pass' => PASS,'db' => DB,'charset' => 'utf8'));
  $User = new CUser($db);

// Если нажата кнопка "Выход", разлогиниваемся и переходим на форму авторизации  
  if($_GET['do'] == 'logout'){
    $ext = $User->logout();
    header("Location: enter.php");
    exit;
  }
// Если не авторизован, авторизуем по куки, если не получается, на enter.php
  if($User->isAuthorized()) {
		// Получаем все данные текущего пользователя
  	$current_user = $User->getByID($User->getID());					
	} else {
  	if ($User->loginByHash()) {
  		$current_user = $User->getByID($User->getID());				// Если залогинились по хэшу, получаем данные текущего пользователя
  	} else {
  		header("Location: enter.php");
    	exit;
  	}
  }
?>

