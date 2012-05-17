<?php 

/**
 * 
 * @file lib/adb.php
 * @brief В файле реализован класс, который обеспечивает взаимодействие с БД,<br />а также инициализируется экземпляр этого класса
 * 
 * Все обращения к БД должны быть реализованы посредством этого класса,
 * а работа с БД в обход этого класса крайне нежелательна. 
 * 
 * Для инициализации экземпляра класса используется объект конфигурации $conf из /etc/avreg/site-defaults.php
 * 
 * Для подключения к БД класс использует /usr/share/php/DB.php
 * 
 */



require_once('/usr/share/php/DB.php');
require_once('config.inc.php');

/// Инициализируем класс по работе с БД
$adb = new Adb($conf);

/**
 * @class Adb
 * @brief Класс взаимодействия с БД
 * 
 */

class Adb {

   private 
		/// Название БД
      $_database = '',
      /// Пользователь БД
      $_user = '',
      /// Пароль БД
      $_password = '',
      /// Тип БД mysql - MySql, pgsql - PostgreSql
      $_dbtype = 'mysql',
      /// Хост БД
      $_host = '',
      /// Объект для работы с БД
      $_db = false;

	/**
	 *  Конструктор по умолчанию
	 * Устанавливает соединение с БД
	 * @param array $param масив конфигурации класса
	 * @return true если соединение с баззой успешно, false если произошла ошибка.
	 */
   public function __construct($param) {
      $this->_database = $param['db-name'];
      $this->_user = $param['db-user'];
      $this->_password = $param['db-passwd'];
      if (isset($param['db-type']) && !empty($param['db-type']))
         $this->_dbtype = $param['db-type'];

      if (isset($param['db-host']) && !empty($param['db-host']))
         $this->_host = $param['db-host'];

      $dsn = "{$this->_dbtype}://{$this->_user }:{$this->_password}@{$this->_host}/{$this->_database}";
      $this->_db = DB::connect($dsn,true);
      $this->_error($this->_db);

      if ($this->_dbtype == 'mysql')
         $res = $this->_db->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
      else
         $res = $this->_db->query("SET NAMES 'utf8'");
      $this->_error($res);
      return true;
   }
	/**
	 *  Деструктор по умолчанию
	 * Закрывает соединение с БД
	 */
   public function __destruct() {
      if (!PEAR::isError($this->_db))
         $this->_db->disconnect();
   }
   /**
    *  Проверка на ошибку в запросе к БД.
    * 
    * @param object $r Объект запроса
    * @param bool $die true - закончить скрипт, false - вывести ошибку
    * 
    * @return true - если ошибка, false - если нет ошибок
    */
   public function _error($r, $die = true) {
      if (PEAR::isError($r)) {
         @header('Content-Type: text/html; charset=' . $GLOBALS['chset']);

         echo 'Standard Message: ' . $r->getMessage() . "<br>";
         echo 'Standard Code: ' . $r->getCode() . "<br>";
         echo 'DBMS/User Message: ' . $r->getUserInfo() . "<br>";
         echo 'DBMS/Debug Message: ' . $r->getDebugInfo() . "<br>";

         if ($die) die($r->getMessage());
         return true;


         echo $r->getDebugInfo();
         if ($die) die($r->getMessage());
         return true;
      }
      return false;
   }
         $res = $this->_db->query($query);
         $this->_error($res);
      }

