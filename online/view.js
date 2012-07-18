/**
 * @file online/view.js
 * @brief JS скрипт страницы наблюдения в реальном времени
 * Содержит функции и глобальные переменные, обеспечивающие соответствующий функционал,
 * осуществляет инициализацию страницы
 */


/* global variables */
///Элемент, в который выводятся раскладка камер
var CANVAS;
///Ширина эл-та CANVAS
var CANVAS_W = -1;
///Высота эл-та CANVAS
var CANVAS_H = -1;

///Элемент раскладки камер
var WIN_DIVS;

///значение left-позиции в раскладке развернутой ячейки
var WIN_DIV_LEFT;
///значение top-позиции в раскладке развернутой ячейки
var WIN_DIV_TOP;
///значение ширины в раскладке развернутой ячейки
var WIN_DIV_W;
///значение высоты в раскладке развернутой ячейки
var WIN_DIV_H;
///значение ширины в раскладке элемента отображения потокового видео
var IMG_IN_DIV_W;
///значение высоты в раскладке элемента отображения потокового видео
var IMG_IN_DIV_H;
///текущий режим отображения : полноэкранный/в ячейке раскладки  
var FS_WIN_DIV;

///Высота элемента в кот. выводится название камеры
var NAME_DIV_H = PrintCamNames?20:0;

///корректировка размеров контейнера для плеера
var CORRECT_H = 4; var CORRECT_W = 4; 

$(document).ready( function() {
	//Кнопки свернуть/развернуть
	var ico_fs = new Image();
	ico_fs.src =  "../img/fs.png";
	var ico_tc = new Image();
	ico_tc.src =  "../img/tc.png";
	//Запуск сценария	   
	fill_canvas();
});



/**
 * Обработчик события mouseover для элементов раскладки камер.
 * Обеспечивает формирование и вывод tooltip
 * @param cell - элемент раскладки
 * @param win_nr - номер элемента раскладки
 */
function img_mouseover(cell, win_nr) {
	
   if ( WINS_DEF[win_nr] == undefined ) return;

   var img_jq = $('.pl_cont',cell);

   var cam_nr = WINS_DEF[win_nr].cam.nr;
   var orig_w = WINS_DEF[win_nr].cam.orig_w;
   var orig_h = WINS_DEF[win_nr].cam.orig_h;
   var url = WINS_DEF[win_nr].cam.url;

   hint = '<table style="font-weight:bold;" cellspacing="0" border="0" cellpadding="1"><tbody><tr>\n' +
      '<td align="right">Камера:<\/td>\n' +
      '<td>#'+cam_nr+' ' +  WINS_DEF[win_nr].cam.name + '<\/td>\n' +
      '<\/tr><tr>\n' +
      '<td align="right">URL:<\/td>\n' +
      '<td>'+url+'<\/td>\n' +
      '<\/tr><tr>\n' +
      '<td align="right">Размер:<\/td>\n' +
      '<td>'+orig_w+'x'+orig_h+' (исходный), ' + img_jq.width()+'x'+img_jq.height()+' (на экране)<\/td>\n' +
      '<\/tr><\/tbody><\/table>\n';

   ddrivetip();
}

/**
 * Обработчик события click по элементу раскладки камер.
 * Если включен режим раскладки разворачивает контекстный елемент в полноэкранный режим.
 * Если включен полноэкранном режим - востанавливает режим раскладки.
 * @param clicked_div - элемент раскладки камер, по кот. осуществлен клик
 */

