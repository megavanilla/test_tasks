<?php
/**
 * Created by PhpStorm.
 * User: Mikhaylov I.A.
 * Date: 04.09.2017
 * Time: 23:22
 */
use mvc\libs;
$Request = new libs\Request();

$folderImgs = $Request->getVariable($params, ['conf', 'main', 'uploads'], '');
?>
<div class="page-main">
    <!--page-main-container-->
    <div class="page-main-container">
        <table id="table_tasks" class="table table-striped"></table>
        <script>
          var tasks4table = new Tasks('table_tasks', 'tasks4table');
          $(document).ready(function () {
            //Инициализируем саму таблицу
            tasks4table.get();
          });
        </script>
        <hr/>
        <br/>
        <div>
            <form id="frmData">
                <div class="form-group">
                    <label for="username">Имя пользователя:</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Имя пользователя"/>
                </div>
                <div class="form-group">
                    <label for="email">Адрес электронной почты:</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="адрес электронной почты"/>
                </div>
                <div class="form-group">
                    <label for="text">Текcт задачи:</label>
                    <textarea class="form-control" id="text" name="text" rows="7" placeholder="Введите текст задачи"></textarea>
                </div>
                <div class="form-group">
                    <label for="href_img">Изображение к задаче:</label>
                    <input type="file" class="form-control" id="href_img" name="href_img" placeholder="" accept='image/*' />
                </div>
                <div class="form-group">
                    <input type="button" value="Предварительный просмотр" onclick="tasks4table.preview('modal');"/>
                    <input type="button" style="float: right;" value="Добавить задачу" onclick="tasks4table.addTask();"/>
                </div>
            </form>
        </div>
    </div>
    <!--/page-main-container-->
</div>
<!--/page-main-->

<!---Модальное окно-->
<div id="modal"></div>
<!---/Модальное окно-->
