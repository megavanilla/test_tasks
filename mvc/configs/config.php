<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 17:57
 */

namespace projects\test_task\mvc\configs;


class Config
{
  public function getConfig($path, $name){
    global $Configs;

    $pathConf = 'mvc/configs/'.$path.'.php';

    if(is_file($pathConf)){
      $Configs['conf'][$name] = include_once($pathConf);
    }
  }
}