function img_click(clicked_div) {
	
//   var pl_cont = $('img',clicked_div);
	var pl_cont = $('.pl_cont',clicked_div);
	
   var tmp_div;
   var clicked_div_jq = $(clicked_div);
   var win_geo; 
   var i;
   //номер ячейки
   var win_nr = parseInt(($(clicked_div).attr('id')).match(/\d+/gi));

   //устанавливаемый src
   var current_src=null;
   
   //если номер камеры не определен
   if(win_nr == null ) return;
   
   if ( FS_WIN_DIV ) {
      // current - fullscreen

	      //меняем на источник для ячейки
	      if (active_cams_srcs[win_nr]['type']!='avregd'){
	    	  if(active_cams_srcs[win_nr]['cell']!=null || active_cams_srcs[win_nr]['cell']!='')
	    		  current_src = active_cams_srcs[win_nr]['cell'] ;
	      }
	   
	   
      if ( WIN_DIV_W == undefined ) {
         //в режиме FS был ресайз CANVAS'a
         change_wins_geo();
         
		   //если в режиме просмотра одной камеры происходил ресайз окна браузера
         if ( MSIE ){
        	 if(current_src!=null) $('.pl_cont',clicked_div_jq).aplayerSetMjpegSrc( current_src ) ;
          }else{
        	  if(current_src!=null) $('.pl_cont',clicked_div_jq).aplayerSetMjpegSrc( current_src );
          }
         
      } else {
   
    	  //востанавливаем исходные размеры отображения камеры
     	 var border_w = clicked_div.offsetWidth - clicked_div.clientWidth;
          var border_h = clicked_div.offsetHeight - clicked_div.clientHeight;
          $(clicked_div)
          .width(WIN_DIV_W + border_w )
          .height(WIN_DIV_H + border_h);
          
          $('.pl_cont',clicked_div_jq)
 	      .width(IMG_IN_DIV_W)
 	      .height(IMG_IN_DIV_H);

          if ( MSIE ){
      		$('.pl_cont',clicked_div_jq)
      		.aplayerSetSize({'height':IMG_IN_DIV_H, 'width': IMG_IN_DIV_W});
      		if(current_src!=null)  $('.pl_cont',clicked_div_jq).aplayerSetMjpegSrc( current_src ) ;
          }else{
         	 $('.pl_cont',clicked_div_jq)
         	 .aplayerResizeToParent();
         	if(current_src!=null) $('.pl_cont',clicked_div_jq).aplayerSetMjpegSrc( current_src );
          }
          
        $(clicked_div).css({'left': WIN_DIV_LEFT + 'px', 'top': WIN_DIV_TOP + 'px' });
         
      }

      for (i=0;i<WIN_DIVS.length;i++) {
          tmp_div=WIN_DIVS[i];
          if ( tmp_div == clicked_div ){
         	 continue;
          }else{
         	 //отображаем остальные камеры
         	 $(tmp_div).show();
          }
       }
      
  	//меняем кнопку на Развернуть
      $('img', '#cell_header_'+win_nr)
      .height($('#cell_header_'+win_nr).height()-4)
      .attr({
      	'src': "../img/fs.png",
      	'title':'Развернуть',
      });

      
       FS_WIN_DIV = undefined;
      
   } else {
	 //Если включем режим - просмотра камер в раскладке
      // current - NO fullscreen
	      for (i=0;i<WIN_DIVS.length;i++) {
	          tmp_div=WIN_DIVS[i];
	          if(tmp_div == clicked_div ){
	         	 continue;
	          }else{
	         	 //прячем остальные камеры
	         	 $(tmp_div).hide();
	          }
	       }

	  WIN_DIV_H = clicked_div.clientHeight;
      WIN_DIV_W = clicked_div.clientWidth;
      WIN_DIV_LEFT=clicked_div.offsetLeft;
      WIN_DIV_TOP=clicked_div.offsetTop;
      IMG_IN_DIV_W=pl_cont.width();
      IMG_IN_DIV_H=pl_cont.height();

      NAME_DIV_H = $('#cell_header_'+win_nr, clicked_div_jq).height();
      if(NAME_DIV_H==null)NAME_DIV_H=0;
      
      win_geo = new calc_win_geo(CANVAS_W, CANVAS_H, CamsAspectRatio, 1, 1, 1);

      clicked_div_jq.css('top',  calc_win_top (win_geo, 0));
      clicked_div_jq.css('left', calc_win_left(win_geo, 0));
  
      $('.pl_cont',clicked_div_jq)
	      .width(win_geo.cam_w+CORRECT_W)
	      .height(win_geo.cam_h+CORRECT_H);

      //меняем на источник для ячейки
      if (active_cams_srcs[win_nr]['type']!='avregd'){
    	  if(active_cams_srcs[win_nr]['fs']!=null || active_cams_srcs[win_nr]['fs']!='')
    		  current_src = active_cams_srcs[win_nr]['fs'] ;
      }

    	if ( MSIE ){
    		$(clicked_div_jq).width(win_geo.win_w+CORRECT_W).height(win_geo.win_h+CORRECT_H);
    		$('.pl_cont',clicked_div_jq)
    		.aplayerSetSize({'height':win_geo.cam_h+CORRECT_H, 'width': win_geo.cam_w+CORRECT_W});
    		if(current_src!=null) {
    			$('.pl_cont',clicked_div_jq).aplayerSetMjpegSrc( current_src );
    		}
        }else{
        	$(clicked_div_jq).width(win_geo.win_w).height(win_geo.win_h);
        	$('.pl_cont',clicked_div_jq)
        	.aplayerResizeToParent();
        	if(current_src!=null){
        		$('.pl_cont',clicked_div_jq).aplayerSetMjpegSrc( current_src );
        	}
        }
    	
    	//меняем кнопку на Свернуть
        $('img', '#cell_header_'+win_nr)
        .height($('#cell_header_'+win_nr).height()-4)
        .attr({
        	'src': "../img/tc.png",
        	'title':'Свернуть'
        });
 
        
        
      FS_WIN_DIV = clicked_div;
      
   }
} // img_click()


