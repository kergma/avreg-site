<?php 
	require ('/etc/avreg/site-defaults.php');

	//Загрузка исходнгого изображения
	$id = $_GET['url'];
	$im = imagecreatefromjpeg($id);
	//Определение размеров исходного изображения
	$im_width=imageSX($im);
	$im_height=imageSY($im);

	//размеры отображения
	$w = $_GET['w'];
	$h = $_GET['h'];
	
	
	//resulted sizes
	$new_width = $w;
	$new_height = $h;
	
	$saveProp = $_GET['prop'];
	//режим сохранять пропорции?
	if($saveProp=='true')
	{
		$im_proportion = $im_width/$im_height;
		$el_proportion = $w/$h;
		
		if($im_proportion > $el_proportion )
		{
			$new_height = $w/$im_proportion;
		}
		else 
		{
			$new_width = $h*$im_proportion;
		}
	}
	

	
	
/*	
	//Массив заданных размеров	
	$sizes = array(
		0 => array('h' => 0, 'w' =>0),
		1 => array('h' => 120, 'w' =>140),
		2 => array('h' => 140, 'w' =>160),
		3 => array('h' => 164, 'w' =>188),
		4 => array('h' => 176, 'w' =>200),
		5 => array('h' => 188, 'w' =>212),
		6 => array('h' => 200, 'w' =>224),
		7 => array('h' => 300, 'w' =>340),
		8 => array('h' => 400, 'w' =>460),
		9 => array('h' => 500, 'w' =>580),
		10 => array('h' => 600, 'w' =>700),
		11 => array('h' => 700, 'w' =>820),
		12 => array('h' => 800, 'w' =>940),
		13 => array('h' => 900, 'w' =>1080)
	);
	//размеры отображения
	$w = $_GET['w'];
	$h = $_GET['h'];
	$sz = false;

	$saveProp = $_GET['prop'];
	//режим сохранять пропорции?
	if($saveProp==true)
	{
		if($im_height>0) $proportion = $im_width/$im_height;
	}
	else {	
		
		foreach ($sizes as $key => $value)
		{
			if ( $h<$value['h'] || $w<$value['w'])
			{
				$sz = $sizes[$key-1];
				break;
			}
		}
		if ($sz == false) {
			$sz = $sizes[count($sizes)-1];
		}
	}
	//result sizes
	$new_width = $sz['w'];
	$new_height = $sz['h'];
	
*/	

	
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