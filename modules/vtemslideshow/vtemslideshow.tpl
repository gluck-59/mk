{if $smarty.const.site_version == "full"}
	<!-- MODULE 1a1 slideshow -->
{if isset($images) AND $images}
	<script type="text/javascript" src="//motokofr.com/modules/vtemslideshow/js/jquery.cycle.js"></script>
	<link href="//motokofr.com/modules/vtemslideshow/styles.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
   		$(document).ready(function(){ldelim}
        $('#vtemslideshow1').cycle({ldelim}
		      fx: '{$fx}',
			  timeout: {$timeout},
              speed: {$speed}, 
              next: '#cycle_next',
              prev: '#cycle_prev',
              pager: '#vtemnav',
              pagerEvent:   'click',
              pagerAnchorBuilder: pagerFactory,
			  startingSlide: {$startingSlide},
			  fit: {$fit},
			  height:{$height},
			  width: {$width}
		{rdelim});
		function pagerFactory(idx, slide){ldelim}
           return '#vtemnav a:eq(' + idx + ') span';
        {rdelim};
        {rdelim});
     </script>
    <div id="vtemslideshow_wapper" style="width:{$width}px; height:{$height}px; margin-left: 10px; margin-bottom: 26px; z-index: 6;">
    <div id="vtemslideshow1" class="vtem_main_slideshow">
	{foreach from=$images item=image key=i}
		{if isset($image.name) AND $image.name}
		    {if isset($image.link) AND $image.link}
                <a href="{$image.link}" {if $i > 0}style="display:none"{/if}>
		    {/if}

			<img src="//motokofr.com/modules/vtemslideshow/slides/{$image.name}" alt="{$image.name}" style="border-top-left-radius: 6px;
border-top-right-radius: 6px; -webkit-box-reflect: below 0px -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(.6, transparent), to(rgba(0,0,0,0.2)));}" >
			{if isset($image.link) AND $image.link}</a>{/if}
		{/if}
	{/foreach}
	</div>
	    <div class="{$position} {$navstyle} vt_{$nav}" id="nav"><div id="vtemnav" style="opacity:0.4">
		{foreach from=$images item=image key=i}
		<a href="#slide-{$i+1}"><span>{$i+1}</span></a>
		{/foreach}
		<div class="clr"></div>
		</div></div>
		<a id="cycle_prev" class="vt_{$next_prev}" ></a>
		<a id="cycle_next" class="vt_{$next_prev}" ></a>
	</div>
					
{/if}
<!-- /MODULE 1a1 slideshow -->
{/if}
