<?php

//добавляем контролы масштаба
print '<div id="scale">'."\n";
print "$strScale: ";
print '<input type="button" value="-" id="zoomout"  />'."\n";
print '<input type="button" value="+" id="zoomin" />'."\n";
print "	</div>\n";

//сортирует массив размеров
function get_scales($scales, $orderByWidth=true){
	$sizes = array();
	foreach($scales as $key=>$value ){
		$temp;
		preg_match_all('/\d+/', $value, $temp);
		if(sizeof($temp[0])!=2) continue;
		$sizes[$key]=array('w'=>$temp[0][0], 'h'=>$temp[0][1]);
	}
	if($orderByWidth) usort($sizes, 'sort_by_width');
	else usort($sizes, 'sort_by_height');
	return  $sizes;
}

//предикаты сортировки
function sort_by_width($f, $s){
	return $f['w']-$s['w'];
}
function sort_by_height($f, $s){
	return $f['h']-$s['h'];
}

?>

<script type="text/javascript" >
	$(function(){
		var expr = new RegExp('scl=\\d+', 'ig');
		var scale = 0;
		var isscl = expr.test(SELF_ADR);
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
		//нажатие кнопоку изменения масштаба 
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
			if(toReload)window.open(act,'_self');
		});
	});
	

</script>
