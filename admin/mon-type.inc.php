<?php

function print_poly($row_nr, $col_nr, $win_text, $win_text_array,
                    $tbl_start, $tbl_end,
                    $tr_start, $tr_end,
                    $td_start, $td_end)
{
   if (!empty($win_text))
      $text_in_win = &$win_text;
      print $tbl_start;
      for ($r=0; $r<$row_nr; $r++) {
         print $tr_start;
         for ($c=0; $c<$col_nr; $c++) {
         if (!$win_text) 
            $text_in_win = $win_text_array[ $r*$col_nr + $c];
            print $td_start . $text_in_win . $td_end;
         }
      print $tr_end;
   }
   print $tbl_end;
}

function show_mon_type ( $mon_type, $max_width, $win_text_array = array(), $win_text = '' )
{
	// print "<pre>".var_dump($win_text_array)."</pre>";

	$brdcol = '#99FFFF';
	$bgcol = '#000099';
	if (empty($max_width)) {
    	$cp =  20; $cs = 0;
    	$brd = 2;
    } else {
    	$cp = 2; $cs = 0;
    	$brd = $max_width >> 6;
		$k = (int) floor( $max_width >> 2 );
		$w =  $k << 2; ; $h = $k * 3;
		$w2 = (int) floor ( $w / 2); $h2 = (int) floor ( $h / 2);
		$w3 = (int) floor ( $w / 3); $h3 = (int) floor ( $h / 3);
		$w4 = (int) floor ( $w / 4); $h4 = (int) floor ( $h / 4);
		$w5 = (int) floor ( $w / 5); $h5 = (int) floor ( $h / 5);
	}	
	$tbl_start = '<table cellspacing="'.$cs.'" border="'.$brd.'" cellpadding="'.$cp.'" style="border-color:'.$brdcol.';">'."\n";
	$tbl_end = '</table>'."\n";
	$r_start = '<tr>'."\n";
	$r_end = '</tr>'."\n";

    if (empty($max_width))
    {
	$t_start_1 = '<td align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;';
	$t_start_2 = &$t_start_1;
	$t_start_3 = &$t_start_1;
	$t_start_4 = &$t_start_1;
	$t_start_5 = &$t_start_1;
    } else {
	$t_start_1 = '<td width="'.$w.'" height="'.$h.'" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;';
	$t_start_2 = '<td width="'.$w2.'" height="'.$h2.'" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;';
	$t_start_3 = '<td width="'.$w3.'" height="'.$h3.'" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;';
	$t_start_4 = '<td width="'.$w4.'" height="'.$h4.'" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;';
	$t_start_5 = '<td width="'.$w5.'" height="'.$h5.'" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;';
	}
	$t_end = '&nbsp;</b></font></td>'."\n";
	
	switch ( $mon_type )
	{
		case 'ONECAM':
			print $tbl_start . $r_start;
			$text_in_win = ($win_text) ? $win_text : $win_text_array[0];
			print $t_start_1 . $text_in_win . $t_end;
			print $r_end . $tbl_end;
			break;
		case 'QUAD_4_4':
                    print_poly(2, 2, $win_text, $win_text_array,
                    $tbl_start, $tbl_end,
                    $r_start, $r_end,
                    $t_start_2, $td_end);
			break;
		case 'POLY_3_2':
                    print_poly(2, 3, $win_text, $win_text_array,
                    $tbl_start, $tbl_end,
                    $r_start, $r_end,
                    $t_start_3, $td_end);
                     break;
		case 'POLY_4_2':
                    print_poly(2, 4, $win_text, $win_text_array,
                    $tbl_start, $tbl_end,
                    $r_start, $r_end,
                    $t_start_3, $td_end);
                     break;
		case 'MULTI_6_9':
			print $tbl_start;
			print $r_start;
			$text_in_win = ($win_text) ? $win_text : $win_text_array[0];
			print '<td rowspan="2" colspan="2" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
			if (!$win_text) $text_in_win = $win_text_array[1];
			print $t_start_3 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
			if (!$win_text) $text_in_win = $win_text_array[2];
			print $t_start_3 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
			if (!$win_text) $text_in_win = $win_text_array[3];
			print $t_start_3 . $text_in_win . $t_end;
			if (!$win_text) $text_in_win = $win_text_array[4];
			print $t_start_3 . $text_in_win . $t_end;
			if (!$win_text) $text_in_win = $win_text_array[5];
			print $t_start_3 . $text_in_win . $t_end;
			print $r_end;
			print $tbl_end;
			break;
		case 'MULTI_7_16':
			print $tbl_start;
			print $r_start;
			$text_in_win = ($win_text) ? $win_text : $win_text_array[0];
			print '<td width="'. ($w4 << 1) .'" colspan="2" height="'. ($h4 << 1) .'" rowspan="2" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
			if (!$win_text) $text_in_win = $win_text_array[1];
			print '<td colspan="2" rowspan="2" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
			print $r_end;
			print $r_start . $r_end;
			print $r_start;
			if (!$win_text) $text_in_win = $win_text_array[2];
			print '<td colspan="2" rowspan="2" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
			if (!$win_text) $text_in_win = $win_text_array[3];
			print $t_start_4 . $text_in_win . $t_end;
			if (!$win_text) $text_in_win = $win_text_array[4];
			print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
			if (!$win_text) $text_in_win = $win_text_array[5];
			print $t_start_4 . $text_in_win . $t_end;
			if (!$win_text) $text_in_win = $win_text_array[6];
			print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $tbl_end;
			break;
		case 'MULTI_8_16':
			print $tbl_start;
			print $r_start;
			$text_in_win = ($win_text) ? $win_text : $win_text_array[0];
			print '<td width="'. ($w4 << 1) .'" colspan="3" height="'. ($h4 << 1) .'" rowspan="3" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
			if (!$win_text) $text_in_win = $win_text_array[1];
			print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
			if (!$win_text) $text_in_win = $win_text_array[2];
			print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
			if (!$win_text) $text_in_win = $win_text_array[3];
			print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
			if (!$win_text) $text_in_win = $win_text_array[4];
			print $t_start_4 . $text_in_win . $t_end;
			if (!$win_text) $text_in_win = $win_text_array[5];
			print $t_start_4 . $text_in_win . $t_end;
			if (!$win_text) $text_in_win = $win_text_array[6];
			print $t_start_4 . $text_in_win . $t_end;
			if (!$win_text) $text_in_win = $win_text_array[7];
			print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $tbl_end;
			break;
		case 'QUAD_9_9':
                    print_poly(3, 3, $win_text, $win_text_array,
                    $tbl_start, $tbl_end,
                    $r_start, $r_end,
                    $t_start_3, $td_end);
			break;
		case 'MULTI_10_16':
			print $tbl_start;
			print $r_start;
				$text_in_win = ($win_text) ? $win_text : $win_text_array[0];
				print '<td colspan="2" height="'. ($h4 << 1) .'" rowspan="2" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
				if (!$win_text) $text_in_win = $win_text_array[1];
				print '<td colspan="2" height="'. ($h4 << 1) .'" rowspan="2" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
			print $r_end;
			print $r_start.$r_end;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[2];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[3];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[4];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[5];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[6];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[7];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[8];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[9];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $tbl_end;
			break;
		case 'POLY_4_3':
			print $tbl_start;
			print $r_start;
				$text_in_win = ($win_text) ? $win_text : $win_text_array[0];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[1];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[2];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[3];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[4];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[5];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[6];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[7];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[8];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[9];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[10];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[11];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $tbl_end;
		break;

		case 'MULTI_13_16':
			print $tbl_start;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[0];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[1];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[2];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[4];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[4];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[5];
				print '<td colspan="2" height="'. ($h4 << 1) .'" rowspan="2" align="center" valign="middle" bgcolor="'.$bgcol.'"><font color="white"><b>&nbsp;'.$text_in_win.'&nbsp;</b></font></td>'."\n";
				if (!$win_text) $text_in_win = $win_text_array[6];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[7];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[8];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $r_start;
				if (!$win_text) $text_in_win = $win_text_array[9];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[10];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[11];
				print $t_start_4 . $text_in_win . $t_end;
				if (!$win_text) $text_in_win = $win_text_array[12];
				print $t_start_4 . $text_in_win . $t_end;
			print $r_end;
			print $tbl_end;
		break;
		case 'QUAD_16_16':
                    print_poly(4, 4, $win_text, $win_text_array,
                    $tbl_start, $tbl_end,
                    $r_start, $r_end,
                    $t_start_4, $td_end);
		break;

		case 'QUAD_25_25':
                    print_poly(5, 5, $win_text, $win_text_array,
                    $tbl_start, $tbl_end,
                    $r_start, $r_end,
                    $t_start_5, $td_end);
		break;

		default:
			print '<font color="' . $GLOBALS['error_color'] . '"><p>Not Defined Monitors Type. Asc to developers.</p></font>' ."\n";	
	} //case
}
?>
