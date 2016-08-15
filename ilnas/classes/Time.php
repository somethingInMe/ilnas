<?php
/**
 * Класс для работы с временем
 * 
 * getCurrentTime Возвращает текущее время персонажа
 * addTime Добавляет к времени персонажа указанное значение
 * setTime Устанавливает новое время персонажа
 */

class Time {
  private $db;
  public $chr;


  function __construct($db, $chr) {
    $this->db = $db;
    $this->chr = $chr;
  }
  
  public function getCurrentTime() {
    $curtime = $this->db->getOne ('SELECT `time` FROM `characters` WHERE `id`=?i', $this->chr);
    return $curtime;
  }

  public function addTime($add) {
    $value = $this->getCurrentTime() + $add;
    $sql = $this->db->parse('UPDATE `characters` SET ?n=?i WHERE `id`=?i', 'time', $value, $this->chr);
    $ins = $this->db->query($sql);
    return $ins;
  }

    public function setTime($value) {
    $sql = $this->db->parse('UPDATE `characters` SET ?n=?i WHERE `id`=?i', 'time', $value, $this->chr);
    $ins = $this->db->query($sql);
    return $ins;
  }  

}
?>