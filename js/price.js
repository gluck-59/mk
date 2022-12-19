function getTax()
{
	if (noTax)
		return 0;
	var selectedTax = document.getElementById('id_tax');
	var taxId = selectedTax.options[selectedTax.selectedIndex].value;
	return taxesArray[taxId];
}

function formatPrice(price)
{
	var fixedToSix = (Math.round(price * 1000000) / 1000000);
	return (Math.round(fixedToSix) == fixedToSix + 0.000001 ? fixedToSix + 0.000001 : fixedToSix);
}

function calcPriceTI()
{
	var tax = getTax();
	var priceTE = parseFloat(document.getElementById('priceTE').value);
	var newPrice = priceTE *  ((tax / 100) + 1);
	document.getElementById('priceTI').value = (isNaN(newPrice) == true || newPrice < 0) ? '' : 
												formatPrice(newPrice).toFixed(0); // toFixed(6) оригинал
	document.getElementById('finalPrice').innerHTML = (isNaN(newPrice) == true || newPrice < 0) ? '' : 
												formatPrice(newPrice).toFixed(2);

	var conversion_rate = parseFloat(document.getElementById('conversion_rate').value);
//	var finalPrice = (document.getElementById('finalPrice').innerHTML);
	document.getElementById('finalPriceRur').innerHTML = (isNaN(newPrice) == true || newPrice < 0) ? '' : 
												formatStr(formatPrice(conversion_rate * newPrice).toFixed(0)); 

	calcReduction();
//alert(finalPrice.innerHTML);	
}

function calcPriceTE()
{
	var tax = getTax();
	var priceTI = parseFloat(document.getElementById('priceTI').value);
	var newPrice = priceTI / ((tax / 100) + 1);
	document.getElementById('priceTE').value =	(isNaN(newPrice) == true || newPrice < 0) ? '' :
	 											formatPrice(newPrice).toFixed(0); // toFixed(6) оригинал
	document.getElementById('finalPrice').innerHTML = (isNaN(newPrice) == true || newPrice < 0) ? '' : 
												formatPrice(priceTI).toFixed(2);
//	var conversion_rate = parseFloat(document.getElementById('conversion_rate').value);
//	document.getElementById('finalPriceRur').innerHTML = (isNaN(newPrice) == true || newPrice < 0) ? '' : 
//												formatStr(formatPrice(conversion_rate * finalPrice).toFixed(0)); 

	calcReduction();
}

function calcReduction()
{
	if (parseFloat(document.getElementById('reduction_price').value) > 0)
		reductionPrice();
	else if (parseFloat(document.getElementById('reduction_percent').value) > 0)
		reductionPercent();
}

function reductionPrice()
{
	var price = document.getElementById('priceTI');
	var newprice = document.getElementById('finalPrice');
	var rprice = document.getElementById('reduction_price');
	document.getElementById('reduction_percent').value = 0;
	if (parseFloat(price.value) <= parseFloat(rprice.value))
		rprice.value = price.value;
	if (parseFloat(rprice.value) < 0 || isNaN(parseFloat(price.value)))
		rprice.value = 0;
	newprice.innerHTML = (price.value - rprice.value).toFixed(2);
}

function reductionPercent()
{
	var price = document.getElementById('priceTI');
	var newprice = document.getElementById('finalPrice');
	var rpercent = document.getElementById('reduction_percent');
	document.getElementById('reduction_price').value = 0;
	if (parseFloat(rpercent.value) >= 100)
		rpercent.value = 100;
	if (parseFloat(rpercent.value) < 0)
		rpercent.value = 0;
	newprice.innerHTML = (price.value * (1 - (rpercent.value / 100))).toFixed(2);
	
}

function formatStr(str) {
    str = str.replace(/(\.(.*))/g, '');
    var arr = str.split('');
    var str_temp = '';
    if (str.length > 3) {
        for (var i = arr.length - 1, j = 1; i >= 0; i--, j++) {
            str_temp = arr[i] + str_temp;
            if (j % 3 == 0) {
                str_temp = ' ' + str_temp;
            }
        }
        return str_temp;
    } else {
        return str;
    }
}