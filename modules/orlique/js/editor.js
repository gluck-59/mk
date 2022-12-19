var currencyData = {
    currencyFormat: currencyFormat,
    currencySign:   currencySign,
    currencyBlank:  currencyBlank
}

function orliqueL(translationString)
{
    if (typeof(translations) == 'object' && translationString in translations)
        return translations[translationString];
    
    return translationString;
}

function createElement(elementTag, elementType, elementClass, elementId, elementName, elementValue)
{
    var newElement = $(document.createElement(elementTag));
    
    if (elementType !== false)
        newElement.attr('type', elementType);
        
    if (elementClass !== false)
        newElement.addClass(elementClass);
        
    if (elementId !== false)
        newElement.attr('id', elementId);
        
    if (elementName !== false)
        newElement.attr('name', elementName);
        
    if (elementValue !== false)
    {
        if (elementTag == 'input')
            newElement.val(elementValue);
        else
            newElement.html(elementValue)
    }
    
    return newElement
}

function newDomElement(options)
{
    if (typeof(options.elementTag) == 'undefined')
        return false;
    
    var newElement = $(document.createElement(options.elementTag));
    
    if (typeof (options.attrs) != 'undefined')
    {
        for (var attributeName in options.attrs)
        {
            var attributeValue = options.attrs[attributeName];
            
            newElement.attr(attributeName, attributeValue);
        }
    }
    
    if (typeof (options.elementValue) != 'undefined')
    {
        if (options.elementTag == 'input')
            newElement.val(options.elementValue);
        else
            newElement.html(options.elementValue);
    }
    
    return newElement
}

function slideUpAndDestroy(jqObj)
{
    if (typeof (jqObj) != 'undefined')
    {
        jqObj.fadeOut('fast');
        jqObj.slideUp('fast', function(){
            $(this).remove()
        })
    }
}

function createMessage(msgClass, message, icon)
{
    if (icon == 'undefined')
        return newDomElement({
            elementTag: 'div',
            attrs: {
                'class': msgClass
            },
            elementValue: message
        });
    else
    {
        var messageBlock = newDomElement({
            elementTag: 'div',
            attrs: {
                'class': msgClass
            },
            elementValue: ' ' + message
        });
        
        messageBlock = $(messageBlock).prepend($(document.createElement('img')).attr('src', '../img/admin/' + icon));
        
        return messageBlock
    }
}

function createConfirmation(message, icon, prependTo, fadeAfter)
{
    prependTo = prependTo || 'div#orderEditorContainer';
    
    var messageBlock = (createMessage('conf confirm', message, icon)).prependTo($(prependTo));
    
    if (fadeAfter)
        setTimeout(function (){
            slideUpAndDestroy(messageBlock)
        }, parseInt(fadeAfter))
}

function createError(message, icon, prependTo)
{
    prependTo = prependTo || 'div#orderEditorContainer';
    
    $(prependTo).prepend(createMessage('alert error', message, icon))
}

function createErrors(errors, appendSelector)
{
    for (var i in errors)
    {
        var error = errors[i];
        
        createError(error, 'warning.gif', appendSelector)
    }
}

function createMarginForm(labelTxt, description, formContent)
{
    var wrapper = newDomElement({elementTag: 'div'});
    
    var label = newDomElement({
        elementTag: 'label',
        elementValue: labelTxt
    });
    
    var innerContent = newDomElement({
        elementTag: 'div',
        attrs: {'class': 'margin-form'}
    });
    
    innerContent.append(formContent);
    
    var marginForm = label.add(innerContent);
    
    return marginForm;
}

function sendRequest(params, callback, format)
{
    format = format || 'html';
    
    if (typeof (params) == 'object')
    {
        params.id_lang  = id_lang;
        params.iem      = iem;
        params.iemp     = iemp
    }
    
    $.post(ajaxPath, params, function (data) {
        callback.apply(data, arguments)
    }, format)
}

function requestProductInfo(event, data, formatted)
{
    var productId   = parseInt(data[1]),
        productName = data[0],
        container   = $('div#productToAdd');
        
    container.slideUp('fast');
    
    var requestInfo = {
        getfull: productId
    };
    
    requestInfo.customer = getValCast('#addressinvoice input.id_customer', parseInt);

    sendRequest(requestInfo, function (data){
        if (data.length > 0) {
            $('#product_autocomplete_input').val('');
            container.html(data).slideDown('fast')
        }
    })
}

function requestCustomerInfo(event, data, formatted)
{
    var customerId = parseInt(data[1]),
        container = $('div#customer');
        
    container.slideUp('fast');
    
    sendRequest({
        getcustomer: customerId
    }, function (data) {
        if (data.length > 0)
        {
            container.html(data);
            $('div#customer div.addressItem:odd').css('margin-right', 0);
            
            container.slideDown('fast')
        }
    })
}

