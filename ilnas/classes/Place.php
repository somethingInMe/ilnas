<?php
/**
 * Класс для работы с локациями
 * 
 * getCurrentLocation Возвращает текущую локацию персонажа
 * getNameCurLoc Возвращает название текущей локации
 * getParamsCurLoc Возвращает все свойства текущей локации
 * updateLocation Изменяет текущую локацию на указанную
 * getTiedLocations Возвращает дефолтных соседей текущей локации
 * getTiedPersonal Возвращает пользовательские соседние локации
 * getTiedMixed Возвращает корректных соседей
 * getTiedVisible Возвращает корректных видимых соседей
 * createNewKey Создаёт новый ключ на основе существующей связки
 * createNewPassage Создаёт новую связку по заданному массиву элементов
 * updatePassage Изменяет выбранные данные ключа в указанной связке
 * updateKey Изменяет параметры ключа в указанной связке. Если указано 'notrec', значение не меняется
 * isTied Возвращает первый попавшийся ID связки двух локаций. Если связки нет, возвращает FALSE
 * isTiedWithCurrent Проверяет, связана ли указанная локация с текущей. Проверка осуществляется по массиву $tied
 * getTransit Возвращает время на переход между локациями по id перехода
 * 
 * 
 * 
 * 
 * 
 * 
 * 
*/

class Place {
  private $db;
  public $chr;
  public $curloc;
  public $tied;

  function __construct($db, $chr) {
    $this->db = $db;
    $this->chr = $chr;
    $this->curloc = $this->getCurrentLocation();
    $this->tied = $this->getTiedVisible();
  }
  
  public function getCurrentLocation() {
    $curloc = $this->db->getOne ('SELECT `location` FROM `characters` WHERE `id`=?i', $this->chr);
    return $curloc;
  }

  public function getNameCurLoc() {
    $curloc = $this->db->getOne ('SELECT `name` FROM `locations` WHERE `id`=?i', $this->curloc);
    return $curloc;
  }

  public function getParamsCurLoc() {
    $curloc = $this->db->getRow ('SELECT * FROM `locations` WHERE `id`=?i', $this->curloc);
    return $curloc;
  }
  
  public function updateLocation($new_location) {
    $upd = $this->db->query('UPDATE `characters` SET `location`=?i WHERE `id`=?i', $new_location, $this->chr);
    return $upd;
  }

  public function getPassage($passage_id) {
    $passage = $this->db->getRow('SELECT * FROM `passages` WHERE `id`=?i', $passage_id);
    return $passage;
  }

  public function getTiedLocations() {
    $deftied = $this->db->getInd('id', 'SELECT * FROM `passages` WHERE (`location1`=?s OR `location2`=?s) AND `char_id`=?i', $this->curloc, $this->curloc, 0);
    foreach ($deftied as $key => $value) {
      if($value['location1'] === $this->curloc) {
        $tied[$value['location2']]['passage_id'] = $key;
        $tied[$value['location2']]['location'] = $value['location2'];
        $tied[$value['location2']]['link'] = $value['link_name2'];
        $tied[$value['location2']]['direction'] = $value['direction2'];
        $tied[$value['location2']]['access'] = $value['access'];
        $tied[$value['location2']]['notice'] = $value['notice'];
        $tied[$value['location2']]['message'] = $value['message'];
      } else if ($value['location2'] === $this->curloc) {
        $tied[$value['location1']]['passage_id'] = $key;
        $tied[$value['location1']]['location'] = $value['location1'];
        $tied[$value['location1']]['link'] = $value['link_name1'];
        $tied[$value['location1']]['direction'] = $value['direction1'];
        $tied[$value['location1']]['access'] = $value['access'];
        $tied[$value['location1']]['notice'] = $value['notice'];
        $tied[$value['location1']]['message'] = $value['message'];
      }
    }
    return $tied;
  }

