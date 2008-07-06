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
   
   var img_jq = $('img',eimg);
   
 hint = '<table style="font-weight:bold;" cellspacing="0" border="0" cellpadding="1"><tbody><tr>\n' +
 '<td align="right">Камера:<\/td>\n' +
 '<td>#'+cam_nr+' ' + CAMS_NAMES[win_nr] + '<\/td>\n' +
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

function img_click(clicked_div, orig_w, orig_h) {
   var img_jq = $('img',clicked_div);
   var tmp_div;
   
   var border_w = clicked_div.offsetWidth - clicked_div.clientWidth;
   var border_h = clicked_div.offsetHeight - clicked_div.clientHeight;

   var new_h;
   var new_w;
   var i;
   if ( FS ) {
      // current - fullscreen
     clicked_div.style.width = WIN_DIV_W+'px';
     clicked_div.style.height = WIN_DIV_H+'px';
     clicked_div.style.left = WIN_DIV_LEFT+'px';
     clicked_div.style.top = WIN_DIV_TOP+'px';

     img_jq.width(IMG_IN_DIV_W);
     img_jq.height(IMG_IN_DIV_H);
     for (i=0;i<WIN_DIVS.length;i++) {
        tmp_div=WIN_DIVS[i];
        if ( tmp_div == clicked_div ) {
           alert('found');
           continue;
        }
        tmp_div.style.visibility='visible';
     }
     FS=false;
   } else {
      // current - NO fullscreen
      for (i=0;i<WIN_DIVS.length;i++) {
        tmp_div=WIN_DIVS[i];
        tmp_div.style.visibility='hidden';
      }

/*
      var cam_geo = new calc_cam_geo(CamsAspectRatio, 1, 1);
      var i,tmp_div;
      for (i=WIN_DIVS.length-1; i>=0; i--) {
         tmp_div=$(WIN_DIVS[i]);
         tmp_div.css('top', cacl_cam_top(cam_geo, parseInt(i/COLS_NR)));
         tmp_div.css('left',calc_cam_left(cam_geo, parseInt(i%COLS_NR)));
         tmp_div.width(cam_geo.width);
         tmp_div.height(cam_geo.height + NAME_DIV_H);
         $('label', WIN_DIVS[i]).text( get_geo_str(tmp_div) );
      }
*/
      WIN_DIV_H = clicked_div.clientHeight;
      WIN_DIV_W = clicked_div.clientWidth;
      WIN_DIV_LEFT=clicked_div.offsetLeft;
      WIN_DIV_TOP=clicked_div.offsetTop;
      IMG_IN_DIV_W=img_jq.width();
      IMG_IN_DIV_H=img_jq.height();
 
      var cdiv_w_max = CANVAS_W - border_w;
      var cdiv_h_max = CANVAS_H - border_h;
      if (PrintCamNames) 
        cdiv_h_max -= NAME_DIV_H;
      
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
      clicked_div.style.height = (new_h+NAME_DIV_H) + 'px' ;
   
      /*
      alert(CANVAS_W + ' x ' + CANVAS_H +
      "\n" + img.width + ' x ' +img.height +
      "\n" + clicked_div.clientWidth + ' x ' +clicked_div.clientHeight+ 
      "\n" + clicked_div.style.width + ' x ' +clicked_div.style.height);
      */
      

      img_jq.width(new_w);
      img_jq.height(new_h);
      clicked_div.style.visibility='visible';
 
     FS=true;
   }
}