/**
 *  Метод получения событий

 * @param int $camera номер камеры
 * @param int $ser_nr
 * @param string $timebegin дата начала
 * @param string $timeend дата окончания
 * @param string $order сортировка
 * 
 * @return array масив событий
 */   
   
   public function get_files($camera, $ser_nr, $timebegin, $timeend = false, $order = ''){
      $files = array();
      $query = 'SELECT '.$this->_date_part('timestamp', 'DT1').' as START, '.$this->_date_part('timestamp', 'DT1').' as FINISH,  EVT_ID, FILESZ_KB, FRAMES, ALT1 as U16_1, ALT2 as U16_2, EVT_CONT';
      $query .= ' FROM EVENTS';
      $query .= " WHERE CAM_NR=$camera AND SESS_NR=$ser_nr";
      $query .= " AND EVT_ID in (15,16,17,18,19,20,21)";
      if (empty($timeend))
         $query .= " AND ((DT1 >= '$timebegin') OR (DT2 >= '$timebegin'))";
      else
         $query .= " AND((DT1 between '$timebegin' and '$timeend') or (DT2 between '$timebegin' and '$timeend'))";
      $query .= " ORDER BY DT1 " .$order;
      $res = $this->_db->query($query);
      $this->_error($res);
      while($res->fetchInto($line)){
         $f = array();
         foreach ($line as $k=>$v) {
            $k = strtoupper($k);
            $f[$k] = trim($v);
         }
         $files[] = $f;
      }
      return $files;
   }

   /**
    *  Метод позволяет получить события для pda-версии

    * @param string $cams_csv список камер 
	* @param string $timebegin дата начала
	* @param string $timeend дата окончания
	* @param string $order сортировка
	* 
	* @return array масив событий
    */
   public function get_pda_events($cams_csv, $timebegin, $timeend , $order = ''){
      $files = array();
      $query = 'SELECT '.$this->_date_part('timestamp', 'E1.DT1').' as START, '.$this->_date_part('timestamp', 'E2.DT1').' as FINISH,  E1.CAM_NR, E1.SESS_NR AS SESS_NR';
      $query .= ' FROM EVENTS AS E1';
      $query .= ' LEFT JOIN EVENTS AS E2 ON (E1.SESS_NR = E2.SESS_NR AND E1.CAM_NR = E2.CAM_NR AND E1.DT1 = E2.DT2 AND E1.EVT_ID = 13 AND E2.EVT_ID = 14)';
      $query .= " WHERE E1.CAM_NR in ($cams_csv)";
      $query .= " AND E1.EVT_ID in (13)";
      $query .= " AND ((E1.DT1 between '$timebegin' and '$timeend') and (E2.DT1 is null or E2.DT1 between '$timebegin' and '$timeend'))";
      $query .= " ORDER BY E1.DT1 " .$order;
      $res = $this->_db->query($query);
      $this->_error($res);
      while($res->fetchInto($line)){
         $f = array();
         foreach ($line as $k=>$v) {
            $k = strtoupper($k);
            $f[$k] = trim($v);
         }
         $files[] = $f;
      }
      return $files;
   }	

/**
 *  Метод позволяет получить масив событий для offline модуля

 * @param array $cams список камер
 * @param int $timemode фильтр даты
 * @param array $date дата
 * @param array $evt_ids тип событий
 * @param array $dayofweek дни недели
 * @param array $page лимит и номер страницы
 * 
 * @return array масив событий
 */
   public function events_select($cams, $timemode = false, $date, $evt_ids, $dayofweek, $page = false){
      $all_continuous_events = array(12,23,32);
      $query_continuous_events    = array_intersect($all_continuous_events,  $evt_ids);
      $query_noncontinuous_events = array_diff($evt_ids, $all_continuous_events);

      $events = array();
      $query = 'SELECT '.$this->_date_part('timestamp', 'DT1').' as UDT1, '.$this->_date_part('timestamp', 'DT2').' as UDT2,';
      $query .= ' CAM_NR, EVT_ID, SESS_NR AS SER_NR, FILESZ_KB, FRAMES, ALT1 as U16_1, ALT2 as U16_2, EVT_CONT';
      $query .= ' FROM EVENTS';
      $query .= ' WHERE';
      $query .= " CAM_NR in (0, ".implode(',', $cams).")";
      $query .= " AND (";

      if (!empty($timemode) && $timemode == 1) {
         $timebegin = sprintf('20%02s-%02u-%02u %02u:%02u:00',$date['from'][0],$date['from'][1],$date['from'][2],$date['from'][3],$date['from'][4]);
         $timeend   = sprintf('20%02s-%02u-%02u %02u:%02u:59',$date['to'][0],$date['to'][1],$date['to'][2],$date['to'][3],$date['to'][4]);


         if ( count($query_continuous_events) > 0 ) {
            $query .= " ( EVT_ID in (".implode(',', $query_continuous_events).") and ( (DT1 between '$timebegin' and '$timeend') or (DT2 between '$timebegin' and '$timeend') ))";
         }
         if ( count($query_noncontinuous_events) > 0 ) {
            if (count($query_continuous_events) > 0)
               $query .= " OR ";
            $query .= "(EVT_ID in (".implode(',', $query_noncontinuous_events).") and (DT1 between '$timebegin' and '$timeend'))";
         }
      } else {
         $timebegin = sprintf('20%02s-%02u-%02u 00:00:00',$date['from'][0],$date['from'][1],$date['from'][2]);
         $timeend   = sprintf('20%02s-%02u-%02u 23:59:59',$date['to'][0],$date['to'][1],$date['to'][2]);
         $time_in_day_begin = sprintf('%02u:%02u:00',$date['from'][3],$date['from'][4]);
         $time_in_day_end   = sprintf('%02u:%02u:59',$date['to'][3],$date['to'][4]);

         if ( count($query_continuous_events) > 0 ) {
            $query .= "( EVT_ID in (".implode(',', $query_continuous_events).") and ( ( DT1 between '$timebegin' and '$timeend' )";
            $query .= " or ( DT2 between '$timebegin' and '$timeend' ) ) and ( ".$this->_date_part('weekday', 'DT1')." in (".implode(',', $dayofweek).") or ".$this->_date_part('weekday', 'DT2')." in (".implode(',', $dayofweek).") )";
            $query .= " and ( ( ".$this->_date_part('time', 'DT1')." between '$time_in_day_begin' and '$time_in_day_end' ) or ( ".$this->_date_part('time', 'DT2')." between '$time_in_day_begin' and '$time_in_day_end' ) ))";
         }

         if ( count($query_noncontinuous_events) > 0 ) {
            if (count($query_continuous_events) > 0)
               $query .= " OR ";
            $query .= "( EVT_ID in (".implode(',', $query_noncontinuous_events).")and ( DT1 between '$timebegin' and '$timeend' )";
            $query .=" and ( ".$this->_date_part('weekday', 'DT1')." in (".implode(',', $dayofweek).") ) and ( (".$this->_date_part('time', 'DT1')." between '$time_in_day_begin' and '$time_in_day_end') ))";
         }
      }

      $query .= " )";
      $query .= ' ORDER BY DT1';
      if (!empty($page)) {
         $query .= ' LIMIT '.$page['limit'];
         $query .= ' OFFSET '.$page['offset'];
      }
      $res = $this->_db->query($query);
      $this->_error($res);
      while($res->fetchInto($line, DB_FETCHMODE_ASSOC)){
         $f = array();
         foreach ($line as $k=>$v) {
            $k = strtoupper($k);
            $f[$k] = trim($v);
         }
         $events[] = $f;
      }
      return $events;
   }
