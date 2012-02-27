<?php
class Gallery {
    public 	
    	$method = '', // метод запроса
    	$result = array(); // ответ запроса
    private 
    	$cache,
    	$db = '',
    	$conf = array(); // настройки галереи
    // конструктор класса
    public function __construct($param) {
    	// получение параметров запроса
    	foreach ($param as $k=>$v) {
    		if(isset($this->$k) && !in_array($k, array('db', 'conf'))) {
    			$this->$k = $v;
    		}
    	}
		// Получение глобальных настроек сайта    	
    	global $conf;
    	$this->conf = $conf;
    	$this->cache = new Cache();
    	// Если существует запрашиваемый метод, то его выполняем с указанными параметрами
    	if (!empty($this->method) && method_exists($this, $this->method)) {
			$this->{$this->method}($param);
    	}
    }
 	// Функция получения событий  
    public function get_events($param) {
    	$events = array();
    	// если есть список камер, то выполняем запрос
    	if (isset($param['cameras'])  && !empty($param['cameras'])) {
    		$cameras = trim($param['cameras'], ',');
    		$param['cameras'] = explode(",", $cameras);
 
    		$type = explode(",", trim($param['type'], ','));
    		
	    	$query = "SELECT DATE_FORMAT(DT1, '%Y_%m_%d_%H'), DT1, EVT_CONT, U16_2, U16_1, CAM_NR, FILESZ_KB, EVT_ID";
	    	$query .= ' FROM EVENTS';
	    	// картинки
	    	$EVT_ID = array();
	    	if (in_array('image', $type)) {
	    		$EVT_ID = array_merge($EVT_ID, array(15,16,17,18,19,20,21));
	    	}
	    	// видео
    		if (in_array('video', $type)) {
    			$EVT_ID = array_merge($EVT_ID, array(23));
	    	}
	    	// аудио
    		if (in_array('audio', $type)) {
    			$EVT_ID = array_merge($EVT_ID, array(32));
	    	}
	    	$query .= ' WHERE EVT_ID in ('. implode(",", $EVT_ID) .')';
			// ТОлько с камер, что мы просили	    	
	    	$query .= ' AND EVENTS.CAM_NR in ('.$cameras.')';
	    	// Только с камер, которые разрешены пользователю
	    	global $GCP_cams_params;
	    	$cameras = implode(',',array_keys($GCP_cams_params));
	    	$query .= ' AND EVENTS.CAM_NR in ('.$cameras.')';
	    	
	    	// Только за заданный промежуток времени
	    	if ($param['tree'] !== 'all') {
		    	$date = explode('_', $param['tree']);
		    	
		    	if (isset($date[0])) {
		    		$query .= ' AND YEAR(DT1) = '.$date[0];
		    	}
	    		if (isset($date[1])) {
		    		$query .= ' AND MONTH(DT1) = '.$date[1];
		    	}
	    		if (isset($date[2])) {
		    		$query .= ' AND DAYOFMONTH(DT1) = '.$date[2];
		    	}
	    		if (isset($date[3])) {
		    		$query .= ' AND HOUR(DT1) = '.$date[3];
		    	}
	    	}
	    	// сортировать по дате, от текущей позиции с лимитом заданный в конфиге
	    	$query .= ' ORDER BY DT1 ASC LIMIT '.$param['sp'].','.$this->conf['gallery-limit'];
	    	// Получение результата
    		$result = mysql_query($query) or die("Query failed");
	    	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
	    		// обработка размера файла
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
	    	//	$events[str_replace(array('/', '.'),'_',$line[5].'_'.$line[2])][2] = 'cam_01/2010-05/05/00_06_00.jpg';
	    	}
    	}
    	// Сохранение результата
    	$this->result = array('events'=>$events);
    }    
    
    // Функция построения дерева события
    public function get_tree_events($param) {
    	global $GCP_cams_params;
	    $cameras = implode(',',array_keys($GCP_cams_params));
    	
    	$query = "SELECT DT1";
    	$query .= ' FROM EVENTS';
    	// только изображения
    	$query .= ' WHERE EVT_ID in (15,16,17,18,19,20,21,23,32)';
    	$query .= ' AND EVENTS.CAM_NR in ('.$cameras.')';
    	// групировать и сортировать по дате
    	$query .= ' ORDER BY DT1 DESC LIMIT 1';
    	$last_event = $this->_fetch_array($query);
    	$last_event_date = $last_event[0]['DT1'];
    	
    	$key = md5($last_event_date.'-'.$last_event_date);
    	
    	$tree_events_result = $this->cache->get($key);
    	if (empty($tree_events_result)) {
	    	$query = "SELECT LAST_UPDATE";
	    	$query .= ' FROM TREE_EVENTS';
	    	$query .= ' WHERE ';
	    	$query .= ' TREE_EVENTS.CAM_NR in ('.$cameras.')';
	    	$query .= ' ORDER BY LAST_UPDATE DESC LIMIT 1';
	    	$last_tree_event = $this->_fetch_array($query);
	    	$last_tree_date = empty($last_tree_event) ? '0000-00-00 00:00:00' : $last_tree_event[0]['LAST_UPDATE'];
			if ($last_tree_date < $last_event_date) {
				$this->_update_tree_events($last_tree_date, $last_event_date);
			}
	    	$query = "SELECT *";
	    	$query .= ' FROM TREE_EVENTS';
	    	$query .= ' WHERE TREE_EVENTS.CAM_NR in ('.$cameras.')';
	    	$query .= ' ORDER BY YEAR(LAST_UPDATE) DESC, LAST_UPDATE ASC';
	    	$tree_events = $this->_fetch_array($query);
	    	
	    	$tree_events_result = array();
	    	foreach ($tree_events as $k=>$v) {
	    		if (!isset($tree_events_result[$v['DATE']])) {
	    			$tree_events_result[$v['DATE']] = array(
	    				'date' => $v['DATE'],
	    			);
	    		}
	    		$tree_events_result[$v['DATE']]['image_'.$v['CAM_NR'].'_count'] = $v['IMAGE_COUNT'];
	    		$tree_events_result[$v['DATE']]['image_'.$v['CAM_NR'].'_size'] = $v['IMAGE_SIZE'];
	    		$tree_events_result[$v['DATE']]['video_'.$v['CAM_NR'].'_count'] = $v['VIDEO_COUNT'];
	    		$tree_events_result[$v['DATE']]['video_'.$v['CAM_NR'].'_size'] = $v['VIDEO_SIZE'];
	    		$tree_events_result[$v['DATE']]['audio_'.$v['CAM_NR'].'_count'] = $v['AUDIO_COUNT'];
	    		$tree_events_result[$v['DATE']]['audio_'.$v['CAM_NR'].'_size'] = $v['AUDIO_SIZE'];
	    	}
	    	
	    	if (!$this->cache->check($key)) {
	    		$this->cache->lock($key);
	    		$this->cache->set($key, $tree_events_result);
	    	}
	    	
	    	
	    	
    	}
    	// возвращаем результат
    	if (!empty($tree_events_result)) {
    		$this->result = array('tree_events'=>$tree_events_result, 'cameras' => $GCP_cams_params, 'status' => 'success');
    	} else {
    		$this->result = array('status' => 'error');
    	}
    }
    
    public function update_tree_events($param) {
    	if (isset($param['start']) && isset($param['end'])) {
    		$this->_update_tree_events($param['start'], $param['end']);
    	}
    }
	public function cron_update_tree_events() {
		$query = "SELECT DT1";
    	$query .= ' FROM EVENTS';
    	// только изображения
    	$query .= ' WHERE EVT_ID in (15,16,17,18,19,20,21,23,32)';
    	// групировать и сортировать по дате
    	$query .= ' ORDER BY DT1 DESC LIMIT 1';
    	$last_event = $this->_fetch_array($query);
    	$last_event_date = $last_event[0]['DT1'];
    	
    	$query = "SELECT LAST_UPDATE";
    	$query .= ' FROM TREE_EVENTS';
    	$query .= ' ORDER BY LAST_UPDATE DESC LIMIT 1';
    	$last_tree_event = $this->_fetch_array($query);
    	$last_tree_date = empty($last_tree_event) ? '0000-00-00 00:00:00' : $last_tree_event[0]['LAST_UPDATE'];
		if ($last_tree_date < $last_event_date) {
			$this->_update_tree_events($last_tree_date, $last_event_date);
		}
    }
    
    private function _update_tree_events($start, $end){
    	$tstart = date('Y-m-d H:00:00',strtotime($start));
    	$tend = date('Y-m-d H:59:59',strtotime($end));
    	$query = "SELECT *";
    	$query .= " FROM EVENTS";
    	$query .= ' WHERE EVT_ID in (15,16,17,18,19,20,21,23,32)';
    	$query .= ' AND DT1 >= "'.$tstart.'"';
    	$query .= ' AND DT1 <= "'.$tend.'"';
    	$query .= ' ORDER BY DT1 ASC';
    	$result = mysql_query($query) or die("Query failed");
    	
    	$tree_events = array();
    	while ($line = mysql_fetch_array($result)) {
    		
    		$date = date('Y_m_d_H',strtotime($line['DT1']));
    		$key = $date.'_'.$line['CAM_NR'];
    		
    		if (!isset($tree_events[$key])) {
    			$tree_events[$key] = array (
    				'DATE' => $date,
    				'CAM_NR' => $line['CAM_NR'],
    				'IMAGE_COUNT' => 0,
   					'IMAGE_SIZE' => 0,
    				'VIDEO_COUNT' => 0,
   					'VIDEO_SIZE' => 0,
    				'AUDIO_COUNT' => 0,
   					'AUDIO_SIZE' => 0,
    				'LAST_UPDATE' => $line['DT1'],
    			);
    		}
    		
    		if (in_array( $line['EVT_ID'], array(15,16,17,18,19,20,21))) {
    			$tree_events[$key]['IMAGE_COUNT']++;
    			$tree_events[$key]['IMAGE_SIZE'] += $line['FILESZ_KB'];
    		} else if (in_array( $line['EVT_ID'], array(23))) {
    			$tree_events[$key]['VIDEO_COUNT']++;
    			$tree_events[$key]['VIDEO_SIZE'] += $line['FILESZ_KB'];
    		} else if (in_array( $line['EVT_ID'], array(32))) {
    			$tree_events[$key]['AUDIO_COUNT']++;
    			$tree_events[$key]['AUDIO_SIZE'] += $line['FILESZ_KB'];
    		}
    		$tree_events[$key]['LAST_UPDATE']=$line['DT1'];
    	}
    	$query = 'DELETE FROM TREE_EVENTS';
    	$query .= ' WHERE DATE >= "'.date('Y_m_d_H',strtotime($start)).'"';
    	$query .= ' AND DATE <= "'.date('Y_m_d_H',strtotime($end)).'"';
    	$result = mysql_query($query) or die("Query failed");
    	
    	foreach ($tree_events as $row) {
    		$query = 'INSERT INTO TREE_EVENTS ';
    		$query .= '(DATE, CAM_NR, IMAGE_COUNT, IMAGE_SIZE, VIDEO_COUNT, VIDEO_SIZE, AUDIO_COUNT, AUDIO_SIZE, LAST_UPDATE)';
    		$query .=' VALUES ("'.$row['DATE'].'",'.$row['CAM_NR'].','.$row['IMAGE_COUNT'].','.$row['IMAGE_SIZE'].','.$row['VIDEO_COUNT'].','.$row['VIDEO_SIZE'].','.$row['AUDIO_COUNT'].','.$row['AUDIO_SIZE'].',"'.$row['LAST_UPDATE'].'")';
    		$result = mysql_query($query) or die("Query failed");
    	}
    }
    
    // отдача результата клиенту
    public function print_result() {
    	echo json_encode($this->result);
    }
    // функция выполнения запроса к БД
    private function _fetch_array($query, $type = MYSQL_ASSOC) {
    	$a = array();
    	
    	$result = mysql_query($query) or die("Query failed");
    	while ($line = mysql_fetch_array($result, $type)) {
    		$a[] = $line;
    	}
    	return $a;
    }
}
?>
