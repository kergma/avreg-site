<?php

$pageTitle = 'strRunQuery';
$pageBgColor = '#D0DCE0';
$body_onload='swtch_timemode();';
require ('../head.inc.php');
DENY($arch_status);
require ('../lib/my_conn.inc.php');
?>

<script type="text/javascript" language="javascript">
<!--

var PlayTimer = null;
var play_direction=0;

function swtch_timemode() {
     var tm_radio = ie?
                 document.all['timemode']:
                 document.getElementById('timemode');
    var weekday_sel = ie?
                 document.all['dayofweek[]']:
                 document.getElementById('dayofweek[]');
    if (tm_radio.checked) {
      if (!weekday_sel.disabled) 
         weekday_sel.disabled=true;
    } else {
       if (weekday_sel.disabled) 
         weekday_sel.disabled=false;     
    }
}

function TimeModeHelp()
{
	alert("<?php echo $strTimeModeHelp; ?>");
}

function TimeModeHelp2()
{
	alert("<?php echo $strTimeModeHelp2; ?>");
}


function CamChoiseHelp()
{
	alert("<?php echo $strCamChoiseHelp; ?>");
}

function FilterHelp()
{
	alert("<?php echo $strFilterHelp; ?>");
}

function ActionHelp()
{
	alert("<?php echo $strActionHelp; ?>");
}

function get_links_array()
{
   var img_link_array = window.parent.frames['result'].document.links;
   links_count = img_link_array.length;

   if ( links_count == 0  )
      alert ('<?php echo $strNotSavedPict; ?>');
  
   return links_count;
}

function img_scroll(new_pos,old_pos)
{
    var img=window.parent.frames['result'].document.images[new_pos];
    if (img==null)
       return;
    window.parent.frames['result'].onBody(img);
}


function set_first_img()
{
    var img_cnt = get_links_array();
    var img_cursor = window.parent.frames['result'].img_cursor;
 
    if (img_cursor<0)
       return false;
	if ( img_cnt > 0 )
	{
       img_scroll (0,img_cursor);
       window.parent.frames['result'].mark_row(0);
    }
}

function set_last_img()
{
    var img_cnt = get_links_array();
    var img_cursor = window.parent.frames['result'].img_cursor;
 
    if (img_cursor<0)
       return false;
	if ( img_cnt > 0 )
	{
       img_scroll (img_cnt-1,img_cursor);
       window.parent.frames['result'].mark_row(img_cnt-1);
    }
}


function _jump_to_pos(step)
{
	var pos;
	var img_cursor = window.parent.frames['result'].img_cursor;
    if (img_cursor<0)
       return false;
    var img_cnt = get_links_array();
	if ( img_cnt <= 0 )
      return false;
	pos = img_cursor + step;
	if ( pos >= 0 && pos < img_cnt ) {
        img_scroll (pos,img_cursor);
		window.parent.frames['result'].mark_row(pos);
        return true;
     } else {
       if (step < 0)
          alert ('<?php echo $strMaxPict; ?>');
        else
          alert ('<?php echo $strMaxPict; ?>');
       return false;
     }
}

function jump_to_pos(step)
{
  _jump_to_pos(step);
}

function do_play(direction)
{
    _direction = parseInt(direction);
    // alert('do_play('+_direction+')');
    if (_direction == 0)
       return;

    if (PlayTimer) {
      clearTimeout(PlayTimer);
      PlayTimer = null;   
    }
    if ( ! _jump_to_pos(_direction) )
       playlist(0);
}

function playlist(direction) {
 
   if (direction == 0) {
     // stop
     if ( PlayTimer  ) { 
       clearTimeout(PlayTimer);
       PlayTimer = null;
     }
     play_direction=0;     
     return;
   }
   play_direction = direction;
   window.parent.frames['view'].obj_loaded(null);
}

// -->
</script>

<?php
function getNearestUIntVal($val_array, $val){
	$delta = 999999;
	$ret = 0;
	$i = 0;
	reset($val_array);
	foreach ($val_array as $_val) {
		$tt = abs ( $val - $_val );
		if ( $delta >  $tt) {
			$ret = $_val;
			$delta = $tt;
		}
		$i++;
	}
	return $ret;
}

$tm2 = localtime();
$tm1 = localtime (strtotime ('-1 hours'));
$min1 = $minute_array[0];
$min2 = $minute_array[count($minute_array) - 1];
$query = 'SELECT c1.CAM_NR, c1.VALUE as work, c2.VALUE as text_left '.
		 'FROM CAMERAS c1 LEFT OUTER JOIN CAMERAS c2 '.
		'ON (c1.BIND_MAC=c2.BIND_MAC AND c1.CAM_NR = c2.CAM_NR AND c2.PARAM=\'text_left\') '.
		 'WHERE c1.BIND_MAC=\'local\' AND c1.CAM_NR>0 AND c1.PARAM = \'work\' '.
		 'ORDER BY c1.CAM_NR';
