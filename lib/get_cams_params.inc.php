<?php
if ( isset($GCP_cams_list) && empty($GCP_cams_list))
   die('not set cam list');
if (!isset($GCP_query_param_list) || !is_array($GCP_query_param_list))
   die('not set params list');

$GCP_def_pars = array();
$GCP_def_pars_nr=0;
$GCP_cams_params = array();
$GCP_cams_nr=0;

$GCP_SQL='';
$GCP_sql_in_par=NULL;
$GCP_sql_cams='';

$_sip=ip2long($sip);
require_once ($params_module_name);

/* build default in progs params */

for ($GCP_i=0;$GCP_i<$PARAMS_NR;$GCP_i++)
{
   $GCP_parname=$PARAMS[$GCP_i]['name'];
   if ( !in_array($GCP_parname, $GCP_query_param_list) ) 
      continue;
   $GCP_def_pars[$GCP_parname] = $PARAMS[$GCP_i]['def_val'];
   if ($GCP_sql_in_par===NULL)
      $GCP_sql_in_par = '\''.$GCP_parname.'\'';
   else
      $GCP_sql_in_par.= ', \''.$GCP_parname.'\'';
}



$result = $adb->get_cam_params($GCP_cams_list, $GCP_sql_in_par);
foreach ( $result as $row )
{
   $__cam_nr = intval($row['CAM_NR']);
   if ($__cam_nr === 0 )
      $GCP_def_pars[$row['PARAM']] = $row['VALUE'];
   else
      $GCP_cams_params[$__cam_nr][$row['PARAM']] = $row['VALUE'];
}
$result=NULL;



$GCP_cams_list=array_keys($GCP_cams_params);
$GCP_cams_nr=count($GCP_cams_list);
if ($GCP_cams_nr)
{
   reset($GCP_cams_list);
   foreach ($GCP_cams_list as $__cam_nr)
   {
      reset($GCP_def_pars);
      while (list($GCP_parname, $GCP_defval) = each($GCP_def_pars))
      {
         if (!array_key_exists($GCP_parname, $GCP_cams_params[$__cam_nr]))
            $GCP_cams_params[$__cam_nr][$GCP_parname] = $GCP_defval;
      }
   }
}
/*
echo "<pre style='text-align: left;'>\n";
var_dump($GCP_def_pars);
var_dump($GCP_cams_params);
echo "</pre>\n";
*/
?>