/**
 *  Добавление параметров камеры

 * @param string $bind_mac 'local'
 * @param int $cam_nr номер камеры
 * @param string $parname название параметра
 * @param string $parval значение параметра
 * @param string $host для какого хоста
 * @param string $user для какого пользователя
 */
   public function add_camera ($bind_mac, $cam_nr, $parname, $parval, $host, $user) {
      $parval = $parval == null ? 'NULL' : "'$parval'";
      $query = 'INSERT INTO CAMERAS ';
      $query .= '(BIND_MAC, CAM_NR, PARNAME, PARVAL, CHANGE_HOST, CHANGE_USER)';
      $query .=" VALUES ('".$bind_mac."',".$cam_nr.",'".$parname."',".$parval.",'".$host."','".$user."')";
      $res = $this->_db->query($query);
      $this->_error($res);
   }

   /**
    * 
    * Обновление параметров камеры
    * 
 * @param string $bind_mac 'local'
 * @param int $cam_nr номер камеры
 * @param string $parname название параметра
 * @param string $parval значение параметра
 * @param string $host для какого хоста
 * @param string $user для какого пользователя
    */
   public function update_camera ($bind_mac, $cam_nr, $parname, $parval, $host, $user) {
      $parval = $parval == null ? 'NULL' : "'$parval'";
      $query = 'UPDATE CAMERAS SET';
      $query .= " PARVAL = $parval";
      $query .= ", CHANGE_HOST = '$host'";
      $query .= ", CHANGE_USER = '$user'";
      $query .= " WHERE BIND_MAC = '$bind_mac'";
      $query .= " AND CAM_NR = $cam_nr";
      $query .= " AND PARNAME = '$parname'";
      $res = $this->_db->query($query);
      $this->_error($res);
   }
/**
 *  Добавление или изменение параметров камеры

 * @param string $bind_mac 'local'
 * @param int $cam_nr номер камеры
 * @param string $parname название параметра
 * @param string $parval значение параметра
 * @param string $host для какого хоста
 * @param string $user для какого пользователя
 */
   public function replace_camera ($bind_mac, $cam_nr, $parname, $parval, $host, $user) {
      $query = 'SELECT * FROM CAMERAS ';
      $query .= " WHERE BIND_MAC = '$bind_mac'";
      $query .= " AND CAM_NR = $cam_nr";
      $query .= " AND PARNAME = '$parname'";
      $res = $this->_db->query($query);
      $this->_error($res);
      $res->fetchInto($line);
      if (empty($line)) {
         $this->add_camera($bind_mac, $cam_nr, $parname, $parval, $host, $user);
      } else {
         $this->update_camera($bind_mac, $cam_nr, $parname, $parval, $host, $user);
      }
   }
