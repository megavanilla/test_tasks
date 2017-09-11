<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 05.09.2017
 * Time: 2:22
 */

namespace mvc\models;


class Tasks extends Model
{
  public function __construct()
  {
    parent::__construct('tasks');
  }

  public function add($username, $email, $text, $href)
  {
    $data = [
        'username' => $username,
        'email' => $email,
        'text' => $text,
        'href_img' => $href,
    ];
    return $this->insert($data);
  }
  public function edit($id, $text, $status)
  {
    $data = [
        'text' => $text,
        'status' => $status,
    ];
    return $this->update($data, 'id', (int)$id);
  }
}