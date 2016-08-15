<?php
  include "config.php";
  include "classes/SafeMySQL.php";
  include "classes/CUser.php";
  include "authentication.php";
  include "classes/Character.php";
    $char = new Character($db, $User->getID());
    $charname = $char->getChar();
  include "time-controller.php";
  include "locations.php";  // Одновременно с переходом между локациями здесь прибавляется 10 единиц времени 
  
  include "mob-controller.php";



  // ВЕСЬ ВЫВОД НИЖЕ
  
  
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <title>Ilnas. Dark Heritage</title>
  <meta charset="utf-8">
  
  <!-- Bootstrap -->
  <link href="templates/css/bootstrap.css" rel="stylesheet">
  <link href="templates/css/styles.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body> 
  <div class="container">  
    <div class="row">
      <img class="main_logo" src="templates/img/manticore_logo.png" alt="manticore_logo">
      <div class="navbar navbar-inverse">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
              <span class="sr-only">Открыть навигацию</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="collapse navbar-collapse" id="responsive-menu">
            <ul class="nav navbar-nav">
              <li><a href="#"><?=$User->getLogin()?></a></li>
              <li><a href="#"><?=$Place->getNameCurLoc()?></a></li>
              <li><a href="index.php?do=logout">Выход</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 "></div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
    </div>
  
        <?  
        //include "templates/header.php";
        include "templates/location_links.php"; 
        include "templates/journal.php"; 

        ?>
  </div>

  

  


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="templates/js/bootstrap.js"></script>
</body>
</html>