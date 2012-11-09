{extends file="main.tpl"}
{block name=title}Связь с автором блога{/block}
{block name=pageScripts}
<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
{/block}
{block name=body}
<div class="posts-list post">

<p>Со мной можно связаться, используя данные средства.</p>

<div class="contacts">
    <table>
        <tr>
            <td width="10%">
                <img src="/uploads/thumbimlogo.jpg" alt="thumbimlogo" title="thumbimlogo" width="142" height="142" style="float:left;border: 0px;padding: 0px;" />
            </td>
            <td>
                <table>
                    <tr>
                        <td class="tbl-content">
                            <table class="tbl-content">
                                <tr>
                                    <td width="20%" align="center"><img src="/uploads/e-mail-icon.png" alt="e-mail-icon" title="e-mail-icon" width="50" height="56" /><br />E-mail</td>
                                    <td style="vertical-align:middle;">
										{mailto address="andrei@bagrintsev.me" encode="javascript_charcode"}
									</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-content">
                                <tr>
                                    <td width="20%" align="center"><img src="/uploads/icq-logo-a3e7b2eb6e-seeklogocom.gif" alt="icq-logo-a3e7b2eb6e-seeklogocom" title="icq-logo-a3e7b2eb6e-seeklogocom" width="50" height="50" /><br />ICQ</td>
                                    <td style="vertical-align:middle;">236-846</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-content">
                                <tr>
                                    <td width="20%" align="center"><img src="/uploads/202px-xmpp_logosvg.png" alt="202px-xmpp_logosvg" title="202px-xmpp_logosvg" width="50" height="51" /><br />Gtalk, Я.Онлайн, Jabber, Other XMPP</td>
                                    <td style="vertical-align:middle;">
										<a href="xmpp:{"andrei@bagrintsev.me"|escape:hexentity}">{"andrei@bagrintsev.me"|escape:hexentity}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table class="tbl-content">
                                <tr>
                                    <td width="20%" align="center"><a href="skype:andreantel?call"><img src="http://mystatus.skype.com/balloon/andreantel" width="150" height="60" alt="Мой статус Skype" /></a><br />Skype</td>
                                    <td style="vertical-align:middle;"><a href="skype:andreantel?call">andreantel</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <p>Также, меня можно найти в этих соц. сетях</p>
    <table>
        <tr>
            <td width="10%">
                <img src="/uploads/d0b1d0b5d0b7-d0b8d0bcd0b5d0bdd0b8.jpg" alt="d0b1d0b5d0b7-d0b8d0bcd0b5d0bdd0b8" title="d0b1d0b5d0b7-d0b8d0bcd0b5d0bdd0b8" width="191" height="289"  style="float:left;border: 0px;padding: 0px;" />
            </td>
            <td align="left" valign="top">
                <table valign="top">
                    <tr>
                        <td>
                            <table class="tbl-content">
                                <tr>
                                    <td width="10%" align="center" style="vertical-align:middle;"><img src="/resource/images/social-icons/facebook-logo.png" alt="facebook-logo" title="facebook-logo" width="50" height="50" /></td>
                                    <td style="vertical-align:middle;"><a target="_blank" href="http://www.facebook.com/andrei.bagrintsev">Я в Facebook</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-content">
                                <tr>
                                    <td width="10%" align="center" style="vertical-align:middle;"><img src="/uploads/52186605_vkontakte_logo_bukva.png" alt="52186605_vkontakte_logo_bukva" title="52186605_vkontakte_logo_bukva" width="50" height="50" /></td>
                                    <td style="vertical-align:middle;"><a target="_blank" href="http://vk.com/andrei.bagrintsev">Я вконтатке</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-content">
                                <tr>
                                    <td width="10%" align="center" style="vertical-align:middle;"><img src="/resource/images/social-icons/twitter-logo.png" alt="twitter-logo" title="twitter-logo" width="50" height="50" /></td>
                                    <td style="vertical-align:middle;"><a target="_blank" href="http://twitter.com/bagrintsev">Я в Twitter</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="tbl-content">
                                <tr>
                                    <td width="10%" align="center" style="vertical-align:middle;"><img src="/uploads/lastfm_logo.png" alt="lastfm_logo" title="lastfm_logo" width="50" height="52" /></td>
                                    <td style="vertical-align:middle;"><a target="_blank" href="http://www.lastfm.ru/user/AndreanTel">Last.FM</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</div>
{/block}
