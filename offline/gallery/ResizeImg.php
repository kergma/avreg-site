<?php 
require ('/etc/avreg/site-defaults.php');
//$id = "/var/www/avreg/media/cam_02/2010-05/01/01_21_36.jpg";
//	http://localhost.sandbox.moonion.com/avreg/offline/gallery/ResizeImg.php?url=media/cam_02/2010-05/01/01_21_36.jpg&size=1

$id = $conf['storage-dir'].$conf['media-alias'].'/'.$_GET['url'];
do {
    $id = preg_replace('#\w+/\.\./#', '', $id, 1, $c);
} while($c);

if (!file_exists($id))
{
		$id = $conf['storage-dir'].$conf['media-alias'].'/'.'../offline/gallery/img/error.jpg';
	do {
    	$id = preg_replace('#\w+/\.\./#', '', $id, 1, $c);
	} while($c);
}

$im = imagecreatefromjpeg($id);
$im_width=imageSX($im);
$im_height=imageSY($im);

/*
$sizes = array(
	0 => array('h' => 164, 'w' =>188),
	1 => array('h' => 176, 'w' =>200),
	2 => array('h' => 188, 'w' =>212),
	3 => array('h' => 200, 'w' =>224),
);
*/

$saveProp = $_GET['prop'];
$proportion = $im_width/$im_height;

//Кол-во позиций ползунка слайдера
$positionNum = 21;


$h=150; 
$w=188;
$delta=12;

if($_GET['mode']=='normal')
{
	$h=400;
	$w=400;
	$delta=30;
}

for ($i = 0; $i< $positionNum ; $i++, $h+=$delta, $w+=$delta)
{
	if($saveProp=='true')
	{
		if($proportion >=1 )
		{
			$h = (int) $w/$proportion;
		}
		else
		{
			$w = (int)$h*$proportion;
		}
	}
	$sizes[$i]= array('h'=>$h, 'w'=>$w);
}



$sz = $sizes[(int)$_GET['size']];


$new_width = $sz['w'];
$new_height = $sz['h'];




// resize
$new_im=imagecreatetruecolor($new_width,$new_height);
imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
ImageCopyResized($new_im,$im,0,0,0,0,$new_width,$new_height,$im_width,$im_height);



//output
header("Content-type: image/jpeg");
Imagejpeg($new_im,'',80); // quality 80
ImageDestroy($im);
ImageDestroy($new_im);



?>