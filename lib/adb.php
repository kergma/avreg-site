<?php 

require_once '/usr/share/php/DB.php';

//$adb = new Adb(array('user' => 'moonion', 'password' => 'B0nxgsGrdguSjMxv', 'database' => 'avreg_test'));

$adb = new Adb(array('user' => 'moonion', 'password' => 'bt7J2Y9xKhmbm2lM', 'database' => 'avreg_test', 'dbtype' =>'pgsql'));





class Adb {
	
	private 
		$_database = '',
		$_user = '',
		$_password = '',
		$_dbtype = 'mysql',
		$_host = 'localhost',
		$_db = false;
	
	public function __construct($param) {
		$this->_database = $param['database'];
		$this->_user = $param['user'];
		$this->_password = $param['password'];
		if (isset($param['dbtype']) && !empty($param['dbtype'])) {
			$this->_dbtype = $param['dbtype'];
		}
		if (isset($param['host']) && !empty($param['host'])) {
			$this->_host = $param['host'];
		}
		
		$dsn = "{$this->_dbtype}://{$this->_user }:{$this->_password}@{$this->_host}/{$this->_database}";
		$this->_db = DB::connect($dsn,true);	
		if (PEAR::isError($this->_db)) {
			return false;
		}
		$this->_db->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
		return true;
	}
	

	public function gallery_get_event($param) {
		$events = array();
 	
    		
	    $query = "SELECT ".$this->_date_format('DT1').", DT1, EVT_CONT, ALT2, ALT1, CAM_NR, FILESZ_KB, EVT_ID, ".$this->_timediff('DT1', 'DT2').", DT2";
	    $query .= ' FROM EVENTS';
	    $query .= ' WHERE EVT_ID in ('. implode(",", $param['events']) .')';
	    $query .= ' AND EVENTS.CAM_NR in ('. implode(",", $param['cameras']).')';
	    	

		    	
	    if (isset($param['date'][0])) {
	    	
	    	$query .= ' AND '.$this->_date_part('year', 'DT1').'= '.$param['date'][0];
	    }
    	if (isset($param['date'][1])) {
    		$query .= ' AND '.$this->_date_part('month', 'DT1').'= '.$param['date'][1];
	    }
    	if (isset($param['date'][2])) {
    		$query .= ' AND '.$this->_date_part('day', 'DT1').'= '.$param['date'][2];
	    }
    	if (isset($param['date'][3])) {
    		$query .= ' AND '.$this->_date_part('hour', 'DT1').'= '.$param['date'][3];
		}
	    	
	    // сортировать по дате, от текущей позиции с лимитом заданный в конфиге
	    $query .= ' ORDER BY DT1 ASC LIMIT '.$param['limit']. ' OFFSET '.$param['offset'];
	    $res = $this->_db->query($query);
		while ($res->fetchInto($line)) {
			$line[6] = filesizeHuman($line[6]);
    		if (in_array((int)$line[7], array(15,16,17,18,19,20,21))) {
    			$line[7] = 'image';
    		} else if ((int)$line[7] == 23 ) {
    			$line[7] = 'video';
    		} else if ((int)$line[7] == 32 ) {
    			$line[7] = 'audio';
    		}
    		// формирование уникального индекса, для работы кеша в браузере пользователя
    		$events[str_replace(array('/', '.'),'_',$line[5].'_'.$line[2])] = $line;
			
		}
		
		return $events;
	}
	
	public function gallery_get_last_event_date($param = array()) {
		$event = '1970-01-01 00:00:00';
		
		$query = "SELECT DT1";
    	$query .= ' FROM EVENTS';
    	// только изображения
    	$query .= ' WHERE EVT_ID in (15,16,17,18,19,20,21,23,32)';
    	if (isset($param['cameras'])) {
    		$query .= ' AND EVENTS.CAM_NR in ('. implode(",", $param['cameras']).')';
    	}
    	// групировать и сортировать по дате
    	$query .= ' ORDER BY DT1 DESC LIMIT 1';
		$res = $this->_db->query($query);
		if ($res->fetchInto($line)){
			$event = $line[0];
		}
		
		return $event;
	}
	
	public function gallery_get_last_tree_event_data($param=array()){
		$event = '1970-01-01 00:00:00';
		
		$query = "SELECT LAST_UPDATE";
    	$query .= ' FROM TREE_EVENTS';
    	if (isset($param['cameras'])) {
	    	$query .= ' WHERE ';
	    	$query .= ' TREE_EVENTS.CAM_NR in ('. implode(",", $param['cameras']).')';
    	}
    	$query .= ' ORDER BY LAST_UPDATE DESC LIMIT 1';
		$res = $this->_db->query($query);
		if ($res->fetchInto($line)){
			$event = $line[0];
		}	
		return $event;
	}
	
