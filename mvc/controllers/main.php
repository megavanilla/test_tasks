<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 14:21
 */

namespace mvc\controllers;

use mvc;


Class Main
{
  public function show()
  {
    global $Configs;
    $Views = new mvc\views\View();
    $Views->showPage('main', $Configs);
  }

  public function get()
  {
    $Tasks = new mvc\models\Tasks();
    print(json_encode($Tasks->get(), JSON_UNESCAPED_UNICODE));
  }

  public function add($request = [])
  {
    $Request = new mvc\libs\Request();
    $Tasks = new mvc\models\Tasks();
    $username = $Request->getVariable($request, ['username'], null);
    $email = $Request->getVariable($request, ['email'], null);
    $text = $Request->getVariable($request, ['text'], null);
    $href = self::uploadImg();

    print(json_encode($Tasks->add($username, $email, $text, $href), JSON_UNESCAPED_UNICODE));
  }

  public function edit($request = [])
  {
    $Request = new mvc\libs\Request();
    $Tasks = new mvc\models\Tasks();
    $id = $Request->getVariable($request, ['id'], null);
    $text = $Request->getVariable($request, ['text'], null);
    $status = $Request->getVariable($request, ['status'], null);

    print(json_encode($Tasks->edit($id, $text, $status), JSON_UNESCAPED_UNICODE));
  }

  private function uploadImg()
  {
    global $Configs;
    $Request = new mvc\libs\Request;
    $folderUpload = $Request->getVariable($Configs, [
        'conf',
        'main',
        'uploads'
    ], null);

    if ($folderUpload == null)
    {
      return '';
    }

    $href = '';
    $resultSave = [];
    foreach ($_FILES as $key => $params)
    {
      $Upload = new mvc\libs\Upload(
          $folderUpload,
          $key
      );
      $result = $Upload->save();
      $resultSave = (array_key_exists(0, $result)) ? $result[0] : null;
      break;
    }

    if (
        is_array($resultSave)
        && !empty($resultSave)
        && array_key_exists('path', $resultSave)
        && array_key_exists('state', $resultSave)
        && $resultSave['state'] == true
    )
    {
      $href = $resultSave['path'];
    }

    return $href;
  }
}