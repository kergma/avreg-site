<?php 
require ('/etc/avreg/site-defaults.php');
$id = $_GET['url'];
/*
$id = $conf['storage-dir'].$conf['media-alias'].'/'.$_GET['url'];
do {
    $id = preg_replace('#\w+/\.\./#', '', $id, 1, $c);
} while($c);
*/
//$im = imagecreatefromjpeg($id);
//if(!$im)
//если файл не существует - заставка ошибки 
//if (!file_exists($id))
//if(!$im)
/*
 if(!is_success(head($id))  
{
	
	$saveProp = $_GET['prop'];
	
	$proportion = 1;
	
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

	
//	$new_im=imagecreatetruecolor($new_width, $new_height);
	
	$new_im=imagecreatetruecolor(200, 160);
	
	
	$string = "FILE NOT FOUND";
	
	$orange = imagecolorallocate($new_im, 220, 210, 60);
	
	$px     = (imagesx($new_im) - 7.5 * strlen($string)) /2.5;
	
	
	imagestring($new_im, 60, $px, $new_height/6 , $string, $orange);
	
	//output
	header("Content-type: image/jpeg");
	Imagejpeg($new_im,'',80); // quality 80
	ImageDestroy($new_im);
	
}
else 
*/
{

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
if($im_height>0) $proportion = $im_width/$im_height;

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

}

?>