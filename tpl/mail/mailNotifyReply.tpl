{append var="title" value="Ответ на Ваш комментарий к \"{$data.article_title}\"" scope=global}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
<head><title>&nbsp;</title>
</head><body>
<p>Здравствуйте, <b>{$data.nick}</b>!</p>
<p>
Пользователь <b><a href="{$urlBase}user/profile/{$smarty.session.user.userID}">{$smarty.session.user.nick}</a></b> ответил на Ваш комментарий к {if $data.type=="news"}новости{else}статье{/if} "{$data.article_title}" на сайте <a href="{$urlBase}">{$siteDomain}</a>.<br />
Это сообщение можно прочитать пройдя по этой <b><a href="{$urlBase}{$data.article_returnPath}/#comment_{$data.commentID}">ссылке</a></b>.
</p>
</body></html>