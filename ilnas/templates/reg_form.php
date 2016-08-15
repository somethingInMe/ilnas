<!DOCTYPE html>
<html lang="ru">
<head>
  <title>Ilnas. Dark Heritage</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="templates/css/bootstrap.css" rel="stylesheet">
  <link href="templates/css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>  
<!--top nav start here-->
<div class="mother-grid">
  <div class="container">
	  <div class="temp-padd">
	    <div class="top-strip">
		    <div class="userbar">
				<span>Пошаговая текстовая ролевая игра</span>
			</div>
			<div class="clearfix"> </div>
   		</div>
<!--top nav end here-->	
<!--title start here-->
		<div class="title-main">
			<img class="main_logo" src="templates/img/manticore_logo.png" alt="manticore_logo">
		</div>
<!--title end here-->
<!--banner start here-->
 <div class="banner">
		<img src="templates/images/logo.png" alt=""/>
		<h3>РЕГИСТРАЦИЯ</h3>
		<form method="post" class="start-form">
			<div class="input_wraper">
				 
				<input type="text" name="user" <?=$val_login?> class="<?=$user_alert?>" placeholder="Логин"/>
				<span class="alert_text"><?=$errors_u[0]?></span>
			</div>
			<div class="input_wraper">
				 
				<input type="text" name="email" <?=$val_email?> class="<?=$email_alert?>" placeholder="E-Mail"/>
				<span class="alert_text"><?=$errors_e[0]?></span>
			</div>
			<div class="input_wraper">
				 
				<input type="password" name="pass" class="<?=$pass_alert?>" placeholder="Пароль"/>
				<span class="alert_text"><?=$errors_p[0]?></span>
			</div>
			<div class="input_wraper">
				 
				<input type="password" name="rpass" class="<?=$rpass_alert?>" placeholder="Повторите пароль"/>
				<span class="alert_text"><?=$errors_r[0]?></span>
			</div>

			<div class="input_wraper">
				<input type="submit" name="submit" class="submit" id="submit_reg" value="Зарегистрироваться" />
			</div>
			<div class="input_wraper">
				<div class="form-link">
					<a href="enter.php">Войти</a>
				</div>
				
			</div>	
		</form>
		
</div>
<!--banner end here-->
</div>
</div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="templates/js/bootstrap.js"></script>
</body>
</html>