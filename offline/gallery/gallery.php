<?php
class Gallery {
    public 	
    	$method = '', // метод запроса
    	$result = array(); // ответ запроса
    private 
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
	    	}
    	}
    	// Сохранение результата
    	$this->result = array('events'=>$events);
    }    
    
    // Функция построения дерева события
    public function get_tree_events($param) {
    	
    	$query = "SELECT DATE_FORMAT(DT1, '%Y_%m_%d_%H') as date";
    	// Только с камер, доступных пользователю
    	global $GCP_cams_params;
    	foreach ($GCP_cams_params as $CAM_NR => $PARAM) {
    		// подсчет количества
    		$query .= ', SUM(IF(CAM_NR = '.$CAM_NR.' AND EVT_ID in (15,16,17,18,19,20,21), 1,0)) as image_'.$CAM_NR.'_count';
    		// подсчет размера
    		$query .= ', SUM(IF(CAM_NR = '.$CAM_NR.' AND EVT_ID in (15,16,17,18,19,20,21), FILESZ_KB,0)) as image_'.$CAM_NR.'_size';
    		
    		 // подсчет количества
    		$query .= ', SUM(IF(CAM_NR = '.$CAM_NR.' AND EVT_ID in (23), 1,0)) as video_'.$CAM_NR.'_count';
    		// подсчет размера
    		$query .= ', SUM(IF(CAM_NR = '.$CAM_NR.' AND EVT_ID in (23), FILESZ_KB,0)) as video_'.$CAM_NR.'_size';
 
    		  		// подсчет количества
    		$query .= ', SUM(IF(CAM_NR = '.$CAM_NR.' AND EVT_ID in (32), 1,0)) as audio_'.$CAM_NR.'_count';
    		// подсчет размера
    		$query .= ', SUM(IF(CAM_NR = '.$CAM_NR.' AND EVT_ID in (32), FILESZ_KB,0)) as audio_'.$CAM_NR.'_size';
    	}
    	
    	$query .= ' FROM EVENTS';
    	// только изображения
    	$query .= ' WHERE EVT_ID in (15,16,17,18,19,20,21,23,32)';
    	// групировать и сортировать по дате
    	$query .= ' GROUP BY date ORDER BY YEAR(DT1) DESC, DT1 ASC';
    	//Выполнение запроса
    	$tree_events = $this->_fetch_array($query);
    	
    	// удаляем временные диапазоны, где нет событий
    	foreach ($tree_events as $k=>$v) {
    		$count = 0;
    		$size = 0;
    		foreach ($GCP_cams_params as $CAM_NR => $PARAM) {
    			$count += $v['image_'.$CAM_NR.'_count'];
    			$size += $v['image_'.$CAM_NR.'_size'];
    			$count += $v['video_'.$CAM_NR.'_count'];
    			$size += $v['video_'.$CAM_NR.'_size'];
    			$count += $v['audio_'.$CAM_NR.'_count'];
    			$size += $v['audio_'.$CAM_NR.'_size'];
    			
    		}
    		if (empty($count) || empty($size)) {
    			unset($tree_events[$k]);
    		}
    	}
    	// возвращаем результат
    	if (!empty($tree_events)) {
    		$this->result = array('tree_events'=>$tree_events, 'cameras' => $GCP_cams_params, 'status' => 'success');
    	} else {
    		$this->result = array('status' => 'error');
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
