{extends file="main.tpl"}
{block name=title}Связь с автором блога{/block}
{block name=pageScripts}
<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
<script type="text/javascript" src="{$urlBase}resource/js/sort.js"></script>
<link rel="stylesheet" href="{$urlBase}resource/css/tables.css" type="text/css" media="all" />
{/block}
{block name=body}
<div class="contacts">

    <div class="page-header">
        <h1>Контакты</h1>
    </div>

    <h3>Со мной можно связаться, используя данные средства:</h3>

    <div class="table-responsive">
        <table class="sortable infoTable table table-bordered table-striped">
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
                        {mailto address="andrey@bagrintsev.me" encode="javascript_charcode"}
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
    </div>
</div>
{/block}
