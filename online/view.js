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

  
      win_geo = new calc_win_geo(CamsAspectRatio, 1, 1);

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

function brout(win_nr, win_div, win_geo) {
   if ( WINS_DEF[win_nr] == undefined )
      return;
   var cam_nr = WINS_DEF[win_nr].cam.nr;
   var id='cam'+cam_nr;
   var orig_w = WINS_DEF[win_nr].cam.orig_w;
   var orig_h = WINS_DEF[win_nr].cam.orig_h;
   var url = WINS_DEF[win_nr].cam.url;
   var ob;
   var W;
   var H;
   W=win_geo.cam_w;
   H=win_geo.cam_h;

   var alt = 'WebCam #' + cam_nr + ' on ' + url + ' , original geo ['+orig_w+'x'+orig_h+']';
   if (GECKO) {
      // $('<img src="/640x480r.png" id="'+id+'" name="cam" alt="' +alt+'" '+
      $('<img src="'+url+'?ab='+___abenc+'" id="'+id+'" name="cam" alt="' +alt+'" '+
      'width="'+orig_w+'px" height="'+orig_h+'px" ' +
      'align="middle" border="0px" />').appendTo(win_div).width(W).height(H);
      win_div.click( function() { img_click(this); } ); 
      win_div.mouseover( function() { img_mouseover(this, win_nr);} );
      win_div.mouseout( function() { hideddrivetip(); } ); 
   } else if (MSIE) {
      /*
      $('<img src="/640x480r.png" id="'+id+'" name="cam" alt="' +alt+'" '+
      'width="'+W+'px" height="'+H+'px" ' +
      'align="middle" border="0px" />').appendTo(win_div).width(W).height(H);
      win_div.click( function() { img_click(this); } );
      win_div.mouseover( function() { img_mouseover(this, win_nr);} );
      win_div.mouseout( function() { hideddrivetip(); } );
      */
      alt += ' Microsoft Internet Explorer on Windows system found. Try ActiveX viewer.';
      $('<OBJECT ID="'+id+'" name="cam" standby="'+alt+'" '+
      ' WIDTH="100%" HEIGHT="100%" border=0 HSPACE=0 VSPACE=0'+
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
      '<\/OBJECT>').appendTo(win_div);
      //obj=$(id);
      //obj.EnableContextMenu = 1;
      
	   $('<script language="JavaScript" ' +
         'for="'+id+'" event="OnDoubleClick(btn, shift, x, y)"> '+
         'if (document.all[id].FullScreen) document.all[id].FullScreen=0; else document.all[id].FullScreen=1;'+
      '<\/script>').appendTo(document);
   } else {
      alt += ' Unknow browser. Try Java viewer applet - Combozolla.';
      $('<applet code="com.charliemouse.cambozola.Viewer" archive="cambozola.jar" '+
      'ID="'+id+'" name="cam" '+
      'WIDTH="'+W+'" HEIGHT="'+H+'" border="0px" ' +
      '<PARAM NAME="URL" VALUE="'+url+'" />' +
      '<br>'+ alt +
      '<\/applet>').appendTo(win_div);
       win_div.click( function() { img_click(this); } ); 
   }
}

