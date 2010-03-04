<?php
if ( isset($timemode) && $timemode == 1) {
   $timebegin = sprintf('20%02s-%02u-%02u %02u:%02u:00',$year_array[$year1],$month1,$day1,$hour1,$minute_array[$minute1]);
   $timeend   = sprintf('20%02s-%02u-%02u %02u:%02u:59',$year_array[$year2],$month2,$day2,$hour2,$minute_array[$minute2]);
} else {
   $timebegin = sprintf('20%02s-%02u-%02u 00:00:00',$year_array[$year1],$month1,$day1);
   $timeend   = sprintf('20%02s-%02u-%02u 23:59:59',$year_array[$year2],$month2,$day2);
}

$_cams_csv   = implode(',', $cams);

$all_continuous_events = array(12,23,32);
$query_continuous_events    = array_intersect($all_continuous_events,  $events);
$query_noncontinuous_events = array_diff($events, $all_continuous_events);

$events_where = '';
if ( isset($timemode) && $timemode == 1) {
   if ( count($query_continuous_events) > 0 ) {
      $qce_cvs = implode(',', $query_continuous_events);
      $events_where = <<<_EOL_
( EVT_ID in ($qce_cvs) and
   (
      (DT1 between '$timebegin' and '$timeend')
         or
      (DT2 between '$timebegin' and '$timeend')
   )
)
_EOL_;
   }
   if ( count($query_noncontinuous_events) > 0 ) {
      if (!empty($events_where))
         $events_where .= "\nor\n";
      $qnce_cvs = implode(',', $query_noncontinuous_events);
      $events_where .= "(EVT_ID in ($qnce_cvs) and (DT1 between '$timebegin' and '$timeend'))";
   }
} else {
   $min1=&$minute_array[$minute1];
   $min2=&$minute_array[$minute2];
   $time_in_day_begin = sprintf('%02u:%02u:00',$hour1,$min1);
   $time_in_day_end   = sprintf('%02u:%02u:59',$hour2,$min2);
   $dw_csv = implode(',', $dayofweek);
   if ( count($query_continuous_events) > 0 ) {
      $qce_cvs = implode(',', $query_continuous_events);
      $events_where = <<<_EOL_
( EVT_ID in ($qce_cvs) and
   (
      ( DT1 between '$timebegin' and '$timeend' )
         or
      ( DT2 between '$timebegin' and '$timeend' )
   )
   and ( weekday(DT1) in ($dw_csv) or weekday(DT2) in ($dw_csv) )
   and (
      ( time(DT1) between '$time_in_day_begin' and '$time_in_day_end' )
         or
      ( time(DT2) between '$time_in_day_begin' and '$time_in_day_end' )
   )
)
_EOL_;
   }

   if ( count($query_noncontinuous_events) > 0 ) {
      if (!empty($events_where))
         $events_where .= "\nor\n";
      $qnce_cvs = implode(',', $query_noncontinuous_events);
      $events_where .= <<<_EOL_
( EVT_ID in ($qnce_cvs)
   and ( DT1 between '$timebegin' and '$timeend' )
   and ( weekday(DT1) in ($dw_csv) )
   and ( (time(DT1) between '$time_in_day_begin' and '$time_in_day_end') )
)
_EOL_;
   }
}

$query = <<<_EOL_
select UNIX_TIMESTAMP(DT1) as UDT1, UNIX_TIMESTAMP(DT2) as UDT2,
CAM_NR,EVT_ID,SER_NR,FILESZ_KB,FRAMES,U16_1,U16_2,EVT_CONT from EVENTS
where (CAM_NR in (0, $_cams_csv))
and $events_where
order by DT1
_EOL_;
?>
