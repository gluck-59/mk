<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Квитанция на оплату</TITLE>

<META http-equiv=Content-Type content="text/html;  charset=utf-8;charset=utf-8">
{literal}
<style type="text/css">
@media screen {
	input,.noprint {
		display: inline;
		height: auto;
	}
	.printable{display: none;}
}

@media print {
	input,.noprint {
		display: none;
	}
	.printable{
		display: inline;
	}
}
</style>
{/literal}
</HEAD>
<BODY bgColor=#ffffff>
<form action="" class="noprint">
<input id="print_button" type="button" value="Печать" alt="Печать" title="Печать" onclick="window.print();return false;"/><br />
<input id="close_button" type="button" value="Закрыть" alt="Закрыть" title="Закрыть" onclick="window.close();return false;"/>

</form><DIV align=center><BR>
<TABLE cellSpacing=0 cellPadding=4 width=600 border=1>
	<TBODY>
		<TR>
			<TD vAlign=bottom width="25%">
			<P align=right>Извещение</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>Кассир</P>
			</TD>
			<TD width="75%">
			<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
				<TBODY>
					<TR>
						<TD colSpan=3><STRONG>Получатель платежа</STRONG></TD>
					</TR>
					<TR>
						<TD colSpan=3>Наименование:&nbsp;{$compname}</TD>
					</TR>
					<TR>
						<TD>Счет:&nbsp;{$schet}</TD>
					</TR>
					<TR>
						<TD>Адрес:&nbsp;{$inn}, &nbsp;{$kpp}</TD>
					</TR>
					<TR>
						<TD colSpan=3>Банк:&nbsp;{$bankname}</TD>
					</TR>
					<TR>
						<TD>SWIFT:&nbsp;{$korschet}</TD>
					</TR>
					<TR>
						<TD>Национальный код:&nbsp;{$bik}</TD>
					</TR>
				</TBODY>
			</TABLE>
			<BR>
			<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
				<TBODY>
					<TR>
						<TD><STRONG>Плательщик</STRONG></TD>
					</TR>
					<TR>
						<TD class="inline_edit">{$firstname}&nbsp;{$lastname}</TD>
					</TR>
					<TR>
						<TD class="inline_edit"><STRONG>Адрес доставки:</STRONG> {$city}, {$addr}</TD>
					</TR>
					<TR><TD>&nbsp;</TD></TR>					
					<TR>
						<TD class="inline_edit"><STRONG>Комиссию банка оплачивает:</STRONG> Плательщик</TD>
					</TR>					
				</TBODY>
			</TABLE>
			<BR>
			<TABLE cellSpacing=0 cellPadding=2 width="100%" border=1>
				<TBODY>
				<TR>
					<TD>
					<DIV align=left style="width:25%"><STRONG>Назначение платежа</STRONG></DIV>
					</TD>
					<TD>
					<DIV align=center style="width:25%"><STRONG>Валюта</STRONG></DIV>
					</TD>
					<TD>
					<DIV align=center style="width:25%"><STRONG>Сумма</STRONG></DIV>
					</TD>
				</TR>
				<TR>
					<TD>
					<DIV align=left class="inline_edit">Заказ №{$id_order} в интернет-магазине MOTOKOFR</DIV>
					</TD>
					<TD>
					<DIV align=center>978</DIV>
					</TD>
					<TD>
					<DIV align=center class="inline_edit">{$total_to_pay}</DIV>
					</TD>
				</TR>
				</TBODY>
			</TABLE>
			<P>Подпись плательщика:</P>
			</TD>
		</TR>
		
		<TR>
			<TD vAlign=bottom>
			<P align=right>Квитанция</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>&nbsp;</P>
			<P align=right>Кассир</P>
			</TD>
			<TD>
			<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
				<TBODY>
					<TR>
						<TD colSpan=3><STRONG>Получатель платежа</STRONG></TD>
					</TR>
					<TR>
						<TD colSpan=3>Наименование:&nbsp;{$compname}</TD>
					</TR>
					<TR>
						<TD>Счет:&nbsp;{$schet}</TD>
					</TR>
					<TR>
						<TD>Адрес:&nbsp;{$inn}, &nbsp;{$kpp}</TD>
					</TR>
					<TR>
						<TD colSpan=3>Банк:&nbsp;{$bankname}</TD>
					</TR>
					<TR>
						<TD>SWIFT:&nbsp;{$korschet}</TD>
					</TR>
					<TR>
						<TD>Национальный код:&nbsp;{$bik}</TD>
					</TR>
				</TBODY>
			</TABLE>
			<BR>
			<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
				<TBODY>
					<TR>
						<TD><STRONG>Плательщик</STRONG></TD>
					</TR>
					<TR>
						<TD class="inline_edit">{$firstname}&nbsp;{$lastname}</TD>
					</TR>
					<TR>
						<TD class="inline_edit"><STRONG>Адрес доставки:</STRONG> {$city}, {$addr}</TD>
					</TR>
					<TR><TD>&nbsp;</TD></TR>
					<TR>
						<TD class="inline_edit"><STRONG>Комиссию банка оплачивает:</STRONG> Плательщик</TD>
					</TR>					
				</TBODY>
			</TABLE>
			<BR>
			<TABLE cellSpacing=0 cellPadding=2 width="100%" border=1>
				<TBODY>
				<TR>
					<TD>
					<DIV align=left style="width:25%"><STRONG>Назначение платежа</STRONG></DIV>
					</TD>
					<TD>
					<DIV align=center style="width:25%"><STRONG>Валюта</STRONG></DIV>
					</TD>
					<TD>
					<DIV align=center style="width:25%"><STRONG>Сумма</STRONG></DIV>
					</TD>
				</TR>
				<TR>
					<TD>
					<DIV align=left class="inline_edit">Заказ №{$id_order} в интернет-магазине MOTOKOFR</DIV>
					</TD>
					<TD>
					<DIV align=center>978</DIV>
					</TD>
					<TD>
					<DIV align=center class="inline_edit">{$total_to_pay}</DIV>
					</TD>
				</TR>
				</TBODY>
			</TABLE>
			<P>Подпись плательщика:</P>
			</TD>
		</TR>
	</TBODY>
</TABLE>
</DIV>
</BODY>
</HTML>
