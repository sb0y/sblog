{extends file="main.tpl"}
{block name=title}Связь с автором блога{/block}
{block name=pageScripts}
{/block}

{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
    <span>С администрацией можно связаться, используя данные средства.</span>
</div>
{/block}
{block name=body}
    <table class="table">
         <tbody>
            <tr>
                <td align="center">
                    {mailto address="andrei@bagrintsev.me" encode="javascript_charcode"}
                </td>
                <td>
                    Электронная почта
                </td>
            </tr>
            <tr>
                <td align="center">
                    236-846
                </td>
                <td>
                    ICQ (аська)
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a href="xmpp:{"andrei@bagrintsev.me"|escape:hexentity}">{"andrei@bagrintsev.me"|escape:hexentity}</a>
                </td>
                <td>
                    Gtalk, Я.Онлайн, Jabber и другие XMPP-совместимые.
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a href="skype:andreantel?call">andreantel</a>
                </td>
                <td>
                    Skype
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a target="_blank" href="http://www.facebook.com/andrei.bagrintsev">Facebook</a>
                </td>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a target="_blank" href="http://vk.com/andrei.bagrintsev">ВКонтатке</a></div>
                </td>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td align="center">
                    <a target="_blank" href="http://twitter.com/bagrintsev">Twitter</a>
                </td>
                <td>
                    
                </td>
            </tr>
        </tbody>
    </table>

</div>
{/block}
