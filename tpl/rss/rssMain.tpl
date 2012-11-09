<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
>
<channel>
<title>sb0y[AT]home</title>
<link>{$urlBase}</link>
<description>Уютный бложик девелопера.</description>
<language>ru</language>
<pubDate>{$smarty.now|date_format:"r"}</pubDate>

<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>{$urlBase}/</generator>
<managingEditor>sb0y@bagrincev.ru</managingEditor>
<webMaster>sb0y@bagrincev.ru</webMaster>

{foreach $items as $key=>$item}
<item>
	<title>{$item.title}</title>
	<link>{$urlBase}blog/{$item.url_name}/</link>
	<description>{$item.short|strip_tags:false|replace:"\r\n":''|replace:"\n":''}</description>
	<content:encoded><![CDATA[[{$item.body}]]></content:encoded>
	<pubDate>{$item.tms|date_format:"r"}</pubDate>
	{*<dc:creator>'.$creator.'</dc:creator>*}
	{foreach $item.cats as $k=>$v}<category><![CDATA[{$v.catName}]]></category>{/foreach}
	<comments>{$urlBase}blog/{$item.url_name}</comments>
	<guid isPermaLink="false">{$urlBase}blog/{$item.url_name}</guid>
</item>
{/foreach}

</channel></rss>
