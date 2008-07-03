var winX;
var winY;
if (MSIE) {
  winX=window.screenLeft;
  winY=window.screenTop;
} else if (GECKO) {
  winX=window.screenX;
  winY=window.screenY;
} else {
  alert ('not supported browser');
}

if (GECKO) {
  if (winX>0 || winY>0)
    window.moveTo(-4,-4);
} else {
  if (winX!=0 || winY!=0)
    window.moveTo(0,0);
}

/*
alert(winX+','+winY + '  ' + window.top + 'x' + window.outerHeight + 
      '  ' + window.screen.availWidth + 'x' + window.screen.availHeight);
     window.resizeTo(window.screen.availWidth,window.screen.availHeight);
*/

if (GECKO)
{
  if (window.outerWidth < window.screen.availWidth)
     window.resizeTo(window.screen.availWidth,window.screen.availHeight);
} else
  window.resizeTo(window.screen.availWidth,window.screen.availHeight);

if (ie||ns6) {
  tipobj=document.all? 
     document.all['tooltip'] :
     document.getElementById? document.getElementById('tooltip') : '';
  if (GECKO)
     document.onmousemove=positiontip;
}

var help_win=null;

var win_info;
var cam_nr;
var ip;
var port;
var orig_w;
var orig_h;
var url;

function parse_win_info(wininfo) {
   win_info = wininfo.split('=');
   cam_nr = parseInt(win_info[1]);
   ip = win_info[2];
   port = parseInt(win_info[3]);
   orig_w = parseInt(win_info[4]);
   orig_h = parseInt(win_info[5]);
 
   url = 'http:\/\/' + ip + ':' + port + '/video.mjpg';
}