function reformatPrices()
{
    $('span.customValue, span.publicView').each(function(){
        var parent = $(this).parent('.editable');
        
        if (parent.length > 0 && parent.attr('class').split(' ').length == 1 && $(this).html() != '')
        {
            var realValue = parent.find('span.realValue > input', 'span.realValue > textarea').val();
            
            formatPrice($(this), realValue);
        }
    });
}

function formatPrice(obj, price)
{
    if (isNaN(price))
        price = 0;
        
    var newPrice = formatCurrency(price, currencyData.currencyFormat, currencyData.currencySign, currencyData.currencyBlank);
    
    obj.html(newPrice)
}

function ps_round(value, precision)
{
    if (typeof (roundMode) == 'undefined')
        roundMode = 2;
        
    if (typeof (precision) == 'undefined')
        precision = 2;
        
    method = roundMode;
    
    if (method == 0)
        return ceilf(value, precision);
    else if (method == 1)
        return floorf(value, precision);
        
    precisionFactor = precision == 0 ? 1 : Math.pow(10, precision);
    
    return Math.round(value * precisionFactor) / precisionFactor
}

function ceilf(value, precision)
{
    if (typeof (precision) == 'undefined')
        precision = 0;
        
    precisionFactor = precision == 0 ? 1 : Math.pow(10, precision);
    
    tmp     = value * precisionFactor;
    tmp2    = tmp.toString();
    
    if (tmp2[tmp2.length - 1] == 0)
        return value;
    
    return Math.ceil(value * precisionFactor) / precisionFactor
}

function floorf(value, precision)
{
    if (typeof (precision) == 'undefined')
        precision = 0;
        
    precisionFactor = precision == 0 ? 1 : Math.pow(10, precision);
    tmp = value * precisionFactor;
    tmp2 = tmp.toString();
    
    if (tmp2[tmp2.length - 1] == 0)
        return value;
    
    return Math.floor(value * precisionFactor) / precisionFactor
}

function formatCurrency(price, currencyFormat, currencySign, currencyBlank)
{
    blank = '';
    price = parseFloat(price).toFixed(6);
    price = ps_round(price, 2);
    
    if (currencyBlank > 0)
        blank = ' ';
        
    if (currencyFormat == 1)
        return currencySign + blank + formatNumber(price, 2, ',', '.');
        
    if (currencyFormat == 2)
        return (formatNumber(price, 2, ' ', ',') + blank + currencySign);
        
    if (currencyFormat == 3)
        return (currencySign + blank + formatNumber(price, 2, '.', ','));
        
    if (currencyFormat == 4)
        return (formatNumber(price, 2, ',', '.') + blank + currencySign);
        
    return price;
}

function formatNumber(value, numberOfDecimal, thousenSeparator, virgule)
{
    value = parseFloat(value).toFixed(numberOfDecimal);
    
    var val_string      = value + '',
        tmp             = val_string.split('.'),
        abs_val_string  = (tmp.length == 2) ? tmp[0] : val_string,
        deci_string     = ('0.' + (tmp.length == 2 ? tmp[1] : 0)).substr(2),
        nb              = abs_val_string.length;
        
    for (var i = 1; i < 4; i++)
        if (value >= Math.pow(10, (3 * i)))
            abs_val_string = abs_val_string.substring(0, nb - (3 * i)) + thousenSeparator + abs_val_string.substring(nb - (3 * i));
            
    if (parseInt(numberOfDecimal) == 0)
        return abs_val_string;
    
    return abs_val_string + virgule + (deci_string > 0 ? deci_string : '00')
}

function recalculateTotal()
{
    var collection      = $('table#orderContents').children('tbody').children('tr'),
        shipping        = parseFloat($('input#total_shipping').val()),
        discounts       = parseFloat($('input#total_discount').val()),
        percent         = parseFloat($('input#discount_percent').val()),
        discountType    = parseInt($('input[name="discountType"]:checked').val()),
        wrapping        = parseFloat($('input#total_wrapping').val()),
        total_products  = 0,
        order_total     = 0;
    
    if (collection.length > 0)
    {
        collection.each(function () {
            var price       = parseFloat($(this).find('input.orderPPrice').val()),
                price_wt    = parseFloat($(this).find('input.orderPPriceWt').val()),
                quantity    = parseInt($(this).find('input.orderPQuantity').val());
                
            formatPrice($(this).find('.productsTotalPrice'), price_wt * quantity);
            total_products += (price_wt * quantity)
        })
    }
    
    order_total = parseFloat(total_products + shipping + wrapping);
    discounts   = discounts ? discounts : (total_products * (percent / 100));
    percent     = percent ? percent : (discounts / (total_products / 100));
    
    order_total -= discounts;
    
    $('#orderTotalProductsF').val(total_products);
    $('#orderShippingPriceF').val(shipping);
    $('#orderTotalPriceF').val(order_total);
    
    formatPrice($('#orderDiscountsValue span.customValue'), discounts);
    $('#orderDiscountsPercent span.customValue').html(parseFloat(percent).toFixed(2)+'%');

    $('input#total_discount').val(parseFloat(discounts).toFixed(2));
    $('input#discount_percent').val(parseFloat(percent).toFixed(2));
    
    formatPrice($('#orderTotalProducts').find('span.customValue'), total_products);
    formatPrice($('#orderTotalPrice').find('span.customValue'), order_total);
    formatPrice($('#orderTotalDiscounts').find('span.customValue'), discounts);
    formatPrice($('#orderWrappingPrice').find('span.customValue'), wrapping);
    formatPrice($('#orderShippingPrice').find('span.customValue'), shipping)
}

