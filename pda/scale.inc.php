<?php

//сортирует массив размеров
function get_resolutions($strResolutions, $orderByWidth=true){
	$sizes = array();
	$sizes = explode(',', $strResolutions);
	
	$resol = array();

	foreach ($sizes as $key=>$val){
		$tmp = array();
		$tmp = explode('x', $val);
		array_push($resol, array('w'=>trim($tmp[0]), 'h'=>trim($tmp[1]) ));
	}
	
	if($orderByWidth) usort($resol, 'sort_by_width');
	else usort($resol, 'sort_by_height');

 	return  $resol;
}

//предикаты сортировки
function sort_by_width($f, $s){
	return $f['w']-$s['w'];
}

function sort_by_height($f, $s){
	return $f['h']-$s['h'];
}


//Отображать контролы управления масштабом
if($show_scale_cntrl){
?>

<!-- добавляем контролы масштаба  -->

<!-- <input type="button" id="btn_scale" value="<?php print $strScale['scale']; ?>" />
 -->

<div id="scale"> <?php print "{$strScale['scale']}: "; ?>
	<input type="button" value="<?php print $strScale['zoom_out'];?>" id="zoomout"  />
	<input type="button" value="<?php print $strScale['zoom_in'];?>" id="zoomin" />
<!-- Выбор типа сортировки
	<br />
	<?php print $strScale['sorting']; ?>
	<br />
	<input type="radio" name="sort_by" value="width" checked /> <?php print $strScale['by_width'];  ?>
	<br />
	<input type="radio" name="sort_by" value="heigth" /> <?php print $strScale['by_height']; ?>
-->
</div>
<br />
<?php 
}
?>



<style>
<!-- -->
#scale{
	position:relative;
}
</style>


<script type="text/javascript" >
var scale=0;
	$(function(){
		var expr = new RegExp('scl=\\d+', 'ig');
		var isscl = expr.test(SELF_ADR);

/*		
		//порядок сортировки массива разрешений
		var sort_by = cookie.getCookie('sort_by');
		if(sort_by==null){
			sort_by = $('[name=sort_by]:checked').attr('value');
			cookie.setCookie('sort_by', sort_by, 7);
		}else{
			$('[value='+sort_by+']:radio').attr('checked', 'checked');
		}
*/		
		//определяем текущий масштаб			
		if(isscl){
			scale = SELF_ADR.match(expr)[0];
			scale = parseInt(scale.replace('scl=', ''));
		}
		

		if(scale>=TOTAL_SCLS-1){
			 $('#zoomin').attr({'disabled':'disabled'});
		}
		if(scale<=0){
			$('#zoomout').attr({'disabled':'disabled'});
		}

/*		
		if(cookie.getCookie('scale_panel')=='on'){
			$("#scale").addClass('switch_on');
		}else{
			$("#scale").addClass('switch_off');
		}
*/

/*		
		//изменение порядка сортировки массива разрешений
		$('[name=sort_by]').click(function(e){
				sort_by = $(e.currentTarget).attr('value');
				cookie.setCookie('sort_by', sort_by, 7);
				window.open(SELF_ADR,'_self');
		});
*/
/*		
		show_scale_panel();
		//нажатие на кнопку Масштаб 
		$("#btn_scale")
		.click(function(e){
			$("#scale").toggleClass('switch_on');
			show_scale_panel();
		});
*/
		//нажатие кнопоки изменения масштаба 
		$('#scale>input[type=button]').click(function(e){
			var act = SELF_ADR ; 
			var toReload = true;
			//устанавливаем новое значение масштаба 
			if( $(e.target).attr('id')=='zoomin' ){
				scale++;		
				if(scale>= TOTAL_SCLS){
					 scale = TOTAL_SCLS-1;
					 toReload = false;
				}
			}
			else{
				scale--;
				if(scale<0){
					scale=0;
					toReload = false;
				}
			}
			if(isscl){
				//если значение масштаба уже установлено 
				act= act.replace(expr, "scl=" + scale );
			}
			else{
				act +=  "&scl=" + scale;
			}

			cookie.setCookie('scl', scale, 7);
			
			if(toReload)window.open(act,'_self');
		});
	});

/*
	//отобразить/скрыть панель масштаба
	var show_scale_panel = function(){
		if($("#scale").hasClass('switch_on')){
			$('#scale').show();
			cookie.setCookie('scale_panel', 'on', 1);
		}
		else{
			$('#scale').hide();
			cookie.setCookie('scale_panel', 'off', 1);
		}
	};
*/
	
	var cookie = {
			getCookie : function(c_name)
			{
				var i,x,y,ARRcookies=document.cookie.split(";");
				for (i=0;i<ARRcookies.length;i++)
				  {
				  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
				  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
				  x=x.replace(/^\s+|\s+$/g,"");
				  if (x==c_name)
				    {
				    return unescape(y);
				    };
				  };
			},

			setCookie : function(c_name,value,exdays)
			{
				var exdate=new Date();
				exdate.setDate(exdate.getDate() + exdays);
				var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
				document.cookie=c_name + "=" + c_value;
			},

	};
	

</script>
