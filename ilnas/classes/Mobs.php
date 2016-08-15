<?php
/**
 * Класс для работы с мобами
 * 
 * getDisableMobs Возвращает список всех мёртвых мобов персонажа
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
*/

class Mobs {
  private $db;
  public $chr;
  public $curloc;
  public $timetool;

  function __construct($db, $chr, $cur_loc, $timetool) {
    $this->db = $db;
    $this->chr = $chr;
    $this->curloc = $cur_loc;
    $this->timetool = $timetool;
  }

  public function getDisableMobs() {
    $Dead = $this->db->getOne ('SELECT `location` FROM `characters` WHERE `id`=?i', $this->chr);
    return $curloc;  
  }


}
?>