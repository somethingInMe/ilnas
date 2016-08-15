<?php
  // Подключаем класс Place
  include('classes/Place.php');
  $Place = new Place($db, $char->getCharID(), $Time);
  // Здесь будет проверяться, есть ли что-то в гет, и если есть, является ли выбранная локация соседом текущей
  if (isset($_GET['loc'])) {
    $newLoc = $Place->isTiedWithCurrent($_GET['loc']);  // Проверяем, является ли локация в гет соседом текущей
    if ($newLoc) {
      if($Place->tied[$newLoc]['message'] !== "") $journal[]=$Place->tied[$newLoc]['message'];   // Записываем сообщение в журнал
      // Если локация открыта перезаписываем текущую локацию не трогая ключ
      if ($Place->tied[$newLoc]['access'] == "" || $Place->tied[$newLoc]['access'] == "open") {
        $upd = $Place->updateLocation($Place->tied[$newLoc]['location']);
        $transit = $Place->getTransit($Place->tied[$newLoc]['passage_id']); // Определяем время на переход
        $transition = $Time->addTime($transit);   // Прибавляем время
      }
      // Если локация открыта ключом перезаписываем текущую локацию и обновляем ключ
      if ($Place->tied[$newLoc]['access'] == "key" || $Place->tied[$newLoc]['access'] == "open_by_key") {
        $upd = $Place->updateLocation($Place->tied[$newLoc]['location']);
        $updkey = $Place->updateKey($Place->tied[$newLoc]['passage_id'], "open", "", "");
        $transit = $Place->getTransit($Place->tied[$newLoc]['passage_id']); // Определяем время на переход
        $transition = $Time->addTime($transit);   // Прибавляем время
      }
    } 
  }
  unset($Place);
  $Place = new Place($db, $char->getCharID());
  // Здесь будет определяться текущая локация персонажа
  $curLoc = $Place->curloc;
?>

