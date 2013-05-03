{extends file="main.tpl"}
{block name=title}Связь с автором блога{/block}
{block name=pageScripts}
<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
<script type="text/javascript" src="{$urlBase}resource/js/sort.js"></script>
<link rel="stylesheet" href="{$urlBase}resource/css/tables.css" type="text/css" media="all" />
{/block}
{block name=body}
<div class="posts-list post">

<div class="contacts">

    <p>Со мной можно связаться, используя данные средства.</p>

    <table class="sortable infoTable">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><div class="tableText">Ссылка</div></th>
                <th><div class="tableText">Тип</div></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">
                    <img src="/resource/images/contacts-icons/e-mail-icon.png" alt="e-mail-icon" title="e-mail-icon" />
                </td>
                <td align="center">
                    {mailto address="andrei@bagrintsev.me" encode="javascript_charcode"}
                </td>
                <td>
                    Электронная почта
                </td>
            </tr>
            <tr>
                <td align="center">
                    <img src="/resource/images/contacts-icons/icq-icon.png" alt="icq-icon" title="icq-icon" />
                </td>
                <td align="center">
                    236-846
                </td>
                <td>
                    ICQ (аська)
                </td>
            </tr>
            <tr>
                <td align="center">
                    <img src="/resource/images/contacts-icons/jabber-icon.png" alt="jabber-icon" title="jabber-icon" />
                </td>
                <td align="center">
                    <a href="xmpp:{"andrei@bagrintsev.me"|escape:hexentity}">{"andrei@bagrintsev.me"|escape:hexentity}</a>
                </td>
                <td>
                    Gtalk, Я.Онлайн, Jabber и другие XMPP-совместимые.
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a href="skype:andreantel?call"><img src="http://download.skype.com/share/skypebuttons/buttons/call_blue_transparent_34x34.png" alt="Мой статус Skype" /></a>
                </td>
                <td align="center">
                    <a href="skype:andreantel?call">andreantel</a>
                </td>
                <td>
                    Skype
                </td>
            </tr>
        </tbody>
    </table>

    <p>Также, меня можно найти в этих соц. сетях</p>

    <table class="sortable infoTable">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><div style="width:59%;text-align:right;" class="tableText">Адрес в социальной сети</div></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">
                    <img src="/resource/images/social-icons/facebook-logo.png" alt="e-mail-icon" title="e-mail-icon" />
                </td>
                <td align="right">
                    <div class="table-row-clear"><a target="_blank" href="http://www.facebook.com/andrei.bagrintsev">Я в Facebook</a></div><div style="width:45%;"></div></td>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <img src="/uploads/52186605_vkontakte_logo_bukva.png" alt="icq-icon" title="icq-icon" />
                </td>
                <td align="right">
                    <div class="table-row-clear"><a target="_blank" href="http://vk.com/andrei.bagrintsev">Я ВКонтатке</a></div><div style="width:45%;"></div>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <img src="/uploads/lastfm_logo.png" alt="lastfm_logo" title="lastfm_logo" />
                </td>
                <td align="right">
                    <div class="table-row-clear"><a target="_blank" href="http://www.lastfm.ru/user/AndreanTel">Last.FM</a></div><div style="width:45%;"></div>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a href="http://twitter.com/bagrintsev" target="_blank"><img src="/resource/images/social-icons/twitter-logo.png" alt="twitter" /></a>
                </td>
                <td align="right">
                    <div class="table-row-clear"><a target="_blank" href="http://twitter.com/bagrintsev">Я в Twitter</a></div><div style="width:45%;"></div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="clear"></div>
</div>
{/block}
