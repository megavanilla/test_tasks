<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 04.09.2017
 * Time: 21:58
 */

namespace projects\test_task\mvc\router;

class Router
{
  private $Request;
  private $method = '';
  private $controller = '';
  private $params = [];
  public function __construct()
  {
    $this->Request = new \mvc\libs\Request();
    $this->params = $this->Request->getRequest();
    $this->method = $this->Request->getVariable($this->params, ['page'], '');
    $this->controller = $this->Request->getVariable($this->params, ['controller'], '');;

    if(!empty($this->controller)){
      unset($this->params['controller']);
    }

    if(!empty($this->method)){
      unset($this->params['page']);
    }
  }
  public function route(){
    $controller = "mvc\controllers\\$this->controller";
    $method = $this->method;
    if($controller == '' ||  $method == ''){
      trigger_error("Не указан контроллер или его метод.", E_USER_WARNING);
      exit;
    }
    if(!class_exists($controller)){
      trigger_error("Не удалось определить контроллер.", E_USER_WARNING);
      exit;
    }
    if(!method_exists(new $controller, $method)){
      trigger_error('Не удалось определить метод контроллера.', E_USER_WARNING);
      exit;
    }

    call_user_func_array([$controller, $method], [$this->params]);
  }
}