	public function gallery_get_tree_events($param){
		$tree_events_result = array();
		

		$query = "SELECT *";
    	$query .= ' FROM TREE_EVENTS';
    	$query .= ' WHERE TREE_EVENTS.CAM_NR in ('. implode(",", $param['cameras']).')';
    	$query .= ' ORDER BY '.$this->_date_part('year', 'LAST_UPDATE').' DESC, LAST_UPDATE ASC';
    	$res = $this->_db->query($query);
    	while ($res->fetchInto($v, DB_FETCHMODE_ASSOC)){
    		$date = $v[$this->_key('BYHOUR')];
    		if (!isset($tree_events_result[$date])) {
    			$tree_events_result[$date] = array(
    				'date' => $date,
    			);
	    	}
    		$tree_events_result[$date]['image_'.$v[$this->_key('CAM_NR')].'_count'] = $v[$this->_key('IMAGE_COUNT')];
    		$tree_events_result[$date]['image_'.$v[$this->_key('CAM_NR')].'_size'] = $v[$this->_key('IMAGE_SIZE')];
    		$tree_events_result[$date]['video_'.$v[$this->_key('CAM_NR')].'_count'] = $v[$this->_key('VIDEO_COUNT')];
    		$tree_events_result[$date]['video_'.$v[$this->_key('CAM_NR')].'_size'] = $v[$this->_key('VIDEO_SIZE')];
    		$tree_events_result[$date]['audio_'.$v[$this->_key('CAM_NR')].'_count'] = $v[$this->_key('AUDIO_COUNT')];
    		$tree_events_result[$date]['audio_'.$v[$this->_key('CAM_NR')].'_size'] = $v[$this->_key('AUDIO_SIZE')];
		}	
		return $tree_events_result;
	}
	
	
	public function gallery_update_tree_events($start, $end, $cameras) {
		$query = "SELECT *";
    	$query .= " FROM EVENTS";
    	$query .= ' WHERE EVT_ID in (15,16,17,18,19,20,21,23,32)';
    	if ($start) {
    		$tstart = date('Y-m-d H:00:00',strtotime($start));
    		$query .= " AND DT1 >= '".$tstart."'";
    	}
    	if ($end) {
    		$tend = date('Y-m-d H:59:59',strtotime($end));
    		$query .= " AND DT1 <= '".$tend."'";
    	}
    	if ($cameras) {
    		$query .= ' AND CAM_NR in ('.$cameras.')';
    	}
    	$query .= ' ORDER BY DT1 ASC';
    	
    	$res = $this->_db->query($query);
    	
    	 
    	
    	$tree_events = array();
    	while ($res->fetchInto($line, DB_FETCHMODE_ASSOC)) {
    		$date = date('Y_m_d_H',strtotime($line[$this->_key('DT1')]));
    		$key = $date.'_'.$line[$this->_key('CAM_NR')];
    		if (!isset($tree_events[$key])) {
    			$tree_events[$key] = array (
    				'DATE' => $date,
    				'CAM_NR' => $line[$this->_key('CAM_NR')],
    				'IMAGE_COUNT' => 0,
   					'IMAGE_SIZE' => 0,
    				'VIDEO_COUNT' => 0,
   					'VIDEO_SIZE' => 0,
    				'AUDIO_COUNT' => 0,
   					'AUDIO_SIZE' => 0,
    				'LAST_UPDATE' => $line[$this->_key('DT1')],
    			);
    		}
    		
    		if (in_array( $line[$this->_key('EVT_ID')], array(15,16,17,18,19,20,21))) {
    			$tree_events[$key]['IMAGE_COUNT']++;
    			$tree_events[$key]['IMAGE_SIZE'] += $line[$this->_key('FILESZ_KB')];
    		} else if (in_array( $line[$this->_key('EVT_ID')], array(23))) {
    			$tree_events[$key]['VIDEO_COUNT']++;
    			$tree_events[$key]['VIDEO_SIZE'] += $line[$this->_key('FILESZ_KB')];
    		} else if (in_array( $line[$this->_key('EVT_ID')], array(32))) {
    			$tree_events[$key]['AUDIO_COUNT']++;
    			$tree_events[$key]['AUDIO_SIZE'] += $line[$this->_key('FILESZ_KB')];
    		}
    		$tree_events[$key]['LAST_UPDATE']=$line[$this->_key('DT1')];
    	}
    	$query = 'DELETE FROM TREE_EVENTS';
    	$query .= ' WHERE 1=1';
    	
    	if ($start) {
    		$query .= " AND BYHOUR >= '".date('Y_m_d_H',strtotime($start))."'";
    	}
    	if ($end) {
    		$query .= " AND BYHOUR <= '".date('Y_m_d_H',strtotime($end))."'";
    	}
    	if ($cameras) {
    		$query .= ' AND CAM_NR in ('.$cameras.')';
    	}
    	
    	$this->_db->query($query);
    	
    	foreach ($tree_events as $row) {
    		
    		$query = 'INSERT INTO TREE_EVENTS ';
    		$query .= '(BYHOUR, CAM_NR, IMAGE_COUNT, IMAGE_SIZE, VIDEO_COUNT, VIDEO_SIZE, AUDIO_COUNT, AUDIO_SIZE, LAST_UPDATE)';
    		$query .=" VALUES ('".$row['DATE']."',".$row['CAM_NR'].','.$row['IMAGE_COUNT'].','.$row['IMAGE_SIZE'].','.$row['VIDEO_COUNT'].','.$row['VIDEO_SIZE'].','.$row['AUDIO_COUNT'].','.$row['AUDIO_SIZE'].",'".$row['LAST_UPDATE']."')";
			$this->_db->query($query);
    	}
	}
	
	
	private function _key($str){
		if ($this->_dbtype == 'pgsql') {
			return strtolower($str);
		}
		return $str;
	}
	