function br_spec_out() {
  if (GECKO)
    document.write('Одинарный клик мышью - камеру на весь экран. &nbsp;F11 - полноэкранный режим.');
  else if (MSIE)
    document.write('Мышь: двойной клик левой - камеру на весь экран, клик правой - контекст. меню. &nbsp;F11 - полноэкранный режим.');
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
var FS_WIN_DIV;
   
var NAME_DIV_H = PrintCamNames?20:0;


// XXX need ie box model 
function calc_win_geo(img_aspect_ratio, cols_nr, rows_nr) {
  var cam_w;
  var cam_h;

  if ( img_aspect_ratio == undefined || 
       img_aspect_ratio == 'fs' ) {
     /* соотношение сторон видеоизображения нас не волнует,
        растягиваем окна камер и сами изображения по всему CANVAS */
     cam_w = parseInt(CANVAS_W/cols_nr) - BorderLeft - BorderRight;
     cam_h = parseInt(CANVAS_H/rows_nr) - NAME_DIV_H - BorderTop - BorderBottom;
  } else {
     
  // create wins
  var calc_canvas_h = CANVAS_H - ((NAME_DIV_H + BorderTop + BorderBottom) *rows_nr);
  
  if ( (CANVAS_W/calc_canvas_h) >= 
        (img_aspect_ratio.num*cols_nr)/(img_aspect_ratio.den*rows_nr) ) {
    cam_h = parseInt(calc_canvas_h/rows_nr);
    cam_h = parseInt(cam_h/img_aspect_ratio.den);
    cam_w = cam_h*img_aspect_ratio.num;
    cam_h *= img_aspect_ratio.den;
  } else {
    cam_w = parseInt(CANVAS_W/cols_nr);
    cam_w = parseInt(cam_w/img_aspect_ratio.num);
    cam_h = cam_w*img_aspect_ratio.den;
    cam_w *= img_aspect_ratio.num;
  }
  }

  this.win_w = cam_w + BorderLeft + BorderRight;
  this.win_h = cam_h + NAME_DIV_H + BorderTop + BorderBottom;

  this.all_cams_width = this.win_w * cols_nr;
  this.offsetX = parseInt((CANVAS_W - this.all_cams_width)/2);  
  this.all_cams_height = this.win_h * rows_nr;
  this.offsetY = parseInt((CANVAS_H - this.all_cams_height)/2);  

  this.cam_w = cam_w; 
  this.cam_h = cam_h;
} // calc_win_geo()


function calc_win_left(win_geo, col) {
   var _left = parseInt(col*win_geo.win_w + win_geo.offsetX);
   return _left;
}

function calc_win_top(win_geo, row) {
   var _top = parseInt( row*win_geo.win_h + win_geo.offsetY );
   return _top; 
}


function change_fs_win_geo(fs_win) {
   var win_geo = new calc_win_geo(CamsAspectRatio, 1, 1);
   var fs_win_div_jq = $(fs_win);
   fs_win_div_jq.css('top',  calc_win_top (win_geo, 0));
   fs_win_div_jq.css('left', calc_win_left(win_geo, 0));
   fs_win_div_jq.width(win_geo.win_w);
   fs_win_div_jq.height(win_geo.win_h);
   if ( GECKO ) {
      $('img',fs_win_div_jq).width(win_geo.cam_w).height(win_geo.cam_h)
         // .attr('alt',win_geo.cam_w + 'x' + win_geo.cam_h);
   } else if (MSIE) {
      $('object',fs_win_div_jq).width(win_geo.cam_w).height(win_geo.cam_h)
         // .text(win_geo.cam_w + 'x' + win_geo.cam_h)
   }

} // change_fs_win_geo()

function change_wins_geo() {
   var win_geo = new calc_win_geo(CamsAspectRatio, COLS_NR, ROWS_NR);
   var i,tmp_div;
   for (i=WIN_DIVS.length-1; i>=0; i--) {
      tmp_div=$(WIN_DIVS[i]);
      tmp_div.css('top',  calc_win_top (win_geo, parseInt(i/COLS_NR)));
      tmp_div.css('left', calc_win_left(win_geo, parseInt(i%COLS_NR)));
      tmp_div.width(win_geo.win_w);
      tmp_div.height(win_geo.win_h);
      if ( GECKO ) {
         $('img',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h)
          // attr('alt',win_geo.cam_w + 'x' + win_geo.cam_h);
      } else if (MSIE) {
         $('object',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h)
            // .text(win_geo.cam_w + 'x' + win_geo.cam_h)
      } else {
         $('applet',tmp_div).width(win_geo.cam_w).height(win_geo.cam_h)
      }
   } // for(allwin)
} // change_wins_geo()

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

  var win_geo = new calc_win_geo(CamsAspectRatio, COLS_NR, ROWS_NR);

  // alert('[ ' + CANVAS_W + 'x' + CANVAS_H + ' ] [ ' + cam_w + 'x' + cam_h + ' ]');
  var row=0;
  var col=0;
  var win_nr=0;
  var top=0;
  var left=0;
  var win_div;
  for (row=0;row<ROWS_NR;row++)
  {
     top = calc_win_top(win_geo, row);

     for (col=0;col<COLS_NR;col++)
     {
        left = calc_win_left(win_geo, col);
        win_div = $('<div id="win'+win_nr+'" name="win" class="win" ' + 
        'style="top:'+top+'px; left:'+left+'px; '+
        ' width:'+win_geo.win_w+'px; height:'+win_geo.win_h+'px;'+
        ' z-index=-'+win_nr+';'+
        '"></div>');
        win_div.appendTo(CANVAS)
        if (  WINS_DEF[win_nr] != undefined )
        {
           if (PrintCamNames) {
             $('<div style="vertical-align:bottom; background-color:#666699;'+
             ' padding:0px; margin:0px; overflow:hidden; border:0px;'+
             ' height:'+NAME_DIV_H+'px;"><div style="'+
             'padding-left:8px; padding-top:2px; padding-bottom:2px; padding-right:2px;'+
             ' color:White; font-size:14px; font-weight: bold;">'+
             WINS_DEF[win_nr].cam.name+
             '<\/div><\/div>').appendTo(win_div);
           }
           brout(win_nr, win_div, win_geo);
        }
        // $('<label></label>').appendTo(win_div).text(get_geo_str(win_div));
        win_nr++;
     }
  }

  WIN_DIVS = $('div.win');

  $('#dialog').jqm({
      overlay: 90,
      onShow: function(h) {
         /* callback executed when a trigger click. Show notice */
         h.w.css('opacity',0.9).slideDown('fast'); 
      },
      onHide: function(h) {
        /* callback executed on window hide. Hide notice, overlay. */
        h.w.slideUp('fast',function() { if(h.o) h.o.remove(); }); } 
      });
});


