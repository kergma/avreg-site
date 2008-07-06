<?php
if (isset($_POST['FitToScreen']))
  print 'var FitToScreen = true;'."\n";
else
  print 'var FitToScreen = false;'."\n";

if (isset($_POST['PrintCamNames']))
  print 'var PrintCamNames = true;'."\n";
else
   print 'var PrintCamNames = false;'."\n";

if (isset($_POST['EnableReconnect']))
  print 'var EnableReconnect = 1;'."\n";
else
  print 'var EnableReconnect = 0;'."\n";

if (isset($_POST['CamsAspectRatio']))
  print 'var CamsAspectRatio = parseFloat('.$_POST['CamsAspectRatio'].");\n";
else
  print 'var CamsAspectRatio = 0; // autodetect '."\n";

print 'var WINS_DEF = new MakeArray('.$wins_nr.')'."\n";

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
 print 'WINS_DEF['.$i.']="'.$tmpstr.'";'."\n";
 $CAMS[$i] = $tmp;
}

$cnames_nr = count($camnames);
if ($cnames_nr>0) {
print 'var CAMS_NAMES = new MakeArray('.$cnames_nr.')'."\n";
for ($i=0;$i<$cnames_nr;$i++) 
  print 'CAMS_NAMES['.$i.']="'.$camnames[$i].'";'."\n";
}

// $user_info config.inc.php
print 'var ___u="'.$user_info['USER']."\"\n";
if (empty($user_info['PASSWD']) /* задан пароль */)
    print 'var ___p="empty"'."\n"; // нужно чтобы AMC не запрашивал пароль при пустом пароле
else
    print 'var ___p="'.$_SERVER["PHP_AUTH_PW"]."\"\n";

print 'var ___abenc="'.base64_encode($user_info['USER'].':'.$_SERVER["PHP_AUTH_PW"])."\"\n";

switch ($mon_type)
{
  case 'ONECAM':
    print 'var ROWS_NR=1;'."\n";
    print 'var COLS_NR=1;'."\n";
    break;
  case 'QUAD_4_4':
    print 'var ROWS_NR=2;'."\n";
    print 'var COLS_NR=2;'."\n";
    break;
  case 'QUAD_9_9':
    print 'var ROWS_NR=3;'."\n";
    print 'var COLS_NR=3;'."\n";
    break;
  case 'QUAD_16_16':
    print 'var ROWS_NR=4;'."\n";
    print 'var COLS_NR=4;'."\n";
    break;
  case 'QUAD_25_25':
    print 'var ROWS_NR=5;'."\n";
    print 'var COLS_NR=5;'."\n";
    break;
  case 'POLY_3_2':
    print 'var ROWS_NR=2;'."\n";
    print 'var COLS_NR=3;'."\n";
    break;
  case 'POLY_4_2':
    print 'var ROWS_NR=2;'."\n";
    print 'var COLS_NR=4;'."\n";
    break;

  case 'POLY_4_3':
    print 'var ROWS_NR=3;'."\n";
    print 'var COLS_NR=4;'."\n";
    break;

  default:
    MYDIE("unknown mon_type=$mon_type",__FILE__,__LINE__);
}
?>
