/*
* Very simple jQuery content slider v 0.1
* Author Malikov A.V. (alexrettvm@gmail.com)
* This work is licensed under the Creative Commons Attribution 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by/3.0/.
*
* Basic usage
* html:
* <div class="slider">
* <ul>
* <li>Slide 1</li>
* <li>Slide 2, any content</li>
* </ul>
* </div>
* <script type="text/javascript">
* $(document).ready(function () {
* $('.slider').simpleSlider();
* });
* </script>
*/

jQuery.fn.simpleSlider = function (options) {
    var settings = jQuery.extend({
        target: 'slider',
        animation: true, // true, false
        effect: 'fade', // fade,slide
        automate: true, // true, false
        timeout: 5000, // time auto scroll
        next: 'next', // default class to next slide button
        prev: 'prev', // default class to prev slide button
        nav: true, // generate navigation buttons
        set_default_css: true // set default css style
    }, options);
    var intID = null;
    return this.each(function () {
        // main function to change slides
        function goto(pos) {
            $slider = $('.'+settings.target+' ul');
            $items = $slider.find('> li');
            $active = $items.filter('.active');
            $first = $items.filter(':first');
            $last = $items.filter(':last');
            $next = $active.next();
            $prev = $active.prev();
            // check is "active" class is enable in layout
            if($active.length == 0)
            {
                $first.addClass('active');
                $active = $first;
            }
            // next slide
            if(pos == 1)
            {
                if($next.length == 0)
                {
                    $next = $first;
                }
            }
            // previous slide
            else if(pos == 2)
            {
                if($prev.length != 0)
                {
                    $next = $prev;
                }
                else
                {
                    $next = $last;
                }
            }
            // change slide
            if(pos)
            {
                if(settings.animation)
                {
                    if(settings.effect == 'slide')
                    {
                        $next.slideDown('slow').addClass('active');
                    }
                    else if(settings.effect == 'fade')
                    {
                        $next.fadeIn().addClass('active');
                    }
                }
                else
                {
                    $next.show().addClass('active');
                }
                $active.hide().removeClass('active');
                
            }
            $('.slider .caption').hide();
            $action = $('.slider .caption').attr('data-action');
            if($action == 'slideDown')
            {
                $('.slider .caption.youtube').css('top','0px').slideDown(100).animate({top: '41%'},300, function(){});
                $('.slider .caption.text').css('bottom','-80px').fadeIn(100).animate({bottom: '0'},300, function(){});
            }
            if($action == 'slideLeft')
            {
                $('.slider .caption').slideLeft(300);
            }
            return false;
        }
        // set auto scroll
        var timer = $.timer(function() {
             goto(1);
        }); 

        function setTimeout()
        {
            if (settings.automate)
            {
                // intID = window.setInterval(function() { goto(1); }, settings.timeout);
                timer.set({ time : settings.timeout, autostart : true });
                timer.play();
                animatePercentage();
            }
        }
        function resetTimeout()
        {
            if (settings.automate)
            {
                // intID = window.setInterval(function() { goto(1); }, settings.timeout);
                timer.set({ time : settings.timeout, autostart : true });
                timer.play();
                resetPercentage();
                animatePercentage();
                pausePercentage();
                timer.pause();
            }
        }
        // event listener to change next slide
        $(this).on('click','a.'+settings.next,function() {
            resetTimeout();
            return goto(1);
        });
        // event listener to change previous slide
        $(this).on('click','a.'+settings.prev,function() {
            resetTimeout();
            return goto(2);
        });
        // initialization
        $(this).ready(function(){
            if(settings.automate)
            {
                setTimeout();
            }
            goto(null);
            // add navigation button
            if(settings.nav)
            {
                $slider.parent().append('<a href="#" class="'+settings.prev+'"></a><a href="#" class="'+settings.next+'"></a>');
            }
            // default css styles
            if(settings.set_default_css)
            {
                $(".slider").css({"position": "relative", "margin-left": "0", "display": "block",  "width": "959px", "height": "366px"});
                $(".slider ul").css({"position": "relative",  "width": "959px", "height": "366px"});
                $(".slider ul li").css({"position": "absolute", "display": "none"});
                $(".slider ul li.active").css({"display": "block"});
                $(".slider ul li img").css({"width": "100%"});
                $(".slider a.prev, .slider a.next").css({"display": "block", "width": "32px", "height": "53px", "background":  "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAAA1CAYAAADxhu2sAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAu9JREFUeNrcmktT2zAQgF2VvOixEEiA8mgPUM78/3+BkzCTae+FPEhhWiBGmll1draWYlteRfLO7MHxRtb3WX7IdpJlWeIpPsu8kXmif1DbxgnrbqCWPdQ2dzzCf5P5QWbXUqfWfUS1v7g7JjzA7yGglcyppXYKNar2K/w3agF7AKLhU5lvlvo3qPEmQQQETyU8+pDAJWAfwT+WgMcSRkTCfiwCVEcvEPyoJLxJwgWHBBEovG0k9EMV0CfD3hU+T0ICgvuhCehDx1QsKxzzRU+My7olCAZ4tbfWDOeWNbSNJRxsW8CBJ3iThHNXCcIR/twjPJWwQBIOfQvA8AuP8FjCGEk4qyqhioBDAj/2DF+rBFEB/iwAeCphjiQMuAQMEPw8AHgsYYIknJaRIErAnyL4SSDwzhJEA+BNh4Pq89BVAIUfBwqvI4M+zmD5yyYJNgFDBD+DhrMk/MhglGIJR2UFDOGPGn4SCTyV8ADLJyYJeQKOIofHEu42SRA58Pqx9UPE8DYJxyYBFP4ucniThGMsQTQcnkq4pxIEusXV19KfDYPHEn6gy/gAC5jCCrV8JbPdQAEtmd+BcQ3M/wTM0JS2C4XtBsL30G3zPT0JLhsqIQ9+broM4geaSsK1zE6D4PFcwXgjhB9pd6CBTkPgF0VvhWOX0C4Cv2kyFKsECj8ywReZDscmQcN3k/8foVd+IILf7oYsoTR8UQEqVkTCdWL/1MV34D7R12i1CNASbmW+EtshwOtRSV+k1ipAxW+wG4oEJ/gqAkKS4AxfVYBJQi82eBcB25RA4dOq8K4CqISWBwl4foI/qUu2JUBLUFeHF2YJeIb6CttcuTZa1ycyT7A3uCRQ+BTEJ6EI4JTQ44KvW4CWQA+H3VDhOQSoeCYSripK0PAtLnguAXVIoPC3HPCcAvIkKKBPJeFfoI0nrk5yfy6vJfyVuQMjwSZhl8CnnPA+BGgJKZJwaam99LXnfQrAI+F5w7Gs1v9BtezxLsAA4ddEAR6QhykAAAAASUVORK5CYII=) no-repeat", "position": "absolute", "top": "130px", "z-index": "200"});
                $(".slider a.prev").css( {"left": "13px"});
                $(".slider a.next").css( {"right": "13px", "background-position": "-30px 0px"});
            }
            
        });

    function getPercentage()
    {
        var timeremaining = timer.remaining;
        var percent = settings.timeout - timeremaining;
        console.log(percent);
    }
    function animatePercentage()
    {
        $('.slider .percent').animate({
            width: "100%"
          }, settings.timeout, function() {
            $(this).css('width', 0);
            animatePercentage();
        });
    }
    function pausePercentage()
    {
       $('.slider .percent').pause(); 
    }
    function resumePercentage()
    {
       $('.slider .percent').resume(); 
    }
    function resetPercentage()
    {
       $('.slider .percent').css('width',0); 
       $('.slider .percent').stop();
    }
    // stop slider when window\tab is not active, and auto start when it focus again.
    if(settings.automate)
    {
        window.addEventListener('focus', function() {
            resumePercentage();
            timer.play();

        });
        window.addEventListener('blur', function() {
            pausePercentage();
            timer.pause();
        });
        $('.slider').mouseenter(function(){
            pausePercentage();
            timer.pause();
        }).mouseleave(function(){
            resumePercentage();
            timer.play();
        });
    }
        
    });
};