function discountEdit()
{
    $('input#discount_percent').val(0);
}

function percentEdit()
{
    $('input#total_discount').val(0);    
}

function getProductDetails(productId)
{
    var product_details = {};
    var collection = $('input[name^="product[' + productId + ']"]');
    
    if (collection.length > 0)
    {
        collection.each(function () {
            var option  = $(this);
            var type    = option.attr('name').match(/product\[\d+\]\[(\w+)\]/);
            
            product_details[type[1]] = option.val()
        })
    }
    
    return product_details
}

function calcPrice(index, p_id, withTax)
{
    var tax         = parseFloat($('input[name="product[' + index + '][' + p_id + '][tax_rate]"]').val()),
        price       = parseFloat($('input[name="product[' + index + '][' + p_id + '][price]"]').val()),
        taxprice    = parseFloat($('input[name="product[' + index + '][' + p_id + '][price_wt]"]').val()),
        priceEmpty  = true;
        
    if ( ! withTax)
    {
        var newprice = price * (1 + tax * 0.01);
        
        priceEmpty = (isNaN(newprice) || newprice < 0);
        
        changeValue(index, p_id, 'price', priceEmpty ? 0 : price, true);
        changeValue(index, p_id, 'price_wt', priceEmpty ? 0 : parseFloat(newprice).toFixed(4), true)
    }
    else if (withTax)
    {
        var newprice = taxprice / (1 + tax * 0.01);
        
        priceEmpty = (isNaN(newprice) == true || newprice < 0);
        
        changeValue(index, p_id, 'price', priceEmpty ? 0 : parseFloat(newprice).toFixed(4), true);
        changeValue(index, p_id, 'price_wt', priceEmpty ? 0 : taxprice, true)
    }
}

function changeValue(index, productId, key, newValue, format)
{
    var obj = $('input[name="product[' + index + '][' + productId + '][' + key + ']"]');
    
    obj.val(newValue);
    
    if (format)
        formatPrice(obj.parents('.editable').find('span.customValue'), newValue)
    else
        obj.parents('.editable').find('span.customValue').html(newValue);
        
    recalculateTotal()
}

function getValCast(selector, cast)
{
    if (cast != null)
        cast = cast || parseFloat;
    else
        cast = false;
    
    var selectorVal = $(selector).val();    
    
    return cast ? cast.apply(null, [selectorVal]) : selectorVal;
}

function getOrderFormRequestStr()
{
    var form        = $('form#orderForm');
    
    var formData     = form.serialize(),
        shipping     = getValCast('input#total_shipping'),
        discounts    = getValCast('input#total_discount'),
        wrapping     = getValCast('input#total_wrapping'),
        orderDateAdd = getValCast('input#order_date_add', null),
        orderLang    = getValCast('select#orderLanguage', parseInt),
        payment      = getValCast('select#paymentModule', parseInt),
        totalP       = getValCast('input#orderTotalProductsF'),
        total        = getValCast('input#orderTotalPriceF'),
        carrier      = getValCast('select#carrierNew', parseInt),
        orderId      = getValCast('input#OrderId', parseInt),
        orderStatus  = getValCast('select#orderStatus', parseInt),
        currency     = getValCast('select#orderCurrency', parseInt);

    var requestStrObj = {
        carrier:        carrier,
        total_shipping: shipping,
        date_add:       orderDateAdd,
        order_lang:     orderLang,
        discounts:      discounts,
        wrapping:       wrapping,
        totalproducts:  totalP,
        total:          total,
        payment:        payment,
        currency:       currency,
        status:         orderStatus
    };
    
    if (isNaN(orderId)) {
        var addressDelivery = getValCast('#addressdelivery input.id_address', parseInt),
            addressInvoice  = getValCast('#addressinvoice input.id_address', parseInt),
            customer        = getValCast('#addressinvoice input.id_customer', parseInt);
            
        if ( ! isNaN(addressDelivery))
            requestStrObj.delivery = addressDelivery;
            
        if ( ! isNaN(addressInvoice))
            requestStrObj.invoice = addressInvoice;
            
        if ( ! isNaN(customer))
            requestStrObj.customer = customer
    }
    
    var requestStr = createPathString(requestStrObj);
    
    formData += requestStr + '&' + $(document).find('input.discountInput').serialize();
    
    return formData;
}

