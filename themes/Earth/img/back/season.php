<?php
//error_reporting(0);
$month = idate(m);

//$month = $month +1; // проверка детекта сезона

 switch ($month) {
    case 1: case 2: case 12:
		echo $img = 'winter.jpg';
        break;
    case 3:  case 4: case 5:
		echo $img = 'spring.jpg';
        break;
    case 6: case 7: case 8:
		echo $img = 'summer.jpg';
        break;
    case 9: case 10: case 11:
		echo $img = 'autumn.jpg';
        break;      
        }

?>