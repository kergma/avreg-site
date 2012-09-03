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

//Отобразить контролы выбора разрешения
function show_select_resolution($resolutions, $select ,$strName = "Resolutions" ){
	echo "{$strName}: ";
	echo '<select id="resolution" size="1" name="resolution">'."\n";
	foreach ($resolutions as $key=>$val){
		echo "<option ".($key==$select?' selected ':'' )." value='{$key}'>{$val['w']} x {$val['h']} </option>\n";
	}
	echo '</select>'."\n"; 

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

		//Выбор новогого разрешения
		$('#resolution').change(function(e){
			var act = SELF_ADR ;
			var toReload = true;
			scale = $('#resolution option:selected').val();
			
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