$result = mysql_query($query) or die("Query failed");
$num_rows = mysql_num_rows($result);
if ( $num_rows > 0 ) {
	$conf_cams = '';
	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
	{
		if ( empty($row['text_left']) ) 
			$conf_cams .= 'cam ' .$row['CAM_NR'] .',';
		else
			$conf_cams .= $row['text_left'].'('.$row['CAM_NR'] .')'.',';
	}
	
	$conf_cams_array = explode(',', substr($conf_cams,0,strlen($conf_cams)-1));
} else {
	print '<p><b>' . $strNotCamsDef2 . '</b></p>' . "\n";

	require ('../foot.inc.php');
	require ('../lib/my_close.inc.php');

	exit;
}

if (isset($_COOKIE))
{
  if (isset($_COOKIE['cams']))
     $cams_sel = str_replace('-' ,',', $_COOKIE['cams'][0]);
  else
     $cams_sel = '0,1,2,3';

  if (isset($_COOKIE['filter']))
     $filter_sel = str_replace('-' ,',', $_COOKIE['filter'][0]);
  else
     $filter_sel = implode(',', array_keys($env_id_ar));

  if (isset($_COOKIE['scale'])) {
     $_i = $_COOKIE['scale'];
     settype($_i,'int');
     $scale_sel = $scale_array[$_i];
  } else
     $scale_sel = '';
  
  if (isset($_COOKIE['row_max'])) {
    $_i = $_COOKIE['row_max'];
    settype($_i,'int');
    $row_max_sel = $_i;
  } else
    $row_max_sel=100;
    
  if (isset($_COOKIE['play_tio'])) {
    $_i = $_COOKIE['play_tio'];
    settype($_i,'int');
    $play_tio_sel = $play_tio_ar[$_i];
  } else
    $play_tio_sel=$play_tio_ar[2];

    $embed_video_sel='';
    if (isset($_COOKIE['embed_video'])) {
      $_i = $_COOKIE['embed_video'];
      if ( settype($_i,'int') && $_i>0)
        $embed_video_sel=' checked ';
    }
}
     
?>

<form action="<?php echo $conf['prefix']; ?>/offline/result.php" method="POST" target="result" onsubmit="playlist(0);">
<table cellspacing="0" border="1" cellpadding="3">
<thead>
<?php print '<tr bgcolor="'.$header_color.'">'."\n"; ?>
	<th class="query" valign="bottom"><?php echo $left_tune; ?>&nbsp;<a href="javascript:void(0);" onclick="CamChoiseHelp();"><sup>help</sup></a></th>
	<?php
	print '<th class="query" valign="bottom">'.$strTimeMode.'&nbsp;<a href="javascript:void(0);" onclick="TimeModeHelp();"><sup>help</sup></a></th>'."\n";
	?>
	<th class="query" valign="bottom"><?php echo $strYear; ?></th>
	<th class="query" valign="bottom"><?php echo $strMonth; ?></th>
	<th class="query" valign="bottom"><?php echo $strDay; ?></th>
	<th class="query" valign="bottom"><?php echo $strDayOfWeek; ?>&nbsp;<a href="javascript:void(0);" onclick="TimeModeHelp2();"><sup>help</sup></a></th>
	<th class="query" valign="bottom"><?php echo $strHour; ?></th>
	<th class="query" valign="bottom"><?php echo $strMinute; ?></th>
	<th class="query" valign="bottom"><?php echo $strFilter; ?>&nbsp;<a href="javascript:void(0);" onclick="FilterHelp();"><sup>help</sup></a></th>
	<th class="query" valign="bottom"><?php echo $strOptions; ?></th>
	<th class="query" valign="bottom"><?php echo $strAction; ?>&nbsp;<a href="javascript:void(0);" onclick="ActionHelp();"><sup>help</sup></a></th>
	<th  class="query"  valign="bottom"><a href="<?php echo $conf['prefix']; ?>/" target="_parent"><?php echo $MainPage; ?></a></th>