function createOrderMessage(message, messageContainer)
{
    var adminMessage = parseInt(message.id_employee) > 0,
        privateMessage = parseInt(message.private) == 1,
        authorName = adminMessage ? message.efirstname + ' ' + message.elastname : message.cfirstname + ' ' + message.clastname;
                        
    var messageWrapper = newDomElement({
        'elementTag': 'li'
    });
    
    var messageInner = newDomElement({
        'elementTag': 'div',
        'attrs': {
            'class': 'message ' + (adminMessage ? 'admin' : 'customer') + (privateMessage ? ' private' : '')
        }
    });

    var msgHeader = newDomElement({
        'elementTag': 'h4',
        'elementValue': authorName
    });
    
    msgHeader.append(newDomElement({
        'elementTag': 'span',
        'attrs': {
            'class': 'msgDate'
        },
        'elementValue': ' (' + message.date_add + ')'
    }));
    
    if (privateMessage)
    {
        msgHeader.append(newDomElement({
            'elementTag': 'span',
            'attrs': {
                'class': 'msgPrivate'
            },
            'elementValue': ' [' + orliqueL('Private') + ']'
        }));
    }
    
    messageInner.append(msgHeader);
    
    messageInner.append(newDomElement({
        'elementTag': 'p',
        'attrs': {
            'class': 'msgText'
        },
        'elementValue': message.message
    }));
    
    messageInner.append(newDomElement({
        'elementTag': 'input',
        'attrs': {
            'type': 'hidden',
            'class': 'msgId'
        },
        'elementValue': message.id_message
    }));
    
    if (parseInt(message.is_new_for_me) == 1)
    {
        messageInner.append(newDomElement({
            'elementTag': 'span',
            'attrs': {
                'class': 'control markMsgRead',
                'id': 'msg_' + parseInt(message.id_message)
            },
            'elementValue': orliqueL('Mark as read')
        }));
    }
    
    messageWrapper.append(messageInner);
    
    return messageContainer.prepend(messageWrapper);
}

function createMessageSendForm(orderId)
{
    var container = $('div#orderMessages div#messagesWrapper'),
        formWrapper = newDomElement({
            'elementTag': 'form',
            'attrs': {
                'id': 'messageSend',
                'action': ajaxPath,
                'method': 'post'
            }
        });
        
    formWrapper.append(newDomElement({
        'elementTag': 'h2',
        'elementValue': orliqueL('Compose a new message')
    }));
    
    var messageFormContainer = createMarginForm(orliqueL('Message'), null, newDomElement({
        'elementTag': 'textarea',
        'attrs': {
            'id': 'txt_msg',
            'name': 'txt_msg'
        }
    }));
    
    var visibilityFormInputWrapper = newDomElement({elementTag: 'div'});
    
    visibilityFormInputWrapper.append(newDomElement({
        'elementTag': 'input',
        'attrs': {
            'type': 'radio',
            'class': 'decline',
            'name': 'visibility'
        },
        'elementValue': 0
    }));
    
    visibilityFormInputWrapper.append(' ' + orliqueL('No') + '  ');
    
    visibilityFormInputWrapper.append(newDomElement({
        'elementTag': 'input',
        'attrs': {
            'type': 'radio',
            'class': 'accept',
            'name': 'visibility',
            'checked': 'checked'
        },
        'elementValue': 1
    }));
    
    visibilityFormInputWrapper.append(' ' + orliqueL('Yes'));
    
    var visibilityFormContainer = createMarginForm(orliqueL('Private message'), null, visibilityFormInputWrapper);
    
    formWrapper.append(messageFormContainer);
    formWrapper.append(visibilityFormContainer);
    
    formWrapper.append(newDomElement({
        'elementTag': 'input',
        'attrs': {
            'type': 'submit',
            'name': 'sendMessage',
            'id': 'sendMessage',
            'class': 'button'
        },
        'elementValue': orliqueL('Add message')
    }));

    container.append(formWrapper);
    
    $('form#messageSend').submit(function(){
        $('div.conf').slideUp('fast', function () {
            $(this).remove()
        });
        $('div.error').slideUp('fast', function () {
            $(this).remove()
        });
        
        sendRequest(
            {
                addMessage: true,
                'id_order': orderId,
                'id_employee': iem,
                'message': $('textarea#txt_msg').val(),
                'private': $('input[name=visibility]:checked').val()
            },
            function (data) {
                if (data && typeof (data) == 'object' && typeof (data.success) != 'undefined') {
                    createConfirmation(data.success, 'enabled.gif', 'div#orderMessages', 500);
                    renderMessageList(orderId);
                }
                else if (data && typeof(data) == 'object' && typeof (data.errors) == 'object') {
                    createErrors(data.errors, 'div#orderMessages');
                }
            }, 'json');
        
        return false;
    });
}

function populateOrderMessages(orderId, messagesContainer)
{
    sendRequest({
        getMessages: orderId
    }, function (data) {
        if (data && typeof (data) == 'object' && typeof (data.messages) == 'object')
        {
            for (var i in data.messages)
            {
                var message = data.messages[i];
                
                createOrderMessage(message, messagesContainer);
            }
        }
    }, 'json');
}