function brout(win_nr, win_div, cam_geo) {
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
     W = cam_geo.width  + 'px';
     H = cam_geo.height + 'px';
   } else {
     if (orig_w<=cam_geo.width && orig_h<=cam_geo.height) {
       W=orig_w + 'px';
       H=orig_h + 'px';
     } else {
       W=cam_geo.width  + 'px';
       H=cam_geo.height + 'px';
     }
   }
   var alt = 'WebCam #' + cam_nr + ' on ' + url + ' , original geo ['+orig_w+'x'+orig_h+']';
   if (GECKO) {
      $('<img src="'+url+'?ab='+___abenc+'" id="'+id+'" name="cam" alt="' +alt+'" '+
      'width="'+W+'" height="'+H+'" ' +
      'align="middle" border="0px" />').appendTo(win_div);
      win_div.click( function() { img_click(this, orig_w, orig_h); } ); 
      win_div.mouseover( function() { img_mouseover(this, win_nr, orig_w, orig_h);} );
      win_div.mouseout( function() { hideddrivetip(); } ); 
   } else if (MSIE) {
      alt += ' Microsoft Internet Explorer on Windows system found. Try ActiveX viewer.';
      $('<OBJECT ID="'+id+'" name="cam" standby="Axis Media Control Active X not loaded" '+
      ' WIDTH="'+W+'" HEIGHT="'+H+'" border="0px" '+
      ' classid="CLSID:745395C8-D0E1-4227-8586-624CA9A10A8D" '+
      ' CODEBASE="AMC.cab" \/>'+
      '<param name="UIMode" value="none">'+
      '<PARAM NAME="AutoStart" VALUE=1 \/>'+
	   '<PARAM NAME="NetworkTimeout" VALUE=5000 \/>'+
	   '<PARAM NAME="StretchToFit" VALUE=1 \/>'+
	   '<PARAM NAME="Popups" VALUE=6 \/>'+
	   '<PARAM NAME="ShowToolbar" VALUE=0 \/>'+
	   '<PARAM NAME="MediaType" VALUE="mjpeg-unicast" \/>'+
      '<PARAM NAME="MediaURL" VALUE="'+url+'?ab='+___abenc+'" />'+
	   '<PARAM NAME="EnableReconnect" VALUE='+EnableReconnect+' \/>'+
      '<br \/>'+alt+
      '<\/OBJECT>').appendTo(win_div);
      obj=document.all[id]; // MSIE only
      obj.EnableContextMenu = 1;
	   //document.writeln('<script language="JavaScript" ' +
      //'for="'+id+'" event="OnDoubleClick(btn, shift, x, y)"> '+
      //'if (document.all[id].FullScreen) document.all[id].FullScreen=0; else document.all[id].FullScreen=1;'+
      //'<\/script>');
   } else {
      alt += ' Unknow browser. Try Java viewer applet - Combozolla.';
      $('<applet code="com.charliemouse.cambozola.Viewer" archive="cambozola.jar" '+
      'ID="'+id+'" name="cam" '+
      'WIDTH="'+W+'" HEIGHT="'+H+'" border="0px" ' +
      '<PARAM NAME="URL" VALUE="'+url+'" />' +
      '<br>'+ alt +
      '<\/applet>').appendTo(win_div);
       win_div.click( function() { img_click(this, orig_w, orig_h); } ); 
   }
}

function br_spec_out() {
  if (GECKO)
    document.write('Одинарный клик мышью - окно на весь экран.');
  else if (MSIE)
    document.write('Мышь: двойной клик левой - на весь экран, клик правой - контекст. меню.');
  else
    document.write('Нужно использовать браузеры: MS Internet Explorer или Firefox.');
}

/* global variables */
var CANVAS;
var CANVAS_W = -1;
var CANVAS_H = -1;

var WIN_DIVS;

// global vars for tooltip
var WIN_DIV_LEFT;
var WIN_DIV_TOP;
var WIN_DIV_W;
var WIN_DIV_H;
var IMG_IN_DIV_W;
var IMG_IN_DIV_H;
var FS=false;
   
var NAME_DIV_H = PrintCamNames?20:0;

function calc_cam_geo(aspect_ratio, cols_nr, rows_nr) {
  // aspect ratio 4/3
  var cam_w;
  var cam_h;

  // create wins
  var calc_canvas_h=CANVAS_H - (NAME_DIV_H*rows_nr);

  if ( (CANVAS_W/calc_canvas_h) >= (4*cols_nr)/(3*rows_nr) ) {
    cam_h = parseInt(calc_canvas_h/rows_nr);
    cam_h = parseInt(cam_h/3);
    cam_w = cam_h*4;
    cam_h *= 3;
  } else {
    cam_w = parseInt(CANVAS_W/cols_nr);
    cam_w = parseInt(cam_w/4);
    cam_h = cam_w*3;
    cam_w *= 4;
  }

  this.all_cams_width = cam_w * cols_nr;
  this.offsetX = parseInt((CANVAS_W - this.all_cams_width)/2);  
  this.all_cams_height = cam_h * rows_nr;
  this.offsetY = parseInt((calc_canvas_h - this.all_cams_height)/2);  

  if (GECKO) {
    // border out
    cam_w-=4;
    cam_h-=3;
  }

  this.width  = cam_w; 
  this.height = cam_h;
  this.calc_canvas_h = calc_canvas_h;
} // calc_cam_geo()