/**
 * Функция осуществляет инициализацию и установку элемента,
 * реализующего воспроизведение видеопотока с заданной камеры,
 * в соответствующую ячейку раскладки камер
 * @param win_nr - номер устанавливаемой ячейки
 * @param win_div - элемент устанавливаемой ячейки
 * @param win_geo - объект, содержащий параметры элемента(размеры, смещение и т.п.)
 */
function brout(win_nr, win_div, win_geo) {
   if ( WINS_DEF[win_nr] == undefined ) return;
   var cam_nr = WINS_DEF[win_nr].cam.nr;
//   var id='cam'+cam_nr;
//   var orig_w = WINS_DEF[win_nr].cam.orig_w;
//   var orig_h = WINS_DEF[win_nr].cam.orig_h;
   var url = WINS_DEF[win_nr].cam.url;

   
   //Установка плеера в элемент  // win_geo.cam_h 
   var cont = $('<div class="pl_cont" />').width(win_geo.cam_w+CORRECT_W).height(win_geo.cam_h+CORRECT_H);
   
	$(win_div).append(cont);
	$(cont).addPlayer({
		'src': url , 
		'controls': false, 
		'scale':'on', 
		'mediaType' : 'mjpeg', 
		'autostart':'on', 
		'aplayer_rtsp_php':'http://'+SERVER_ADR+'/avreg/lib/js/aplayer_rtsp.php' 
	}); 
	
	if ( MSIE ){
		$(win_div).width(win_geo.win_w+CORRECT_W).height(win_geo.win_h+CORRECT_H);
		$('.pl_cont',cont).aplayerSetSize({'height':win_geo.cam_h+CORRECT_H+2 , 'width': win_geo.cam_w+CORRECT_W+2});

    }else{
	   $(cont).aplayerResizeToParent();
    }
   
	//установка обработчика клика по изображению камеры
	$(win_div).click( function(e) {
	if ( typeof(e.target) == "undefined" || typeof(e.target.tagName) == "undefined" )
    	return img_click(this);
	else {
		if ( e.target.tagName != 'A')
        	return img_click(this);
        }
	});
	   
	   //установка тултипа
      $(win_div).bind('mouseover', function() { img_mouseover(this, win_nr);} );
      win_div.mouseout( function() { hideddrivetip(); } ); 
   
}