</tr>
</thead>
<tbody>
<tr align="center" valign="top">
<td>
<?php print getSelectHtml('cams[]', $conf_cams_array, TRUE, 7, 1, $cams_sel, FALSE, FALSE); ?>
</td>
<td align="left">
<input type="radio" checked name="timemode" id="timemode" value="1" onclick="swtch_timemode();"><?php echo $strUnBreak; ?>
<br><br>
<input type="radio" name="timemode" value="2" onclick="swtch_timemode();"><?php echo $strBreak; ?>
</td>
<td align="left">
<?php print getSelectHtml('year1', $year_array, FALSE, 1, 0, $tm1[5]+1900, FALSE, FALSE); ?>
<br>
<?php print getSelectHtml('year2', $year_array, FALSE, 1, 0, $tm2[5]+1900, FALSE, FALSE); ?>
</td>
<td align="left">
<?php print getSelectHtml('month1', $month_array, FALSE, 1, 1, $month_array[$tm1[4]], FALSE, FALSE); ?>
<br>
<?php print getSelectHtml('month2', $month_array, FALSE, 1, 1, $month_array[$tm2[4]], FALSE, FALSE); ?>
</td>
<td align="left">
<?php print getSelectHtml('day1', $day_array, FALSE, 1, 1, $tm1[3], FALSE, FALSE); ?>
<br>
<?php print getSelectHtml('day2', $day_array, FALSE, 1, 1, $tm2[3], FALSE, FALSE); ?>
</td>
<td align="center">
<?php print getSelectHtml('dayofweek[]', $day_of_week, TRUE, 7, 0, '0,1,2,3,4,5,6', FALSE, FALSE); ?>
</td>
<td align="left">
<?php print getSelectHtml('hour1', $hour_array, FALSE, 1, 0, $tm1[2], FALSE, FALSE); ?>
<br>
<?php print getSelectHtml('hour2', $hour_array, FALSE, 1, 0, $tm2[2], FALSE, FALSE); ?>
</td>
<td align="left">
<?php print getSelectHtml('minute1', $minute_array, FALSE, 1, 0, $min1, FALSE, FALSE); ?>
<br>
<?php print getSelectHtml('minute2', $minute_array, FALSE, 1, 0, $min2, FALSE, FALSE); ?>
</td>
<td>
<?php print getSelectByAssocAr('filter[]', $env_id_ar, TRUE, 7, 1, $filter_sel, FALSE, FALSE); ?>
</td>
<td>
<?php
print $strScale.'<br>'.getSelectHtml('scale', $scale_array, FALSE, 1, 0, $scale_sel, TRUE, FALSE,$strScaleTitle);
if (false !== strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) {
print '<br><hr size="1" noshade>';
print $strEmbdedVideo.'&nbsp;&nbsp;<input type="checkbox" '.$embed_video_sel.' name="embed_video" id="embed_video" title="'.$str_embed_Title.'">'."\n";
}
?>
</td>
<td>
<br><?php print getSelectHtmlByName('row_max', $row_max_ar, FALSE, 1, 0, $row_max_sel, FALSE, FALSE,NULL, $str_row_maxTitle); ?>
<input type="submit" name="btOk" value="<?php echo $strDisplay; ?>">
<br><br>
<input type="reset" name="btClear" value="<?php echo $strReset; ?>">
</td>
<td nowrap>
&nbsp;
<a href="javascript:void(0);" onclick="set_first_img();" title="В начало списка"><img src="<?php echo $conf['prefix']; ?>/img/2leftarrow.gif" width="22" height="22" border="0"></a>
<a href="javascript:void(0);" onclick="set_last_img();" title="В конец списка"><img src="<?php echo $conf['prefix']; ?>/img/2rightarrow.gif" width="22" height="22" border="0"></a>
<br>
&nbsp;
<a href="javascript:void(0);" onclick="jump_to_pos(-1);" title="Предыдущая ссылка"><img src="<?php echo $conf['prefix']; ?>/img/player_start.gif" width="22" height="22" border="0"></a>
<a href="javascript:void(0);" onclick="jump_to_pos(1);" title="Следующая ссылка"><img src="<?php echo $conf['prefix']; ?>/img/player_end.gif" width="22" height="22" border="0"></a>
<hr size="1" noshade>
<a href="javascript:void(0);" onclick="playlist(-1);" title="Автопросмотр назад"><img src="<?php echo $conf['prefix']; ?>/img/player_playback.gif" width="22" height="22" border="0"></a>
<a href="javascript:void(0);" onclick="playlist(0);" title="Остановить"><img src="<?php echo $conf['prefix']; ?>/img/player_stop.gif" width="22" height="22" border="0"></a>
<a href="javascript:void(0);" onclick="playlist(1);" title="Автопросмотр вперед"><img src="<?php echo $conf['prefix']; ?>/img/player_play.gif" width="22" height="22" border="0"></a>
<br><?php print getSelectHtml('play_tio', $play_tio_ar, FALSE, 1, 0, $play_tio_sel, FALSE, FALSE); ?>
</td>
</tr>
</tbody>
</table>
<input type="hidden" name="page" value="0">
</form>

<?php
	require ('../foot.inc.php');
	require ('../lib/my_close.inc.php');
?>
