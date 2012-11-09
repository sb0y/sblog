{append var="title" value="Запрос на восстановление пароля" scope=global}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
<head><title>&nbsp;</title>
</head><body>
<p>Здравствуйте, <b>{$appeal}</b>.</p>
<p>Это автоматически ответ на запрос восстановления пароля, для логина <b>{$emailForSend}</b>, с сайта <b><a href="{$urlBase}">{$urlBase}</a></b></p>
<p>Чтобы установить новый пароль, пройдите по <b><a href="{$urlBase}user/passwordRestore/code/{$code|urlencode}">ссылке</a></b>.</p>
<p>Ссылка перестанет работать ровно через три дня.</p>
</body></html>