/**
 * 
 * Метод получает настройки камеры по умолчанию
 * @param int $cam_nr номер камеры
 * @param string $bind_mac 'local'
 * @return array параметры камеры
 */
   public function get_def_cam_params($cam_nr = 0, $bind_mac = 'local') {
      $cams = array();
      $query = 'SELECT CAM_NR, PARNAME, PARVAL, CHANGE_HOST, CHANGE_USER, CHANGE_TIME FROM CAMERAS';
      $query .= ' WHERE BIND_MAC=\''.$bind_mac.'\' AND (CAM_NR=0 OR CAM_NR='.$cam_nr.')';

      $res = $this->_db->query($query);

      $this->_error($res);

      while ($res->fetchInto($line, DB_FETCHMODE_ASSOC)) {
         $cams[] = array(
            'CAM_NR' => trim($line[$this->_key('CAM_NR')]),
            'PARAM' => trim($line[$this->_key('PARNAME')]),
            'VALUE' => trim($line[$this->_key('PARVAL')]),
            'CHANGE_HOST' => trim($line[$this->_key('CHANGE_HOST')]),
            'CHANGE_USER' => trim($line[$this->_key('CHANGE_USER')]),
            'CHANGE_TIME' => trim($line[$this->_key('CHANGE_TIME')]),

         );
      }
      return  $cams;
   }

/**
 * 
 * Метод позволяет получить параметры камер
 * @param string $cams_list  список камер
 * @param string $param_list список параметров
 * @param string $bind_mac 'local'
 * @return array параметры камер
 */
   public function get_cam_params($cams_list = '', $param_list = '', $bind_mac = 'local') {
      $cams = array();
      $query = 'SELECT CAM_NR, PARNAME, PARVAL FROM CAMERAS';
      $query .= ' WHERE BIND_MAC=\''.$bind_mac.'\'';
      if (!empty($cams_list)) {
         $query .= ' AND (CAM_NR=0  OR CAM_NR in('.$cams_list.'))';
      }
      $query .= ' AND PARNAME IN ('.$param_list.') AND  PARVAL<>\'\' AND PARVAL IS NOT NULL ';
      $query .= ' ORDER BY CAM_NR';
      $res = $this->_db->query($query);
      $this->_error($res);
      while ($res->fetchInto($line, DB_FETCHMODE_ASSOC)) {
         $cams[] = array(
            'CAM_NR' => trim($line[$this->_key('CAM_NR')]),
            'PARAM' => trim($line[$this->_key('PARNAME')]),
            'VALUE' => trim($line[$this->_key('PARVAL')]),
         );
      }
      return  $cams;
   }
/**
 * 
 * Метод позволяет получить названия камер
 * @param string $cams_list Список камер
 * @return array Список названий
 */
   public function get_cameras_name($cams_list = false) {
      $cams = array();
      /* Performing new SQL query */
      $query = 'SELECT c1.CAM_NR, c1.PARVAL as work , c2.PARVAL as text_left, '.
         'c1.CHANGE_HOST, c1.CHANGE_USER, c1.CHANGE_TIME '.
         'FROM CAMERAS c1 LEFT OUTER JOIN CAMERAS c2 '.
         'ON ( c1.CAM_NR = c2.CAM_NR AND c1.BIND_MAC=c2.BIND_MAC AND c2.PARNAME = \'text_left\' ) '.
         'WHERE c1.BIND_MAC=\'local\' AND';
      if (empty($cams_list))
         $query .= ' c1.CAM_NR>0';
      else
         $query .= " c1.CAM_NR in($cams_list)";

      $query .=' AND c1.PARNAME = \'work\' '.
         'ORDER BY c1.CAM_NR';
      $res = $this->_db->query($query);
      $this->_error($res);
      while ($res->fetchInto($line, DB_FETCHMODE_ASSOC)) {
         $cams[] = array(
            'CAM_NR' => trim($line[$this->_key('CAM_NR')]),
            'work' => trim($line[$this->_key('work')]),
            'text_left' => trim($line[$this->_key('text_left')]),
         );
      }
      return  $cams;

   }