/**
 * Функция осуществляет вычисление размеров и расположения элементов раскладки камер
 * @param _canvas_w - ширина эл-та CANVAS
 * @param _canvas_h - высота эл-та CANVAS
 * @param img_aspect_ratio - объект, содержит коэфициенты для пропорционального преобразования размеров элементов воспроизведения
 * @param _rows_nr - номер строки текущего элемента
 * @param _cols_nr - номер столбца текущего элемента
 * @param _rowspan - сколько позиций элемент занимает в раскладке
 */
// XXX need ie box model 
function calc_win_geo(_canvas_w, _canvas_h, img_aspect_ratio, _rows_nr, _cols_nr, _rowspan) {
   var cam_w;
   var cam_h;

   if (_rowspan == undefined)
      _rowspan = 1;

   if ( img_aspect_ratio == undefined || 
         img_aspect_ratio == 'fs' ) {
      // соотношение сторон видеоизображения нас не волнует,
      //  растягиваем окна камер и сами изображения по всему CANVAS 
      cam_w = parseInt(_canvas_w/_cols_nr) - BorderLeft - BorderRight;
      cam_h = parseInt(_canvas_h/_rows_nr) - NAME_DIV_H*_rowspan - BorderTop - BorderBottom;
   } else {
      // create wins
      var calc_canvas_h = _canvas_h - ((NAME_DIV_H*_rowspan + BorderTop + BorderBottom) * _rows_nr);

      if ( (_canvas_w/calc_canvas_h) >= 
            (img_aspect_ratio.num*_cols_nr)/(img_aspect_ratio.den*_rows_nr) ) {
         cam_h = parseInt(calc_canvas_h/_rows_nr);
         cam_h = parseInt(cam_h/img_aspect_ratio.den);
         cam_w = cam_h*img_aspect_ratio.num;
         cam_h *= img_aspect_ratio.den;
      } else {
         cam_w = parseInt(_canvas_w/_cols_nr - BorderLeft - BorderRight);
         cam_w = parseInt(cam_w/img_aspect_ratio.num);
         cam_h = cam_w*img_aspect_ratio.den;
         cam_w *= img_aspect_ratio.num;
      }
   }

   this.win_w = cam_w + BorderLeft + BorderRight;
   this.win_h = cam_h + NAME_DIV_H*_rowspan + BorderTop + BorderBottom;

   this.offsetX = parseInt((_canvas_w - this.win_w * _cols_nr)/2);  
   this.offsetY = parseInt((_canvas_h - this.win_h * _rows_nr)/2);  

   this.cam_w = cam_w; 
   this.cam_h = cam_h;
} // calc_win_geo()


/**
 * Вычисляет положение left для элементов раскладки
 * Вызывается при:
 * <ul>
 * <li> Установке элементов в раскладке
 * <li> При переходе в элемента в полноэкраный режим
 * <li> При ресайзе окна
 * <li>	При выходе из полноэкранного режима, если этом режиме был ресайз окна
 * </ul>
 * @param win_geo - объект, содержит параметры контекстного элемента
 * @param col - колонка раскладки по которой позиционируется элемент
 */
function calc_win_left(win_geo, col) {
   var _left = parseInt(col*win_geo.win_w + win_geo.offsetX);
   return _left;
}

/**
 * Вычисляет положение top для элементов раскладки
 * Вызывается при:
 * <ul>
 * <li> Установке элементов в раскладке
 * <li> При переходе в элемента в полноэкраный режим
 * <li> При ресайзе окна
 * <li>	При выходе из полноэкранного режима, если этом режиме был ресайз окна
 * </ul>
 * @param win_geo - объект, содержит параметры контекстного элемента
 * @param row - строка раскладки по которой позиционируется элемент
 */
