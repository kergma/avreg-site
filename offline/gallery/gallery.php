<?php
/**
*
* @file offline/gallery/gallery.php
* @brief Содержит класс Gallery
*
* инициализируется в offline/gallery.php
*
*@class Gallery
* Обеспечивает получение данных для построения и конфигурирования галереи 
*
*/

class Gallery {
    public 	
    	$method = '', /// метод запроса
    	$result = array(); /// ответ запроса
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
    	
    	global $adb;
    	$this->db = $adb;
    	
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

	    	
	    	$p = array (
	    		'cameras' => $param['cameras'],
	    		'events' => $EVT_ID,
	    		'date' => $param['tree'] !== 'all' ? explode('_', $param['tree']) : array(),
	    		'limit' => $this->conf['gallery-limit'],
	    		'offset' => $param['sp'],
	    	);
   			$events = $this->db->gallery_get_event($p);
    	}

    	// Сохранение результата
    	$this->result = array('events'=>$events);
    }    
    
    // Функция построения дерева события
    public function get_tree_events($param) {
    	global $GCP_cams_params;
	    $cameras = implode(',',array_keys($GCP_cams_params));
    	
	    
	    $last_event_date = $this->db->gallery_get_last_event_date(array('cameras' => array_keys($GCP_cams_params)));
	   
    	$key = md5($cameras.'-'.$last_event_date);
    	
    	$tree_events_result = $this->cache->get($key);
    	$tree_events_result = false;
    	if (empty($tree_events_result)) {
	    	$last_tree_date = $this->db->gallery_get_last_tree_event_data(array('cameras' => array_keys($GCP_cams_params)));
	    	
			if ($last_tree_date < $last_event_date) {
				$this->db->gallery_update_tree_events($last_tree_date, $last_event_date, array());
			}
			
			$tree_events_result = $this->db->gallery_get_tree_events(array('cameras' => array_keys($GCP_cams_params)));
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
    	$start = isset($param['start']) ? $param['start'] : false;
    	$end = isset($param['end']) ? $param['end'] : false;
    	$cameras = isset($param['cameras']) ? $param['cameras'] : false;
    	$this->db->gallery_update_tree_events($start, $end, $cameras);
    }
	public function cron_update_tree_events() {
    	$last_event_date = $this->db->gallery_get_last_event_date();
    	$last_tree_date = $this->db->gallery_get_last_tree_event_data();
		if ($last_tree_date < $last_event_date) {
			$this->db->gallery_update_tree_events($last_tree_date, $last_event_date);
		}
    }
    

    
    // отдача результата клиенту
    public function print_result() {
    	echo json_encode($this->result);
    }

}
?>
