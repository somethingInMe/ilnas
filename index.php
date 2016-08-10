<?php
  require_once __DIR__ . '/vendor/autoload.php';

  // Создание контейнера
  $container = new \Pimple\Container();
  
  // HTTPFoundation
  $container['request'] = function ($container) {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
  };
  
  
  $container['response'] = function ($container) {
  return new \Symfony\Component\HttpFoundation\Response();
  };

  $cookie = new \Symfony\Component\HttpFoundation\Cookie('foo', 'bar');
  $container['response']->headers->setCookie($cookie);

  
  
  echo "<pre>";
  var_dump($container['response']);
  echo "</pre>";

/*  // Объект для работы с сессиями
  $container['session'] = function ($container) {
    return new \Symfony\Component\HttpFoundation\Session\Session();
  };
  
  // Старт сессии
  //$container['session']->start();

  // Установка и получение значения атрибутов
  $container['session']->set('name', 'Drak');
  echo $container['session']->get('name');

  // Завершение сессии
  //$container['session']->invalidate();
*/  



/*
  $config = new \Doctrine\DBAL\Configuration();
  //..
  $connectionParams = array(
    'dbname' => 'ilnas2',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
  );
  $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
*/


?>