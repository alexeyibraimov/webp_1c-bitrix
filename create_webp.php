<?
ob_start();
function CreateWebP($file){
	$file = current(explode("?", $file));
	$lname = explode(".", $file);
	$ftype = end($lname);
	unset($lname[count($lname)-1]);
	$lnameWebP = implode(".",$lname).'.webp';
	if (file_exists($file)){
		if($ftype=="jpg" || $ftype=="jpeg") {
			$image = imagecreatefromjpeg($file);
			imagejpeg($image,NULL,100);
			$cont=  ob_get_contents();
			ob_end_clean();
			imagedestroy($image);
			$content =  imagecreatefromstring($cont);
			imagewebp($content,$lnameWebP);
			imagedestroy($content);
		}elseif($ftype=="png"){
			$image = imagecreatefrompng($file); // или imagecreatefromjpeg
			imagepalettetotruecolor($image);
			imagealphablending($image, false);
			imagesavealpha($image, true);
			imagewebp($image,$lnameWebP);
		}
		header("Content-Type: image/webp");
		header("Expires: ".gmdate("D, d M Y H:i:s")."GMT");  
		header("Cache-Control: no-cache, must-revalidate");  
		header("Pragma: no-cache");  
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT"); 
		return  file_get_contents($lnameWebP);
	}else{
		header("HTTP/1.0 404 Not Found");
		die();
	}
}
echo (isset($_REQUEST["f"])) ? CreateWebP($_SERVER["DOCUMENT_ROOT"].urldecode($_REQUEST["f"])) : CreateWebP($_SERVER["DOCUMENT_ROOT"].urldecode($_SERVER["REQUEST_URI"]));
?>