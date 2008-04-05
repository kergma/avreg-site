<?php

if (!isset($_POST) || !isset($_POST['mon_type']) || !isset($_POST['cams']) || !is_array($_POST['cams']))
   die('you need start from /online/index.php');

$ccnt=count($_POST['cams']);
$ccnr_a=0;
for ($i=0;$i<$ccnt;$i++) 
   if (!empty($_POST['cams'][$i]))
      $ccnr_a++;
if ($ccnr_a===0) 
   die('not defined cams for view');

$mon_type=$_POST['mon_type'];
switch ($mon_type)
{
  case 'ONECAM':
    $wins_nr=1;
    break;
  case 'QUAD_4_4':
    $wins_nr=4;
    break;
  case 'POLY_3_2':
    $wins_nr=6;
    break;
  case 'POLY_4_2':
    $wins_nr=8;
    break;
  case 'QUAD_9_9':
    $wins_nr=9;
    break;
  case 'POLY_4_3':
    $wins_nr=12;
    break;
  case 'QUAD_16_16':
    $wins_nr=16;
    break;
  case 'QUAD_25_25':
    $wins_nr=25;
    break;
  case 'QUAD_36_36':
    $wins_nr=36;
    break;
  default:
    die("unknown mon_type=$mon_type");
}
$cfts = 'avreg_' . $mon_type . '_FitToScreen'; 
$cnm  = 'avreg_' . $mon_type . '_PrintCamNames';
$expired = time()+5184000;
$ca=dirname($_SERVER['SCRIPT_NAME']).'/build_mon.php';
if (isset($_POST['FitToScreen']))
   setcookie($cfts,  '1', $expired,$ca);
else
   setcookie($cfts,  '0', $expired,$ca);
if (isset($_POST['PrintCamNames']))
   setcookie($cnm,  '1', $expired,$ca);
else
   setcookie($cnm,  '0', $expired,$ca);

for ($i=0;$i<$wins_nr;$i++) 
  if (isset($_POST['cams'][$i]))
     setcookie('avreg_' . $mon_type.'_cams['.$i.']',$_POST['cams'][$i],$expired,$ca);


$pageTitle = 'WEBcam';
$body_style='margin:0; padding:0;';
require ('../head.inc.php');
if ( !isset($cams) || !is_array($cams)) 
   MYDIE('not set cams',__FILE__,__LINE__);
   
/*
print '<pre>';
var_dump($cams);
var_dump($camnames);
print '</pre>'."\n";
die();
*/

print '<script type="text/javascript" language="JavaScript1.2">'."\n";
print '<!--'."\n";
if (isset($_POST['FitToScreen']))
  print 'var FitToScreen = true;'."\n";
else
  print 'var FitToScreen = false;'."\n";

if (isset($_POST['PrintCamNames']))
  print 'var PrintCamNames = true;'."\n";
else
  print 'var PrintCamNames = false;'."\n";

print 'var WINS = new MakeArray('.$wins_nr.')'."\n";

 
$CAMS=array();
for ($i=0;$i<$wins_nr;$i++)
{
  $tmp=array();
  if (empty($cams[$i])) {
    $tmp['set']=0;
  } else {
     if (!preg_match("/^cam (\d*) on (\d*\.\d*\.\d*\.\d*|[a-zA-Z-_0-9\.]+):(\d+) \[(\d+)x(\d+)\]/i",
            $cams[$i], $matches) )
           MYDIE("preg_match($cams[$i]) failed",__FILE__,__LINE__);
    $cam_nr = $matches[1];settype($cam_nr,'int');
    $_sip = $matches[2];
    $w_port = $matches[3];settype($w_port,'int');
    $_ww=$matches[4]; settype($_ww,'int');
    $_wh=$matches[5]; settype($_wh,'int');
    $tmp['set']=1;
    $tmp['cam_nr']=$cam_nr;
    $tmp['ip']=$_sip;
    $tmp['port']=$w_port;
    $tmp['orig_w']=$_ww;
    $tmp['orig_h']=$_wh;
 }
 $tmpstr=implode('=',$tmp);
 print 'WINS['.$i.']="'.$tmpstr.'";'."\n";
 $CAMS[$i] = $tmp;
}