function img_mouseover(eimg,win_nr,orig_w,orig_h) {
 
   var splice = WINS_DEF[win_nr];
   var onoff = parseInt(splice.charAt(0));
   if ( onoff == 0 )
      return;
   parse_win_info(splice);
   
   var div = eimg.parentNode;
   
 hint = '<table style="font-weight:bold;" cellspacing="0" border="0" cellpadding="1"><tbody><tr>\n' +
 '<td align="right">Камера:<\/td>\n' +
 '<td>#'+cam_nr+' ' + CAMS_NAMES[win_nr] + '<\/td>\n' +
 '<\/tr><tr>\n' +
 '<td align="right">URL:<\/td>\n' +
 '<td>'+url+'<\/td>\n' +
 '<\/tr><tr>\n' +
 '<td align="right">Размер:<\/td>\n' +
 '<td>'+orig_w+'x'+orig_h+' (исходный), ' + eimg.width+'x'+eimg.height+' (на экране)<\/td>\n' +
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

var win_div_left;
var win_div_top;
var win_div_w;
var win_div_h;
var img_in_div_w;
var img_in_div_h;

var FS=false;

function img_click(img, orig_w, orig_h) {

   var clicked_div = img.parentNode;
   var tmp_div=null;
   
   var border_w = clicked_div.offsetWidth - clicked_div.clientWidth;
   var border_h = clicked_div.offsetHeight - clicked_div.clientHeight;

   var new_h;
   var new_w;
   var i;
   if (FS) {
     clicked_div.style.width = win_div_w+'px';
     clicked_div.style.height = win_div_h+'px';
     clicked_div.style.left = win_div_left+'px';
     clicked_div.style.top = win_div_top+'px';

     img.width=img_in_div_w;
     img.height=img_in_div_h;
     for (i=0;i<WIN_CNT;i++) {
        tmp_div=WIN_DIVS[i];
        if (tmp_div==clicked_div)
          continue;
        tmp_div.style.visibility='visible';
     }
     FS=false;
   } else {
      for (i=0;i<WIN_CNT;i++) {
        tmp_div=WIN_DIVS[i];
        tmp_div.style.visibility='hidden';
      }

      win_div_h = clicked_div.clientHeight;
      win_div_w = clicked_div.clientWidth;
      win_div_left=clicked_div.offsetLeft;
      win_div_top=clicked_div.offsetTop;
      img_in_div_w=img.width;
      img_in_div_h=img.height;
 
      var cdiv_w_max = CANVAS_W - border_w;
      var cdiv_h_max = CANVAS_H - border_h;
      if (PrintCamNames) 
        cdiv_h_max -= name_div_h;
      
      var ratio = parseFloat(orig_w/orig_h);
      if (ratio == (4/3)) {
        new_w=parseInt(cdiv_w_max/4);
        new_h=new_w*3;
        new_w*=4;
        if (new_h>cdiv_h_max) {
           new_h=parseInt(cdiv_h_max/3);
           new_w=new_h*4;
           new_h*=3;
        }
        
      } else {
        new_w=cdiv_w_max;
        new_h=parseInt(new_w/ratio);
        if (new_h>cdiv_h_max) {
           new_h=cdiv_h_max;
           new_w=parseInt(new_h*ratio);
        }
      }
      // alert(clicked_div.style.left + ' ' + clicked_div.style.top + ' ' + clicked_div.offsetLeft + ' ' + clicked_div.offsetTop);
      if (clicked_div.offsetLeft!=0)
         clicked_div.style.left='0px';
      if (clicked_div.offsetTop!=0)
         clicked_div.style.top='0px';
      clicked_div.style.width = new_w + 'px';
      clicked_div.style.height = (new_h+name_div_h) + 'px' ;
   
      /*
      alert(CANVAS_W + ' x ' + CANVAS_H +
      "\n" + img.width + ' x ' +img.height +
      "\n" + clicked_div.clientWidth + ' x ' +clicked_div.clientHeight+ 
      "\n" + clicked_div.style.width + ' x ' +clicked_div.style.height);
      */
      

      img.width=new_w;
      img.height=new_h;
      clicked_div.style.visibility='visible';
 
     FS=true;
   }
}

var br_specific='';
if (GECKO)
  br_specific='в настройках браузера отключена опция &quot;загружать изображения&quot;';
else if (MSIE) 
  br_specific='настройки браузера не позволяют загрузать и выполнять компоненты ActiveX - спросите у Вашего системного администратора или у нас';
else
  br_specific='Вы НЕ пользуетесь браузерами Microsoft Internet Explorer, Firefox, Mozilla, Netscape';
function not_show() {
   if(help_win == null || help_win.closed)
   {
      help_win = window.open('','_blank','width=510,height=600,menubar=0,toolbar=0,location=0,status=0');
      help_win.document.write('<body bgcolor="lightyellow"><br \/>'+
'<div id="div_help_win" >'+
'Если Вы не видите изображения от видеокамер, то возможно:'+
'<ul>'+
'<li style="margin:15px;">другие пользователи сейчас смотрят камеры (есть ограничение по количеству одновременных просмотров: параметр wc_limit);<\/li>'+
'<li style="margin:15px;">сервер avregd не работает;<\/li>'+
'<li style="margin:15px;">камера не настроена должным образом для просмотра по сети;<\/li>'+
'<li style="margin:15px;">вам не разрешено смотреть эту(и) камеру(ы);<\/li>'+
'<li style="margin:15px;">'+br_specific+';<\/li>'+
'<li style="margin:15px;">в другом окне браузера на Вашем компьютере уже запущен просмотр камер;<\/li>'+
'<li style="margin:15px;">настройки сетевого экрана firewall на Вашем компьютере блокируют запросы к камерам;<\/li>'+
'<i style="margin:15px;">возможно просто нужно перезапустить браузер или обновить страницу;<\/li>'+
'<li style="margin:15px;">ещё какая-нибудь причина которую мы пока не знаем :)<\/li>'+
'<\/ul>'+
'<br \/>'+
'<center>'+
'<input type="submit" name="Close" style="background-color:#ffa500;" value="Закрыть окно" onclick="window.close();" \/>'+
'<\/center>'+
'<\/div><\/body>');
     help_win.document.close();
   } else {
     help_win.focus();
   }
}

function brout(win_nr, cam_w, cam_h) {
   // alert('win ' + win_nr + '[ ' + cam_w + 'x' + cam_h + ' ]');

   var splice = WINS_DEF[win_nr];

   var onoff = parseInt(splice.charAt(0));
   if ( onoff == 0 )
      return;
  
   parse_win_info(splice);
   var id='cam'+cam_nr;
   var obj=null;
   var W;
   var H;
   if (FitToScreen) {
     W=cam_w+'px';
     H=cam_h+'px';
   } else {
     if (orig_w<=cam_w && orig_h<=cam_h) {
       W=orig_w+'px';
       H=orig_h+'px';
     } else {
       W=cam_w+'px';
       H=cam_h+'px';
     }
   }
   var alt = 'WebCam #' + cam_nr + ' on ' + url + ' , original geo ['+orig_w+'x'+orig_h+']';
   if (GECKO) {
      alt += ' Found Gecko engine browser (Firefox, Mozilla or Netscape).';
      document.writeln('<img src="'+url+'?ab='+___abenc+'" id="'+id+'" name="cam" alt="' +alt+'" '+
      'width="'+W+'" height="'+H+'" ' +
      'align="middle" border="0px" ' +
      'onclick="img_click(this,'+orig_w+', '+orig_h+');" '+
      'onmouseover="img_mouseover(this,'+win_nr+','+orig_w+','+orig_h+');" '+
      'onmouseout="hideddrivetip();" '+
      ' />');
   } else if (MSIE) {
      alt += ' Microsoft Internet Explorer on Windows system found. Try ActiveX viewer.';
      document.writeln('<OBJECT ID="'+id+'" name="cam" standby="Axis Media Control Active X not loaded" '+
      ' WIDTH="'+W+'" HEIGHT="'+H+'" border="0px" '+
      ' classid="CLSID:745395C8-D0E1-4227-8586-624CA9A10A8D" '+
      ' CODEBASE="AMC.cab" \/>');
      document.writeln('<param name="UIMode" value="none">');
      document.writeln('<PARAM NAME="AutoStart" VALUE=1 \/>');
	  document.writeln('<PARAM NAME="NetworkTimeout" VALUE=5000 \/>');
	  document.writeln('<PARAM NAME="StretchToFit" VALUE=1 \/>');
	  document.writeln('<PARAM NAME="Popups" VALUE=6 \/>');
	  document.writeln('<PARAM NAME="ShowToolbar" VALUE=0 \/>');
	  document.writeln('<PARAM NAME="MediaType" VALUE="mjpeg-unicast" \/>');
      document.writeln('<PARAM NAME="MediaURL" VALUE="'+url+'?ab='+___abenc+'" />');
      // document.writeln('<PARAM NAME="MediaUsername" VALUE="'+___u+'" />');
      // document.writeln('<PARAM NAME="MediaPassword" VALUE="'+___p+'" />');
	  document.writeln('<PARAM NAME="EnableReconnect" VALUE='+EnableReconnect+' \/>');
      document.writeln('<br \/>'+alt);
      document.writeln('<\/OBJECT>');
      obj=document.all[id];
      obj.EnableContextMenu = 1;
	  document.writeln('<script language="JavaScript" ' +
      'for="'+id+'" event="OnDoubleClick(btn, shift, x, y)"> '+
      'if (document.all[id].FullScreen) document.all[id].FullScreen=0; else document.all[id].FullScreen=1;'+
      '<\/script>');
   } else {
      alt += ' Unknow browser. Try Java viewer applet - Combozolla.';
      document.writeln('<applet code="com.charliemouse.cambozola.Viewer" archive="cambozola.jar" '+
      'ID="'+id+'" name="cam" '+
      'WIDTH="'+W+'" HEIGHT="'+H+'" border="0px" ' +
      'onclick="img_click(this,'+orig_w+', '+orig_h+');" >');
      document.writeln('<PARAM NAME="URL" VALUE="'+url+'" />');
      document.writeln('<br>'+alt);
      document.writeln('<\/applet>');
   }
}

function br_spec_out() {
  if (GECKO)
    document.write('Один клик мышью - окно на весь экран.');
  else if (MSIE)
    document.write('Мышь: двойной клик левой - на весь экран, клик правой - контекст. меню.');
  else
    document.write('Нужно использовать сл. браузеры: Microsoft Internet Explorer, Firefox, Mozilla, Netscape.');
}

/* global variables */
var CANVAS;
var CANVAS_W = 0;
var CANVAS_H = 0;

var WIN_DIVS;
var WIN_CNT = 0;

function calc_cam_geo(aspect_ratio) {
  // aspect ratio 4/3
  var cam_w;
  var cam_h;
  // create wins
  var name_div_h=0;
  var calc_canvas_h=CANVAS_H;
  if (PrintCamNames) {
     name_div_h=20;
     calc_canvas_h -= (name_div_h*ROW_NR);
  }

  if ( (CANVAS_W/calc_canvas_h) >= (4*COL_NR)/(3*ROW_NR) ) {
    cam_h = parseInt(calc_canvas_h/ROW_NR);
    cam_h = parseInt(cam_h/3);
    cam_w = cam_h*4;
    cam_h *= 3;
  } else {
    cam_w = parseInt(CANVAS_W/COL_NR);
    cam_w = parseInt(cam_w/4);
    cam_h = cam_w*3;
    cam_w *= 4;
  }

  if (GECKO) {
    // border out
    cam_w-=4;
    cam_h-=3;
  }

  this.width  = cam_w; 
  this.height = cam_h;
  this.calc_canvas_h = calc_canvas_h;
  this.name_div_h = name_div_h;
}


function calc_cam_left(cam_geo, col) {
   if (GECKO) 
      return col*(cam_geo.width+4);
   else
      return col*cam_geo.width;
}

function cacl_cam_top(cam_geo, row) {
   if (GECKO)
      return row*(cam_geo.height + cam_geo.name_div_h + 3);
   else
      return row*(cam_geo.height + cam_geo.name_div_h);
}

function canvas_growth() {
   CANVAS_W = (($.browser.msie)?ietruebody().clientWidth:window.innerWidth);
   CANVAS_H = (($.browser.msie)?ietruebody().clientHeight:window.innerHeight) - $('div#toolbar').height();
   var canvas_changed = false;
   if ( CANVAS_H != CANVAS.height()) {
      CANVAS.height(CANVAS_H);
      canvas_changed = true;
   }
   if ( CANVAS_W != CANVAS.width()) {
      CANVAS.width(CANVAS_W);
      canvas_changed = true;
   }
   if (!canvas_changed)
      return;

   var cam_geo = new calc_cam_geo(CamsAspectRatio);
   alert('new CANVAS is ' + CANVAS.width() + 'x' + CANVAS.height());

   var i,tmp_div;
   for (i=WIN_CNT-1;i>=0;i--) {
      tmp_div=WIN_DIVS[i];
      tmp_div.style.top    = cacl_cam_top(cam_geo, i/COL_NR) + 'px';
      tmp_div.style.left   = calc_cam_left(cam_geo, i%COL_NR) + 'px';
      tmp_div.style.width  = cam_geo.width + 'px';
      tmp_div.style.height = (cam_geo.height + cam_geo.name_div_h) + 'px' ;
   }
}


$(document).ready( function() {
   // calc and set  CANVAS width & height
   CANVAS = $('div#canvas');
   canvas_growth();

   $(window).bind('resize', function() {
     canvas_growth();
   });

   $(window).bind('scroll', function(){return false;});

  var cam_geo = new calc_cam_geo(CamsAspectRatio);

  // alert('[ ' + CANVAS_W + 'x' + CANVAS_H + ' ] [ ' + cam_w + 'x' + cam_h + ' ]');
  var row=0;
  var col=0;
  var win_nr=0;
  var top=0;
  var left=0;
  var win_div;
  var splice;
  var onoff;
  for (row=0;row<ROW_NR;row++)
  {
     top = cacl_cam_top(cam_geo, row);

     for (col=0;col<COL_NR;col++)
     {
         left = calc_cam_left(cam_geo, col);

        /*
        alert('win_nr=' + win_nr + ', row=' + row + ', col=' + col + "\n" +
        '[ ' + top + ',' + left + ' ] [ ' + cam_geo.width + 'x' + cam_geo.height + ' ]');
        */
        win_div = $('<div id="win'+win_nr+'" name="win" class="win" ' + 
        'style="top:'+top+'px; left:'+left+'px; '+
        ' width:'+cam_geo.width+'px; height:'+(cam_geo.height+cam_geo.name_div_h)+'px; '+
        '" ></div>');
        win_div.appendTo(CANVAS)
        splice = WINS_DEF[win_nr];
        onoff = parseInt(splice.charAt(0));
        if ( onoff > 0 )
        {
           if (PrintCamNames) {
             $('<div style="vertical-align:bottom; background-color:#666699;'+
             ' padding:0px; margin:0px; overflow:hidden; border:0px;'+
             ' height:'+cam_geo.name_div_h+'px;"><div style="'+
             'padding-left:8px; padding-top:2px; padding-bottom:2px; padding-right:2px;'+
             ' color:White; font-size:14px; font-weight: bold;">'+
             CAMS_NAMES[win_nr]+
             '<\/div><\/div>').appendTo(win_div);
           }
/*
           if (GECKO)
             brout(win_nr, cam_geo.width, cam_geo.height);
           else 
             brout(win_nr, cam_geo.width-4, cam_geo.height-3);
*/
        }
        $('<p style="padding:0px; margin:5px;">' + cam_geo.width + ' x ' + (cam_geo.height+cam_geo.name_div_h) + '</p>').appendTo(win_div);
        win_nr++;
     }
  }

  WIN_DIVS = document.getElementsByName('win');
  WIN_CNT = WIN_DIVS.length;
});


