<?php
header("Cache-Control: max-age=7776000, must-revalidate");

//error_reporting(0);
//ini_set('display_errors','On');

function getRequestHeaders() 
{ 
    if (function_exists("apache_request_headers")) 
    { 
        if($headers = apache_request_headers()) 
        { 
            return $headers; 
        } 
    } 

    $headers = array(); 
    // Grab the IF_MODIFIED_SINCE header 
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) 
    { 
        $headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE']; 
    } 
    return $headers; 
}

$folder = '.';
$img = null;

if (substr($folder,-1) != '/') {
	$folder = $folder.'/';
}


$month = idate(m);
//$month = 3;
 switch ($month) {
    case 1: case 2: case 12:
		$img = 'winter';
        break;
    case 3:  case 4: case 5:
		$img = 'spring';
        break;
    case 6: case 7: case 8:
		$img = 'summer';
        break;
    case 9: case 10: case 11:
		$img = 'autumn';
        break;      
        }

	$img = $folder.$img.'.jpg'; // расширение
	$imageInfo = pathinfo($img);


//echo 'http://motokofr.com/themes/Earth/img/back/'.$img;die;	

	
	//    header('Content-transfer-encoding: binary'); 
	//  header('Content-length: '.filesize($img)); 	
	
	$fileModTime = filemtime($img); 
    // Getting headers sent by the client. 
    $headers = getRequestHeaders(); 
	header ("Content-type: image/jpeg"); // заголовок

    // Checking if the client is validating his cache and if it is current. 
 
    if (isset($headers['If-Modified-Since']) && 
        (strtotime($headers['If-Modified-Since']) == $fileModTime)) 
    { 
        // Client's cache IS current, so we just respond '304 Not Modified'. 
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 304); 
    } 
    else 
    { 
        // Image not cached or cache outdated, we respond '200 OK' and output the image. 
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 200); 
        readfile($img);

    } 

	//       readfile($img);

//echo $month;  
 
     
	

?>