function renderMessageList(orderId)
{
    var messagesContainer = $('div#orderMessages ul');
    
    if (messagesContainer.length == 0)
    {
        messagesContainer = newDomElement({
            'elementTag': 'div',
            'attrs': {
                'id': 'orderMessages',
                'class': 'full' 
            }
        });
        
        var messagesWrapper = newDomElement({
            'elementTag': 'div',
            'attrs': {
                'id': 'messagesWrapper'
            }
        });
        
        messagesWrapper.append(newDomElement({
            'elementTag': 'ul',
            'attrs': {
                'id': 'messagesContainer'
            }
        }));
        
        messagesContainer.append(messagesWrapper);
        
        populateOrderMessages(orderId, messagesWrapper.find('ul'));

        messagesContainer.hide().appendTo('div.orderMessagesTab').slideDown('fast');
    }
    else
    {
        messagesContainer.slideUp('fast', function(){
            $(this).empty();
            populateOrderMessages(orderId, $(this));
            $(this).slideDown('fast');
        });
    }
}

function updateOrderMessages()
{
    var orderId           = parseInt($('#orderId').val()),
        messagesContainer = $('fieldset#orderMessages ul');
        
    if (isNaN(orderId))
        return ;
        
    renderMessageList(orderId);
    
    createMessageSendForm(orderId);
    
    $('span.showMessages').live('click', function(){
        $(this).toggleClass('msgOpened');
        
        $('div#messagesWrapper').slideToggle('fast');
    });
    
    $('span.markMsgRead').live('click', function(){
        var msgId = $(this).parents('div.message').find('input.msgId').val();
        
        $('div.conf').slideUp('fast', function () {
            $(this).remove()
        });
        $('div.error').slideUp('fast', function () {
            $(this).remove()
        });
        
        if (msgId.length > 0)
        {
            sendRequest(
                {
                    markMessageRead: true,
                    'id_employee': iem,
                    'message_id': msgId
                },
                function (data) {
                    if (data && typeof (data) == 'object' && typeof (data.success) != 'undefined') {
                        createConfirmation(data.success, 'enabled.gif', 'fieldset#orderMessages', 5000);
                        renderMessageList(orderId);
                    }
                    else if (data && typeof(data) == 'object' && typeof (data.errors) == 'object') {
                        createErrors(data.errors, 'fieldset#orderMessages');
                    }
                }, 'json');
        }
    });
}

function updateOrder(redirectOnComplete)
{
    redirectOnComplete = redirectOnComplete || false;
    
    $('div.conf').slideUp('fast', function () {
        $(this).remove()
    });
    
    $('div.error').slideUp('fast', function () {
        $(this).remove()
    });
    
    $('div#invoicePDF, div#deliveryPDF').slideUp('fast', function () {
        $(this).remove()
    });
    
    var formData = getOrderFormRequestStr() + '&order_update=1';
    
    $.post(ajaxPath, formData, function (data) {
        if (data.order)
        {
            $('form#orderForm').append(newDomElement({
                elementTag: 'input',
                attrs: {
                    'type': 'hidden',
                    'id': 'orderId',
                    'name': 'orderId'
                },
                elementValue: data.order
            }));
            
            if (data.details)
            {
                var productRow;
                var renderedProducts = $('table#orderContents').find('tbody').children('tr');
                
                for (var index in data.details)
                {
                    var detail = data.details[index];
                    
                    productRow = $('input.indexProduct[value="' + index + '"]');
                    
                    if (productRow.length)
                    {
                        $('form#orderForm').append(newDomElement({
                            elementTag: 'input',
                            attrs: {
                                'type': 'hidden',
                                'class': 'orderDetailId',
                                'name': 'product[' + index + '][' + detail.product + '][order_detail]'
                            },
                            elementValue: detail.order_detail
                        }));
                    }
                }
                
                $('fieldset#customerSelector').slideUp('fast', function () {
                    $(this).remove()
                })
            }

            $('div#productToAdd').slideUp('fast', function () {
                $(this).empty()
            });
            
            if (data.invoiceLink)
            {
                var invoiceBlock  = $(document.createElement('div')).addClass('formMargin').attr('id', 'invoicePDF');
                
                invoiceBlock.append($(document.createElement('a')).attr('href', data.invoiceLink).append('<img src="../img/admin/tab-invoice.gif" /> ' + orliqueL('Download invoice')))
                
                $('fieldset#orderTotalsWrapper').append(invoiceBlock);
            }
            
            if (data.deliveryLink)
            {
                var deliveryBlock = $(document.createElement('div')).addClass('formMargin').attr('id', 'deliveryPDF');
                
                deliveryBlock.append($(document.createElement('a')).attr('href', data.deliveryLink).append('<img src="../img/admin/delivery.gif" /> ' + orliqueL('Download delivery slip')))
                
                $('fieldset#orderTotalsWrapper').append(deliveryBlock);
            }
            
            if (redirectOnComplete && 'viewLink' in data)
                document.location.href = data.viewLink;
            else
                createConfirmation(data.message, 'enabled.gif', 'div#tabPane1', 5000)
        }
        else if (data.errors)
            createErrors(data.errors, 'div#tabPane1');
            
        var collection = $('span.customValue').not('.orderDetails');
        
        if (collection.length > 0)
        {
            collection.each(function () {
                if ($(this).html().length) {
                    $(this).next('span.publicView').html($(this).html());
                    $(this).html('')
                }
            })
        }
    }, 'json')
}

