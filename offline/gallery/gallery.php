<?php

/**
 * @file offline/gallery/gallery.php
 * @brief класс служит для получения и обновления данных о событиях
 * инстанцируется:
 * <ol>
 * <li> в offline/gallery.php - при отображении галереи
 * <li> в cron.php - для обновления данных дерева событий
 * </ol>
 *
 * информация в таблице tree_events обновляется при открытии
 * галереи начиная с времени последнего обновления и заканчивая
 * последним событием в events.
 * Это осуществляется вызовом метода getTreeEvents($param) в offline/gallery/js/main.js .
 *
 * cron.php при открытии галереи вообще не используется.
 *
 * cron.php - предоставляет возможность выполнять обновление этой
 * таблицы по некоторому расписанию, например в crontab.
 * Что это дает:
 *
 * 1. галерея открывается быстрей, поскольку для обновления надо
 * обработать меньшее кол-во данных
 * (может быть актуально при редком открытии галереи и большом кол-ве камер).
 * Выполняется вызовом метода cronUpdateTreeEvents().
 *
 * 2. после работы чистильщика, надо обновить tree_events для
 * того периода, для которого были удалены файлы.
 * (это необходимо, поскольку, как было сказано, при открытии галереи
 * таблица обновляется начиная с времени последнего обновления,
 * а не полностью с самого начала).
 * Выполняется вызовом метода updateTreeEvents($param),
 * которому в качестве параметров передаются начало(start) и конец(end) временного диапазона,
 * а так же номера камер(cameras) для которых надо выполнить обновление
 *
 *
 * */

namespace Avreg;

class Gallery
{
    public $method = ''; // метод запроса
    public $result = array(); // ответ запроса
    private $cache;
    private $db = '';
    private $limit = 0;
    private $conf = array(); // настройки галереи

    // конструктор класса
    public function __construct($param)
    {
        // получение параметров запроса
        foreach ($param as $k => $v) {
            if (isset($this->$k) && !in_array($k, array('db', 'conf'))) {
                $this->$k = $v;
            }
        }
        // Получение глобальных настроек сайта
        global $conf;
        $this->conf = $conf;
        $this->cache = new Cache();
        if (!$this->limit) {
            $this->limit = $this->conf['gallery-limit'];
        }
        global $adb;
        $this->db = $adb;

        // Если существует запрашиваемый метод, то его выполняем с указанными параметрами
        if (!empty($this->method) && method_exists($this, $this->method)) {
            $this->{$this->method}($param);
        }
    }

    // Функция получения событий
    public function getEvents($param)
    {
        $events = array();
        // если есть список камер, то выполняем запрос
        if (isset($param['cameras']) && !empty($param['cameras'])) {
            $cameras = trim($param['cameras'], ',');
            $param['cameras'] = explode(",", $cameras);

            $type = explode(",", trim($param['type'], ','));

            // картинки
            $EVT_ID = array();
            if (in_array('image', $type)) {
                $EVT_ID = array_merge($EVT_ID, array(15, 16, 17));
            }
            // видео
            if (in_array('video', $type)) {
                $EVT_ID = array_merge($EVT_ID, array(23));
            }
            // аудио
            if (in_array('audio', $type)) {
                $EVT_ID = array_merge($EVT_ID, array(32));
            }
            // видео+audio
            if (in_array('video', $type) || in_array('audio', $type)) {
                $EVT_ID = array_merge($EVT_ID, array(12));
            }

            $p = array(
                'cameras' => $param['cameras'],
                'events' => $EVT_ID,
                'date' => $param['tree'] !== 'all' ? explode('_', $param['tree']) : array(),
                'limit' => $this->limit,
                'offset' => $param['sp'],
            );
            //$events = $this->db->galleryGetEvent($p);
            if ($this->limit > 1) {
                $events = $this->db->galleryGetEvent($p);
                // Сохранение результата
                $this->result = array('events' => $events);
            } else {
                $date = $this->db->galleryGetEventDate($p);
                // Сохранение результата
                $this->result = $date;
            }
        }
    }

    // Функция построения дерева события
    public function getTreeEvents($param)
    {
        global $GCP_cams_params;
        $cameras = implode(',', array_keys($GCP_cams_params));

        $last_event_date = $this->db->galleryGetLastEventDate(array('cameras' => array_keys($GCP_cams_params)));

        $key = md5($cameras . '-' . $last_event_date);

        $tree_events_result = $this->cache->get($key);  // todo fix cache or remove
        if (empty($tree_events_result)) {
            $last_tree_date = $this->db->galleryGetLastTreeEventDate(
                array('cameras' => array_keys($GCP_cams_params))
            );

            if ($last_tree_date < $last_event_date) {

                $evt_updt_rst = $this->db->galleryUpdateTreeEvents(
                    $last_tree_date,
                    $last_event_date,
                    array(),
                    $param["on_dbld_evt"]
                );
                //проверка дублей событий
                if ($evt_updt_rst['status'] == 'error') {
                    $this->result = $evt_updt_rst;
                    return;
                }
            }

            $tree_events_result = $this->db->galleryGetTreeEvents(array('cameras' => array_keys($GCP_cams_params)));
            if (!$this->cache->check($key)) {
                $this->cache->lock($key);
                $this->cache->set($key, $tree_events_result);
            }
        }
        // возвращаем результат
        if (!empty($tree_events_result)) {
            $this->result = array(
                'tree_events' => $tree_events_result,
                'cameras' => $GCP_cams_params,
                'status' => 'success'
            );
        } else {
            $this->result = array('status' => 'error', 'code' => '0', 'description' => 'No events.', 'qtty' => 0);
        }
    }

    public function updateTreeEvents($param)
    {
        $start = isset($param['start']) ? $param['start'] : false;
        $end = isset($param['end']) ? $param['end'] : false;
        $cameras = isset($param['cameras']) ? $param['cameras'] : false;
        $this->db->galleryUpdateTreeEvents($start, $end, $cameras);
    }

    public function cronUpdateTreeEvents()
    {
        $last_event_date = $this->db->galleryGetLastEventDate();
        $last_tree_date = $this->db->galleryGetLastTreeEventDate();
        if ($last_tree_date < $last_event_date) {
            $this->db->galleryUpdateTreeEvents($last_tree_date, $last_event_date);
        }
    }

    // отдача результата клиенту
    public function printResult()
    {
        if ($this->limit > 1) {
            echo json_encode($this->result);
        } else {
            if (is_array($this->result)) {
                echo 'is array result';
            } else {
                echo $this->result;
            }
        }
    }
}