$cnames_nr = count($camnames);
if ($cnames_nr>0) {
print 'var CNAMES = new MakeArray('.$cnames_nr.')'."\n";
for ($i=0;$i<$cnames_nr;$i++) 
  print 'CNAMES['.$i.']="'.$camnames[$i].'";'."\n";
}  

?>

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
 
   url = 'http:\/\/'+ip+':'+ port;
}

function img_mouseover(eimg,win_nr,orig_w,orig_h) {
 
   var splice = WINS[win_nr];
   var onoff = parseInt(splice.charAt(0));
   if ( onoff == 0 )
      return;
   parse_win_info(splice);
   
   var div = eimg.parentNode;
   
 hint = '<table style="font-weight:bold;" cellspacing="0" border="0" cellpadding="1"><tbody><tr>\n' +
 '<td align="right">Камера:<\/td>\n' +
 '<td>#'+cam_nr+' ' + CNAMES[win_nr] + '<\/td>\n' +
 '<\/tr><tr>\n' +
 '<td align="right">URL:<\/td>\n' +
 '<td>'+url+'<\/td>\n' +
 '<\/tr><tr>\n' +
 '<td align="right">Размер:<\/td>\n' +
 '<td>'+orig_w+'x'+orig_h+' (исходный), ' + eimg.width+'x'+eimg.height+' (на экране)<\/td>\n' +
/*  
 '<\/tr><tr>\n' +
 '<td align="right">canvas:<\/td>\n' +
 '<td>'+canvas_w+'x'+canvas_h+'<\/td>\n' +
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

   var cdiv = img.parentNode;
   var tmp_div=null;
   
   var border_w = cdiv.offsetWidth - cdiv.clientWidth;
   var border_h = cdiv.offsetHeight - cdiv.clientHeight;

   var new_h;
   var new_w;
   var i;
   if (FS) {
     cdiv.style.width = win_div_w+'px';
     cdiv.style.height = win_div_h+'px';
     cdiv.style.left = win_div_left+'px';
     cdiv.style.top = win_div_top+'px';

     img.width=img_in_div_w;
     img.height=img_in_div_h;
     for (i=0;i<wins_cnt;i++) {
        tmp_div=win_divs[i];
        if (tmp_div==cdiv)
          continue;
        tmp_div.style.visibility='visible';
     }
     FS=false;
   } else {
      for (i=0;i<wins_cnt;i++) {
        tmp_div=win_divs[i];
        tmp_div.style.visibility='hidden';
      }

      win_div_h = cdiv.clientHeight;
      win_div_w = cdiv.clientWidth;
      win_div_left=cdiv.offsetLeft;
      win_div_top=cdiv.offsetTop;
      img_in_div_w=img.width;
      img_in_div_h=img.height;
 
      var cdiv_w_max = canvas_w - border_w;
      var cdiv_h_max = canvas_h - border_h;
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
      // alert(cdiv.style.left + ' ' + cdiv.style.top + ' ' + cdiv.offsetLeft + ' ' + cdiv.offsetTop);
      if (cdiv.offsetLeft!=0)
         cdiv.style.left='0px';
      if (cdiv.offsetTop!=0)
         cdiv.style.top='0px';
      cdiv.style.width = new_w + 'px';
      cdiv.style.height = (new_h+name_div_h) + 'px' ;
   
      /*
      alert(canvas_w + ' x ' + canvas_h +
      "\n" + img.width + ' x ' +img.height +
      "\n" + cdiv.clientWidth + ' x ' +cdiv.clientHeight+ 
      "\n" + cdiv.style.width + ' x ' +cdiv.style.height);
      */
      

      img.width=new_w;
      img.height=new_h;
      cdiv.style.visibility='visible';
 
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
'<li style="margin:15px;">сервер videoserv не запущен;<\/li>'+
'<li style="margin:15px;">камера не настроена должным образом для просмотра по сети;<\/li>'+
'<li style="margin:15px;">'+br_specific+';<\/li>'+
'<li style="margin:15px;">в другом окне браузера на Вашем компьютере уже запущен просмотр камер;<\/li>'+
'<li style="margin:15px;">настройки сетевого экрана firewall на Вашем компьютере блокируют запросы к камерам;<\/li>'+
'<i style="margin:15px;">возможно просто нужно перезапустить браузер или обновить страницу;<\/li>'+
'<li style="margin:15px;">ещё какая-нибудь причина которую мы пока не знаем :)<\/li>'+
'<\/ul>'+
'<br \/>'+
'<center>'+
'<input type="submit" name="Close" style="background-color:#ffa500;" value="<? echo $strClose; ?>" onclick="window.close();" \/>'+
'<\/center>'+
'<\/div><\/body>');
     help_win.document.close();
   } else {
     help_win.focus();
   }
}

