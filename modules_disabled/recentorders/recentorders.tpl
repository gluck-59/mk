{if $smarty.const.site_version == "full"}
<!-- MODULE recentorders -->
<!-- <div id="recentorders" class="block products_block"> -->
<div id="recentorders" class="block products_block">
<h4>Посылки в пути {*$smarty.now|date_format:"%e %b"*}</h4>
<div class="block_content">
	<ul class="products" style="height: {$RECENT_ORDER_NUMBER_DISPLAY*98}px; overflow: hidden;" direction="up";>
{foreach from=$result item=item}
		<li class="firts_item">		
		<table width="100%" border="0"; ><tr>
		<td valign="middle"; height="90px" width="87px"><a href="{$item.product_link}"><img class="recentorders" src="{$item.img}" alt="{$item.product_name}"></a></td>
		<td valign="middle"; height="90px"><a rel="nofollow" href="{$item.product_link}">{$item.product_name|truncate:4:"...":true:true}<br>
		Едет в {$item.address|truncate:2:"...":true:true}<br> байкеру {$item.biker}</a></td>
		</tr></table>
		</li>  
{/foreach} 
	</ul> 
{literal}
<script type="text/javascript">
(function($){
	var methods = {
        marquee: function marquee(user_settings) {
            var self = $(this);            
			var sch = self.attr('scrollHeight');
            var settings = {
                timeout: null,                
                events: {
                    play: function(evt) {
                        var self = $(this);						
						var sct = self.scrollTop();
						if(sct>=sch)self.scrollTop(sct=sct-sch);
						self.animate({scrollTop:sct+settings.step-sct%settings.step},400,function(){
							if(settings.timeout) clearTimeout(settings.timeout);
							settings.timeout = setTimeout(function(){self.marqueePlay()},settings.time);
						});
                    },
                    stop: function(evt) {
                        var self = $(this);
                        clearTimeout(settings.timeout);self.stop();
                    }
                }
            };
            if(self.data("marquee.settings")) {
                settings = self.data("marquee.settings");
            }
            settings = $.extend(user_settings, settings);            
            for(var event in settings.events) {
                var evt = "marquee." + event;
                self.unbind(evt);
                self.bind(evt, settings.events[event]);
            }            
            self.data("marquee.settings", settings);
			self.marqueePlay();
            return self;
        },
        marqueePlay: function() {
            $(this).trigger("marquee.play");         
        },
        marqueeStop: function() {
            $(this).trigger("marquee.stop");
        }
    };
    $.each(methods, function(i) {
        $.fn[i] = this;
    });
})(jQuery);
$('#recentorders ul').marquee({time: 5000, step: 98});
$('#recentorders ul').hover(function(){$('#recentorders ul').marqueeStop()},function(){$('#recentorders ul').marqueePlay()});
</script>
{/literal}

</div>
</div>
</noindex>
<!-- /MODULE recentorders -->
{/if}
