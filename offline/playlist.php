<?php
session_start();
$pageTitle = 'PlaylistTitle';
$pageBgColor = '#D0DCE0';
$body_onload='switch_timemode();';
$lang_file = '_offline.php';
require ('../head.inc.php');
DENY($arch_status);
require ('../lib/my_conn.inc.php');
?>

<script type="text/javascript" language="javascript">
<!--

var ie = document.all;
var t = null;
var do_wait = false;

function switch_timemode() {
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

function on_submit()
{
   if ( do_wait )
		return false;
   var btsubmit = ie? document.all['btSubmit']: document.getElementById('btSubmit');
   var btclr = ie? document.all['btClear']: document.getElementById('btClear');
   btsubmit.value='<?php echo $strWait; ?> ...';
   btclr.disabled = true;
   t = setTimeout("window.location.reload()", 3000);
   do_wait = true;
   return true;
}

function TimeModeHelp() {
	alert("<?php echo $strTimeModeHelp; ?>");
}
function TimeModeHelp2() {
	alert("<?php echo $strTimeModeHelp2; ?>");
}
function CamChoiseHelp() {
	alert("<?php echo $strCamChoiseHelp; ?>");
}
function FilterHelp() {
	alert("<?php echo $strFilterHelp; ?>");
}
function ActionHelp() {
	alert("<?php echo $strActionHelp; ?>");
}
function OptionHelp() {
	alert("<?php echo $strOptionHelp; ?>");
}
// -->
</script>

<?php

if (empty($GCP_cams_list)) 
   $camsnr_cond = 'c1.CAM_NR>0';
else
   $camsnr_cond = 'c1.CAM_NR IN (' . $GCP_cams_list . ')';
$query = 'SELECT c1.CAM_NR, c1.VALUE as work, c2.VALUE as text_left '.
	'FROM CAMERAS c1 LEFT OUTER JOIN CAMERAS c2 '.
	'ON (c1.BIND_MAC=c2.BIND_MAC AND c1.CAM_NR = c2.CAM_NR AND c2.PARAM=\'text_left\') '.
	'WHERE c1.BIND_MAC=\'local\' AND '.$camsnr_cond.' AND c1.PARAM = \'work\' '.
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

/* presets */
$range_checked='checked';
$intervals_checked='';
$xspf_checked = 'checked';
$m3u_checked  = '';

// tohtml($_COOKIE);
// tohtml($_SESSION);

if (isset($_COOKIE))
{
  if (isset($_COOKIE['avreg_cams']))
     $cams_sel = str_replace('-' ,',', $_COOKIE['avreg_cams'][0]);
  else
     $cams_sel = '0,1,2,3';

  if (isset($_COOKIE['avreg_timemode'])) {
      if ( $_COOKIE['avreg_timemode'] == '2' ) {
         $range_checked='';
         $intervals_checked='checked';
      }
  }

  if (isset($_COOKIE['avreg_pl_fmt'])) {
      if ( 0 == strcasecmp('M3U', $_COOKIE['avreg_pl_fmt']) ) {
         $m3u_checked = 'checked';
         $xspf_checked = '';
      }
  }
}

if (isset($_SESSION))
{ 
   if ( isset($_SESSION['dayofweek']) &&
        is_array($_SESSION['dayofweek']) &&
        count($_SESSION['dayofweek']) > 0 )
     $day_of_week_preset = implode(',' , $_SESSION['dayofweek']);

    $int_vals=array('timemode',
        'year1', 'month1', 'day1', 'hour1', 'minute1',
        'year2', 'month2', 'day2', 'hour2', 'minute2');
    foreach ($int_vals as &$value)
       if (isset($_SESSION[$value]))
         $GLOBALS[$value] = (int)$_SESSION[$value];
    $int_vals=array('month1', 'day1', 'month2', 'day2');
    foreach ($int_vals as &$value)
       if (isset($GLOBALS[$value]))
         $GLOBALS[$value] -= 1;
} /* session */

if (empty($day_of_week_preset))
  $day_of_week_preset = '0,1,2,3,4,5,6';


if ( !isset($year1) || !isset($year2)) {
  $tm2 = localtime(time(), true);
  $tm1 = localtime (strtotime ('-1 hours'), true);
  $year1   = array_search ($tm1['tm_year'] - 100, $year_array);
  $month1  = $tm1['tm_mon'];
  $day1    = array_search($tm1['tm_mday'],$day_array);
  $hour1   = array_search($tm1['tm_hour'],$hour_array);
  $year2   = array_search ($tm2['tm_year'] - 100, $year_array);
  $month2  = $tm2['tm_mon'];
  $day2    = array_search($tm2['tm_mday'],$day_array);
  $hour2   = array_search($tm2['tm_hour'],$hour_array);
  $minute1 = 0;
  $minute2 = count($minute_array) - 1;
}

if ( isset($_SESSION) && isset($_SESSION['error'])/* ошибка */ )
{

  if ( $_SESSION['error'] == '0' ) {
     /* нет данных */
     echo "<div class=\"warn\">\n";
     echo "<h3>$strNotSavedPict</h3>\n";
     if ( $conf['debug'] && !empty($_SESSION['sql']))
       echo $_SESSION['sql'];
  } else {
     echo "<div class=\"error\">\n";
     echo 'error: invalid field &quot;'.$_SESSION['error']."&quot;\n";
  }
  echo "</div>\n";
}

?>

<form action="<?php echo $conf['prefix']; ?>/offline/_playlist.php" method="POST" onclick="return(on_submit())">
<fieldset>
<legend><?php echo $left_tune; ?>&nbsp;<a href="javascript:void(0);" onclick="CamChoiseHelp();"><sup>help</sup></a></legend>
<table cellspacing="0" border="0" cellpadding="5">
<tr valign="top">
<td>
<?php print getSelectHtml('cams[]', $conf_cams_array, TRUE, 7, 1, $cams_sel, FALSE, FALSE); ?>
</td>
<td>
<!--
<fieldset>
<legend><?php echo $strPlFmtTitle; ?></legend>
<input type="checkbox" checked name="filter[]" value="12"><?php echo $env_id_ar[1]; ?>
<br>
<input type="checkbox" checked name="filter[]" value="2"><?php echo $env_id_ar[2]; ?>
</fieldset>
</td>
// !-->
</tr>
</table>
</fieldset>
<br>
<fieldset>
<legend><?php echo $strTimeRangeSelect; ?></legend>
<table cellspacing="0" border="0" cellpadding="5">
<?php print '<tr bgcolor="'.$header_color.'">'."\n"; ?>
	<?php
	print '<th class="query" valign="bottom">'.$strTimeMode.'&nbsp;<a href="javascript:void(0);" onclick="TimeModeHelp();"><sup>help</sup></a></th>'."\n";
	?>
	<th class="query" valign="bottom"><?php echo "$strYear / $strMonth / $strDay"; ?></th>
	<th class="query" valign="bottom"><?php echo $strDayOfWeek; ?>&nbsp;<a href="javascript:void(0);" onclick="TimeModeHelp2();"><sup>help</sup></a></th>
	<th class="query" valign="bottom"><?php echo "$strHour:$strMinute"; ?></th>
</tr>
<tr align="center" valign="top">
<td align="left">
<br>
<input type="radio" <?php echo $range_checked; ?> name="timemode" id="timemode" value="1" onclick="switch_timemode();"><?php echo $strUnBreak; ?>
<br><br>
<input type="radio" <?php echo $intervals_checked; ?> name="timemode" value="2" onclick="switch_timemode();"><?php echo $strBreak; ?>
</td>
<td align="left" nowrap>
<?php
print "$sFromDate<br />\n";
print getSelectHtml('year1', $year_array, FALSE, 1, 0, $year_array[$year1], FALSE, FALSE);
print getSelectHtml('month1', $month_array, FALSE, 1, 1, $month_array[$month1], FALSE, FALSE);
print getSelectHtml('day1', $day_array, FALSE, 1, 1, $day_array[$day1], FALSE, FALSE);
print "<br /><br />$sToDate<br />\n";
print getSelectHtml('year2', $year_array, FALSE, 1, 0, $year_array[$year2], FALSE, FALSE);
print getSelectHtml('month2', $month_array, FALSE, 1, 1, $month_array[$month2], FALSE, FALSE);
print getSelectHtml('day2', $day_array, FALSE, 1, 1, $day_array[$day2], FALSE, FALSE);
?>
</td>
<td align="center">
<?php print getSelectHtml('dayofweek[]', $day_of_week, TRUE, 7, 0, $day_of_week_preset, FALSE, FALSE); ?>
</td>
<td align="left" nowrap>
<?php
print "$sFromTime<br />\n";
print getSelectHtml('hour1', $hour_array, FALSE, 1, 0, $hour_array[$hour1], FALSE, FALSE);
print getSelectHtml('minute1', $minute_array, FALSE, 1, 0, $minute_array[$minute1], FALSE, FALSE);
print "<br /><br />$sToTime<br />\n";
print getSelectHtml('hour2', $hour_array, FALSE, 1, 0, $hour_array[$hour2], FALSE, FALSE);
print getSelectHtml('minute2', $minute_array, FALSE, 1, 0, $minute_array[$minute2], FALSE, FALSE);
?>
</td>
</tr>
</table>
</fieldset>
<br>
<fieldset>
<legend><?php echo $strPlFmtTitle; ?></legend>
<input type="radio" <?php echo $xspf_checked; ?>  name="pl_fmt" value="XSPF"><?php echo $strXSPF; ?>
<br>
<input type="radio" name="pl_fmt" <?php echo $m3u_checked; ?> value="M3U"><?php echo $strM3U; ?>
</fieldset>
<br>
<fieldset>
<input type="submit" id="btSubmit" value="<?php echo $GetPlaylistStr; ?>">
&nbsp;&nbsp;
<input type="reset" id="btClear" value="<?php echo $strReset; ?>">
&nbsp;&nbsp;
<a href="<?php echo $conf['prefix']; ?>/" target="_parent"><?php echo $MainPage; ?></a>
</fieldset>
</form>
<div class="help">
<legend><?php echo $strAdvices; ?>.</legend>
<ul>
<li><?php echo $strBrowserHandlers; ?></li>
<li><?php echo $strViewFrame2; ?></li>
</div>

<?php
require ('../foot.inc.php');
require ('../lib/my_close.inc.php');
?>
