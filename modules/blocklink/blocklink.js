var gpSiteTitle = (typeof gp_wdg_title != "undefined") ? gp_wdg_title : "Путь посылки:";
var gpImageUrl = (typeof gp_wdg_iurl != "undefined") ? gp_wdg_iurl : "http://cdn.gdeposylka.ru/assets/images/widgets";
var gpWidgetUrl = (typeof gp_wdg_url != "undefined") ? gp_wdg_url : "http://gdeposylka.ru/widgets/v2";
var gpOffsetWidth = (typeof gp_wdg_ow != "undefined") ? gp_wdg_ow : 350;
var gpOffsetHeight = (typeof gp_wdg_oh != "undefined") ? gp_wdg_oh : 200;
var gpNoDomWait = (typeof gp_wdg_nodomwait != "undefined") ? gp_wdg_nodomwait : true;
var gpWidget = {
    GetWindowWidth: function() {
        var b = 0;
        if (typeof(window.innerWidth) == "number") {
            b = window.innerWidth
        } else {
            if (document.documentElement && document.documentElement.clientWidth) {
                b = document.documentElement.clientWidth
            } else {
                if (document.body && document.body.clientWidth) {
                    b = document.body.clientWidth
                }
            }
        }
        return b
    },
    GetWindowHeight: function() {
        var b = 0;
        if (typeof(window.innerHeight) == "number") {
            b = window.innerHeight
        } else {
            if (document.documentElement && document.documentElement.clientHeight) {
                b = document.documentElement.clientHeight
            } else {
                if (document.body && document.body.clientHeight) {
                    b = document.body.clientHeight
                }
            }
        }
        return b
    },
    GetWindowScroll: function() {
        var b = 0;
        if (self.pageYOffset) {
            b = self.pageYOffset
        } else {
            if (document.documentElement && document.documentElement.scrollTop) {
                b = document.documentElement.scrollTop
            } else {
                if (document.body) {
                    b = document.body.scrollTop
                }
            }
        }
        return b
    },
    ShowWidget: function(e) {
        var f = "";
        if (typeof(e) == "object") {
            var c = e.getAttribute("href");
            if (c.lastIndexOf("/") > -1) {
                f = c.substr(c.lastIndexOf("/") + 1)
            }
        }
        document.getElementById("gpWidgetBody").innerHTML = '<iframe 	scrolling="auto" height="300" frameborder="0" align="left"style="width:100%;border: 0pt none; position: relative;"src="' + gpWidgetUrl + "/" + f + '">Frame error</iframe>';
        var b = gpWidget.GetWindowWidth() / 2 - gpOffsetWidth;
//        var d = gpWidget.GetWindowHeight() / 2 - gpOffsetHeight + gpWidget.GetWindowScroll();
        var d = gpWidget.GetWindowHeight() / 2 - gpOffsetHeight;        
        document.getElementById("gpWidget").style.top = d + "px";
        document.getElementById("gpWidget").style.left = b + "px";
		$('#gpWidget').fadeIn(200);
        document.getElementById("gpWidget").style.display = "block"
//        document.getElementById("overlay").style.display = "block"
		document.getElementById("overlay").style.opacity = 0.4
		$('body').css("overflow", "hidden")
        $('div#page').addClass("blur")		
		$('#overlay').fadeIn(200);		


    },
    HideWidget: function() {
        document.getElementById("gpWidget").style.display = "none"
//        document.getElementById("overlay").style.display = "none"
		$('#overlay').fadeOut(200);         
		$('body').css("overflow", "scroll");
        $('div#page').removeClass("blur")		
        document.getElementById("overlay").style.opacity = 0.6		
		

    },
    WriteCSS: function() {
        var b = '#gpWidget {z-in1dex:1001;font-size:11px; color:#3F4543; }.gpWidgetB {background:url(' + gpImageUrl + "/b.png);}.gpWidgetTL {background:url(" + gpImageUrl + "/tl.png);}.gpWidgetTR {background:url(" + gpImageUrl + "/tr.png);}.gpWidgetBL {background:url(" + gpImageUrl + "/bl.png);}.gpWidgetBR {background:url(" + gpImageUrl + "/br.png);}.gpWidgetTL, .gpWidgetTR, .gpWidgetBL, .gpWidgetBR {height: 10px;width: 10px;overflow: auto; padding: 0;}#gpWidget td {border-bottom: 0; padding: 0;}#gpWidgetContainer {line-height:0px;width:670px;padding: 10px;background: #fff;}#gpWidgetCloseButton {margin-top:2px;margin-right:2px; float:right;display:block; width:16px; height:16px; background: transparent url(" + gpImageUrl + "/cancel.gif) 100% 0px no-repeat; z-index:101;}#gpWidgetCloseButton:hover {background-position: 100% 100%; cursor:pointer;}#gpWidgetLogo {float:right; margin:0;}#gpWidgetTitle {margin-top:5px;}#gpWidgetHeader {line-height:1em;font-size:12px;font-weight:bold;z-index:2000;padding:2px 0px 0 20px;height:23px;background-color:#EEEEEE;}";
        document.write('<style type="text/css">' + b + "</style>")
    },

    WriteHTML: function() {
        var b = '<a onclick="gpWidget.HideWidget();"><div id="overlay"></div></a><div id="gpWidget" style="position:fixed; display: none; top: 0px; left: 0px;"><div style="position: relative;"><table style="border-collapse: collapse;"><tbody><tr>	<td class="gpWidgetTL"></td>	<td class="gpWidgetB"></td>	<td class="gpWidgetTR"></td></tr><tr>	<td class="gpWidgetB"></td>	<td id="gpWidgetContainer">		<div id="gpWidgetHeader">			<a onclick="gpWidget.HideWidget();" title="" id="gpWidgetCloseButton"></a>			<div id="gpWidgetLogo">				<a target="_blank" href="http://gdeposylka.ru">				<img height="23" border="0" width="118" alt="" src="' + gpImageUrl + '/widget_logo.png"></a>			</div>			<div id="gpWidgetTitle">' + gpSiteTitle + '</div>			<div style="clear:both;"></div>		</div>		<div id="gpWidgetBody">		</div>	<div style="clear:both;"></div>	</td>	<td class="gpWidgetB"></td></tr><tr>	<td class="gpWidgetBL"></td>	<td class="gpWidgetB"></td>	<td class="gpWidgetBR"></td></tr></tbody></table></div></div>';
        document.write(b)
    },
    Init: function() {
        gpWidget.WriteCSS();
        gpWidget.WriteHTML()
    }
};

function gp_ClickHandler() {
    gpWidget.ShowWidget(this);
    return false
}

function gp_LoadHandler() {
    var f = document.getElementsByTagName("body")[0];
    var e = new RegExp("gdeposylka");
    var b = f.getElementsByTagName("a");
    for (var d = 0, c = b.length; d < c; d++) {
        if (e.test(b[d].getAttribute("rel"))) {
            b[d].onclick = gp_ClickHandler
        }
    }
}
if (typeof(gp_Init) == "undefined") {
    gp_Init = true;
    gpWidget.Init();
    if (gpNoDomWait) {
        gp_LoadHandler()
    } else {
        if (typeof window.addEventListener != "undefined") {
            window.addEventListener("load", gp_LoadHandler, false)
        } else {
            if (typeof document.addEventListener != "undefined") {
                document.addEventListener("load", gp_LoadHandler, false)
            } else {
                if (typeof window.attachEvent != "undefined") {
                    window.attachEvent("onload", gp_LoadHandler)
                } else {
                    if (typeof window.onload == "function") {
                        var a = onload;
                        window.onload = function() {
                            a();
                            gp_LoadHandler()
                        }
                    } else {
                        window.onload = gp_LoadHandler
                    }
                }
            }
        }
    }
};
