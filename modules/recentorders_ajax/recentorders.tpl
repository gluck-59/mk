{if $smarty.const.site_version == "full"}
<noindex>
<!-- MODULE recentorders -->
<div id="recentorders" class="block products_block">
    <h4>Ништяки в пути {*$smarty.now|date_format:"%e %b"*}</h4>
    <div class="block_content">
	    <ul class="products"  id="recentorders_ajaxx" style="height:{$qty*99}px; overflow: hidden;" direction="up";>
            {* пихать аяксом сюда *}
        </ul> 
    </div>
</div>

<script type="text/javascript">
max = {$max};
ind = 0;
{literal}
   
window.onload = (function($){
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
													
                        if (ind <= max) // пока индекс меньше максимума RECENT_ORDER_NUMBER в настройках
                        {
    						self.animate({scrollTop:sct+settings.step-sct%settings.step},500,function(){
    							if(settings.timeout) clearTimeout(settings.timeout);
    							settings.timeout = setTimeout(function(){self.marqueePlay()},settings.time);
    						});
  							getloader(); // запросим аякс лоадер
						}
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
$('#recentorders ul').marquee({time: 5000, step: 100});
$('#recentorders ul').hover(function(){$('#recentorders ul').marqueeStop()},function(){$('#recentorders ul').marqueePlay()});


function getloader()
{    
    recent_loader=new XMLHttpRequest();
    recent_loader.onreadystatechange=function(){
      if (recent_loader.readyState==4 && recent_loader.status==200)
         document.getElementById('recentorders_ajaxx').innerHTML+=recent_loader.responseText;
      }
   recent_loader.open('GET','/modules/recentorders_ajax/ajax.php?ind='+ind,true);
   recent_loader.send();
   ind = ind+1;

}
{/literal}
</script>   
<!-- /MODULE recentorders -->
</noindex>
{/if}