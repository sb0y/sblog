{append var="title" value="Ваш материал успешно экспортирован в vk.com" scope=global}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
<head><title>&nbsp;</title>
</head><body>
<p>Здраствуйте, уважаемый {$mail.nick}.</p>
<p>Ваша статья была эскпортирована с сайта <b><a href="http://{$mail.domain}">{$mail.domain}</a></b> в социальную сеть "ВКонтакте".</p>
<p>Прямая ссылка на результат экспорта:<br />
<b><a href="http://vk.com/wall-58178821_{$mail.post_id}">http://vk.com/wall-58178821_{$mail.post_id}</a></b>
</p>
</body></html>
