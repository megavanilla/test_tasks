function Tasks(id_table, object_owner) {
  this.isReadable = true;
  this.id_table = id_table || null;
  this.render = function () {
    $("#" + this.id_table).dataTable({
      'destroy': true,
      'iDisplayLength': 3,
      'lengthMenu': [1, 3, 5, 10, 25, 50, 75, 100],
      'sPaginationType': 'full_numbers',
      'language': {
        "processing": "Подождите...",
        "search": "Поиск:",
        "lengthMenu": "Показать _MENU_ записей",
        "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
        "infoEmpty": "Записи с 0 до 0 из 0 записей",
        "infoFiltered": "(отфильтровано из _MAX_ записей)",
        "infoPostFix": "",
        "loadingRecords": "Загрузка записей...",
        "zeroRecords": "Записи отсутствуют.",
        "emptyTable": "В таблице отсутствуют данные",
        "paginate": {
          "first": "Первая",
          "previous": "Предыдущая",
          "next": "Следующая",
          "last": "Последняя"
        },
        "aria": {
          "sortAscending": ": активировать для сортировки столбца по возрастанию",
          "sortDescending": ": активировать для сортировки столбца по убыванию"
        }
      }
    }).api();
  };
  this.get = function () {
    var parent = this;
    $.ajax({
      method: 'POST',
      type: 'POST',
      data: {
        'controller': 'main',
        'page': 'get'
      },
      url: 'index.php',
      success: function (data) {
        parent.initTable(parent.jsonParse(data));
      }
    });
  };
  this.getById = function (id) {
    var parent = this;
    $.ajax({
      method: 'POST',
      type: 'POST',
      data: {
        'controller': 'main',
        'page': 'getById',
        'id': id
      },
      url: 'index.php',
      success: function (data) {
        parent.initTable(parent.jsonParse(data));
      }
    });
  };
  this.setById = function (id) {
    var parent = this;
    $.ajax({
      method: 'POST',
      type: 'POST',
      data: {
        'controller': 'main',
        'page': 'setById',
        'id': id
      },
      url: 'index.php',
      success: function (data) {
        parent.initTable(parent.jsonParse(data));
      }
    });
  };
  this.initTable = function (data) {
    var table = document.getElementById(this.id_table) || null;
    if (table === null) {
      return false;
    }

    var theadEl = document.createElement('thead');
    var tr_th = document.createElement('tr');
    var th0 = document.createElement('th');
    var th1 = document.createElement('th');
    var th2 = document.createElement('th');
    var th3 = document.createElement('th');
    var th4 = document.createElement('th');
    th0.innerHTML = 'Статус задачи';
    th1.innerHTML = 'Имя пользователя';
    th2.innerHTML = 'E-mail';
    th3.innerHTML = 'Текст задачи';
    th4.innerHTML = 'Картинка';

    tr_th.appendChild(th0);
    tr_th.appendChild(th1);
    tr_th.appendChild(th2);
    tr_th.appendChild(th3);
    tr_th.appendChild(th4);

    var tbodyEl = document.createElement('tbody');
    for (var row in data) {
      if (
        data.hasOwnProperty(row)
        && data[row].hasOwnProperty('id')
        && data[row].hasOwnProperty('status')
        && data[row].hasOwnProperty('username')
        && data[row].hasOwnProperty('email')
        && data[row].hasOwnProperty('text')
        && data[row].hasOwnProperty('href_img')
      ) {
        var tr = document.createElement('tr');
        //tr.setAttribute('id', data[row]['id']);
        var tdS = document.createElement('td');
        var tdU = document.createElement('td');
        var tdE = document.createElement('td');
        var tdT = document.createElement('td');
        var tdH = document.createElement('td');

        tdS.innerHTML = (data[row]['status'] === "0") ? 'Не запущена' : 'Запущена';
        tdU.innerHTML = data[row]['username'];
        tdE.innerHTML = data[row]['email'];
        tdT.innerHTML = data[row]['text'];
        var img_href = (data[row]['href_img']) ? 'web/tasks/' + data[row]['href_img'] : 'web/img/no-image.gif';
        tdH.innerHTML = '<img src="' + img_href + '" height="50" width="50" />';

        if (this.isReadable) {
          tr.setAttribute('onclick', object_owner + ".loadEditor('modal', '" + data[row]['id'] + "', '" + data[row]['text'] + "', '" + data[row]['status'] + "');");
        }

        tr.appendChild(tdS);
        tr.appendChild(tdU);
        tr.appendChild(tdE);
        tr.appendChild(tdT);
        tr.appendChild(tdH);
        tbodyEl.appendChild(tr);
      }
    }

    theadEl.appendChild(tr_th);
    table.innerHTML = '';
    table.insertBefore(theadEl, table.firstChild);
    table.insertBefore(tbodyEl, table.lastChild);

    this.render();
  };
  this.addTask = function () {
    var parent = this;
    var form = document.querySelector('#frmData');
    var formData = new FormData(form);
    formData.append('controller', 'main');
    formData.append('page', 'add');

    form.reset();

    $.ajax({
      processData: false,
      contentType: false,
      method: 'POST',
      type: 'POST',
      data: formData,
      url: 'index.php',
      success: function () {
        parent.get();
      }
    });
  };
  this.editTask = function () {
    var parent = this;
    var form = document.querySelector('#frmData');
    var formData = new FormData(form);
    formData.append('controller', 'main');
    formData.append('page', 'edit');

    form.reset();

    $.ajax({
      processData: false,
      contentType: false,
      method: 'POST',
      type: 'POST',
      data: formData,
      url: 'index.php',
      success: function () {
        parent.get();
      }
    });
  };
  this.jsonParse = function (str) {
    try {
      var res = JSON.parse(str);
    } catch (e) {
      return false;
    }
    return res;
  };
  this.readURL = function (inputFile, imgEl) {
    if (inputFile.files && inputFile.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        try {
          imgEl.src = e.target.result;
        } catch (e) {
          return false;
        }
      };

      reader.readAsDataURL(inputFile.files[0]);
    }
  };
  this.preview = function (id_insert) {

    //Получим содержимое введённых данных
    var htmlPreview = this.createHTMLPreview();

    var button_modal_open = this.createModal(id_insert, htmlPreview.innerHTML);
    if (button_modal_open !== false) {
      var el_href_img = document.getElementById('href_img') || null;
      var el_img = document.getElementById('img_preview') || null;

      if (el_img !== null && el_href_img !== null) {
        this.readURL(el_href_img, el_img);
      }
      button_modal_open.click();
    }
  };

  this.loadEditor = function (id_insert, id, text, status) {
//Получим содержимое введённых данных
    var htmlPreviewEdit = this.createHTMLPreviewEdit(id, text, status);

    var button_modal_open = this.createModal(id_insert, htmlPreviewEdit.innerHTML);
    if (button_modal_open !== false) {
      var el_href_img = document.getElementById('href_img') || null;
      var el_img = document.getElementById('img_preview') || null;

      if (el_img !== null && el_href_img !== null) {
        this.readURL(el_href_img, el_img);
      }
      //Добавим кнопку сохранения
      button_modal_open.click();
    }
  };
  this.createHTMLPreviewEdit = function (id, text, status) {
    var el_text = document.createElement('textarea');
    el_text.value = text;
    el_text.innerText = text;
    var el_status = document.createElement('input');
    el_status.setAttribute('type', 'checkbox');
    if (status) {
      el_status.setAttribute('checked', 'checked');
    } else {
      el_status.removeAttribute('checked');
    }
    el_status.setAttribute('value', status);

    var el_desc_checkbox = document.createElement('span');
    el_desc_checkbox.innerText = 'Запуск задачи';

    var el_id = document.createElement('hidden');
    el_id.setAttribute('value', id);
    var br = document.createElement('br');


    var previewHTML = document.createElement('div');
    previewHTML.appendChild(el_text);
    previewHTML.appendChild(br.cloneNode());
    previewHTML.appendChild(el_status);
    previewHTML.appendChild(el_desc_checkbox);
    previewHTML.appendChild(el_id);

    return previewHTML;
  };
  this.createHTMLPreview = function () {
    var el_username = document.getElementById('username') || null;
    var el_email = document.getElementById('email') || null;
    var el_text = document.getElementById('text') || null;

    if (el_username !== null) {
      var el_cl_username = el_username.cloneNode();
      el_cl_username.setAttribute('value', el_username.value);
    }
    if (el_email !== null) {
      var el_cl_el_email = el_email.cloneNode();
      el_cl_el_email.setAttribute('value', el_email.value);
    }
    if (el_text !== null) {
      var el_cl_el_text = el_text.cloneNode();
      el_cl_el_text.value = el_text.value;
      el_cl_el_text.innerText = el_text.value;
    }

    var el_img = document.createElement('img');
    el_img.setAttribute('alt', 'Место картинки');
    el_img.setAttribute('id', 'img_preview');
    el_img.setAttribute('height', '100');
    el_img.setAttribute('width', '100');

    var previewHTML = document.createElement('div');
    previewHTML.appendChild(el_cl_username);
    previewHTML.appendChild(el_cl_el_email);
    previewHTML.appendChild(el_cl_el_text);
    previewHTML.appendChild(el_img.cloneNode(true));

    return previewHTML;

  };
  /**
   * Создаёт вёрстку для модального окна и возвращает ссылку на кнопку, вызывающую запск этого окна
   * @param id_insert
   * @param content
   * @returns {*}
   */
  this.createModal = function (id_insert, content) {
    var el_insert = document.getElementById(id_insert) || null;
    if (el_insert === null) {
      return false;
    }
    el_insert.innerHTML = '';
    var contentHTML = content || '';


    var button_modal_open = document.createElement('button');
    button_modal_open.setAttribute('class', 'hide');
    button_modal_open.setAttribute('type', 'button');
    button_modal_open.setAttribute('data-toggle', 'modal');
    button_modal_open.setAttribute('data-target', '#modal_window_preview');

    var div_modal_fade = document.createElement('div');
    div_modal_fade.setAttribute('id', 'modal_window_preview');
    div_modal_fade.setAttribute('class', 'modal fade');
    div_modal_fade.setAttribute('tabindex', '-1');
    div_modal_fade.setAttribute('role', 'dialog');
    var div_modal_dialog = document.createElement('div');
    div_modal_dialog.setAttribute('class', 'modal-dialog');
    div_modal_dialog.setAttribute('role', 'document');
    var div_modal_content = document.createElement('div');
    div_modal_content.setAttribute('class', 'modal-content');
    var div_modal_header = document.createElement('div');
    div_modal_header.setAttribute('class', 'modal-header');
    var div_modal_body = document.createElement('div');
    div_modal_body.setAttribute('class', 'modal-body');
    div_modal_body.innerHTML = contentHTML;
    var div_modal_footer = document.createElement('div');
    div_modal_footer.setAttribute('class', 'modal-footer');


    var h5_modal_title = document.createElement('h5');
    h5_modal_title.setAttribute('class', 'modal-title');
    h5_modal_title.innerHTML = 'Предварительный просмотр.';
    var button_close = document.createElement('button');
    button_close.setAttribute('class', 'btn btn-secondary');
    button_close.setAttribute('type', 'button');
    button_close.setAttribute('data-dismiss', 'modal');
    button_close.innerText = 'Закрыть';

    //Собираем окно
    div_modal_header.appendChild(h5_modal_title);
    div_modal_footer.appendChild(button_close);

    div_modal_content.appendChild(div_modal_header);
    div_modal_content.appendChild(div_modal_body);
    div_modal_content.appendChild(div_modal_footer);

    div_modal_dialog.appendChild(div_modal_content);

    div_modal_fade.appendChild(div_modal_dialog);

    el_insert.appendChild(div_modal_fade);
    el_insert.appendChild(button_modal_open);

    return button_modal_open;
  };
}