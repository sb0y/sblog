{append var="title" value="Новое личное сообщение на сайте $siteDomain" scope=global}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
<head><title>&nbsp;</title>
</head><body>
<p>Здравствуйте, <b>{$mail.nick}</b>!</p>
<p>
Пользователь <b><a href="{$urlBase}user/profile/{$smarty.session.user.userID}">{$smarty.session.user.nick}</a></b> отправил Вам личное сообщение на сайте <a href="{$urlBase}">{$siteDomain}</a>.<br />
Это сообщение можно прочитать пройдя по этой <b><a href="{$urlBase}user/mail/message/{$mail.data.messageID}">ссылке</a></b> (<small>необходимо быть авторизованным на сайте</small>) или просто зайдя в раздел <a href="{$urlBase}user/mail">личные сообщения</a> на сайте.
</p>
</body></html>