/*
 * Pause jQuery plugin v0.1
 *
 * Copyright 2010 by Tobia Conforto <tobia.conforto@gmail.com>
 *
 * Based on Pause-resume-animation jQuery plugin by Joe Weitzel
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or(at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */
(function(){var e=jQuery,f="jQuery.pause",d=1,b=e.fn.animate,a={};function c(){return new Date().getTime()}e.fn.animate=function(k,h,j,i){var g=e.speed(h,j,i);g.complete=g.old;return this.each(function(){if(!this[f]){this[f]=d++}var l=e.extend({},g);b.apply(e(this),[k,e.extend({},l)]);a[this[f]]={run:true,prop:k,opt:l,start:c(),done:0}})};e.fn.pause=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&g.run){g.done+=c()-g.start;if(g.done>g.opt.duration){delete a[this[f]]}else{e(this).stop();g.run=false}}})};e.fn.resume=function(){return this.each(function(){if(!this[f]){this[f]=d++}var g=a[this[f]];if(g&&!g.run){g.opt.duration-=g.done;g.done=0;g.run=true;g.start=c();b.apply(e(this),[g.prop,e.extend({},g.opt)])}})}})();

/**
 * jquery.timer.js
 *
 * Copyright (c) 2011 Jason Chavannes <jason.chavannes@gmail.com>
 *
 * http://jchavannes.com/jquery-timer
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
 * BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

(function($) {
    $.timer = function(func, time, autostart) { 
        this.set = function(func, time, autostart) {
            this.init = true;
            if(typeof func == 'object') {
                var paramList = ['autostart', 'time'];
                for(var arg in paramList) {if(func[paramList[arg]] != undefined) {eval(paramList[arg] + " = func[paramList[arg]]");}};
                func = func.action;
            }
            if(typeof func == 'function') {this.action = func;}
            if(!isNaN(time)) {this.intervalTime = time;}
            if(autostart && !this.isActive) {
                this.isActive = true;
                this.setTimer();
            }
            return this;
        };
        this.once = function(time) {
            var timer = this;
            if(isNaN(time)) {time = 0;}
            window.setTimeout(function() {timer.action();}, time);
            return this;
        };
        this.play = function(reset) {
            if(!this.isActive) {
                if(reset) {this.setTimer();}
                else {this.setTimer(this.remaining);}
                this.isActive = true;
            }
            return this;
        };
        this.pause = function() {
            if(this.isActive) {
                this.isActive = false;
                this.remaining -= new Date() - this.last;
                this.clearTimer();
            }
            return this;
        };
        this.stop = function() {
            this.isActive = false;
            this.remaining = this.intervalTime;
            this.clearTimer();
            return this;
        };
        this.toggle = function(reset) {
            if(this.isActive) {this.pause();}
            else if(reset) {this.play(true);}
            else {this.play();}
            return this;
        };
        this.reset = function() {
            this.isActive = false;
            this.play(true);
            return this;
        };
        this.clearTimer = function() {
            window.clearTimeout(this.timeoutObject);
        };
        this.setTimer = function(time) {
            var timer = this;
            if(typeof this.action != 'function') {return;}
            if(isNaN(time)) {time = this.intervalTime;}
            this.remaining = time;
            this.last = new Date();
            this.clearTimer();
            this.timeoutObject = window.setTimeout(function() {timer.go();}, time);
        };
        this.go = function() {
            if(this.isActive) {
                this.action();
                this.setTimer();
            }
        };
        
        if(this.init) {
            return new $.timer(func, time, autostart);
        } else {
            this.set(func, time, autostart);
            return this;
        }
    };
})(jQuery);