function renderStatesList(countryInput)
{
    var countryWrapper        = countryInput.parent('div.margin-form'),
        existingStatesWrapper = countryWrapper.next('div.stateWrapper');
    
    if (existingStatesWrapper.length > 0)
        slideUpAndDestroy(existingStatesWrapper);
    
    var info       = countryInput.attr('name').match(/addressNw\[(\d+)\]\[(\w+)\]/),
        identifier = parseInt(info[1]);
    
    sendRequest({
        getStates: parseInt(countryInput.val())
    }, function (data) {
        if (data && typeof (data) == 'object' && typeof (data.states) == 'object')
        {
            var container = newDomElement({
                elementTag: 'div',
                attrs: {
                    'class': 'stateWrapper'
                }
            });
            
            var labelContainer = newDomElement({
                elementTag: 'label',
                attrs: {
                    'for': 'test'
                },
                elementValue: orliqueL('State')
            });
            
            var selectElement = newDomElement({
                elementTag: 'select',
                attrs: {
                    'name': 'addressNw[' + identifier + '][id_state]',
                    'id': 'stateSelect' + identifier
                }
            });
            
            for (var i in data.states)
            {
                var state = data.states[i];
                
                var optionElement = document.createElement('option');
                
                optionElement.text = state.name;
                optionElement.value = state.id_state;

                try {
                  selectElement[0].add(optionElement, null);
                }
                catch(ex) {
                  selectElement[0].add(optionElement);
                }
            }
            
            var statesWrapper = newDomElement({
                elementTag: 'div',
                attrs: {
                    'class': 'margin-form'
                }
            });
            
            container.append(labelContainer);
            statesWrapper.append(selectElement);
            
            container.append(statesWrapper).hide().insertAfter(countryWrapper).slideDown('fast');
        }
    }, 'json');
}

function createPathString(obj)
{
    var path = '';
    
    if (typeof (obj) == 'object')
    {
        obj.id_lang = id_lang;
        obj.iem = iem;
        obj.iemp = iemp;
        
        for (var key in obj)
        {
            var value = obj[key];
            
            if (value != "undefined")
                path += '&' + key + '=' + value
        }
        
        return path
    }
    
    return null;
}

