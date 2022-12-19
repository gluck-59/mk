{if $smarty.const.site_version == "full"}
<!-- Block vkgroups module -->
<div id="vkgroups" style="margin-bottom:20px">
<!-- <div id="vkgroups" class="block"> -->

<!-- <h4>{l s='Мы Вконтакте' mod='vkgroups'}</h4> -->
     <div id="vk_groups"></div> 
    <script async type="text/javascript">
    VK.Widgets.Group("vk_groups", {ldelim}mode: {$vk_groups_mode}, width: "200", height: "431"{rdelim}, {$vk_groups_id});
    </script>
</div>
{/if}