function gettrackXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}




// javascript-код 
function gdeposylka_request(tracks, country, order_id) {
//
	// (1) создать объект для запроса к серверу
	var req = gettrackXmlHttp()  
       
	// span рядом с кнопкой
	// в нем будем отображать ход выполнения
	var statusElem = document.getElementById('gdeposylka') 
	
	req.onreadystatechange = function() {  
        // onreadystatechange активируется при получении ответа сервера
		if (req.readyState == 4) { 

            // если запрос закончил выполняться
			statusElem.innerHTML = req.statusText // показать статус (Not Found, ОК..)

			if(req.status == 200) { 

                 // если статус 200 (ОК) - выдать ответ пользователю
				statusElem.innerHTML = req.responseText;
			}
			// тут можно добавить else с обработкой ошибок запроса
		}

	}

       // (3) задать адрес подключения
	req.open('GET', '/adminka/gettrack.php?track_id='+tracks+'&country_id='+country+'&order_id='+order_id, true);  

	// объект запроса подготовлен: указан адрес и создана функция onreadystatechange
	// для обработки ответа сервера
	 
        // (4)
	req.send(null);  // отослать запрос
  
        // (5)
//	statusElem.innerHTML = '' 
}
