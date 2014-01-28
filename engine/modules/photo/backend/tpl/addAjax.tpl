
<form id="picture_form" method="post" action="/adm/photo/addPageAjax?key={fromPost var='key' arr=$fill}" enctype="multipart/form-data">
    <label for="url">Выберите файл</label>
    <input type="file" id="picture" name="picture" value="{fromPost var='picture' arr=$fill}" />
    <button type="submit">Загрузить</button>
    <input type="hidden" id="key" name="key" value="{fromPost var='key' arr=$fill}" />
</form> 