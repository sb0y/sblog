<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
>
<channel>
<title>9000 Games</title>
<link>{$urlBase}</link>
<description>RSS лента новостного сайта 9000 Games</description>
<language>ru</language>
<pubDate>{$smarty.now|date_format:"r"}</pubDate>

<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>{$urlBase}/</generator>
<managingEditor>hostmaster@9kg.me</managingEditor>
<webMaster>hostmaster@9kg.me</webMaster>

{foreach $items as $key=>$item}
<item>
	<title>{$item.title}</title>
	<link>{$urlBase}news/{$item.slug}/</link>
	<description>{$item.short|strip_tags:false|replace:"\r\n":''|replace:"\n":''}</description>
	<content:encoded><![CDATA[[{$item.body}]]></content:encoded>
	<pubDate>{$item.tms|date_format:"r"}</pubDate>
	<dc:creator>{$item.author}</dc:creator>
	{foreach $item.cats as $k=>$v}<category><![CDATA[{$v.catName}]]></category>{/foreach}
	<comments>{$urlBase}news/{$item.slug}</comments>
	<guid isPermaLink="false">{$urlBase}news/{$item.slug}</guid>
</item>
{/foreach}

</channel></rss>
