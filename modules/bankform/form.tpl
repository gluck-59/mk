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
						<TD>ИНН:&nbsp;{$inn} / КПП:&nbsp;{$kpp}</TD>
					</TR>
					<TR>
						<TD colSpan=3>Наименование
						банка:&nbsp;{$bankname}</TD>
					</TR>
					<TR>
						<TD>Кор.&nbsp;счет:&nbsp;{$korschet}&nbsp;БИК:&nbsp;{$bik}</TD>
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
						<TD class="inline_edit">{$city}, {$addr}</TD>
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
					<DIV align=center style="width:25%"><STRONG>Дата</STRONG></DIV>
					</TD>
					<TD>
					<DIV align=center style="width:25%"><STRONG>Сумма</STRONG></DIV>
					</TD>
				</TR>
				<TR>
					<TD>
					<DIV align=left class="inline_edit">п/п №{$id_order}, перевод средств по договору № 5004652375 Якунчев Рубен Сергеевич НДС не облагается</DIV>
					</TD>
					<TD>
					<DIV align=center>&nbsp;</DIV>
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
						<TD>ИНН:&nbsp;{$inn} / КПП:&nbsp;{$kpp}</TD>
					</TR>
					<TR>
						<TD colSpan=3>Наименование
						банка:&nbsp;{$bankname}</TD>
					</TR>
					<TR>
						<TD>Кор.&nbsp;счет:&nbsp;{$korschet}&nbsp;БИК:&nbsp;{$bik}</TD>
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
						<TD class="inline_edit">{$city}, {$addr}</TD>
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
						<DIV align=center style="width:25%"><STRONG>Дата</STRONG></DIV>
						</TD>
						<TD>
						<DIV align=center style="width:25%"><STRONG>Сумма</STRONG></DIV>
						</TD>
					</TR>
					<TR>
						<TD>
					<DIV align=left class="inline_edit">п/п №{$id_order}, Перевод средств по договору № 5004652375 Якунчев Рубен Сергеевич НДС не облагается</DIV>
						</TD>
						<TD>&nbsp;</TD>
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
<!-- This document saved from https://demo-ru.webasyst.net/shop/print_form/?form_class=invoicephys&orderID=2757&order_time=MjAxMC0wNi0wNiAwMzoxNTozNg==&customer_email=dGVzdEB0ZXN0LnJ1 -->
