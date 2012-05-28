

/**
 * Выводит список доступных раскладок
 * @returns {String} - html -разметка
 */

function layouts_to_list(){
var html = '<div>';
	
	$.each(layouts_list, function(i, value){
		html+='<div style="float:left; padding:5px;"><a id="layout_'+value.MON_NR+'" onclick="change_layout('+value.MON_NR+')" href="#">';
		html+= (value.MON_NAME==''? value.MON_TYPE :value.MON_NAME);
		html+= (value.IS_DEFAULT==1? '(def)' :'');
		html+='</a>&nbsp;&nbsp;&nbsp;&nbsp;</div>';
	});
	html+='</div>';
	return html;
}




/**
 * Смена раскладки
 * @param mon_nr - номер устанавливаемой раскладки (из MON_NR в WEB_MONITORS БД)
 */
function change_layout(mon_nr){

	//Если был включен режим - 1 камера на весь экран
	FS_WIN_DIV = null;
	
	//целевая раскладка
	var layout=null;
	//кол-во элементов для отображения камер в целевой раскладке
	var wins_nr = 0;
	//структура целевой раскладки
	var l_defs = null;
	//Пропорции
	var AspectRatio;
	
	
	
	//Устанавливаем целевую раскладку
	$.each(layouts_list, function(i, value){
		if(value['MON_NR']==mon_nr){
			layout = value;
			return;
		}
	});
	
	//Чистим канвас
	$('#canvas').empty();
	

   l_defs = layouts_defs[layout['MON_TYPE']];

	//кол-во элементов для отображения камер
    wins_nr = l_defs[0];

	//пересоздаем объект текущей раскладки
	WINS_DEF = new MakeArray(wins_nr);

	//размеры камер
	major_win_cam_geo = null;
	
	//и перезаполняем новыми значениями
	$.each(WINS_DEF, function(i, value){
		//корректировка индекса
		var index = i+1;

		if(layout['WIN'+index]=='' || layout['WIN'+index]==null || GCP_cams_params[layout['WIN'+index]] == null) return;
		
		//Параметры текущего типа раскладки
		var l_wins = l_defs[3][i];
		var cam_nr = layout['WIN'+index];
		var wxh = GCP_cams_params[layout['WIN'+index]]['geometry'];
		var cam_width = parseInt(wxh.slice(0, wxh.indexOf('x')));
		var cam_height = parseInt(wxh.slice(wxh.indexOf('x')+1));
		if(cam_width==null || cam_width==0) cam_width = 640;
		if(cam_height==null || cam_height==0) cam_height = 480;
		//Что это такое и зачем надо - не понятно, а потому может неверно интерпретировано: if(!empty($GCP_cams_params[$cam_nr]['Hx2'])) $height*=2;
		if( GCP_cams_params[layout['WIN'+index]]['Hx2']!=0 && GCP_cams_params[layout['WIN'+index]]['Hx2']!=null ) cam_height *=2;
		
		if (major_win_cam_geo == null /* || major_win_nr === win_nr */ )
		      major_win_cam_geo = new Array(cam_width, cam_height);
		
		var net_cam_host=null;
		if (operator_user && ( GCP_cams_params[layout['WIN'+index]]['cam_type'] == 'netcam' ) ){
			      net_cam_host = GCP_cams_params[layout['WIN'+index]]['InetCam_IP'];
		}
	   else{
		   net_cam_host = null;
	   }

		WINS_DEF[i] = {
				row : l_wins[0],
			    col : l_wins[1],
			    rowspan : l_wins[2],
			    colspan : l_wins[3],
			    cam: {
			    	nr:   cam_nr,
			        name: GCP_cams_params[layout['WIN'+index]]['text_left'] ,
			        url:  get_cam_http_url(conf, cam_nr,'mjpeg', false ), 
			        orig_w: cam_width,
			        orig_h: cam_height,
			        netcam_host: net_cam_host
			    }
		};
	});
	
	//Вывод в шапке элемента отображения камеры - названия камеры
	PrintCamNames = (layout['PRINT_CAM_NAME']=="on")? true : false;
	NAME_DIV_H = PrintCamNames?20:0;
	
	//Установка пропорций
	AspectRatio = layout['PROPORTION'];
	
	//если растягивается на весь экран
	if(AspectRatio=='fs' ) {
		   CamsAspectRatio = 'fs';
	} //если сохраняем пропорции
	else {
		var rex = new RegExp("[0-9]+", "g");
		if (AspectRatio=='calc'){
		      ar = calcAspectForGeo(major_win_cam_geo[0], major_win_cam_geo[1]);
		      CamsAspectRatio = { num : ar[0] , den : ar[1] };
		   //Если пропропорции заданы в БД
		   } else if ( rex.test(AspectRatio) ) {
			   var m = AspectRatio.match(rex); 
			   CamsAspectRatio = { 'num': m[0], 'den': m[1] };
		   }
		   else
		      CamsAspectRatio = 'fs';
		}
	
	WINS_NR = wins_nr ;
	ROWS_NR = l_defs[1];
	COLS_NR = l_defs[2];
	
	fill_canvas();
}


/**
 * Расчет размеров элементов отображения камер в раскладке
 * @param w - ширина изображения камеры
 * @param h - высота изображения камеры
 * @returns объект с размерами элементов отображения камер в раскладке
 */