/**
 * 
 * Метод позволяет получить последний номер камеры
 * @param string $bind_mac 'local'
 * @return int номер камеры
 */
   public function max_cam_nr($bind_mac = 'local') {
      $query = 'SELECT MAX(CAM_NR) AS LAST_NUM FROM CAMERAS WHERE BIND_MAC=\''.$bind_mac.'\'';
      $res = $this->_db->query($query);
      $this->_error($res);
      $res->fetchInto($line);
      return isset($line[0]) ? $line[0] : false; 
   }	
/**
 * 
 * Метод позволяет удалить камеру
 * @param int $cam_nr номер камеры
 * @param string $bind_mac 'local'
 */

   public function delete_camera($cam_nr, $bind_mac = 'local') {
      $query = sprintf('DELETE FROM CAMERAS WHERE BIND_MAC=\''.$bind_mac.'\' AND CAM_NR=%d', $cam_nr);
      $res = $this->_db->query($query);
      $this->_error($res);
   }
/**
 * 
 * Метод позволяет добавить раскладку
 * @param string $display
 * @param int $mon_nr
 * @param string $mon_type
 * @param string $mon_name
 * @param string $remote_addr
 * @param string $login_user
 * @param array $fWINS
 * @param array $vWINS
 * @param string $bind_mac
 */
   public function add_monitors($display,$mon_nr,$mon_type,$mon_name, $remote_addr, $login_user, $fWINS, $vWINS,$bind_mac = 'local') {
      $query = sprintf('INSERT INTO MONITORS (BIND_MAC, DISPLAY, MON_NR, MON_TYPE, MON_NAME, %s, CHANGE_HOST, CHANGE_USER) VALUES (\'local\', \'%s\', %d, \'%s\', \'%s\', %s, \'%s\', \'%s\')',
         implode (', ',$fWINS), $display, $mon_nr, $mon_type, $mon_name, implode (', ',$vWINS), $remote_addr, $login_user);
      $res = $this->_db->query($query);
      $this->_error($res);
   }
/**
 * 
 * Метод позволяет удалить раскладку
 * @param string $display
 * @param int $mon_nr
 * @param string $bind_mac
 */
   public function delete_monitors($display, $mon_nr, $bind_mac = 'local') {
      $query = 'DELETE FROM MONITORS';
      $query .= " WHERE BIND_MAC ='$bind_mac'";
      $query .= " AND DISPLAY ='$display'";
      $query .= " AND MON_NR = $mon_nr";		
      $res = $this->_db->query($query);
      $this->_error($res);
   }
/**
 * 
 * Метод позволяет обновить данные о раскладке
 * @param string $display
 * @param unknown_type $mon_nr
 * @param string $mon_type
 * @param string $mon_name
 * @param string $host
 * @param string $user
 * @param array $fWINS
 * @param array $vWINS
 * @param string $bind_mac
 */
   public function update_monitors ($display,$mon_nr,$mon_type,$mon_name, $host, $user, $fWINS, $vWINS,$bind_mac = 'local') {
      $query = 'UPDATE MONITORS SET ';
      $query .= "MON_TYPE = '$mon_type'";
      $query .= ", MON_NAME = '$mon_name'";
      $query .= ", CHANGE_HOST = '$host'";
      $query .= ", CHANGE_USER = '$user'";

      for ($i = 0; $i < count($vWINS); $i++) {
         if (is_int($vWINS[$i]))
            $query .= ", {$fWINS[$i]} = {$vWINS[$i]}";
         else
            $query .= ", {$fWINS[$i]} = '{$vWINS[$i]}'";
      }

      $query .= " WHERE BIND_MAC ='$bind_mac'";
      $query .= " AND DISPLAY ='$display'";
      $query .= " AND MON_NR = $mon_nr";		

      $res = $this->_db->query($query);
      $this->_error($res);
   }
/**
 * 
 * Метод добавляет или обновляет параметры раскладки
 * @param string $display
 * @param unknown_type $mon_nr
 * @param string $mon_type
 * @param string $mon_name
 * @param string $host
 * @param string $user
 * @param array $fWINS
 * @param array $vWINS
 * @param string $bind_mac
 */
   public function replace_monitors ($display,$mon_nr,$mon_type,$mon_name, $host, $user, $fWINS, $vWINS, $bind_mac = 'local') {
      $query = 'SELECT * FROM MONITORS ';
      $query .= " WHERE BIND_MAC = '$bind_mac'";
      $query .= " AND MON_NR = $mon_nr";
      $query .= " AND DISPLAY = '$display'";
      $res = $this->_db->query($query);
      $this->_error($res);
      $res->fetchInto($line);
      if (empty($line))
         $this->add_monitors($display,$mon_nr,$mon_type,$mon_name, $host, $user, $fWINS, $vWINS);
      else
         $this->update_monitors($display,$mon_nr,$mon_type,$mon_name, $host, $user, $fWINS, $vWINS);
   }	
