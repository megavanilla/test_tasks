<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 04.09.2017
 * Time: 21:58
 */

namespace mvc\views;

class View
{
  public function showPage($page, $params = [])
  {
    ob_start();
    $this->loadLayout('header');
    $this->loadLayout('auth');

    if (!$page)
    {
      $this->loadPage('main', $params);
    }else{
      $this->loadPage($page, $params);
    }

    $this->loadLayout('footer');
    print(ob_get_clean());
  }

  public function loadLayout($name)
  {
    $layout_file = __DIR__.'/layouts/'.$name.'.tpl';
    if(is_file($layout_file)){
      include_once($layout_file);
    }else{
      print '';
    }
  }

  public function loadPage($name, $params){
    $page_file = __DIR__.'/pages/'.$name.'.php';
    if(is_file($page_file)){
      include_once($page_file);
    }else{
      print '';
    }
  }

}