function brout(win_nr, cam_w, cam_h) {
   
   // alert('win ' + win_nr + '[ ' + cam_w + 'x' + cam_h + ' ]');
   
   var splice = WINS[win_nr];

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
      document.writeln('<img src="'+url+'" id="'+id+'" name="cam" alt="' +alt+'" '+
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
	  ' CODEBASE="amc.cab" \/>');
      document.writeln('<PARAM NAME="AutoStart" VALUE=1 \/>');
	  document.writeln('<PARAM NAME="NetworkTimeout" VALUE=5000 \/>');
	  document.writeln('<PARAM NAME="StretchToFit" VALUE=1 \/>');
	  document.writeln('<PARAM NAME="DisplayMessages" VALUE=1 \/>');
	  document.writeln('<PARAM NAME="ShowToolbar" VALUE=0 \/>');
	  document.writeln('<PARAM NAME="MediaType" VALUE="mjpeg-unicast" \/>');
	  document.writeln('<PARAM NAME="MediaURL" VALUE="'+url+'" />');
      document.writeln('<br \/>'+alt);
      document.writeln('<\/OBJECT>');
	  obj=document.all[id];
	  obj.EnableContextMenu=1;
	  obj.EnableReconnect=0;
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

// -->
</script>

<div id="toolbar" style="position:absolute; height:25px; width:100%; margin:0; padding:0;background-color:#003366;overflow:hidden;">
<img src="<?echo $conf['prefix']; ?>/img/dvrlogo-134x25.png" width="134" height="25" align="left" border="0">
<table cellspacing="0" border="0" cellpadding="1" align="right">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td><p style="color:white;font-weight:bold;"><script type="text/javascript" language="JavaScript1.2">br_spec_out();</script> &nbsp;&nbsp;Если Вы не видите изображение от видеокамер нажмите <a title="HELP" onclick="not_show(); return false;"  style="cursor: pointer; color:#FF9933;font-weight:bold;">здесь</a></p></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button" value="<? echo $strClose; ?>" class="btnNormal" onClick="window.close();"></td>
    </tr>
  </tbody>
</table>
</div>
<script type="text/javascript" language="JavaScript1.2">
<!--
ch = ((ns6)?document.body.clientHeight:ietruebody().clientHeight) - 25;
document.write('<div id="canvas" style="position:absolute; top:25px; background-color:#e5e5e5; width:100%; height:' + ch + 'px; overflow:hidden; margin:0; padding:0;">')
var canvas = ie?document.all['canvas']:document.getElementById('canvas');
var canvas_w = canvas.clientWidth;
var canvas_h = canvas.clientHeight;

<?php

switch ($mon_type)
{
  case 'ONECAM':
    print 'var row_nr=1;'."\n";
    print 'var col_nr=1;'."\n";
    break;
  case 'QUAD_4_4':
    print 'var row_nr=2;'."\n";
    print 'var col_nr=2;'."\n";
    break;
  case 'QUAD_9_9':
    print 'var row_nr=3;'."\n";
    print 'var col_nr=3;'."\n";
    break;
  case 'QUAD_16_16':
    print 'var row_nr=4;'."\n";
    print 'var col_nr=4;'."\n";
    break;
  case 'QUAD_25_25':
    print 'var row_nr=5;'."\n";
    print 'var col_nr=5;'."\n";
    break;
  case 'POLY_3_2':
    print 'var row_nr=2;'."\n";
    print 'var col_nr=3;'."\n";
    break;
  case 'POLY_4_2':
    print 'var row_nr=2;'."\n";
    print 'var col_nr=4;'."\n";
    break;

  case 'POLY_4_3':
    print 'var row_nr=3;'."\n";
    print 'var col_nr=4;'."\n";
    break;

  default:
    MYDIE("unknown mon_type=$mon_type",__FILE__,__LINE__);  
}
?>
  
 // aspect ratio 4/3
 
  var name_div_h=0;
  var calc_canvas_h=canvas_h;
  if (PrintCamNames) {
     name_div_h=20;
     calc_canvas_h -= (name_div_h*row_nr);
  }

  var cam_w;
  var cam_h;
  var mul;
  if ( (canvas_w/calc_canvas_h) >= (4*col_nr)/(3*row_nr) ) {
    cam_h = parseInt(calc_canvas_h/row_nr);
    cam_h = parseInt(cam_h/3);
    cam_w = cam_h*4;
    cam_h *= 3;
  } else {
    cam_w = parseInt(canvas_w/col_nr);
    cam_w = parseInt(cam_w/4);
    cam_h = cam_w*3;
    cam_w *= 4;
  }

  if (GECKO) {
    // border out 
    cam_w-=4;
    cam_h-=3;
  }
  // alert('[ ' + canvas_w + 'x' + canvas_h + ' ] [ ' + cam_w + 'x' + cam_h + ' ]');
  var row=0;
  var col=0;
  var win_nr=0;
  var top=0;
  var left=0;
  for (row=0;row<row_nr;row++)
  {
     if (GECKO) top=row*(cam_h+name_div_h+3); else top=row*(cam_h+name_div_h);
     
     for (col=0;col<col_nr;col++)
     {
        if (GECKO) left=col*(cam_w+4); else left=col*cam_w; 
        /*
        alert('win_nr=' + win_nr + ', row=' + row + ', col=' + col + "\n" +
        '[ ' + top + ',' + left + ' ] [ ' + cam_w + 'x' + cam_h + ' ]');
        */
        
        document.writeln('<div id="win'+win_nr+'" name="win" class="win" ' + 
        'style="top:'+top+'px; left:'+left+'px; '+
        ' width:'+cam_w+'px; height:'+(cam_h+name_div_h)+'px; '+
        '" >');
        var splice = WINS[win_nr];
        var onoff = parseInt(splice.charAt(0));
        if ( onoff > 0 )
        {
           if (PrintCamNames) {
             document.writeln('<div style="vertical-align:bottom; background-color:#666699;'+
             ' padding:0px; margin:0px; overflow:hidden; border:0px;'+
             ' height:'+name_div_h+'px;">');
             document.writeln('<div style="'+
             'padding-left:8px; padding-top:2px; padding-bottom:2px; padding-right:2px;'+
             ' color:White; font-size:14px; font-weight: bold;">'+
             CNAMES[win_nr]+
             '<\/div>');
             document.writeln('<\/div>');
           }
           if (GECKO)
             brout(win_nr, cam_w, cam_h);
           else 
             brout(win_nr, cam_w-4, cam_h-3);
        }
        document.writeln('<\/div>');
        win_nr++;
     }
  }
  
  var win_divs = document.getElementsByName('win');
  var wins_cnt = win_divs.length;

// -->
</script>

<?php
require ('../foot.inc.php');
?>