/**
 * 
 * Метод позволяет получить параметры раскладки
 * @param string $display
 * @param int $mon_nr
 * @param string $bind_mac
 * @return array параметры
 */
   public function get_monitor($display, $mon_nr, $bind_mac = 'local') {
      $query = 'SELECT MON_NR, MON_TYPE, MON_NAME, IS_DEFAULT, ' .
         'WIN1, WIN2, WIN3, WIN4, WIN5, WIN6, WIN7, WIN8, WIN9, WIN10, WIN11, WIN12, WIN13, WIN14, WIN15, WIN16, WIN17, WIN18, WIN19, WIN20, WIN21, WIN22, WIN23, WIN24, WIN25, '.
         'CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
         'FROM MONITORS '.
         'WHERE BIND_MAC=\''.$bind_mac.'\' AND DISPLAY=\''.$display.'\' AND MON_NR='.$mon_nr;
      $res = $this->_db->query($query);
      $this->_error($res);
      $res->fetchInto($line);
      return $line;
   }
/**
 * 
 * Метод позволяет получить параметры всех раскладок
 * @param string $bind_mac
 * @return array раскладки
 */
   public function get_monitors($bind_mac = 'local'){
      $mon = array();
      $query = 'SELECT * FROM MONITORS';
      $query .= " WHERE BIND_MAC='$bind_mac'";
      $query .= ' ORDER BY MON_NR';

      $res = $this->_db->query($query);
      $this->_error($res);
      while ($res->fetchInto($line, DB_FETCHMODE_ASSOC)) {
         $m = array();
         foreach ($line as $k=>$v) {
            $k = strtoupper($k);
            $m[$k] = trim($v);
         }
         $mon[] = $m;
      }
      return  $mon;
   }
/**
 * Метод добавляет пользователя
 * 
 * @param string $u_host Допустимые IP-адреса пользователького хоста.
 * @param string $u_name Логин
 * @param string $passwd пароль
 * @param string $groups група
 * @param string $u_devacl Доступные камеры
 * @param string $u_forced_saving_limit Максимальная длительность принудительной записи (по команде) в минутах
 * @param string $sessions_per_cam Ограничение количества одновременных просмотров каждой конкретной камеры
 * @param string $limit_fps limit_fps, кадров в секунду, [1-25] или sec/frames
 * @param string $nonmotion_fps nonmotion_fps, примеры допустимых значений: "1" - 1 кадр в 1 сек.; "2/1" - 1 кадр каждые 2 секунды.
 * @param string $limit_kbps limit_kbps, Kбит/сек
 * @param string $session_time session_time - по времени, в минутах
 * @param string $session_volume session_volume - по "закаченному" объёму, в МегаБайтах (10242)
 * @param string $u_longname ФИО
 * @param string $remote_addr хост на котором добавляют
 * @param string $login_user пользователь, который добавляет
 * @return bool результат добавления
 */
   public function add_user($u_host, $u_name, $passwd, $groups, $u_devacl, $u_forced_saving_limit, $sessions_per_cam,$limit_fps,$nonmotion_fps, $limit_kbps, $session_time, $session_volume, $u_longname, $remote_addr, $login_user) {
      $query = sprintf('INSERT INTO USERS 
         ( ALLOW_FROM, USER_LOGIN, PASSWD, STATUS, ALLOW_CAMS, FORCED_SAVING_LIMIT, SESSIONS_PER_CAM,
         LIMIT_FPS, NONMOTION_FPS, LIMIT_KBPS,
         SESSION_TIME, SESSION_VOLUME,
         LONGNAME, CHANGE_HOST, CHANGE_USER, CHANGE_TIME) 
         VALUES ( %s, %s, %s, %u, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW())',
            sql_format_str_val($u_host),
            sql_format_str_val($u_name),
            $this->_crypt($passwd),
            $groups,
            sql_format_str_val($u_devacl),
            sql_format_int_val($u_forced_saving_limit),
            sql_format_int_val($sessions_per_cam),
            sql_format_str_val($limit_fps),
            sql_format_str_val($nonmotion_fps),
            sql_format_int_val($limit_kbps),
            sql_format_int_val($session_time),
            sql_format_int_val($session_volume),
            sql_format_str_val($u_longname),
            sql_format_str_val($remote_addr),
            sql_format_str_val($login_user));

      $res = $this->_db->query($query);   
      return !$this->_error($res, false);
   }
