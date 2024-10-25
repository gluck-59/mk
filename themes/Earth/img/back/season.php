<?php
$link = __DIR__.'/current.jpg';
$month = idate('m');

//$month = $month -6; // проверка детекта сезона

 switch ($month) {
    case 1: case 2: case 12:
        $target = 'winter.jpg';
        break;
    case 3:  case 4: case 5:
        $target  = 'spring.jpg';
        break;
    case 6: case 7: case 8:
        $target = 'summer.jpg';
        break;
    case 9: case 10: case 11:
        $target = 'autumn.jpg';
        break;
        }

if (is_file(__DIR__.'/current.jpg')) {
    unlink(__DIR__.'/current.jpg');
}
symlink($target, $link);
?>