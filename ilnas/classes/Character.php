<?php
/**
 * Класс для работы с персонажами
 * 
 * getChar - Получает все данные персонажа по имени пользователя 
 * 
 */

class Character {
  private $db;
  public $uid;
  public $charname;
  public $charid;

  function __construct($db, $uid) {
    $this->db = $db;
    $this->uid = $uid;
  }
  
  public function getChar() {
    $chardata = $this->db->getRow ('SELECT * FROM `characters` WHERE `user_id`=?i', $this->uid); 
    return $chardata;
	}

  public function getCharName() {
    $this->charname = $this->db->getOne ('SELECT `char_name` FROM `characters` WHERE `user_id`=?i', $this->uid); 
    return $this->charname;
  }

  public function getCharID() {
    $this->charid = $this->db->getOne ('SELECT `id` FROM `characters` WHERE `user_id`=?i', $this->uid); 
    return $this->charid;
  }

  public function newChar($uid, $username, $location) {
    $sql = $this->db->parse('INSERT INTO `characters`(`user_id`, `char_name`, `location`) VALUES (?i,?s,?i)', $uid, $username, $location);
    $new = $this->db->query ($sql); 
    return $new;
  }
}
?>