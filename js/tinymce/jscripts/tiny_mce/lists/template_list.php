<?

// This list may be created by a server logic page PHP/ASP/ASPX/JSP in some backend system.
// There templates will be displayed as a dropdown in all media dialog if the "template_external_list_url"
// option is defined in TinyMCE init.

$path		=	"../templates/"; 
$diretorio	=	dir($path); 

echo '
	var tinyMCETemplateList = 
	[
		
	// Name, URL, Description
'; 

$virgula	= ' ';
while ($arquivo = $diretorio->read()) 
{ 
	if (is_file( $path.$arquivo ) )
	{
		$name	= explode(".",$arquivo);
		
		echo '
		'.$virgula.'["'.$name[0].'", "../js/tinymce/jscripts/tiny_mce/templates/'.
		$arquivo.'", "'.$name[0].'"]
		';
		$virgula	= ',';
	}
	
	
} 
$diretorio->close(); 

echo '
	];';

?>