function calc_win_top(win_geo, row) {
   var _top = parseInt( row*win_geo.win_h + win_geo.offsetY );
   return _top; 
}

/**
 * Вычисляет и устанавливает размеры отображаемого элемента в полноэкранном режиме при ресайзе окна
 * @param fs_win - отображаемый  в полноэкранном режиме элемент
 */
function change_fs_win_geo(fs_win) {
   var win_geo = new calc_win_geo(CANVAS_W, CANVAS_H, CamsAspectRatio, 1, 1, 1);
   var fs_win_div_jq = $(fs_win);
   
   fs_win_div_jq.css('top',  calc_win_top (win_geo, 0));
   fs_win_div_jq.css('left', calc_win_left(win_geo, 0));

   if ( GECKO ) {
	   
	   $(fs_win_div_jq)
	   .width(win_geo.win_w)
	   .height(win_geo.win_h);
	   
	   $('.pl_cont',fs_win_div_jq).width(win_geo.cam_w+CORRECT_W).height(win_geo.cam_h+CORRECT_H).aplayerResizeToParent();
         // .attr('alt',win_geo.cam_w + 'x' + win_geo.cam_h);
   } else if ( MSIE ) {
     	$(fs_win_div_jq)
       	.width(win_geo.win_w+CORRECT_W)
       	.height(win_geo.win_h+CORRECT_H);
       	
       	$('.pl_cont',fs_win_div_jq)
       	.width(win_geo.cam_w+CORRECT_W)
       	.height(win_geo.cam_h+CORRECT_H)
       	.aplayerSetSize({'height':win_geo.cam_h+CORRECT_H , 'width': win_geo.cam_w+CORRECT_W}) ;   
         // .text(win_geo.cam_w + 'x' + win_geo.cam_h)
   }
} // change_fs_win_geo()


/**
 * Вычисляет и устанавливает размеры элементов раскладки после ресайза окна
 */
function change_wins_geo() {
   var base_win_geo = new calc_win_geo(CANVAS_W, CANVAS_H, CamsAspectRatio, ROWS_NR, COLS_NR, 1);
   var win_geo;
   var i,tmp_div,win_def,win_nr,win_id;
   
   for (i=WIN_DIVS.length-1; i>=0; i--) {
      win_id = WIN_DIVS[i].id;
      win_nr = parseInt(win_id.substr(3));
      if ( win_nr == NaN ) {
         alert('Error: win.id="' + WIN_DIVS[i].id + '"');
         return;
      }
      tmp_div=$(WIN_DIVS[i]);
      win_def = WINS_DEF[win_nr];
      if ( win_def.rowspan == 1 && win_def.colspan == 1 )
         win_geo = base_win_geo;
      else
         win_geo = new calc_win_geo(base_win_geo.win_w*win_def.colspan,
               base_win_geo.win_h*win_def.rowspan,
               CamsAspectRatio, 1, 1, win_def.rowspan);
      tmp_div.css('top',  calc_win_top (base_win_geo, win_def.row));
      tmp_div.css('left', calc_win_left(base_win_geo, win_def.col));
//      tmp_div.width(win_geo.win_w);
//      tmp_div.height(win_geo.win_h);
      if ( GECKO ) {
          $(tmp_div).width(win_geo.win_w).height(win_geo.win_h);    	  
    	  $('.pl_cont',tmp_div).width(win_geo.cam_w+CORRECT_W).height(win_geo.cam_h+CORRECT_H).aplayerResizeToParent();
      } else if (MSIE) {
         	$(tmp_div).width(win_geo.win_w+CORRECT_W).height(win_geo.win_h+CORRECT_H);
        	$('.pl_cont',tmp_div).width(win_geo.cam_w+CORRECT_W).height(win_geo.cam_h+CORRECT_H)
        	.aplayerSetSize({'height':win_geo.cam_h+CORRECT_H , 'width': win_geo.cam_w+CORRECT_W}) ;
      } else {
         $('applet',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h);
      }
   } // for(allwin)
} // change_wins_geo()

