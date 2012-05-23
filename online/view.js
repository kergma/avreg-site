/**
 * @file online/view.js
 * @brief JS скрипт страницы наблюдения в реальном времени
 * Содержит функции и глобальные переменные, обеспечивающие соответствующий функционал,
 * осуществляет инициализацию страницы
 */


/**
 * Обработчик события mouseover для элементов раскладки камер.
 * Обеспечивает формирование и вывод tooltip
 * @param eimg - элемент раскладки
 * @param win_nr - номер элемента раскладки
 */
function img_mouseover(eimg, win_nr) {
	
   if ( WINS_DEF[win_nr] == undefined )
      return;

   var img_jq = $('img',eimg);

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
      /*  
          '<\/tr><tr>\n' +
          '<td align="right">CANVAS:<\/td>\n' +
          '<td>'+CANVAS_W+'x'+CANVAS_H+'<\/td>\n' +
          '<\/tr><tr>\n' +
          '<td align="right">win div:<\/td>\n' +
          '<td>'+div.offsetTop+','+div.offsetLeft+ '  cl=' +div.clientWidth+'x'+div.clientHeight+'  Off='+
          div.offsetWidth+'x'+div.offsetHeight+'  Scr='+
          div.scrollWidth+'x'+div.scrollHeight+'  wh='+div.style.width+' x '+div.style.height+'<\/td>\n' +
          */
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
   var img_jq = $('img',clicked_div);
   var tmp_div;
   var clicked_div_jq = $(clicked_div);
   var win_geo; 
   var i;
   if ( FS_WIN_DIV ) {
      // current - fullscreen

      if ( WIN_DIV_W == undefined ) {
         /* в момент FS было изменение CANVAS */
         change_wins_geo();
      } else {
         var border_w = clicked_div.offsetWidth - clicked_div.clientWidth;
         var border_h = clicked_div.offsetHeight - clicked_div.clientHeight;
         clicked_div.style.width = WIN_DIV_W + border_w + 'px';
         clicked_div.style.height = WIN_DIV_H + border_h + 'px';
         clicked_div.style.left = WIN_DIV_LEFT + 'px';
         clicked_div.style.top = WIN_DIV_TOP + 'px';
         img_jq.css('width', IMG_IN_DIV_W);
         img_jq.css('height', IMG_IN_DIV_H);
      }

      for (i=0;i<WIN_DIVS.length;i++) {
         tmp_div=WIN_DIVS[i];
         if ( tmp_div == clicked_div )
            continue;
         tmp_div.style.visibility='visible';
      }
      FS_WIN_DIV = undefined;
   } else {
      // current - NO fullscreen
      for (i=0;i<WIN_DIVS.length;i++) {
         tmp_div=WIN_DIVS[i];
         if ( tmp_div == clicked_div )
            continue;
         tmp_div.style.visibility='hidden';
      }

      WIN_DIV_H = clicked_div.clientHeight;
      WIN_DIV_W = clicked_div.clientWidth;
      WIN_DIV_LEFT=clicked_div.offsetLeft;
      WIN_DIV_TOP=clicked_div.offsetTop;
      IMG_IN_DIV_W=img_jq.width();
      IMG_IN_DIV_H=img_jq.height();


      win_geo = new calc_win_geo(CANVAS_W, CANVAS_H, CamsAspectRatio, 1, 1, 1);

      clicked_div_jq.css('top',  calc_win_top (win_geo, 0));
      clicked_div_jq.css('left', calc_win_left(win_geo, 0));
      clicked_div_jq.width(win_geo.win_w);
      clicked_div_jq.height(win_geo.win_h);
      $('img',clicked_div_jq).width(win_geo.cam_w).height(win_geo.cam_h)
         // .attr('alt',win_geo.cam_w + 'x' + win_geo.cam_h);

         clicked_div.style.visibility='visible';

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
   if ( WINS_DEF[win_nr] == undefined )
      return;
   var cam_nr = WINS_DEF[win_nr].cam.nr;
   var id='cam'+cam_nr;
   var orig_w = WINS_DEF[win_nr].cam.orig_w;
   var orig_h = WINS_DEF[win_nr].cam.orig_h;
   var url = WINS_DEF[win_nr].cam.url;

   var alt = 'Camera #' + cam_nr + ' ' + WINS_DEF[win_nr].cam.name + ' ['+orig_w+'x'+orig_h+'] ' + url;
   if (MSIE) {
      var amc = document.createElement('object');
      amc.id = id;
      amc.alt = alt + ' Microsoft Internet Explorer on Windows system found. Try ActiveX viewer.';
      amc.border = 0;
      amc.hspace = 0;
      amc.vspace = 0;
      amc.width = win_geo.cam_w;
      amc.height = win_geo.cam_h;
      win_div.get(0).appendChild(amc);
      amc.codeBase = "AMC.cab#Version=5,6,2,5";
      amc.classid = "clsid:745395C8-D0E1-4227-8586-624CA9A10A8D";
      amc.UIMode = "none";
      amc.ShowToolbar = false;
      amc.ShowStatusBar = false;
      amc.StretchToFit = true;
      amc.Popups = 6;
      amc.EnableReconnect = EnableReconnect;
      amc.EnableContextMenu = 1;
      amc.MediaType = "mjpeg";
      amc.MediaURL = url+'&ab='+___abenc;
      amc.AutoStart = true;
   } else {
      // $('<img src="/640x480r.png" id="'+id+'" name="cam" alt="' +alt+'" '+
      $('<img src="'+url+'&ab='+___abenc+'" id="'+id+'" name="cam" alt="' +alt+'" '+
            'width="'+orig_w+'px" height="'+orig_h+'px" ' +
            'align="middle" border="0px" />').appendTo(win_div).width(win_geo.cam_w).height(win_geo.cam_h);
      win_div.click( function(e) {
            if ( typeof(e.target) == "undefined" || typeof(e.target.tagName) == "undefined" )
               return img_click(this);
            else {
               if ( e.target.tagName != 'A')
                  return img_click(this);
            }
         } );
      win_div.mouseover( function() { img_mouseover(this, win_nr);} );
      win_div.mouseout( function() { hideddrivetip(); } ); 
   }
}

/* global variables */
///Элемент, в который выводятся раскладка камер
var CANVAS;
///Ширина эл-та CANVAS
var CANVAS_W = -1;
///Высота эл-та CANVAS
var CANVAS_H = -1;

///Элемент раскладки камер
var WIN_DIVS;

///global var for tooltip
var WIN_DIV_LEFT;
///global var for tooltip
var WIN_DIV_TOP;
///global var for tooltip
var WIN_DIV_W;
///global var for tooltip
var WIN_DIV_H;
///global var for tooltip
var IMG_IN_DIV_W;
///global var for tooltip
var IMG_IN_DIV_H;
///global var for tooltip
var FS_WIN_DIV;

///Высота элемента в кот. выводится название камеры
var NAME_DIV_H = PrintCamNames?20:0;


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
      /* соотношение сторон видеоизображения нас не волнует,
         растягиваем окна камер и сами изображения по всему CANVAS */
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
   fs_win_div_jq.width(win_geo.win_w);
   fs_win_div_jq.height(win_geo.win_h);
   if ( GECKO ) {
      $('img',fs_win_div_jq).width(win_geo.cam_w).height(win_geo.cam_h)
         // .attr('alt',win_geo.cam_w + 'x' + win_geo.cam_h);
   } else if ( MSIE ) {
      var r = $('object',fs_win_div_jq).width(win_geo.cam_w).height(win_geo.cam_h)
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
      tmp_div.width(win_geo.win_w);
      tmp_div.height(win_geo.win_h);
      if ( GECKO ) {
         $('img',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h)
            // attr('alt',win_geo.cam_w + 'x' + win_geo.cam_h);
      } else if (MSIE) {
         // $('img',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h)
         $('object',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h)
            // .text(win_geo.cam_w + 'x' + win_geo.cam_h)
      } else {
         $('applet',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h)
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

function get_geo_str(JQ_elem) {
   return JQ_elem.attr('nodeName') + '#' +  JQ_elem.attr('id') +
      ': [ ' + JQ_elem.css('left') + ',' +  JQ_elem.css('top') + ' : ' +
      JQ_elem.width() + ' x ' + JQ_elem.height() + ' ]';
}

function try_fs() {
   var winX;
   var winY;
   if (MSIE) {
      winX=window.screenLeft;
      winY=window.screenTop;
   } else { /* else if (GECKO) { */
      winX=window.screenX;
      winY=window.screenY;
   }

   if (GECKO) {
      if (winX>0 || winY>0)
         window.moveTo(-4,-4);
   } else {
      if (winX!=0 || winY!=0)
         window.moveTo(0,0);
   }
   if (GECKO)
   {
      if (window.outerWidth < window.screen.availWidth)
         window.resizeTo(window.screen.availWidth,window.screen.availHeight);
   } else
      window.resizeTo(window.screen.availWidth,window.screen.availHeight);
   }

   $(document).ready( function() {

	   
///---------------------------------------------- Заменить содержимое на вызов ---> fill_canvas();
/*	   
         if (ie||ns6) {
         tipobj=document.all? 
         document.all['tooltip'] :
         document.getElementById? document.getElementById('tooltip') : '';
         if (GECKO)
         document.onmousemove=positiontip;
         }

         // try_fs();

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

         $('#dialog').jqm({overlay: 90});
*/
///---------------------------------------------- Заменить содержимое на вызов ---> fill_canvas();

         fill_canvas();
         
});


