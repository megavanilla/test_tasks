<?php

namespace mvc\libs;

/**
 * Класс для работы с переменными SERVER, GET, POST, $_SESSION, $_COOKIE
 * @var $actual - содержит последний выбранный массив
 */
class Request
{
  /**
   * @var array - последний полученный массив
   */
  protected $actual = [];

  /**
   * Получаем массив $_SERVER.
   *
   * @return array
   */
  public function getServer()
  {
    $this->actual = $_SERVER;
    return $this->actual;
  }

  /**
   * Получаем массив $_POST.
   *
   * @return array
   */
  public function getPost()
  {
    $this->actual = $_POST;
    return $this->actual;
  }

  /**
   * Получение массива $_REQUEST.
   *
   * @return array
   */
  function getRequest()
  {
    $this->actual = $_REQUEST;
    return $this->actual;
  }

  /**
   * Получаем массив $_GET.
   *
   * @return array
   */
  public function getGet()
  {
    $this->actual = $_GET;
    return $this->actual;
  }

  /**
   * Получаем массив $_SESSION.
   *
   * @return array
   */
  public function getSession()
  {
    $this->actual = $_SESSION;
    return $this->actual;
  }

  /**
   * Получаем массив $_COOKIE.
   *
   * @return array
   */
  public function getCookie()
  {
    $this->actual = $_COOKIE;
    return $this->actual;
  }

  /**
   * Получаем значение из массива.
   *
   * @param string $name - ключ, по  которому искать
   *
   * @return string
   */
  public function get($name = '')
  {
    if (!empty($this->actual))
    {
      if (!empty($this->actual[$name]))
      {
        return $this->actual[$name];
      }
      else
      {
        return '';
      }
    }

    return '';
  }

  /**
   * Провереям, что пришел AJAX.
   *
   * @return bool
   */
  public function isAjax()
  {
    $this->getServer();
    $header = $this->get('HTTP_X_REQUESTED_WITH');
    if (isset($header)
        && $header === 'XMLHttpRequest'
    )
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * Рекурсивная функция, работает так:
   * 1. Если передана скалярная переменная и она не пустая, то вернётся её значение;
   * 2. Если передана скалярная переменная и она пустая, то вернётся значение по умолчанию;
   * 3. Если передан массив или объект и не заполнен массив ключей, то вернётся массив или объект соответственно;
   * 4. Если передан массив или объект и в нём нет указанного ключа/параметра, то вернёт значение по умолчанию;
   * 5. Если передан массив или объект и в нём удалось найти всю цепочку ключей/параметров,
   *    то вернётся значение соответствующее этой цепочке.
   * Цепочка ключей/параметров - это последовательность вложенных ключей/параметров в массив/объект.
   * Например у массива $data['ke1']['key2'], цепочка ключей это 'key1','key2' при вызове функции
   * указывается так: getVariable($data, ['key1','key2'], 'Значение по умолчанию').
   * Для объекта $data->key1->key2: вызов функции выглядит так же, как и для массива.
   *
   * @param array  $variable  - Переменная
   * @param array  $keys      - Массив ключей в переменной
   * @param string $def_value - Значение по умолчанию
   *
   * @return mixed
   */
  public function getVariable($variable, $keys = [], $def_value = '')
  {
    //Для пустой переменной вернём значение по умолчанию
    if (!empty($variable) || $variable === 0)
    {
      if (!empty($keys)
          && is_array($keys)
          && (is_array($variable) || is_object($variable))
      )
      {
        //Если переменная объект, то конвертируем в массив
        $variable = (is_object($variable)) ? (array)$variable : $variable;
        //Извлечём первый элемент массива ключей
        $key = array_shift($keys);

        //Если ключ в массиве найден, то предполагаеться более глубокая вложенность массива
        if (array_key_exists($key, $variable))
        {
          return $this->getVariable($variable[$key], $keys, $def_value);
        }
        else
        {
          return $def_value;
        }
      }
      else
      {
        return $variable;
      }
    }
    else
    {
      return $def_value;
    }
  }

  /**
   * Удаление лишних пробелов из данных запроса
   *
   * @return array
   */
  public function trimData()
  {
    $result = $this->actual;
    array_walk_recursive
    (
        $result,
        function(&$item)
        {
          $item = trim($item);
        }
    );
    return $result;
  }
}