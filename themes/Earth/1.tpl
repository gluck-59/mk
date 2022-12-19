{$smarty.cookies.email}
<br>
{$smarty.cookies.firstname}
</pre>



			{include file=$tpl_dir./product-sort.tpl}
			{include file=$tpl_dir./product-list.tpl products=$products}
			{include file=$tpl_dir./pagination.tpl}
