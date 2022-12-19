{if isset($p) AND $p}
	{if $smarty.get.id_category|intval}
		{assign var='requestPage' value=$link->getPaginationLink('category', $category, false, false, true, false)}
		{assign var='requestNb' value=$link->getPaginationLink('category', $category, true, false, false, true)}
		
	{elseif $smarty.get.id_manufacturer|intval}
		{assign var='requestPage' value=$link->getPaginationLink('manufacturer', $manufacturer, false, false, true, false)}
		{assign var='requestNb' value=$link->getPaginationLink('manufacturer', $manufacturer, true, false, false, true)}

	{elseif $smarty.get.id_supplier|intval}
		{assign var='requestPage' value=$link->getPaginationLink('supplier', $supplier, false, false, true, false)}
		{assign var='requestNb' value=$link->getPaginationLink('supplier', $supplier, true, false, false, true)}

	{else}
		{assign var='requestPage' value=$link->getPaginationLink(false, false, false, false, true, false)}
		{assign var='requestNb' value=$link->getPaginationLink(false, false, true, false, false, true)}
	{/if}
	
	<!-- не нашел ништяк -->	
	{if $pages_nb > 1 AND $p != $pages_nb}
	{else}
	<ul id="product_list" class="clear">
		<div id="out">
		<div id="in">
	
			<li class="ajax_block_product item">
				<div class="center_block">
					<a href="/contact-form.php" class="product_img_link" >
							<img src="/img/no_product.png" {if $smarty.const.site_version == "mobile"}style="height: 200px;"{/if}>
					</a>
					
					<h3>
					<a href="/contact-form.php">Не нашел нужный ништяк?</a>
					</h3>
					
					<p class="product_desc">Напиши нам и мы поищем его в каталогах поставщиков</p>
	
					<a href="/contact-form.php" ></a>
					
					</div>
	
					<div class="right_block">
						<div>
						<span class="price" style="display: inline;">&nbsp;</span>
						</div>
									
						<span class="align_center">
						<a class="ebutton blue plist" href="/contact-form.php">Написать</a>
						</span>
					</div>
		</div>
	</div>
	<br class="clear"/>
	</li>
	</ul>
	{/if}


	<!-- Pagination -->
	<div id="pagination" class="pagination">
	{if $start!=$stop}
		<ul class="pagination">
		{if $p != 1}
			{assign var='p_previous' value=$p-1}
			<li id="pagination_previous">
				<a class="ebutton blue small" href="{$link->goPage($requestPage, $p_previous)}">&laquo;&nbsp;{l s='Previous'}</a>
			</li>
		{else}
			<li id="pagination_previous">
				<a  class="ebutton small inactive" >&laquo;&nbsp;{l s='Previous'}</a>
			</li>
		{/if}
		{if $start>3}
			<li><a href="{$link->goPage($requestPage, 1)}">1</a></li>
			<li class="truncate">...</li>
		{/if}
		{section name=pagination start=$start loop=$stop+1 step=1}
			{if $p == $smarty.section.pagination.index}
				<li class="current"><span>{$p|escape:'htmlall':'UTF-8'}</span></li>
			{else}
				<li><a href="{$link->goPage($requestPage, $smarty.section.pagination.index)}">{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}</a></li>
			{/if}
		{/section}
		{if $pages_nb>$stop+2}
			<li class="truncate">...</li>
			<li><a href="{$link->goPage($requestPage, $pages_nb)}">{$pages_nb|intval}</a></li>
		{/if}
		{if $pages_nb > 1 AND $p != $pages_nb}
			{assign var='p_next' value=$p+1}
			<li id="pagination_next"><a class="ebutton blue small" href="{$link->goPage($requestPage, $p_next)}">{l s='Next'}&nbsp;&raquo;</a></li>
		{else}
			<li id="pagination_next"><a class="ebutton small inactive">{l s='Next'}&nbsp;&raquo;</a></li>
		{/if}
		</ul>
	{/if}
	</div>
	<!-- /Pagination -->
	
	{if $smarty.const.site_version == "full"}				
	<p>&nbsp;</p>
	<form  action="{if !is_array($requestNb)}{$requestNb}{else}{$requestNb.requestUrl}{/if}" method="get" class="pagination">
			{if isset($query) AND $query}<input type="hidden" name="search_query" value="{$query|escape:'htmlall':'UTF-8'}" />{/if}
			{if isset($tag) AND $tag AND !is_array($tag)}<input type="hidden" name="tag" value="{$tag|escape:'htmlall':'UTF-8'}" />{/if}			
			<!--input type="submit" class="ebutton blue  mini" value="{l s='OK'}" /-->
			<p class="select">
			{if $full_ajax}
			<select name="n" id="nb_item" onchange="blockAdvanceSearch_{$duliq_id}.advcLoadUrl($(this).val());">
			{else}
			<select name="n" id="nb_item" onChange="{if $requestPage|strpos:'advancesearch' !== false}setAttr('n',this.value){else}submit(){/if}"> 
			{/if}
			{foreach from=$nArray item=nValue}
				<option value="{$nValue|escape:'htmlall':'UTF-8'}" {if $n == $nValue}selected="selected"{/if}>{$nValue|escape:'htmlall':'UTF-8'}</option>
			{/foreach}

			</select>
			<label for="nb_item">{l s='на странице:'}</label>
			</p>
			{if is_array($requestNb)}
				{foreach from=$requestNb item=requestValue key=requestKey}
					{if $requestKey != 'requestUrl' AND $requestKey != 'tag'}
						<input type="hidden" name="{$requestKey|escape:'htmlall':'UTF-8'}" value="{$requestValue|escape:'htmlall':'UTF-8'}" />
					{/if}
				{/foreach}
			{/if}
	</form>
	{/if}

{/if}

{*$link->goPage($requestPage, $p_next)*}
{*$link|print_r*}

{* сюда нельзя getattr / setattr - отрубятся линки под tips *}