function calc_cam_left(cam_geo, col) {
   var ret;
   if (GECKO) 
      ret = col*(cam_geo.width+4);
   else
      ret = col*cam_geo.width;
   return parseInt(ret + cam_geo.offsetX);
}

function cacl_cam_top(cam_geo, row) {
   var ret;
   if (GECKO)
      ret = row*(cam_geo.height + NAME_DIV_H + 3);
   else
      ret = row*(cam_geo.height + NAME_DIV_H);
   return parseInt(ret + cam_geo.offsetY)
}

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

   var cam_geo = new calc_cam_geo(CamsAspectRatio, COLS_NR, ROWS_NR);
   // alert('new CANVAS is ' + CANVAS.width() + 'x' + CANVAS.height());

   if ( WIN_DIVS == undefined )
       return;
   var i,tmp_div;
   for (i=WIN_DIVS.length-1; i>=0; i--) {
      tmp_div=$(WIN_DIVS[i]);
      tmp_div.css('top', cacl_cam_top(cam_geo, parseInt(i/COLS_NR)));
      tmp_div.css('left',calc_cam_left(cam_geo, parseInt(i%COLS_NR)));
      tmp_div.width(cam_geo.width);
      tmp_div.height(cam_geo.height + NAME_DIV_H);
      $('label', WIN_DIVS[i]).text( get_geo_str(tmp_div) );
   }
}

function get_geo_str(JQ_elem) {
    return '[ ' + JQ_elem.css('left') + ',' +  JQ_elem.css('top') + ' : ' +
                JQ_elem.width() + ' x ' + JQ_elem.height() + ' ]';
}

$(document).ready( function() {

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

   // calc and set  CANVAS width & height
   CANVAS = $('#canvas');
   canvas_growth();

   $(window).bind('resize', function() {
     canvas_growth();
   });

   $(window).bind('scroll', function(){return false;});

  var cam_geo = new calc_cam_geo(CamsAspectRatio, COLS_NR, ROWS_NR);

  // alert('[ ' + CANVAS_W + 'x' + CANVAS_H + ' ] [ ' + cam_w + 'x' + cam_h + ' ]');
  var row=0;
  var col=0;
  var win_nr=0;
  var top=0;
  var left=0;
  var win_div;
  var splice;
  var onoff;
  for (row=0;row<ROWS_NR;row++)
  {
     top = cacl_cam_top(cam_geo, row);

     for (col=0;col<COLS_NR;col++)
     {
        left = calc_cam_left(cam_geo, col);
        win_div = $('<div id="win'+win_nr+'" name="win" class="win" ' + 
        'style="top:'+top+'px; left:'+left+'px; '+
        ' width:'+cam_geo.width+'px; height:'+(cam_geo.height+NAME_DIV_H)+'px; '+
        '" ></div>');
        win_div.appendTo(CANVAS)
        splice = WINS_DEF[win_nr];
        onoff = parseInt(splice.charAt(0));
        if ( onoff > 0 )
        {
           if (PrintCamNames) {
             $('<div style="vertical-align:bottom; background-color:#666699;'+
             ' padding:0px; margin:0px; overflow:hidden; border:0px;'+
             ' height:'+NAME_DIV_H+'px;"><div style="'+
             'padding-left:8px; padding-top:2px; padding-bottom:2px; padding-right:2px;'+
             ' color:White; font-size:14px; font-weight: bold;">'+
             CAMS_NAMES[win_nr]+
             '<\/div><\/div>').appendTo(win_div);
           }
           brout(win_nr, win_div, cam_geo.width, cam_geo.height);
        }
        $('<label style="padding:0px; margin:5px;">' + get_geo_str(win_div) + '</label>').appendTo(win_div);
        win_nr++;
     }
  }

  WIN_DIVS = $('div.win');

  $('#dialog').jqm({
      overlay: 75,
      onShow: function(h) {
         /* callback executed when a trigger click. Show notice */
         h.w.css('opacity',0.75).slideDown('fast'); 
      },
      onHide: function(h) {
        /* callback executed on window hide. Hide notice, overlay. */
        h.w.slideUp('fast',function() { if(h.o) h.o.remove(); }); } 
      });
});