var delay = (function () {
    var timer = 0;
    
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$(document).ready(function (){
    
    $(this).ajaxStart(function(){
        $('div#orderEditorContainer').prepend(newDomElement({
            'elementTag': 'div',
            'attrs': {
                'id': 'preload'
            }
        }).fadeIn('fast'));
    });
    
    $(this).ajaxStop(function(){
        $('div#preload').fadeOut('fast', function(){$(this).remove();})
    });
    
    $.ajaxPrefilter(function(options, originalOptions, jqXHR)
    {
        if ('port' in originalOptions && originalOptions.port == 'autocomplete')
            options.global = false;
    });
    
    $('select#orderCurrency').change(function () {
        sendRequest({
            getCurrency: $(this).val()
        }, function (data) {
            if (data && typeof (data) == 'object') {
                currencyData = data;
                reformatPrices();
                recalculateTotal();
            }
        }, 'json');
    });
    
    $('span.realValue > input').live('blur', function (evt) {
        $(this).parent('span.realValue').fadeOut('fast', function () {
            var parent = $(this).parent('.editable');
            parent.find('span.publicView').fadeIn('fast')
        })
    });
    
    $('span.realValue > input, span.realValue > textarea').live('keyup', function (evt) {
        var thisObj = $(this);
        delay(function () {
            thisObj.val(thisObj.val().replace(/,/g, '.'));
            if (thisObj.hasClass('cpOnKeyUp')) {
                var withTax = false;
                var info = thisObj.attr('name').match(/product\[(\d+)\]\[(\d+)\]\[(\w+)\]/);
                var index = parseInt(info[1]);
                var productId = parseInt(info[2]);
                if (info[3] == 'price_wt') withTax = true;
                calcPrice(index, productId, withTax)
            }
            else {
                if (thisObj.hasClass('priceFormat')) {
                    formatPrice(thisObj.parents('.editable').find('span.customValue'), thisObj.val())
                }
                else if (thisObj.hasClass('weightFormat')) {
                    thisObj.parents('.editable').find('span.customValue').html(thisObj.val() + weightUnit)
                }
                else if (thisObj.hasClass('percentageFormat')) {
                    thisObj.parents('.editable').find('span.customValue').html(thisObj.val() + '%')
                }
                else if (thisObj.hasClass('productName')) thisObj.parents('.editable').find('span.customValue').html(thisObj.val().replace('\n', '<br />\n'));
                else thisObj.parents('.editable').find('span.customValue').html(thisObj.val());
                recalculateTotal()
            }
        }, 1000);
    });
    
    $('span.publicView').live('click', function (evt) {
        $('span.realValue:visible').fadeOut('fast', function () {
            $(this).prev('span.publicView').fadeIn('fast')
        });
        var editable = $(this).parent('.editable');
        var input = editable.find('span.realValue');
        var view = editable.find('span.publicView');
        if (input.length && view.length) view.fadeOut('fast', function () {
            input.fadeIn('fast');
            input.find('input[type="text"]').focus()
        })
    });
    
    $('span.addProduct').live('click', function (evt) {
        var orderId = getValCast('input#orderId', parseInt);
        var attr = $(this).attr('id').split('_');
        var info = $('table#orderContents').children('tbody').find('tr:last').find('td:first').find('input[type="hidden"]');
        var index = 0;
        var orderCurrency = $('select#orderCurrency').val();
        
        if (info.length)
        {
            info = info.attr('name').match(/product\[(\d+)\]\[\d+\]\[\w+\]/);
            index = parseInt(info[1]) + 1
        }
        
        switch (attr[0])
        {
            case 'combo':
                var productArgs = {
                    addProduct: parseInt(attr[1]),
                    index: index,
                    oid: orderId,
                    currency: orderCurrency,
                    id_combination: parseInt(attr[2])
                };
                break;
            case 'product':
                var productArgs = {
                    addProduct: parseInt(attr[1]),
                    index: index,
                    oid: orderId,
                    currency: orderCurrency,
                    id_combination: 0
                };
                break
        }
        
        var addressDelivery = getValCast('#addressdelivery input.id_address', parseInt),
            addressInvoice  = getValCast('#addressinvoice input.id_address', parseInt),
            customer        = getValCast('#addressinvoice input.id_customer', parseInt);
            
        if ( ! isNaN(addressDelivery))
            productArgs.address_delivery = addressDelivery;
            
        if ( ! isNaN(addressInvoice))
            productArgs.address_invoice = addressInvoice;
            
        if ( ! isNaN(customer))
            productArgs.customer = customer;
        
        sendRequest(productArgs, function (data) {
            $('table#orderContents').children('tbody').fadeTo(600, 0, function () {
                $(this).append(data);
                recalculateTotal();
                $(this).fadeTo(600, 1)
            })
        })
    });
    
    $('span.deleteProduct').live('click', function (evt) {
        $(this).parents('tr').fadeOut('fast', function () {
            $(this).remove();
            recalculateTotal();
        })
    });
    
    $('span.addAddress').live('click', function (evt) {
        var address           = $(this).parents('div.addressItem').find('div.address'),
            deliverySelector  = '#addressdelivery',
            invoiceSelector   = '#addressinvoice',
            deliveryContainer = $(deliverySelector).find('div.addressContainer'),
            invoiceContainer  = $(invoiceSelector).find('div.addressContainer'),
            target            = $(this).hasClass('shipping') ? deliveryContainer : invoiceContainer;
            
        $(target).fadeOut('fast', function () {
            $(this).empty();
            $(this).prepend(address.clone());
            $(this).fadeIn('fast');
            
            var deliveryHasAddress = $(deliverySelector).find('div.addressContainer').find('div.address');
            var invoiceHasAddress  = $(invoiceSelector).find('div.addressContainer').find('div.address');
            
            if (deliveryHasAddress.length && invoiceHasAddress.length > 0)
            {
                $('div#customer').slideUp('fast', function () {
                    $(this).empty()
                })
            }
        })
    });
    
    $('input#autoCalculate').click(function () {
        $('div#tabPane1').find('div.conf').slideUp('fast', function () {
            $(this).remove()
        });
        $('div#tabPane1').find('div.error').slideUp('fast', function () {
            $(this).remove()
        });
        
        var formData = getOrderFormRequestStr() + '&calculateShipping=1';
        
        $.post(ajaxPath, formData, function (data) {

            if ('shipping_price' in data)
            {
                var shippingContainer = $('#shippingPriceContainer').find('.editable');
                formatPrice(shippingContainer.find('span.customValue'), data.shipping_price);
                $('input#total_shipping').val(parseFloat(data.shipping_price));
                recalculateTotal();
                createConfirmation(data.message, 'enabled.gif', 'div#tabPane1', 5000)
            }
            else if (data.errors)
                createErrors(data.errors, 'div#tabPane1');
        }, 'json')
    });
    
    $('a#customerCreate').click(function (evt) {
        evt.preventDefault();
        slideUpAndDestroy($('form#newCustomerWrapper'));
        sendRequest({
            customerCreate: true
        }, function (data) {
            $('a#customerCreate').slideUp('fast', function () {
                $(data).insertAfter($(this)).slideDown('fast', function(){
                    var countrySelect = $(this).find('select.countrySelect:first');
                    
                    if (countrySelect.length > 0)
                        renderStatesList(countrySelect);
                    });
            });
        });
        
        return false;
    });
    
    $('select.countrySelect').live('change', function(){
        renderStatesList($(this));
    });

    updateOrderMessages();
    
    $('form#newCustomerWrapper').live('submit', function (evt) {
        evt.preventDefault();
        var data        = {},
            addressData = {},
            groups      = [];
            
        $('form#newCustomerWrapper').find('div.conf').slideUp('fast', function () {
            $(this).remove()
        });
        
        $('form#newCustomerWrapper').find('div.error').slideUp('fast', function () {
            $(this).remove()
        });
        
        $(this).find('input[name^=addressNw], select[name^=addressNw], textarea[name^=addressNw]').each(function () {
            var parsed = $(this).attr('name').match(/addressNw\[(\d+)\]\[(\w+)\]/);
            var tmp = parsed[2];
            
            if (typeof (addressData[parsed[1]]) == 'undefined')
                addressData[parsed[1]] = {};
                
            addressData[parsed[1]][parsed[2]] = $(this).val();
        });
        
        $(this).find('input.groupBox:checked').each(function () {
            groups.push($(this).val());
        });
        
        $(this).find('input.customerNw, select.customerNw').each(function () {
            data[$(this).attr('name')] = $(this).val();
        });
        
        $.post(ajaxPath, {
            id_lang: id_lang,
            iem: iem,
            iemp: iemp,
            customerCreateSave: true,
            customer: data,
            groups: groups,
            addresses: addressData
        }, function (data) {
            if (data.success) {
                createConfirmation(data.message, 'enabled.gif', 'form#newCustomerWrapper', 5000);
                setTimeout(function () {
                    slideUpAndDestroy($('form#newCustomerWrapper'));
                    $('a#customerCreate').slideDown('fast');
                    requestCustomerInfo(false, [0, data.customerID]);
                }, 500)
            }
            else if (data.errors) {
                createErrors(data.errors, 'form#newCustomerWrapper');
            }
        }, 'json');
        return false;
    });
    
    $('a.duplicator').live('click', function (evt) {
        evt.preventDefault();
        var copyRow         = $(this).prev('.duplicatedRow'),
            newRow          = copyRow.clone(),
            inputs          = newRow.find('input, select, textarea'),
            renderStates    = copyRow.hasClass('addressRow'),
            duplicatorLink  = $(this);
            
        if (inputs.length > 0) {
            inputs.each(function () {
                var inputName = $(this).attr('name').match(/(\w+)\[(\d+)\]\[(\w+)\]/);
                if (inputName === null) {
                    inputName = $(this).attr('name').match(/(\w+)\[(\d+)\]/);
                }
                if (typeof (inputName[3]) != 'undefined') {
                    var newName = inputName[1] + '[' + (parseInt(inputName[2]) + 1) + '][' + inputName[3] + ']';
                }
                else {
                    var newName = inputName[1] + '[' + (parseInt(inputName[2]) + 1) + ']';
                }
                $(this).attr('name', newName).val('').removeAttr('selected');
            });
            if (newRow.find('a.removeRow').length == 0) {
                var deleteLink = $(document.createElement('a')).addClass('removeRow').html(orliqueL('Delete this row'));
                
                if (duplicatorLink.hasClass('appendRight'))
                    newRow.append(deleteLink);
                else
                    newRow.prepend(deleteLink);
            }
        }
        
        if (renderStates)
        {
            var countrySelect = newRow.find('select.countrySelect');
            
            if (countrySelect.length > 0)
                renderStatesList(countrySelect);
        }
            
        
        newRow.insertAfter(copyRow).hide().slideDown('fast');
    });
    
    $('a.removeRow').live('click', function (evt) {
        $(this).parents('.duplicatedRow').slideUp('fast', function () {
            $(this).remove();
        });
    });
    
    var redirectAfterUpdate = false;
    
    $('input.orderSaveBtn').click(function(){
        redirectAfterUpdate = $(this).hasClass('saveAndPreview');
        
        $('form#orderForm').trigger('submit');
    });
    
    $('form#orderForm').submit(function () {
        recalculateTotal();
        updateOrder(redirectAfterUpdate);
        
        return false
    });
    
    $('form#orliqueInvoice').submit(function(){
        $('div.conf').slideUp('fast', function () {
            $(this).remove()
        });
        $('div.error').slideUp('fast', function () {
            $(this).remove()
        });
        
        sendRequest({
            orliqueInvoice: true,
            orderId: getValCast('input#orderId', parseInt),
            invoiceData: $('form#orliqueInvoice').serialize()
        }, function (data) {
            if (data && typeof (data) == 'object')
            {
                if (typeof (data.success) != 'undefined') {
                    createConfirmation(data.success, 'enabled.gif', 'fieldset#orliqueInvoiceWrapper', 5000);
                }
                else if (typeof (data.errors) == 'object') {
                    createErrors(data.errors, 'fieldset#orliqueInvoiceWrapper');
                }
            }
        }, 'json');
        
        return false;
    });
});
