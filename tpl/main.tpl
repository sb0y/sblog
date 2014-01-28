{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/commentsCounter.js"></script>    
<link rel='stylesheet' id='style-css'  href='{$urlBase}resources/css/diapo.css' type='text/css' media='all' /> 
<script type='text/javascript' src='{$urlBase}resources/js/jquery.simpleSlider.js'></script> 


{/block}
{block name=videos}
    {plugin exec=video}
    {if isset($videos) }
        <!-- <div class="row dark_gray horizontal_borders"> -->
            <!-- <div class="col span_8 vertical_borders">
                <div class="video_place">
                    {*{$videos[0].video}*}
                    <a href="{$urlBase}video/{$videos[0].slug}" style="background: url({$urlBase}content/videoPreview/{$videos[0].pictures}) no-repeat 0px -60px; width: 635px; height: 360px; display: block; "></a>
                </div>
            </div>
            <div class="col span_4 vertical_borders">
                <div class="video_nav">
                    <ul>
                        {foreach $videos as $key => $value}
                            <li>
                                <a href="javascript:void(null)" data-id="{$value.slug}"  data-pic="{$value.pictures}" class="{if $key == 0}active{/if}" data-target="video_place">
                                    <b>{$value.title}</b>
                                    <i>Кол-во просмотров - {$value.views_count}</i>
                                    <em>Кол-во комментариев: {$value.comments_count}</em>
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div> -->
            <!-- <img src="/content/.jpg" /> -->
        <!-- </div> -->
    {/if}
    {/plugin}
    <script>
        $(function(){
            
        $('.slider').simpleSlider(
          {
            target: 'slider',
            animation: true, // true, false
            effect: 'fade', // fade,slide
            automate: true, // true, false
            timeout: 10000, // time auto scroll
            next: 'next', // default class to next slide button
            prev: 'prev', // default class to prev slide button
            nav: true, // generate navigation buttons
            set_default_css: true // set default css style
          });
    
        });
        </script>
    <section> 
        <div style="overflow:hidden; width:959px; margin: 0 auto;" class="soft_dark_gray slider"> 
                <ul class="">
                    <li data-thumb="{$urlBase}content/payday2.jpg">
                        <a href="/article/payday2-the-armored-transport-dlc"><img src="{$urlBase}content/payday2.jpg">
                        <div class="caption youtube elemHover slideDown" data-action="slideDown"></div>
                        </a>
                    </li>
                    <li data-thumb="{$urlBase}content/bioshock.jpg">
                        <a href="/article/bioshock-infinite-burial-at-sea-dlc-epizod-1"><img src="{$urlBase}content/bioshock.jpg">
                        <div class="caption youtube elemHover " data-action="slideDown"></div>
                        <div class="caption text elemHover" data-action="slideLeft">
                            <p>Очень востроженный обзор, про потрясающий DLC</p> 
                        </div>
                         </a>
                    </li>
               </ul>
               <a href="javascript:;" class="prev"></a>
               <a href="javascript:;" class="next"></a>
               <div class="percentage">
                <div class="percent"></div>
               </div>
        </div>
    </section> 
{/block}
{block name=homepage_title}
  <div class="row soft_dark_gray horizontal_borders shadow">
        <div class="col span_6">
            <a href="{$urlBase}news/rss" class="btn dark pull_left margin_10"><i class="icon rss"></i></a><h2 class="dark_title pull_left">Горячие новости <small>С пылу, с жару</small></h2>
        </div>
        <div class="col span_6">
            <a href="{$urlBase}article/rss" class="btn dark pull_left margin_10"><i class="icon rss"></i></a><h2 class="dark_title pull_left">Заметки <small>Полезно знать</small></h2>
        </div>
    </div>
{/block}
{block name=homepage}
        <div class="row">
            <div class="col span_6 entry_6">
                <div class="entries">
                {foreach $posts.col1 as $key=>$value}
                    <div class="offset_15 entry" id="{$value.slug}">
                        <div class="row">
                        {if $value.poster}
                            <div class="col span_4">
                                <a href="{$urlBase}{$value.URL}"><img class="entry_img" src="{$urlBase}content/photo/200x200/{$value.poster}" alt="image" title="image" /></a>
                            </div>
                            <div class="col span_8">
                                <div class="offset_0_15">
                            {else}
                                <div class="col span_12">
                            {/if}
                                <h2 class="title">
                                    <a href="{$urlBase}{$value.URL}">{$value.title}</a>
                                </h2>
                                    {if $value.short}{$value.short}{else}{$value.body|truncate:100}{/if}
                                </div>
                            </div>
                        {if $value.poster}</div>{/if}
                        <div class="row offset_10_0">
                            <div class="col span_12">
                                <div class="offset_0_0_15">
                                    <a href="{$urlBase}{$value.URL}" class="pull_left readmore">Читать далее</a>
                                    <a href="{$urlBase}{$value.URL}" class="dynamicCount pull_right margin_0_10">{if $value.comments_count > 0}Комментарии ({$value.comments_count}){else}Откомментировать{/if}</a>
                                    <span class="pull_right date">{$value.dt|date_format:"%d"} {$value.dt|month_declination} {$value.dt|date_format:"%Y"}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}  
                </div>
            </div>
            <div class="col span_6  entry_6 offset_minus_1">
                <div class="entries">
                {foreach $posts.col2 as $key=>$value}
                    <div class="entry offset_15" id="{$value.slug}">
                        <div class="row">
                        {if $value.poster}
                            <div class="col span_4">
                                <a href="{$urlBase}{$value.URL}"><img class="entry_img" src="{$urlBase}content/photo/200x200/{$value.poster}" alt="image" title="image" /></a>
                            </div>
                            <div class="col span_8">
                                <div class="offset_0_15">
                            {else}
                                <div class="col span_12">
                            {/if}
                                    <h2 class="title">
                                        <a href="{$urlBase}{$value.URL}">{$value.title}</a>
                                    </h2>

                                    {if $value.short}{$value.short}{else}{$value.body|truncate:100}{/if}
                                </div>
                            </div>
                            {if $value.poster}</div>{/if}
                        <div class="row offset_10_0">
                            <div class="col span_12">
                                <div class="offset_0_0_15">
                                    <a href="{$urlBase}{$value.URL}" class="pull_left readmore">Читать далее</a>
                                    <a href="{$urlBase}{$value.URL}" class="dynamicCount pull_right margin_0_10">{if $value.comments_count > 0}Комментарии ({$value.comments_count}){else}Откомментировать{/if}</a>
                                    <span class="pull_right date">{$value.dt|date_format:"%d"} {$value.dt|month_declination} {$value.dt|date_format:"%Y"}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}      
                </div>
            </div>
        </div>
{/block}