/**
 * 
 * Метод позволяет обновить информацию о пользователе
 * @param string $u_host Допустимые IP-адреса пользователького хоста.
 * @param string $u_name Логин
 * @param string $passwd пароль
 * @param string $groups група
 * @param string $u_devacl Доступные камеры
 * @param string $u_forced_saving_limit Максимальная длительность принудительной записи (по команде) в минутах
 * @param string $sessions_per_cam Ограничение количества одновременных просмотров каждой конкретной камеры
 * @param string $limit_fps limit_fps, кадров в секунду, [1-25] или sec/frames
 * @param string $nonmotion_fps nonmotion_fps, примеры допустимых значений: "1" - 1 кадр в 1 сек.; "2/1" - 1 кадр каждые 2 секунды.
 * @param string $limit_kbps limit_kbps, Kбит/сек
 * @param string $session_time session_time - по времени, в минутах
 * @param string $session_volume session_volume - по "закаченному" объёму, в МегаБайтах (10242)
 * @param string $u_longname ФИО
 * @param string $remote_addr хост на котором добавляют
 * @param string $login_user пользователь, который добавляет
 * @param string $old_u_host старый хост
 * @param string $old_u_name старый логин
 * @return bool результат обновления
 */
   public function update_user($u_host,$u_name,$passwd, $groups, $u_devacl, $u_forced_saving_limit, $sessions_per_cam, $limit_fps, $nonmotion_fps, $limit_kbps, $session_time, $session_volume, $u_longname, $remote_addr, $login_user, $old_u_host,$old_u_name){
      $query = sprintf(
         'UPDATE USERS SET ALLOW_FROM=%s, USER_LOGIN=%s, PASSWD=%s, STATUS=%d, ALLOW_CAMS=%s, FORCED_SAVING_LIMIT=%s, SESSIONS_PER_CAM=%s, LIMIT_FPS=%s, NONMOTION_FPS=%s, LIMIT_KBPS=%s, SESSION_TIME=%s, SESSION_VOLUME=%s, LONGNAME=%s, CHANGE_HOST=%s, CHANGE_USER=%s, CHANGE_TIME=NOW() WHERE ALLOW_FROM=%s AND USER_LOGIN=%s',
         sql_format_str_val($u_host),
         sql_format_str_val($u_name),
         $this->_crypt($passwd),
         $groups,
         sql_format_str_val($u_devacl),
         sql_format_int_val($u_forced_saving_limit),
         sql_format_int_val($sessions_per_cam),
         sql_format_str_val($limit_fps),
         sql_format_str_val($nonmotion_fps),
         sql_format_int_val($limit_kbps),
         sql_format_int_val($session_time),
         sql_format_int_val($session_volume),
         sql_format_str_val($u_longname),
         sql_format_str_val($remote_addr),
         sql_format_str_val($login_user),
         sql_format_str_val($old_u_host),
         sql_format_str_val($old_u_name));
      $res = $this->_db->query($query);  
      $res = $this->_db->query($query);   
      return !$this->_error($res, false);
   }

/**
 * 
 * Метод позволяет удалить пользователя
 * @param string $u_name логин
 * @param string $u_host хост 
 * @param int $u_status статус
 */   
   public function delete_user($u_name, $u_host, $u_status){
      $query = sprintf('DELETE FROM USERS WHERE USER_LOGIN="%s" AND ALLOW_FROM="%s" AND STATUS=%u',
         $u_name, $u_host, $u_status);
      $res = $this->_db->query($query);   
      $this->_error($res);
   }
/**
 * 
 * Метод позволяет получить пароль пользователя
 * @param string $u_name логин
 * @param string $hosts хост
 * @return string пароль
 */
   public function get_user_passwd($u_name, $hosts) {
      $query = sprintf("SELECT PASSWD FROM USERS WHERE ALLOW_FROM in(%s) AND USER_LOGIN='%s'", "'".implode("','",$hosts)."'", $u_name);
      $res = $this->_db->query($query);  
      $this->_error($res);
      $res->fetchInto($line);
      return isset($line[0]) ? trim($line[0]) : false;
   }