	private function _date_part($type, $value){
		if ($this->_dbtype == 'pgsql') {
			switch ($type) {
				case 'year':
					$str = "date_part('year', %%)";
				break;
				case 'month':
					$str = "date_part('month', %%)";
				break;
				case 'day':
					$str = "date_part('day', %%)";
				break;
				case 'hour':
					$str = "date_part('hour', %%)";
				break;
			}
		} else {
		
			switch ($type) {
				case 'year':
					$str = 'YEAR(%%)';
				break;
				case 'month':
					$str = 'MONTH(%%)';
				break;
				case 'day':
					$str = 'DAYOFMONTH(%%)';
				break;
				case 'hour':
					$str = 'HOUR(%%)';
				break;
				
				
			}
		}
		$str = str_replace('%%', $value,$str);
		
		return $str;
	}
	public function _date_format($value) {
		if ($this->_dbtype == 'pgsql') {
			$str = "to_char(%%, 'yyyy_mm_dd_hh24')";
		} else {
			$str = "DATE_FORMAT(%%, '%Y_%m_%d_%H')";
		}
		$str = str_replace('%%', $value,$str);
		
		return $str;
		
	}

	public function _timediff($d1, $d2){
		if ($this->_dbtype == 'pgsql') {
			$str = $d1."-".$d2;
		} else {
			$str = "TIMEDIFF(".$d1." , ".$d2.")";
		}
		
		return $str;
		
	}
}



/*

$database = 'avreg_test';
$user = 'moonion';
$password = 'B0nxgsGrdguSjMxv';

$dsn = "mysql://$user:$password@localhost/$database";
$db = DB::connect($dsn,true);

if (PEAR::isError($db)) {
   
    echo 'Standard Message: ' . $db->getMessage() . "\n";
    echo 'Standard Code: ' . $db->getCode() . "\n";
    echo 'DBMS/User Message: ' . $db->getUserInfo() . "\n";
    echo 'DBMS/Debug Message: ' . $db->getDebugInfo() . "\n";
    exit;
}


$res =& $db->query("select BYHOUR from TREE_EVENTS");
 
while ($res->fetchInto($row)) {
	var_dump($row[0]);
}


$pdatabase = 'avreg_test';
$puser = 'moonion';
$ppassword = 'bt7J2Y9xKhmbm2lM';

$pdsn = "pgsql://$puser:$ppassword@localhost/$pdatabase";
$pdb = DB::connect($pdsn,true);

if (PEAR::isError($pdb)) {

    echo 'Standard Message: ' . $pdb->getMessage() . "\n";
    echo 'Standard Code: ' . $pdb->getCode() . "\n";
    echo 'DBMS/User Message: ' . $pdb->getUserInfo() . "\n";
    echo 'DBMS/Debug Message: ' . $pdb->getDebugInfo() . "\n";
    exit;
}


$pres =& $pdb->query("select BYHOUR from TREE_EVENTS");
 
while ($pres->fetchInto($prow)) {
	var_dump(trim($prow[0]));
}

*/

?>