  public function getTiedPersonal() {
    $perstied = $this->db->getInd('id', 'SELECT * FROM `passages` WHERE (`location1`=?s OR `location2`=?s) AND `char_id`=?i', $this->curloc, $this->curloc, $this->chr);
    foreach ($perstied as $key => $value) {
      if($value['location1'] === $this->curloc) {
        $tied[$value['location2']]['passage_id'] = $key;
        $tied[$value['location2']]['location'] = $value['location2'];
        $tied[$value['location2']]['link'] = $value['link_name2'];
        $tied[$value['location2']]['direction'] = $value['direction2'];
        $tied[$value['location2']]['access'] = $value['access'];
        $tied[$value['location2']]['notice'] = $value['notice'];
        $tied[$value['location2']]['message'] = $value['message'];
      } else if ($value['location2'] === $this->curloc) {
        $tied[$value['location1']]['passage_id'] = $key;
        $tied[$value['location1']]['location'] = $value['location1'];
        $tied[$value['location1']]['link'] = $value['link_name1'];
        $tied[$value['location1']]['direction'] = $value['direction1'];
        $tied[$value['location1']]['access'] = $value['access'];
        $tied[$value['location1']]['notice'] = $value['notice'];
        $tied[$value['location1']]['message'] = $value['message'];
      }
    }
    if (!isset($tied)) $tied='';
    return $tied;
  }

  public function getTiedMixed() {
    $deftied = $this->getTiedLocations(); 
    $perstied = $this->getTiedPersonal();
    if($perstied === '') {
      $tied = $deftied;
    } else {
        foreach ($perstied as $k => $v) {
          foreach ($deftied as $key => $value) {
            if ($value['location']==$v['location']) unset($deftied[$key]);
          }
        }
        $tied = $deftied + $perstied;
      }
    
    return $tied;
  }

  public function getTiedVisible() {
    $tied = $this->getTiedMixed();
    foreach ($tied as $key => $value) {
      if ($value['access'] === 'invisible') unset($tied[$key]);
    }
    return $tied;
  }

  public function createNewKey($passage, $access, $notice, $message) {
    $passage = $this->getPassage($passage);
    $add = array ('access' => $access,
                  'notice' => $notice,
                  'message' => $message,
                  'id' => NULL,
                  'char_id' => $this->chr 
                  );
    $newkey = array_replace($passage, $add);
    $sql = 'INSERT INTO `passages` SET ?u';
    $insert = $this->db->query($sql, $newkey);
    return $insert;
  }

  public function createNewPassage($array) {
    $sql = $this->db->parse('INSERT INTO `passages` SET ?u', $array);
    $ins = $this->db->query($sql);
    return $ins; 
  }

  public function updatePassage($passage, $parameter, $value) {
    if (is_numeric($value)) {
      $sql = $this->db->parse('UPDATE `passages` SET ?n=?i WHERE `id`=?i', $parameter, $value, $passage);
    } else {
      (string) $value;
      $sql = $this->db->parse('UPDATE `passages` SET ?n=?s WHERE `id`=?i', $parameter, $value, $passage);
    }
    $upd = $this->db->query ($sql);
    return $upd; 
  }

  public function updateKey($passage, $access, $notice, $message) {
    if ($access !== 'notrec') $updac = $this->updatePassage($passage, 'access', $access); else $updac = TRUE;
    if ($notice !== 'notrec') $updnt = $this->updatePassage($passage, 'notice', $notice); else $updnt = TRUE;
    if ($message !== 'notrec') $updms = $this->updatePassage($passage, 'message', $message); else $updms = TRUE;
    if ($updac && $updnt && $updms) return TRUE;
    return FALSE;
  }

  public function isTied($locationA, $locationB) {
    $res = $this->db->getOne ('SELECT `id` FROM `passages` WHERE (`location1`=?i AND `location2`=?i) OR (`location1`=?i AND `location2`=?i)', $locationA, $locationB, $locationB, $locationA);
    return $res;
  }

  public function isTiedWithCurrent($location) {
    $tied = $this->tied;
    foreach ($tied as $key => $value) {
      if ($value['location']==$location) {
        $res[]=$key;
      } 
    }
    if (isset($res)) {
      $rs = $res[0];
    } else {
      $rs = FALSE;
    } 
    return $rs;
  }
  
  public function getTransit($id) {
    $res = $this->db->getOne ('SELECT `transit` FROM `passages` WHERE `id`=?i', $id);
    return $res;
  }
}
?>