/**
 * Обработчик ресайза окна
 */
function canvas_growth() {
   var canvas_changed = false;
   var avail_h = (($.browser.msie)?ietruebody().clientHeight:window.innerHeight) - $('#toolbar').height();
   var avail_w = (($.browser.msie)?ietruebody().clientWidth:window.innerWidth);
   if ( avail_h !=  CANVAS_H) {
      CANVAS_H = avail_h;
      CANVAS.height(CANVAS_H);
      canvas_changed = true;
   }
   if ( avail_w != CANVAS_W) {
      CANVAS_W = avail_w;
      CANVAS.width(CANVAS_W);
      canvas_changed = true;
   }
   if (!canvas_changed)
      return;
   if ( WIN_DIVS == undefined )
      return;

   WIN_DIV_W = undefined;

   if ( FS_WIN_DIV ) {
      change_fs_win_geo(FS_WIN_DIV);
   } else {
      change_wins_geo();
   } // if ( FS_WIN_DIV )
} // canvas_growth()

   /**
    * Выводит список доступных раскладок
    * @returns {String} - html -разметка
    */

   function layouts_to_list(){
	   var html = '<div>';
   	$.each(layouts_list, function(i, value){
   		html+='<div class="layout'+((cur_layout==value.MON_NR)? ' selectedLayout':'' )+'" ><a id="layout_'+value.MON_NR+'" onclick="change_layout('+value.MON_NR+')" href="#">';
   		html+= (value.SHORT_NAME==''? value.MON_TYPE :value.SHORT_NAME);
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

   	
   	cur_layout = mon_nr;
   	
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
   	
   	layout_wins = $.parseJSON(layout['WINS']);
   	active_cams_srcs = new Array();
   	
   	//и перезаполняем новыми значениями
   	$.each(WINS_DEF, function(i, value){
   		if(layout_wins[i]==null || GCP_cams_params[layout_wins[i][0]]==null ) return;
   		//Параметры текущего типа раскладки
   		var l_wins = l_defs[3][i];
   		var cam_nr = layout_wins[i][0];
   		//установка url камеры
   		active_cams_srcs[i] = new Array();
   		var cam_url = '';
   		switch(layout_wins[i][1]){
   		case '0':
   		case '1': //avregd
   			cam_url =  get_cam_http_url(conf, cam_nr,'mjpeg', false );
   	   		active_cams_srcs[i]['type']='avregd';
   	   		active_cams_srcs[i]['cell']=cam_url;
   	   		active_cams_srcs[i]['fs']=cam_url;
   	   		break;
   		case '2': //alt 1
   			cam_url = GCP_cams_params[layout_wins[i][0]]['cell_url_alt_1'];
   	   		active_cams_srcs[i]['type']='alt_1';
   	   		active_cams_srcs[i]['cell']=cam_url;
   	   		active_cams_srcs[i]['fs']=GCP_cams_params[cam_nr]['fs_url_alt_1'];
   			break;
   		case '3': //alt 2
   			cam_url =GCP_cams_params[layout_wins[i][0]]['cell_url_alt_2'];
   	   		active_cams_srcs[i]['type']='alt_1';
   	   		active_cams_srcs[i]['cell']=cam_url;
   	   		active_cams_srcs[i]['fs']=GCP_cams_params[cam_nr]['fs_url_alt_2'];
   			break;
   		}
   		
   		var wxh = GCP_cams_params[ layout_wins[i][0] ]['geometry'];
   		var cam_width = parseInt(wxh.slice(0, wxh.indexOf('x')));
   		var cam_height = parseInt(wxh.slice(wxh.indexOf('x')+1));
   		if(cam_width==null || cam_width==0) cam_width = 640;
   		if(cam_height==null || cam_height==0) cam_height = 480;
   		//Возможно неверно интерпретировано: if(!empty($GCP_cams_params[$cam_nr]['Hx2'])) $height*=2;
   		if( GCP_cams_params[layout_wins[i][0]]['Hx2']!=0 && GCP_cams_params[layout_wins[i][0]]['Hx2']!=null ) cam_height *=2;
   		
   		if (major_win_cam_geo == null /* || major_win_nr === win_nr */ )
   		      major_win_cam_geo = new Array(cam_width, cam_height);
   		
   		var net_cam_host=null;
   		if (operator_user && ( GCP_cams_params[layout_wins[i][0]]['cam_type'] == 'netcam' ) ){
   			      net_cam_host = GCP_cams_params[layout_wins[i][0]]['InetCam_IP'];
   		}
   	   else{
   		   net_cam_host = null;
   	   }
   		//устанавливаем параметры и камеру для ячейки
   		WINS_DEF[i] = {
   				row : l_wins[0],
   			    col : l_wins[1],
   			    rowspan : l_wins[2],
   			    colspan : l_wins[3],
   			    cam: {
   			    	nr:   cam_nr,
   			        name: GCP_cams_params[layout_wins[i][0]]['text_left'] ,
   			        url:  cam_url, 
   			        orig_w: cam_width,
   			        orig_h: cam_height,
   			        netcam_host: net_cam_host
   			    }
   		};
   	});
   	
   	//Вывод в шапке элемента отображения камеры - названия камеры
   	PrintCamNames = (layout['PRINT_CAM_NAME']=='t' || layout['PRINT_CAM_NAME']==true)? true : false;
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
              else{
                 win_geo = new calc_win_geo(base_win_geo.win_w*win_def.colspan,
                       base_win_geo.win_h*win_def.rowspan,
                       CamsAspectRatio, 1, 1, win_def.rowspan);
              }
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
                    ' z-index: 30;' +
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
                 var hdr = $('<div id="cell_header_'+win_nr+'" style="cursor:default; background-color:#555588;'+ // #666699
                       ' padding:0px; margin:0px; overflow:hidden; border:0px;'+
                       ' height:'+ NAME_DIV_H*win_def.rowspan +'px;"><span style="'+
                       'vertical-align: middle; padding-left:8px; padding-top:2px; padding-bottom:2px; padding-right:2px;'+
                       ' color:#e5e5e5; font-size:'+14*win_def.rowspan+'px; font-weight: bold; width:100%; overflow:hidden;">'+
                       ipcamhost_link_begin + WINS_DEF[win_nr].cam.name + ipcamhost_link_end +
                       '<\/span><\/div>')
                       .appendTo(win_div);

                 $('<img src="../img/fs.png" title="Развернуть">')
                 .height($(hdr).height()-4)
                 .css({
                	 'position':'absolute',
                	 'cursor':'pointer',
                	 'top':'1px',
                	 'right':'1px'
                 })
                 .appendTo(hdr);
                 
                 
              }
              brout(win_nr, win_div, win_geo);
           }

           WIN_DIVS = $('div.win');

           $('#dialog').jqm({
   overlay: 90
   });

   //Выводим список камер
   $("#toolbar table tr")
   .html('<td>'+layouts_to_list()+'</td>');
           
    //Убрать тултип при перетаскивании
	$('.MediaCont').mousedown(function(e){ 
		e.preventDefault();
		 hideddrivetip(); 
		 $(e.currentTarget).addClass('cursorMove');
		 return false;
	});
	$('#canvas').mouseup(function(e){ 
		$('.MediaCont').removeClass('cursorMove');
	});
   
   
}
  
   

   
 
   
   
   
   
   
   
   
   
   
   