/**
 * 
 * Метод позволяет обновить пароль пользователя
 * @param string $u_name логин
 * @param string $u_pass пароль
 * @param string $hosts хост
 */
   public function update_user_passwd($u_name, $u_pass, $hosts) {
      $query = sprintf("UPDATE USERS SET PASSWD=%s	 WHERE ALLOW_FROM in(%s) AND USER_LOGIN='%s'",
         $this->_crypt($u_pass),
         "'".implode("','",$hosts)."'",
         $u_name);		
      $res = $this->_db->query($query);
      $this->_error($res);  
      return true;
   }
/**
 * Метод позволяет получить пользователей
 * 
 * @param int $status статус
 * @return array масив пользователей
 */
   public function get_users($status = false) {
      $users = array();
      $query = 'SELECT ALLOW_FROM, USER_LOGIN,  PASSWD, STATUS, ALLOW_CAMS, FORCED_SAVING_LIMIT,  SESSIONS_PER_CAM, LIMIT_FPS, NONMOTION_FPS, LIMIT_KBPS, SESSION_TIME, SESSION_VOLUME,LONGNAME, CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
         'FROM USERS ';
      if ($status)
         $query .= "WHERE STATUS = $status ";
      $query .=  'ORDER BY ALLOW_FROM, USER_LOGIN';

      $res = $this->_db->query($query);
      $this->_error($res);
      while ($res->fetchInto($line, DB_FETCHMODE_ASSOC)) {
         $users[] =array(
            'HOST' => trim($line[$this->_key('ALLOW_FROM')]),
            'USER' => trim($line[$this->_key('USER_LOGIN')]),
            'PASSWD' => trim($line[$this->_key('PASSWD')]),
            'STATUS' => trim($line[$this->_key('STATUS')]),
            'ALLOW_CAMS' => trim($line[$this->_key('ALLOW_CAMS')]),
            'FORCED_SAVING_LIMIT' => trim($line[$this->_key('FORCED_SAVING_LIMIT')]),
            'SESSIONS_PER_CAM' => trim($line[$this->_key('SESSIONS_PER_CAM')]),
            'LIMIT_FPS' => trim($line[$this->_key('LIMIT_FPS')]),
            'NONMOTION_FPS' => trim($line[$this->_key('NONMOTION_FPS')]),
            'LIMIT_KBPS' => trim($line[$this->_key('LIMIT_KBPS')]),
            'SESSION_TIME' => trim($line[$this->_key('SESSION_TIME')]),
            'SESSION_VOLUME' => trim($line[$this->_key('SESSION_VOLUME')]),
            'LONGNAME' => trim($line[$this->_key('LONGNAME')]),
            'CHANGE_HOST' => trim($line[$this->_key('CHANGE_HOST')]),
            'CHANGE_USER' => trim($line[$this->_key('CHANGE_USER')]),
            'CHANGE_TIME' => trim($line[$this->_key('CHANGE_TIME')]),
         );
      }
      return $users;
   }

   private function _key($str){
      if ($this->_dbtype == 'pgsql')
         return strtolower($str);
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
         case 'weekday':
            $str = "date_part('dow', %%)";
            break;
         case 'time':
            $str = "%%::time";
            break;
         case 'timestamp':
            $str = "date_part('epoch', %%)";
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
         case 'weekday':
            $str = 'weekday(%%)';
            break;
         case 'time':
            $str = 'time(%%)';
            break;

         case 'timestamp':
            $str = "UNIX_TIMESTAMP(%%)";
            break;

         }
      }
      $str = str_replace('%%', $value,$str);

      return $str;
   }
   private function _date_format($value) {
      if ($this->_dbtype == 'pgsql')
         $str = "to_char(%%, 'yyyy_mm_dd_hh24')";
      else
         $str = "DATE_FORMAT(%%, '%Y_%m_%d_%H')";
      $str = str_replace('%%', $value,$str);
      return $str;
   }

   private function _timediff($d1, $d2){
      if ($this->_dbtype == 'pgsql')
         $str = $d1."-".$d2;
      else
         $str = "TIMEDIFF(".$d1." , ".$d2.")";
      return $str;
   }

   private function _crypt($value) {
      if (empty($value))
         return "''";

      if ($this->_dbtype == 'pgsql')
         $str = "crypt('%%', 'av')";
      else
         $str = "encrypt('%%')";
      $str = str_replace('%%', $value,$str);
      return $str;
   }
}
?>