function calcAspectForGeo( w, h) {
	
	$.each(WellKnownAspects, function(i, val){
		
		if ( 0 == w % val[0] &&  0 == h % val[1] ) {
			if (w/val[0] == h/val[1] )
	            return val;
		}
		if ( h % val[0] &&  w % val[1] ) {
	         if ( h/val[0]== w/val[1] )
	            return new array(val[1],val[0]);
	      }
	});
	var ar = new Array(w, h);
	var _stop = (w > h)? h : w;
	for (var i=1; i<=_stop; i++) 
	{
		if ( 0 == w % i && 0 == h % i ) {
			ar[0] = w / i;
			ar[1] = h / i;
		}
	}
	   return ar;
	}




/**
 * 
 * Функция, которая возвращает ссылку на просмотр видео с камеры
 * аналог php-функции из lib/get_cam_url.php
 * @param array $conf масив настроек
 * @param int $cam_nr номер камеры
 * @param string $media тип медиа
 * @param bool $append_abenc аутентификация пользователя
 * @return string адрес видео с камеры
 */
function get_cam_http_url(conf, cam_nr, media, append_abenc){
	var url = '';
	   if (cams_subconf && cams_subconf[cam_nr]!=null && (cams_subconf[cam_nr]['avregd-httpd']).length!=0) {
		   url = cams_subconf[$cam_nr]['avregd-httpd'];
	   } 
	   else{
		   url = http_cam_location; 
	   }

	   var path_var = 'avregd-'+media+'-path';
	   
	   if( conf[path_var]!=null ){
		   url += conf[path_var]+"?camera="+cam_nr;
	   }
	   
	   if (append_abenc && user_info_USER.length>0 ) {
	      url += '&ab='+base64_encode_user_info_USER+':'+PHP_AUTH_PW;
	   }
	   return url;
}


/**
 * Выводит раскладку с он-лайн камерами в канвас
 */
function fill_canvas(){
    if (ie||ns6) {
        tipobj=document.all? 
        document.all['tooltip'] :
        document.getElementById? document.getElementById('tooltip') : '';
        if (GECKO)
        document.onmousemove=positiontip;
        }

        // calc and set  CANVAS width & height
        CANVAS = $('#canvas');
        canvas_growth();

        $(window).bind('resize', function() {
           canvas_growth();
           });

        $(window).bind('scroll', function(){return false;});

        var base_win_geo = new calc_win_geo(CANVAS_W, CANVAS_H, CamsAspectRatio, ROWS_NR, COLS_NR, 1);
        var win_geo;
        // alert('[ ' + CANVAS_W + 'x' + CANVAS_H + ' ] [ ' + cam_w + 'x' + cam_h + ' ]');
        var win_nr;
        var _top=0;
        var _left=0;
        var win_div;
        var win_def;

        for (win_nr = 0; win_nr < WINS_NR; win_nr++ ) {
           if (  WINS_DEF[win_nr] == undefined )
              continue;
           win_def = WINS_DEF[win_nr];

           if ( win_def.rowspan == 1 && win_def.colspan == 1 )
              win_geo = base_win_geo;
           else
              win_geo = new calc_win_geo(base_win_geo.win_w*win_def.colspan,
                    base_win_geo.win_h*win_def.rowspan,
                    CamsAspectRatio, 1, 1, win_def.rowspan);
           _top  = calc_win_top(base_win_geo, win_def.row);
           _left = calc_win_left(base_win_geo, win_def.col);
           win_div = $('<div id="win' + win_nr + '" name="win" class="win" ' + 
                 ' style="position: absolute; '+
                 ' top:'+_top+'px;'+
                 ' left:'+_left+'px; '+
                 ' width:'+ win_geo.win_w +'px;'+
                 ' height:'+ win_geo.win_h +'px;'+
                 ' border-top: '+BorderTop+'px solid  #ffa500;' +
                 ' border-left: '+BorderLeft+'px solid  #ffa500;' +
                 ' border-bottom: '+BorderBottom+'px solid  #ffa500;' +
                 ' border-right: '+BorderRight+'px solid  #ffa500;' + 
                 ' z-index=-' + win_nr + ';' +
                 '"><\/div>');
           win_div.appendTo(CANVAS);

           if (PrintCamNames) {
              var ipcamhost_link_begin = '';
              var ipcamhost_link_end = '';
              if ( typeof(WINS_DEF[win_nr].cam.netcam_host) == "string" ) {
                 ipcamhost_link_begin = '<a href="http://' +
                                          WINS_DEF[win_nr].cam.netcam_host +
                                          '" target="_blank" style="color:inherit;" title="Перейти в веб интерфейс IP-камеры">';
                 ipcamhost_link_end   = ' &rarr;<\/a>';
              }
              $('<div style="background-color:#666699;'+
                    ' padding:0px; margin:0px; overflow:hidden; border:0px;'+
                    ' height:'+ NAME_DIV_H*win_def.rowspan +'px;"><span style="'+
                    'vertical-align: middle; padding-left:8px; padding-top:2px; padding-bottom:2px; padding-right:2px;'+
                    ' color:#e5e5e5; font-size:'+14*win_def.rowspan+'px; font-weight: bold; width:100%; overflow:hidden;">'+
                    ipcamhost_link_begin + WINS_DEF[win_nr].cam.name + ipcamhost_link_end +
                    '<\/span><\/div>').appendTo(win_div);
           }
           brout(win_nr, win_div, win_geo);
        }

        WIN_DIVS = $('div.win');

        $('#dialog').jqm({
overlay: 90
});

//Выводим список камер
$("#toolbar table tr").html('<td>'+layouts_to_list()+'</td>');
        
}

