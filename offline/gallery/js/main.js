    var Base64 = {

    	// private property
    	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    	// public method for encoding
    	encode : function (input) {
    		var output = "";
    		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
    		var i = 0;

    		input = Base64._utf8_encode(input);

    		while (i < input.length) {

    			chr1 = input.charCodeAt(i++);
    			chr2 = input.charCodeAt(i++);
    			chr3 = input.charCodeAt(i++);

    			enc1 = chr1 >> 2;
    			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
    			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
    			enc4 = chr3 & 63;

    			if (isNaN(chr2)) {
    				enc3 = enc4 = 64;
    			} else if (isNaN(chr3)) {
    				enc4 = 64;
    			}

    			output = output +
    			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
    			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

    		}

    		return output;
    	},

    	// public method for decoding
    	decode : function (input) {
    		var output = "";
    		var chr1, chr2, chr3;
    		var enc1, enc2, enc3, enc4;
    		var i = 0;

    		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

    		while (i < input.length) {

    			enc1 = this._keyStr.indexOf(input.charAt(i++));
    			enc2 = this._keyStr.indexOf(input.charAt(i++));
    			enc3 = this._keyStr.indexOf(input.charAt(i++));
    			enc4 = this._keyStr.indexOf(input.charAt(i++));

    			chr1 = (enc1 << 2) | (enc2 >> 4);
    			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
    			chr3 = ((enc3 & 3) << 6) | enc4;

    			output = output + String.fromCharCode(chr1);

    			if (enc3 != 64) {
    				output = output + String.fromCharCode(chr2);
    			}
    			if (enc4 != 64) {
    				output = output + String.fromCharCode(chr3);
    			}

    		}

    		output = Base64._utf8_decode(output);

    		return output;

    	},

    	// private method for UTF-8 encoding
    	_utf8_encode : function (string) {
    		string = string.replace(/\r\n/g,"\n");
    		var utftext = "";

    		for (var n = 0; n < string.length; n++) {

    			var c = string.charCodeAt(n);

    			if (c < 128) {
    				utftext += String.fromCharCode(c);
    			}
    			else if((c > 127) && (c < 2048)) {
    				utftext += String.fromCharCode((c >> 6) | 192);
    				utftext += String.fromCharCode((c & 63) | 128);
    			}
    			else {
    				utftext += String.fromCharCode((c >> 12) | 224);
    				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
    				utftext += String.fromCharCode((c & 63) | 128);
    			}

    		}

    		return utftext;
    	},

    	// private method for UTF-8 decoding
    	_utf8_decode : function (utftext) {
    		var string = "";
    		var i = 0;
    		var c = c1 = c2 = 0;

    		while ( i < utftext.length ) {

    			c = utftext.charCodeAt(i);

    			if (c < 128) {
    				string += String.fromCharCode(c);
    				i++;
    			}
    			else if((c > 191) && (c < 224)) {
    				c2 = utftext.charCodeAt(i+1);
    				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
    				i += 2;
    			}
    			else {
    				c2 = utftext.charCodeAt(i+1);
    				c3 = utftext.charCodeAt(i+2);
    				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
    				i += 3;
    			}

    		}

    		return string;
    	}

    }



// глобальные настройки аякс запроса
/**
 * Global AJAX setup
 */
$.ajaxSetup({
	type: 'POST',
	dataType: 'json',
//	async: false,
	cache: false,
	timeout: 5000,
	complete: function (XMLHttpRequest, textStatus) {
		
		//this; // the options for this ajax request
	//	console.info('ajax has finished, status: ' + textStatus);
		if (textStatus == 'timeout') {
			alert(lang.ajax_timeout);
			if (typeof( matrix.send_query ) != 'undefined' ) {
				matrix.send_query = false;
				$('#matrix_load').hide();
			}
		}
	}

});

// основной объект галереи
var gallery = {
		config : {
			
		},
		// объект изменения ширины столбцов
		resize_column : {
			myWidth: null, // ширина 
			myHeight: null, // высота
			res: false,
			// функция изменения ширины столбцов
			resize : function(pageX) {
				var self = this;
				
				$('#sidebar').width(pageX + 2);
				$('#sidebar .block').width(pageX-7);
				$('#sidebar #statistics').width(pageX-27);
				// fix content width on resize
			//	$('#content').css("left",pageX);
				
				if( typeof( window.innerWidth ) == 'number' ) {
					//Non-IE
					self.myWidth = window.innerWidth;
					self.myHeight = window.innerHeight;
				} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
					//IE 6+ in 'standards compliant mode'
					self.myWidth = document.documentElement.clientWidth;
					self.myHeight = document.documentElement.clientHeight;
				}
				$('#content').width(self.myWidth - $('#sidebar').width() + 2);
				$('#list_panel').width($('#content').width()-26);
				
				
			},
			// функция инициализации
			init: function() {
				var self = this;
				$('.block','#sidebar').width($('#sidebar').width()-9);
				$('#statistics','#sidebar').width($('.block','#sidebar').width()-20);
				// обработка изменение ширины используя вертикальный разделитель
				$('#handler_vertical').mousedown(function(e){
					self.res = true;	
					e.preventDefault();
					$(document).mousemove(function(e){
						self.resize(e.pageX);
					})
				});
				$(document).mouseup(function(e){
					if (self.res){
						self.res = false;
						matrix.resize();
						gallery.cookie.set('resize_column', $('#sidebar').width()-2);
					}
					$(document).unbind('mousemove');
				});
				// востанавливаем расположения из куков
				pageX = parseInt(gallery.cookie.get('resize_column'));
				
				if (pageX) {
					self.resize(pageX);
				}
				
			}
		},
		cookie : {
			config: {
				"days": "30",
				"path": "/avreg/offline/gallery.php",
				"name" : "gallery"
			},
			getobject : function() {
				var self = this;
				var strcook = '';
				if (ReadCookie(self.config.name))
					strcook = Base64.decode(ReadCookie(self.config.name));
				
				var objcook = {};
				if (strcook) {
					objcook = $.parseJSON(strcook);
				}
				return objcook;
			},
			setobject : function (objcook) {
				var self = this;
				var strcook = Base64.encode(JSON.stringify(objcook));
				SetCookie(self.config.name, strcook, self.config.days, self.config.path);
			},
			set : function (name, value) {
				var self = this;
				var objcook = self.getobject();
				objcook[name] = value;
				self.setobject(objcook);
			},
			get : function (name) {
				var self = this;
				var objcook = self.getobject();
				if (typeof(objcook[name]) != 'undefined' ) {
					return objcook[name];
				}
				return false;
			},
			init: function(config) {
				var self = this;
				// обновление настроек
				if (config && typeof(config) == 'object') {
				    $.extend(self.config, config);
				}
			}
		},
		// объект построения дерева событий
		tree_event : {
			holder: null,
			// функция обновления дерева
			reload : function(){
				var self = this;
				// получения настроек формирование дерева
				var variable = {};
				$('input[name="type_event"]').each(function(){
					if ($(this).attr('checked')) {
						// по типу (изображения, видео, аудио)
						var type = $(this).val();
						// какие камеры выбраны
						$('input[name="cameras"]').each(function(){
							if ($(this).attr('checked')) {
								var k = type + '_' + $(this).val();
								variable[k] = 1;
							}
						});
					}
				});
				
				// js кеш нового дерева
				matrix.curent_tree_events = {
						all : {
							size : 0,
							count: 0
						}
				};
				// html код дерева
				var html = '<ul><li id="tree_all"><a href="#">'+lang.all+'</a><ul>';
				// предыдущее событие
				var old_value = false;
				// предыдущий год, месяц, день
				var o0 = false, o1 = false, o2 = false;
				var ii = 0;
				$.each(matrix.tree_events, function( i,value) {
					// временной диапазон
					var key = value.date;
					// размер временного диапазона
					var size = 0;
					// количество файлов во временном диапазоне
					var count = 0;
					//считаем размер и количество файлов в временном диапазоне в выбранных настройках
					$.each(variable, function(k, v) {
						if (typeof(value[k+'_size']) != 'undefined' ) {
							size += parseFloat(value[k+'_size']);
						}
						if (typeof(value[k+'_count']) != 'undefined' ) {
							count += parseInt(value[k+'_count']);
						}
					});
					// если не пучто, то строим дерево
					if (count > 0 && size > 0) {
						// разбиваем дату на год месяц день
						var e = key.split('_');
						var year = e[0];
						var month = e[1];
						var day = e[2];
					
						// определяем самый первый диапазон для всего дерева
						if (ii == 0) {
							matrix.curent_tree_events['all'].from = e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0];
							ii++;
						}
						// обновляем самы последний диапазон для всего дерева
						matrix.curent_tree_events['all'].to = e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0];
						// если есть предыдущее событие
						if (old_value != false) {
							var o = old_value.split('_');
							// и оно не относиться к дню текущего события, то закрываем день
							if (e[0]+'_'+e[1]+'_'+e[2] != o[0]+'_'+o[1]+'_'+o[2]) {
								html += '</ul>';
								matrix.curent_tree_events[o[0]+'_'+o[1]+'_'+o[2]].to = o[3]+':00 ' + o[2] + ' ' + monthNames[parseInt(o[1])]+ ' ' + o[0];
								matrix.curent_tree_events[o[0]+'_'+o[1]+'_'+o[2]].next = e[0]+'_'+e[1]+'_'+e[2];
								o2 = o[0]+'_'+o[1]+'_'+o[2];
							}
							// и оно не относиться к месяцу текущего события, то закрываем месяц
							if (e[0]+'_'+e[1] != o[0]+'_'+o[1]) {
								html += '</ul>';
								matrix.curent_tree_events[o[0]+'_'+o[1]].to = o[3]+':00 ' + o[2] + ' ' + monthNames[parseInt(o[1])]+ ' ' + o[0];
								matrix.curent_tree_events[o[0]+'_'+o[1]].next = e[0]+'_'+e[1];
								o1 = o[0]+'_'+o[1];
							}
							// и оно не относиться к году текущего события, то закрываем год
							if (e[0] != o[0]) {
								html += '</ul>';
								matrix.curent_tree_events[o[0]]['to'] = o[3]+':00 ' + o[2] + ' ' + monthNames[parseInt(o[1])]+ ' ' + o[0];
								matrix.curent_tree_events[o[0]].next = e[0];
								o0 = o[0];
							}
						}
						// обновляем размер и количество файлов всего дерева
						matrix.curent_tree_events['all'].size += size;
						matrix.curent_tree_events['all'].count += count;
						
						// если в кеше нет года текущего события, то..
						if (typeof(matrix.curent_tree_events[e[0]]) == 'undefined' ) {
							//записываем новые данные в кеш
							matrix.curent_tree_events[e[0]] = {
									size : size,
									count : count,
									from : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
									to : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
									prev : o0
							};
							// строим дерево
							html += '<li id="tree_'+e[0]+'"><a href="#">'+e[0]+'</a><ul>';
						} else {
							//если есть то обновляем размер и количество
							matrix.curent_tree_events[e[0]].size += size;
							matrix.curent_tree_events[e[0]].count += count;
						}
						
						// если в кеше нет месяца текущего события, то..
						if (typeof(matrix.curent_tree_events[e[0]+'_'+e[1]]) == 'undefined' ) {
							//записываем новые данные в кеш
							matrix.curent_tree_events[e[0]+'_'+e[1]] = {
									size : size,
									count : count,
									from : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
									to : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
									prev: o1
							};
							// строим дерево
							html += '<li id="tree_'+e[0]+'_'+e[1]+'"><a href="#">'+monthNames[e[1].replace('0','')]+'</a><ul>';
						} else {
							//если есть то обновляем размер и количество
							matrix.curent_tree_events[e[0]+'_'+e[1]].size += size;
							matrix.curent_tree_events[e[0]+'_'+e[1]].count += count;
						}
						
						// если в кеше нет дня текущего события, то..
						if (typeof(matrix.curent_tree_events[e[0]+'_'+e[1]+'_'+e[2]]) == 'undefined' ) {
							//записываем новые данные в кеш
							matrix.curent_tree_events[e[0]+'_'+e[1]+'_'+e[2]] = {
									size : size,
									count : count,
									from : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
									to : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
									prev : o2
							};
							// строим дерево
							html += '<li id="tree_'+e[0]+'_'+e[1]+'_'+e[2]+'"><a href="#">'+e[2]+'</a><ul>';
						} else {
							//если есть то обновляем размер и количество
							matrix.curent_tree_events[e[0]+'_'+e[1]+'_'+e[2]].size += size;
							matrix.curent_tree_events[e[0]+'_'+e[1]+'_'+e[2]].count += count;
						}
						
						//записываем новые данные о события в кеш
						matrix.curent_tree_events[key] = {
								size : size,
								count : count,
								from : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
								to : e[3]+':00 ' + e[2] + ' ' + monthNames[e[1].replace('0','')]+ ' ' + e[0],
								next : false,
								prev : old_value
						};
						// строим дерево
						html += '<li id="tree_'+key+'"><a href="#">'+e[3]+':00</a>';
						// записываем следующее события
						if (old_value) {
							matrix.curent_tree_events[old_value].next = key;
						}
						// сохраняем старое событие
						old_value = key;
					}
				});
				html += '</ul></ul>';
				// высчитываем новый выбранный диапазон событий если старого в новом дереве нет 
				if (matrix.tree != 'all' && typeof(matrix.curent_tree_events[matrix.tree]) == 'undefined') {
					var str = matrix.tree;
					while (str != ''){
						var end = str.lastIndexOf( '_' ); 
						str = str.substr(0, end);
						if (typeof(matrix.curent_tree_events[str]) != 'undefined') {
							matrix.tree = str;
							break;
						}
					}
				}
				if (typeof(matrix.curent_tree_events[matrix.tree]) == 'undefined') {
					matrix.tree = 'all';
				}
			
				var open = '#tree_'+matrix.tree;
				var parent = $(self.holder).parent().hide();
				
				$("#tree_new").remove();
				parent.append('<div id="tree_new"></div>');
				// построение дерева
				$(self.holder).html(html)
					.jstree({
						"core" : {animation : 0},
						"plugins" : ["themes","html_data","ui","crrm"]
					})
					// событие возникает, если пользователь выбрал новый диапазон событий
					.bind("select_node.jstree", function (event, data) { 
						
						tree = data.rslt.obj.attr("id").replace('tree_', '');
						
						// если новый диапазон, перестраиваем матрицу
						if (matrix.tree != tree) {
							matrix.tree = tree;
							matrix.build();
						}
						// если режим детального просмотра, обновляем картинку
						if (matrix.mode == 'detail') {
							matrix.preview();
						}
					})
					.bind("loaded.jstree", function (event, data) {
						$.jstree._focused().select_node(open);
						$.jstree._focused().open_node(open);
						$('#tree').show();
					})
				.delegate("a", "click", function (event, data) { event.preventDefault(); }).show();	
				
				
				matrix.build();
			},
			// инициалзация дерева
			init: function(holder) {
				var self = this;
				self.holder = holder;
				// получаем данные о постройке дерева события
				$.post(WwwPrefix+'/offline/gallery.php', {'method': 'get_tree_events'}, function(data) {
					if (data.status == 'success'){
						matrix.tree_events = data.tree_events;
						matrix.cameras = data.cameras;
						gallery.tree_event.reload();
					}
				});
			}
		},
		// объект управлением цветом камер
		cameras_color : {
			camera_id : '', // ид выбранной камеры
			camera_title : '', // заголовок выбранной камеры
			camera_collor : '', // цвет выбранной камеры
			camera_link : '', // ссылка на камеру
			// показываем окно выбора цвета
			open: function() {
				$('#overlay').show();
				$('#cameras_color').show();
			},
			// закрываем окно выбора цвета
			close : function() {
				$('#cameras_color').hide();
				$('#overlay').hide();
			},
			// выбор нового цвета камеры
			select : function() {
				var self = this;
				// читаем старый цвет камеры
				old_camera_collor = gallery.cookie.get('camera_'+self.camera_id+'_color');
				// записываем новый цвет камеры
				gallery.cookie.set('camera_'+self.camera_id+'_color',self.camera_collor);
				//удаляем старый цвет камеры 
				if (old_camera_collor != '') {
					$('.camera_'+self.camera_id).removeClass(old_camera_collor);
					self.camera_link.removeClass(old_camera_collor + '_font');
				}
				// устанавливаем новый цвет
				$('.camera_'+self.camera_id).addClass(self.camera_collor);
				self.camera_link.addClass(self.camera_collor+'_font');
				
			},
			
			init:	function() {
				var self = this;
				// обработка нажатия ссылки камеры
				$('.set_camera_color').click(function(e){
					e.preventDefault();
					self.camera_id = $(this).attr('href').replace('#','');
					self.camera_title = $(this).html();
					self.camera_link = $(this);
					self.open();
					return false;
				});
				// обработка выбора цвета камеры
				$('#cameras_color .window_body li').click(function(){
					self.camera_collor = $(this).attr('class');
					self.select();	
					self.close();
				});
				
				// обработка закрытия окна
				$('#cameras_color .close').click(function(){
					self.close();
				});
				
			}
		},
		// объект, показывающий сообщения хочет ли пользователь перейти на следующий временной диапазон
		nextwindow : {
			mode: '', // вверх или вниз по дереву
			// показ окна
			open: function(mode) {
				var self = this;
				self.mode = mode;
				$('#overlay').show();
				$('#nextwindow').show();
			},
			// закрытие окна
			close : function() {
				$('#nextwindow').hide();
				$('#overlay').hide();
			},
			// если пользователь нажал да
			select : function() {
				var self = this;
				// запомнить выбор если выбрана галочка
				if ($('#checknextwindow').attr('checked')) {
					gallery.cookie.set('checknextwindow','yes');
				}
				
				if (self.mode == 'left') {
					// если пользователь идет вверх по дереву
					// обновляем матрицу и перемещаем текущий указатель в конец матрицы
					prev = matrix.curent_tree_events[matrix.tree].prev;
					new_num = matrix.curent_tree_events[prev].count - 1;
					sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
					matrix.num = new_num;
					scroll.position = sp;
					matrix.select_node = 'left';
					$.jstree._focused().deselect_node("#tree_"+matrix.tree);
					$("#tree_"+prev).jstree("set_focus");
					$.jstree._focused().select_node("#tree_"+prev);
					scroll.setposition(sp);
					
				} else if (self.mode == 'right') {
					// если пользователь идет вниз по дереву
					// обновляем матрицу и перемещаем текущий указатель в начало матрицы
					next = matrix.curent_tree_events[matrix.tree].next;
					matrix.num = 0;
					matrix.select_node = 'right';
					$.jstree._focused().deselect_node("#tree_"+matrix.tree);
					$("#tree_"+next).jstree("set_focus");
					$.jstree._focused().select_node("#tree_"+next);
				}
				
			},
			init:	function() {
				var self = this;
				// обработка события если пользователь нажал да
				$('#nextwindow .yes').click(function(){
					self.select();	
					self.close();
				});
				// обработка события если пользователь нажал нет
				$('#nextwindow .no').click(function(){
					if ($('#checknextwindow').attr('checked')) {
						gallery.cookie.set('checknextwindow','no');
					}
					self.close();
				});
				
			}
			
			
		},
		// инициализация галереи
		init : function(config) {
			var self = this;
			// обновление настроек
			if (config && typeof(config) == 'object') {
			    $.extend(self.config, config);
			}
			
			$('#matrix_load').show();
			
			self.cookie.init({path:WwwPrefix+'/offline/gallery.php'});
			
			// организация увеличение размера списка камер
			if ($('#win_top').height() > 70) {
				$('#more_cam').show();
				self.win_top = $('#win_top').height();
				$('#win_top').hover(
						function(){
							$('#more_cam').hide();
							$('#win_top').height(self.win_top);
						},
						function(){
							$('#more_cam').show();
							$('#win_top').height(70);
						}
				);
			}
			$('#win_top').height(70);
			
			// обработка выбора чекбокса камеры 
			$('input[name="cameras"]').click(function(){
				var count = 0;
				var cook = '';
				$('input[name="cameras"]').each(function(){
					if ($(this).attr('checked')) {
						count++;
						cook += $(this).val()+',';
					}
				});
				if (count >0 ){
					gallery.cookie.set('cameras',cook);
					// обновляем дерево
					self.tree_event.reload();
				} else {
					// не дадим пользователю снять последний чекбокс
					$(this).attr('checked', 'checked');
					alert(lang.empty_cameras);
				}
			});
			
			// обработка выбора чекбокса типа события 
			$('input[name="type_event"]').click(function(){
				var count = 0;
				var cook = '';
				$('input[name="type_event"]').each(function(){
					if ($(this).attr('checked')) {
						count++;
						v = $(this).val();
						cook += v.substr(0,1)+',';
					}
				});
				if (count >0 ){
					gallery.cookie.set('type_event', cook);
					// обновляем дерево
					self.tree_event.reload();
				} else {
					// не дадим пользователю снять последний чекбокс
					$(this).attr('checked', 'checked');
					alert(lang.empty_event);
				}
			});
			
			
			
			// инициализация изменения размеров столбцов
			self.resize_column.init();
			
			// инициализация матрицы
			
			matrix.init(self.config.matrix);
			
			// инициализация дерева событий
			self.tree_event.init('#tree_new');
			// инициализация выбора цвета камеры
			self.cameras_color.init();
			
			// инициализация перехода на следующий временной диапазон
			self.nextwindow.init();
		}
}



// основной объект матрицы
var matrix = {
	config : {
		cell_padding: 5, // паддинг ячейки
		cell_border : 0, // толщина бордера ячейки
		min_cell_width : 192, // минимальная ширина ячейки
		min_cell_height : 192, // минимальная высота ячейки
		max_cell_width: 0,  // максимальная ширина ячейки
		max_cell_height: 0,  // максимальная высота ячейки
		event_limit : 20000
	},
	tree: 'all', // текущий временной диапазон
	height: 0, // текущая высота матрицы
	width: 0, // текущая ширина матрицы
	cell_height: 0, // высота ячейки
	cell_width: 0, // ширина ячейки
	thumb_width : 0, // ширина миниатюры
	thumb_height : 0, //высота миниатюры
	cell_count: 2, // количество ячеек
	count_row : 5, //количество строк в матрице
	count_column: 5, // количество столцов в матрице
	events : {}, // текущие евенты в матрице
	all_events : {}, // кеш евентов
	num : 0, // текущая позиция в матрице
	scroll: false, // использование скрола
	count_src : 0, 
	load_src: 0,
	mode : 'preview', // режим просмотра
	cur_count_item : 0, // текущее количество загруженных событий
	send_query: false, // можно ли посылать запросы к базе
	select_node : false, // можно ли выбирать другой диапазон
	init: function(config) {
		
		if (config && typeof(config) == 'object') {
		    $.extend(matrix.config, config);
		}
		
		// обновление ширины и высоты ячейки
		matrix.cell_height = matrix.config.min_cell_height;
		matrix.cell_width = matrix.config.min_cell_width;
		
		// обработка переключение режима матрицы
		$('.matrix_mode a').click(function(e) {
			e.preventDefault();
			var mode = $(this).attr('href').replace('#','');
			matrix[mode]();
			return false;
		});
		
		$('#scroll_content .content_item .img_block a').live('click', function(e) {
			e.preventDefault();
			matrix.num = parseInt($(this).attr('href').replace('#cell_',''));
			matrix.detail();
			return false;
		});
		
		// обработка чекбокса сохранять пропорции
		$('#proportion').click(function(){
			$('#scroll_content .show').each(function() {
				matrix.setimagesize($(this).attr('id').replace('cell_',''));
			});
			matrix.loaddetailsrc();
			if ($(this).attr('checked')) {
				gallery.cookie.set('proportion', 'checked');
			} else {
				gallery.cookie.set('proportion', '');
			}
		});
			
		// обработка чекбокса показывать информацию
		$('#info').click(function(){
			if ($(this).attr('checked')) {
				gallery.cookie.set('info', 'checked');
				matrix.thumb_height -= 24;
				$('.content_item .info_block').show();
			} else {
				gallery.cookie.set('info', '');
				matrix.thumb_height += 24;
				$('.content_item .info_block').hide();
			}
			$('#scroll_content .show').each(function() {
				matrix.setimagesize($(this).attr('id').replace('cell_',''));
			});
		});
		
		// обновление матрицы
		matrix.resize();
		//инициализации элемента масштаба режима миниатюр
		scale.init();
		
		//инициализации элемента масштаба детального режима 
		scale2.init();
		
		
		self.res = false
		// изменить размер матрицы если было изменено размеры окна
		$(window).bind("resize", function(){
		//	console.log($(window).height());
			clearInterval(self.res);
			self.res = setTimeout(function() {matrix.resize(); clearInterval(self.res);}, 200) 
			
		});
		
	
	},
	// если включили режим детальный просмотр
	detail : function() {
		matrix.mode = 'detail';
		matrix.loaddetailsrc();
		$('#win_bot').hide();
		$('#toolbar .preview').hide();
		$('#win_bot_detail').show();
		$('#toolbar .detail').show();
	},
	// если включили режим миниатюр
	preview : function() {
		if (typeof(matrix.curent_tree_events[matrix.tree]) != 'undefined') {
			matrix.mode = 'preview'; 	
			// обновлаем статистику
			var stat = '<span><strong>'+lang.count_files+'</strong>'+matrix.curent_tree_events[matrix.tree].count+'</span><br />\
			<span><strong>'+lang.size_files+'</strong>'+readableFileSize(matrix.curent_tree_events[matrix.tree].size)+'</span><br />\
			<span><strong>'+lang.date_from+'</strong>'+matrix.curent_tree_events[matrix.tree].from+'</span><br />\
			<span><strong>'+lang.date_to+'</strong>'+matrix.curent_tree_events[matrix.tree].to+'</span><br />'
			$('#statistics').html(stat);
			
			$('#win_bot_detail').hide();
			$('#toolbar .detail').hide();
			$('#win_bot').show();
			$('#toolbar .preview').show();
			
			// обновляем матрицу с использованием новой позиции
			if (!$('#cell_'+matrix.num).hasClass('show')){
				sp = Math.floor(matrix.num / scroll.row_count) * scroll.row_count;
				scroll.updateposition(sp);
				scroll.setposition(sp);
			}
			$('#scroll_content .content_item').removeClass('active');
			$('#cell_'+matrix.num).addClass('active');
		}
	},
	// перестраиваем матрицу при зменении размеров
	resize: function() {
		// обновляем ширину колонок
		gallery.resize_column.resize($('#sidebar').width()-2);
		$('#tree').height($('#sidebar').height() - $('#type_event').height() - $('#favorite').height() - $('#statistics').height()-90);
		
		// высчитываем размеры табнейлов 
		matrix.thumb_width = matrix.cell_width-matrix.config.cell_padding*2;
		matrix.thumb_height = matrix.cell_height-matrix.config.cell_padding*2;
		
		// показываем или скрываем информацию о событии
		if ($('#info').attr('checked')) {
			matrix.thumb_height -= 24;
			$('.content_item .info_block').show();
		} else {
			$('.content_item .info_block').hide();
		}
		// определяем новые размеры матрицы
		matrix.height = $('#list_panel').height()-140;
		matrix.width = $('#list_panel').width();
		$('#matrix_load img').css('margin-top', matrix.height/2);
		
		// обновляем размеры детального просмотра
		$('#win_bot_detail').height($('#content').height() - $('#win_top').height() - $('#toolbar').height()-30);
		$('#win_bot_detail').width($('#content').width() - $('#win_top').width() - $('#toolbar').width());

		
		// высчитываем новую высоту и ширину ячейки
		var old_width = matrix.config.max_cell_width;
		if ((matrix.height-(matrix.config.cell_padding+matrix.config.cell_border)*2) > (matrix.width/2 -(matrix.config.cell_padding+matrix.config.cell_border)*4)) {
			matrix.config.max_cell_width = (matrix.width/2 -(matrix.config.cell_padding+matrix.config.cell_border)*4)
			matrix.config.max_cell_height = (matrix.width/2 -(matrix.config.cell_padding+matrix.config.cell_border)*4)
		} else {
			matrix.config.max_cell_width = matrix.height-(matrix.config.cell_padding+matrix.config.cell_border)*2;
			matrix.config.max_cell_height = matrix.height-(matrix.config.cell_padding+matrix.config.cell_border)*2;
		}
		if (matrix.config.max_cell_width < matrix.cell_width || matrix.config.max_cell_height < matrix.cell_height) {
			matrix.cell_height = matrix.config.max_cell_height;
			matrix.cell_width = matrix.config.max_cell_width;
			
		}
		
		
		// обновляем элемент масштаба
		if (old_width != matrix.config.max_cell_width) {
			scale.reload(old_width);
		}
		
		// задаем новые размеры ячейки
		$('#scroll_content .content_item').height(matrix.cell_height);
		$('#scroll_content .content_item').width(matrix.cell_width);

		// высчитываем количество, рядов, столбцов ячеек в матрице
		matrix.count_column = Math.floor(matrix.width /  (matrix.cell_width+(matrix.config.cell_padding+matrix.config.cell_border)*2));
		matrix.count_row = Math.floor(matrix.height /  (matrix.cell_height+(matrix.config.cell_padding+matrix.config.cell_border)*2));
		matrix.cell_count =  matrix.count_column * matrix.count_row;
		
		// центрируем содержимое в ячейках
		var left =  Math.floor((matrix.width-matrix.count_column*matrix.cell_width)/ matrix.count_column/2);
		var top =  Math.floor((matrix.height-matrix.count_row*matrix.cell_height)/ matrix.count_row/2);
		matrix.cell_padding = top + 'px ' + left + 'px';
		$('#scroll_content .content_item').css({'padding':matrix.cell_padding});
		
		// если элемента скрола нет, то создаем его
		if (matrix.scroll == true) {
			var sp = scroll.position;
			scroll.init({height:matrix.height-28, cell_count:Math.ceil(matrix.count_item/matrix.count_column), row_count: matrix.count_column, matrix_count: Math.ceil(matrix.cell_count/matrix.count_column)});
			sp = Math.floor(sp/scroll.row_count)*scroll.row_count;
			scroll.updateposition(sp, true);	
			scroll.setposition(sp);
		
		}
	},
	// задаем размер изображения в ячейке
	setimagesize : function(el) {
		
		if (typeof(matrix.events[el]) != 'undefined') {
			var thumb_width = matrix.thumb_width;
			var thumb_height = matrix.thumb_height;
			
			if ($('#proportion').attr('checked')) {
				// если выбран чекбокс сохранять пропорции
				var w = thumb_width;
				var h = Math.floor(matrix.events[el][3]*w/matrix.events[el][4]);
				
				if (h > thumb_height) {
					h = thumb_height;
					w = Math.floor(matrix.events[el][4]*h/matrix.events[el][3]);
				}
				
				thumb_width = w;
				thumb_height = h;
			}
			
			// задаем новые размеры
			$('#cell_'+el+' .img_block img').attr('width',thumb_width);	
			$('#cell_'+el+' .img_block img').attr('height',thumb_height);	
		}
	},
	// загружаем изображение в окно детального просмотра
	loaddetailsrc : function() {
		if (typeof(matrix.events[matrix.num]) != 'undefined') {
			var value = matrix.events[matrix.num];

			$('#image_detail').attr('src', MediaUrlPref+value[2]);

			// размер матрицы
			var width = matrix.width;
			var height = matrix.height;
			// максимальный размер увеличения
			var wm = width*2;
			var hm = height*2;
			if ($('#proportion').attr('checked')) {
				// если выбран режим сохранять пропорции
				if (value[3] < matrix.height && value[4] < matrix.width) {
					// если изображение влазиет в окно просмотра, то используем оригинальные размеры
					width = value[4];
					height = value[3];
					wm = width*2;
					hm = height*2;
				} else {
					// если не влазит то используем ширину матрицы а высоту в впропорциях изменяем
					var w = matrix.width;
					var h = Math.floor(value[3]*w/value[4]);
					wm = value[4];
					hm = Math.floor(h*value[4]/w);
					
					// если высота не влазит, то используем высоту матрицы, а ширину подгоняем в пропорциях
					if (h > matrix.height) {
						h = matrix.height;
						w = Math.floor(value[4]*h/value[3]);
						hm = value[3];
						wm = Math.floor(w*value[4]/h);
					}
					
					width = w;
					height = h;
				}
			} 
			// устанавливаем новую ширину и высоту
			$('#image_detail').attr('width', width);
			$('#image_detail').attr('height', height);
			
			// обновляем параметры элемента масштаба
			scale2.min_width = width;
			scale2.min_height = height;
			scale2.max_width = wm;
			scale2.max_height = hm;
			scale2.reload();
			
			// обновляем статистику события
			var stat = '<span><strong>'+lang.camera+'</strong>'+matrix.cameras[value[5]].text_left+'</span><br />\
				<span><strong>'+lang.size+'</strong>'+value[6]+'</span><br />\
				<span><strong>'+lang.WH+'</strong>'+value[4]+'x'+value[3]+'</span><br />\
				<span><strong>'+lang.date+'</strong>'+value[1]+'</span><br />'
			$('#statistics').html(stat);
		}
	},
	// загрузка изображения
	loadsrc : function(el) {
		// увеличиваем счетчик изображений
		matrix.count_src++;
		if (matrix.count_src > matrix.load_src) {
			// если количество загруженных изображений меньше количество всего изображений, показываем ромашку
			$('#matrix_load').show();
		}
		// создаем объект изображения
		var img = new Image();
		img.onload = function() { 
			//изображение загрузилось
			// показываем картинку в ячейке

			$('#cell_'+el+' .img_block img').attr('src', MediaUrlPref + matrix.events[el][2]); 

			// задаем новые размеры изображения
			matrix.setimagesize(el);
			// обновляем счетчик загруженных изображений
			matrix.load_src++; 
			if (matrix.load_src == matrix.count_src) {
				// если все изображения загружены, то убираем ромашку
				$('#matrix_load').hide();
			}
			// записываем в кеш, что изображение уже загрузилось и есть в кеше браузера
			matrix.events[el].image_chache = true;
			};
			
		img.onerror = function() {
			//изображение не загрузилось
			// показываем картинку ошибки в ячейке
			$('#cell_'+el+' .img_block img').attr('src', WwwPrefix+'/offline/gallery/img/error.jpg'); 
			// задаем новые размеры изображения
			matrix.setimagesize(el);
			// обновляем счетчик загруженных изображений
			matrix.load_src++; 
			if (matrix.load_src == matrix.count_src) {
				// если все изображения загружены, то убираем ромашку
				$('#matrix_load').hide();
			}
		};	
		// загружаем изображение
		img.src = MediaUrlPref + matrix.events[el][2];

	},

	// обовление матрицы
	update : function(sp) {
		$('#matrix_load').show();
		$('#scroll_content').empty();
		var html = '';
		var i = sp;
		var active = '';
		var get = false;
		// чистим кэш
		var aa = 0;
		$.each(matrix.events, function( i,value) {
			aa++;
		});
		
		var dev = aa - matrix.config.event_limit;
		if (dev > 0) {
			$.each(matrix.events, function( i,value) {
				delete matrix.events[i];
				dev--;
				if (dev <= 0) {
					 return false;
				}
			});
		}
			
		// происходит проверка, есть ли необходимые элементы в кеше
		var count_events = matrix.cell_count > matrix.curent_tree_events[matrix.tree].count ? matrix.curent_tree_events[matrix.tree].count : matrix.cell_count;
		for (var i = sp; i < sp + count_events; i++) {
			if (typeof( matrix.events[i]) == 'undefined') {
				get = true;
				break;
			}
		}
		if (get) {
			// нет необходимых элементов в кеше, делаем запрос
			matrix.get_events(sp);
		} else {
			// все элементы матрицы есть в кеше, строим матрицу
			var loadimage = {};
			for (var i = sp; i < sp+ matrix.cell_count; i++) {
				if (typeof( matrix.events[i]) != 'undefined') {
					value = matrix.events[i];
					
					active = i == matrix.num ? ' active' : '';
					camera_class = gallery.cookie.get('camera_'+value[5]+'_color');
					if (camera_class != '') {
						camera_class = ' '+camera_class;
					}
					html += '<div id="cell_'+i+'" class="content_item show'+active+' camera_'+value[5]+' '+camera_class+'">';
					
					if (value[7] == 'image') {
					
						if (typeof( value.image_chache) != 'undefined' && value.image_chache) {
							html += '<div class="img_block"><a href="#cell_'+i+'"><img src="'+MediaUrlPref + value[2]+'" /></a></div>';
							loadimage[i] = true;
							
						} else {
							html += '<div class="img_block"><a href="#cell_'+i+'"><img src="" /></a></div>';
							loadimage[i] = false;
							
						}
					} else if (value[7] == 'video') {
						html += '<div class="img_block">'+value[2]+'</div>';
					} else if (value[7] == 'audio') {
						html += '<div class="img_block">'+value[2]+'</div>';
					}
					
					html += '<div class="info_block"';
					if ($('#info').attr('checked')) {
						html += ' style="display:block;"';
					} else {
						html += ' style="display:none;"';
					}
					html += '>'+matrix.cameras[value[5]].text_left+'<br />\
						'+value[6]+' \
						'+value[4]+'x'+value[3]+'<br />\
						</div>';
					html += '</div>';
				}
			}
			$('#scroll_content').html(html);
			// проверяем какие изображения есть в кеше браузера, а какаие надо загрузить
			var ci = i + matrix.count_column;
			var hide_over = true;
			for(i; i<=ci; i++) {
				if (typeof( matrix.events[i]) != 'undefined') {
					if (typeof( matrix.events[i].image_chache) != 'undefined' && matrix.events[i].image_chache) {
						loadimage[i] = true;
					} else {
						loadimage[i] = false;
						hide_over = false;
					}
				}
			}
			// загружаем изображения и меняем размеры
			$.each(loadimage, function(key, value) {
				if (value) {
					matrix.setimagesize(key);
				}else {
					matrix.loadsrc(key);
				}
			});
			// обновляем размеры и позиционирование ячеек в матрице
			$('#scroll_content .content_item').height(matrix.cell_height);
			$('#scroll_content .content_item').width(matrix.cell_width);
			$('#scroll_content .content_item').css({'padding' : matrix.cell_padding });
			if (hide_over) {
				$('#matrix_load').hide();
			}
		}
	},
	// выполнения запроса новых событий
	get_events : function (sp) {
		// определяем тип событий и список камер
		var type = '', cameras = '';
		// проверяем закончился ли предыдущий запрос
		if (!matrix.send_query) {
			// устанавливаем флаг что запрос выполняеться
			matrix.send_query = true;
			var variable = [];
			var i = 0;
			$('input[name="type_event"]').each(function(){
				if ($(this).attr('checked')) {
					type += $(this).val()+',';
				}
			});
			$('input[name="cameras"]').each(function(){
				if ($(this).attr('checked')) {
					cameras += $(this).val()+',';
					variable[i] =  $(this).val();
					i++;
				}
			});
			
			// определяем с какой позиции загружать события
			var get_sp = sp;
			if (matrix.select_node == 'left' ) {
				if (sp - matrix.config.limit+ matrix.cell_count > 0) {
					get_sp = sp - matrix.config.limit+ matrix.cell_count;
				} else {
					get_sp = 0;
				}
			} 
			// делаем запрос
			$.post(WwwPrefix+'/offline/gallery.php',{'method':'get_events', 'tree':matrix.tree, 'sp':get_sp, 'type': type, 'cameras': cameras}, function(data) {
				var i = get_sp;
				
				// обновляем кеш
				$.each(data.events, function(key, value) {
					matrix.all_events[key] = value;
					matrix.events[i] = value;
					i++
				});
				// строим матрицу
				$('#scroll_content').empty();
				var html = '';
				var i = sp;
				var active = '';
				
				var loadimage = {};
				for (var i = sp; i < sp+ matrix.cell_count; i++) {
					if (typeof( matrix.events[i]) != 'undefined') {
					value = matrix.events[i];
					active = i == matrix.num ? ' active' : '';
					camera_class = gallery.cookie.get('camera_'+value[5]+'_color');
					if (camera_class != '') {
						camera_class = ' '+camera_class;
					}
					html += '<div id="cell_'+i+'" class="content_item show'+active+' camera_'+value[5]+' '+camera_class+'">';
					
					if (value[7] == 'image') {
						
						if (typeof( value.image_chache) != 'undefined' && value.image_chache) {
							html += '<div class="img_block"><a href="#cell_'+i+'"><img src="'+MediaUrlPref + value[2]+'" /></a></div>';
							loadimage[i] = true;
							
						} else {
							html += '<div class="img_block"><a href="#cell_'+i+'"><img src="" /></a></div>';
							loadimage[i] = false;
							
						}
					} else if (value[7] == 'video') {
						html += '<div class="img_block">'+value[2]+'</div>';
					} else if (value[7] == 'audio') {
						html += '<div class="img_block">'+value[2]+'</div>';
					}
					
					html += '<div class="info_block"';
					if ($('#info').attr('checked')) {
						html += ' style="display:block;"';
					} else {
						html += ' style="display:none;"';
					}
					html += '>'+matrix.cameras[value[5]].text_left+'<br />\
						'+value[6]+' \
						'+value[4]+'x'+value[3]+'<br />\
						</div>';
					html += '</div>';
					}
				};
				$('#scroll_content').html(html);
				// проверяем какие изображения есть в кеше браузера, а какаие надо загрузить
				var ci = i + matrix.count_column;
				var hide_over = true;
				for(i; i<=ci; i++) {
					if (typeof( matrix.events[i]) != 'undefined') {
						if (typeof( matrix.events[i].image_chache) != 'undefined' && matrix.events[i].image_chache) {
							loadimage[i] = true;
						} else {
							loadimage[i] = false;
							hide_over = false;
						}
					}
				}
				// загружаем изображения и меняем размеры
				$.each(loadimage, function(key, value) {
					if (value) {
						matrix.setimagesize(key);
					}else {
						matrix.loadsrc(key);
					}
				});
				// обновляем размеры и позиционирование ячеек в матрице
				$('#scroll_content .content_item').height(matrix.cell_height);
				$('#scroll_content .content_item').width(matrix.cell_width);
				$('#scroll_content .content_item').css({'padding' : matrix.cell_padding });
				// устанавливаем флаг, что запрос выполнился
				matrix.send_query = false;
				if (hide_over) {
					$('#matrix_load').hide();
				}
			});
		}
	},
	// постройка матрицы временного диапазона
	build: function(){
		$('#matrix_load').show();
		matrix.cur_count_item = 0;
		
		if (typeof( matrix.curent_tree_events[matrix.tree]) != 'undefined') {
			// обновляем статистику		
			var stat = '<span><strong>'+lang.count_files+'</strong>'+matrix.curent_tree_events[matrix.tree].count+'</span><br />\
			<span><strong>'+lang.size_files+'</strong>'+readableFileSize(matrix.curent_tree_events[matrix.tree].size)+'</span><br />\
			<span><strong>'+lang.date_from+'</strong>'+matrix.curent_tree_events[matrix.tree].from+'</span><br />\
			<span><strong>'+lang.date_to+'</strong>'+matrix.curent_tree_events[matrix.tree].to+'</span><br />'
			$('#statistics').html(stat);
			// записываем количество событий в данном временном диапазоне
			matrix.count_item = matrix.curent_tree_events[matrix.tree].count;
		}
		
		// критерии просмотра: тип, камеры
		var variable = [];
		var type = [];
		var i = 0;
		$('input[name="type_event"]').each(function(){
			if ($(this).attr('checked')) {
				type[i] = $(this).val();
				i++;
			}
		});
		var i = 0;
		$('input[name="cameras"]').each(function(){
			if ($(this).attr('checked')) {
				variable[i] =  $(this).val();
				i++;
			}
		});
		
		matrix.events = {};
		var count_events = 0;
		var all_count_events = 0;
		var me = [];
		// заполняем кеш матрицы элементами из общего кеша
		$.each(matrix.all_events, function( i,value) {
			if ($.inArray(value[7], type) != -1 && $.inArray(value[5], variable) != -1 && (matrix.tree == 'all' || matrix.tree == value[0].substr(0, matrix.tree.length))) {
				matrix.events[count_events] = value;
				me[count_events] = i;
				count_events++;
			}
			all_count_events++;
		});
		
		var dev = all_count_events - matrix.config.event_limit;
		if (dev > 0) {
			$.each(matrix.all_events, function( i,value) {
				if ($.inArray(i, me) == -1) {
					delete matrix.all_events[i];
					dev--;
					if (dev <= 0) {
						 return false;
					}
				}
			});
		}
		
		// если идет переход вверх по дереву, то показываем самый последние элементы в матрице нового диапазона
		if (matrix.select_node == 'left') {
			sp = scroll.position;
		} else {
			sp = 0;
		}
		
		
		if(count_events < matrix.cell_count && count_events < matrix.curent_tree_events[matrix.tree].count) {
			// если нет элементов, то выполняем запрос на сервер
			matrix.get_events(sp);
		} else {
			// если есть элементы, то обновляем матрицу
			matrix.update(sp);
		}
		//инициализируем элемент скрола
		scroll.init({height:matrix.height-28, cell_count:Math.ceil(matrix.count_item/matrix.count_column), row_count: matrix.count_column, matrix_count: Math.ceil(matrix.cell_count/matrix.count_column)});
		matrix.scroll = true;
	}
};
// элемент скрол
var scroll = {
		id : '#scroll_v', // ид элемента скрола
		height : 100, // высота скрола
		cell_count : 100, // количество ячеек в скроле
		row_count : 10, // количество рядов
		matrix_count: 10, // размер матрицы
		position : 0, // текущая позиция в скроле
		min_height : 10, // минимальная высота ползунка
		init : function(config) {
			if (config && typeof(config) == 'object') {
			    $.extend(scroll, config);
			}
			// задаем высоту скрола
			$(scroll.id + ' .scroll_body_v').height(scroll.height);
			// высчитываем высоту ползунка в зависимости от элементов в матрице и всех элементов в диапазоне 
			h = Math.floor(scroll.height/scroll.cell_count*scroll.matrix_count);
			scroll.polzh = 0;
			if ( h < scroll.min_height) {
				scroll.polzh = scroll.min_height - h; 
				h = scroll.min_height;
				
			}
			// задаем параметры ползунка
			$(scroll.id + ' .scroll_polz_v').height(h);
			$(scroll.id + ' .scroll_polz_v').css('top',0);
			// обработка нажатия стрелки вверх на скроле
			$(scroll.id + ' .scroll_top_v').unbind('click');
			$(scroll.id + ' .scroll_top_v').click(function() {
				scroll.num_up();
			});
			// обработка нажатия стрелки вниз на скроле
			$(scroll.id + ' .scroll_bot_v').unbind('click');
			$(scroll.id + ' .scroll_bot_v').click(function() {
				scroll.num_down();
			});
			
			// обработка нажатия стрелки предыдущее
			$('#toolbar .prew').unbind('click');
			$('#toolbar .prew').click(function(e) {
				e.preventDefault();
				scroll.num_left();
				return false;
			});
			// обработка нажатия стрелки следующее
			$('#toolbar .next').unbind('click');
			$('#toolbar .next').click(function(e) {
				e.preventDefault();
				scroll.num_right();
				return false;
			});
			
			// обработка перемещения ползунка
			scroll.mousemove = false;
			$(scroll.id + ' .scroll_polz_v').unbind('mousedown');
			$(scroll.id + ' .scroll_polz_v').mousedown(function(e){
				e.preventDefault();
				var start = e.pageY - $(this).offset().top; 
				var start_top = $(this).offset().top - $(this).position().top;
				$(document).mousemove(function(e){
					scroll.mousemove = true;
					var top = e.pageY - start_top - start;
					if (top >= 0 && top <= $(scroll.id + ' .scroll_body_v').height()- $(scroll.id + ' .scroll_polz_v').height()) {
						$(scroll.id + ' .scroll_polz_v').css('top', top);
						var sp = Math.floor(top/((scroll.height-scroll.polzh)/scroll.cell_count)) * scroll.row_count;
						scroll.position = sp;
					}
				})
				
			});
			$(document).mouseup(function(e){
				if (scroll.mousemove) {
					$(document).unbind('mousemove');
					scroll.updateposition(scroll.position, true);
					scroll.mousemove = false;
				}
			});
			
			$("#win_bot").unbind('mousewheel');
			$("#win_bot").mousewheel(function(event, delta) {
				if (delta > 0) {
					scroll.num_up();
				} else {
					scroll.num_down();
				}
			});
			
			// обработка нажатий клавиатуры
			$(document).unbind('keydown');
			$(document).keydown(function (e) {
				/*
				 left - 37
				 up	- 38
				 right - 39
				 down - 40
				 Page Up - 33
				 Page Down - 34
				 */
				
				if (e.which == 37) {
					scroll.num_left();
					return false;
				} else if (e.which == 38) {
					scroll.num_up();
					return false;
				} else if (e.which == 39) {
					scroll.num_right();
					return false;
				} else if (e.which == 40) {
					scroll.num_down();
					return false;
				} else if (e.which == 33) {
					var sp = scroll.position;
					sp = sp - scroll.matrix_count*scroll.row_count;
					if (sp < 0) {
						sp = 0;
					}
					matrix.num = matrix.num - scroll.matrix_count*scroll.row_count;
					if (matrix.num < 0) {
						// если вышли за пределы переходим на предыдущий если пользователь согласился
						if (matrix.curent_tree_events[matrix.tree].prev) {
							var checknextwindow = gallery.cookie.get('checknextwindow');
							if (checknextwindow == 'yes') {
								prev = matrix.curent_tree_events[matrix.tree].prev;
								new_num = matrix.curent_tree_events[prev].count - 1;
								sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
								matrix.num = new_num;
								scroll.position = sp;
								matrix.select_node = 'left';
								$.jstree._focused().deselect_node("#tree_"+matrix.tree);
								$("#tree_"+prev).jstree("set_focus");
								$.jstree._focused().select_node("#tree_"+prev);
								scroll.setposition(sp);
							} else if (checknextwindow != 'no'){
								gallery.nextwindow.open('left');
							}
						}
					} else {
						scroll.updateposition(sp);
						scroll.setposition(sp);
					}
					return false;
				} else if (e.which == 34) {
					var sp = scroll.position;
					if (sp + scroll.matrix_count*scroll.row_count*2 >  scroll.cell_count*scroll.row_count) {
						if (matrix.curent_tree_events[matrix.tree].next) {
							var checknextwindow = gallery.cookie.get('checknextwindow');
							if (checknextwindow == 'yes') {
								next = matrix.curent_tree_events[matrix.tree].next;
								matrix.num = 0;
								matrix.select_node = 'right';
								$.jstree._focused().deselect_node("#tree_"+matrix.tree);
								$("#tree_"+next).jstree("set_focus");
								$.jstree._focused().select_node("#tree_"+next);
							} else if (checknextwindow != 'no'){
								gallery.nextwindow.open('right');
							}
							return false;
						}
						sp = scroll.cell_count*scroll.row_count - scroll.matrix_count*scroll.row_count;
						matrix.num =scroll.cell_count*scroll.row_count - scroll.matrix_count*scroll.row_count;
					} else {
						sp =  sp + scroll.matrix_count*scroll.row_count;
						matrix.num = matrix.num + scroll.matrix_count*scroll.row_count;
					}
					scroll.updateposition(sp);
					scroll.setposition(sp);
					return false;
				}
			});
			// обработка нажатия на область между ползунком и края скрола
			$(scroll.id + ' .scroll_body_v').unbind('mousedown');
			$(scroll.id + ' .scroll_body_v').mousedown(function(e){
				e.preventDefault();
				var y = e.pageY -$(this).offset().top;
				var sp = scroll.position;
				if (y < $(scroll.id + ' .scroll_polz_v').position().top) {
					sp = sp - scroll.matrix_count*scroll.row_count;
					if (sp < 0) {
						sp = 0;
					}
				} else if (y > $(scroll.id + ' .scroll_polz_v').position().top + $(scroll.id + ' .scroll_polz_v').height()){
					if (sp + scroll.matrix_count*scroll.row_count*2 >=  scroll.cell_count*scroll.row_count) {
						sp = scroll.cell_count*scroll.row_count - scroll.matrix_count*scroll.row_count;
					} else {
						sp = sp + scroll.matrix_count*scroll.row_count;
					}
				}
				scroll.updateposition(sp);
				scroll.setposition(sp);
			});
			
			scroll.position = 0;
			$(scroll.id).show();
		},
		
		// сдвиг влево
		num_left : function() {
			var new_num = matrix.num - 1;
			if (new_num >=0) {
				//если находимся в этом же диапазоне событий 
				if (matrix.mode == 'preview') {
					
					if (!$('#cell_'+new_num).hasClass('show')){
						sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
						scroll.updateposition(sp);
						scroll.setposition(sp);
					}
					$('#cell_'+matrix.num).removeClass('active');
					$('#cell_'+new_num).addClass('active');
					matrix.num = new_num;
				} else if (matrix.mode == 'detail'){
					matrix.num = new_num;
					matrix.loaddetailsrc();
				}
			} else {
				// если вышли за пределы переходим на предыдущий если пользователь согласился
				if (matrix.curent_tree_events[matrix.tree].prev) {
					var checknextwindow = gallery.cookie.get('checknextwindow');
					if (checknextwindow == 'yes') {
						prev = matrix.curent_tree_events[matrix.tree].prev;
						new_num = matrix.curent_tree_events[prev].count - 1;
						sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
						matrix.num = new_num;
						scroll.position = sp;
						matrix.select_node = 'left';
						$.jstree._focused().deselect_node("#tree_"+matrix.tree);
						$("#tree_"+prev).jstree("set_focus");
						$.jstree._focused().select_node("#tree_"+prev);
						scroll.setposition(sp);
					} else if (checknextwindow != 'no'){
						gallery.nextwindow.open('left');
					}
				}
			}
		},
		// смещаемся на ряд вверх
		num_up : function() {
			var new_num = matrix.num - scroll.row_count;
			if (new_num >=0) {
				//если находимся в этом же диапазоне событий 
				if (matrix.mode == 'preview') {
					if (!$('#cell_'+new_num).hasClass('show')){
						sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
						scroll.updateposition(sp);
						scroll.setposition(sp);
					}
					$('#cell_'+matrix.num).removeClass('active');
					$('#cell_'+new_num).addClass('active');
					matrix.num = new_num;
					
				}	else if (matrix.mode == 'detail'){
					matrix.num = new_num;
					matrix.loaddetailsrc();
				}
			} else {
				// если вышли за пределы переходим на предыдущий если пользователь согласился
				if (matrix.curent_tree_events[matrix.tree].prev) {
					var checknextwindow = gallery.cookie.get('checknextwindow');
					if (checknextwindow == 'yes') {
						prev = matrix.curent_tree_events[matrix.tree].prev;
						new_num = matrix.curent_tree_events[prev].count - 1;
						sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
						matrix.num = new_num;
						scroll.position = sp;
						matrix.select_node = 'left';
						$.jstree._focused().deselect_node("#tree_"+matrix.tree);
						$("#tree_"+prev).jstree("set_focus");
						$.jstree._focused().select_node("#tree_"+prev);
						scroll.setposition(sp);
					} else if (checknextwindow != 'no'){
						gallery.nextwindow.open('left');
					}
				}
			}
		},
		// смещаемся вправо
		num_right : function() {
			var new_num = matrix.num + 1;
			if (new_num < scroll.cell_count*scroll.row_count) {
				if (matrix.mode == 'preview') {
					if (!$('#cell_'+new_num).hasClass('show')){
						sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
						scroll.updateposition(sp);
						scroll.setposition(sp);
					}
					$('#cell_'+matrix.num).removeClass('active');
					$('#cell_'+new_num).addClass('active');
					matrix.num = new_num;
				} else if (matrix.mode == 'detail'){
					matrix.num = new_num;
					matrix.loaddetailsrc();
				}
				
			}else {
				if (matrix.curent_tree_events[matrix.tree].next) {
					var checknextwindow = gallery.cookie.get('checknextwindow');
					if (checknextwindow == 'yes') {
						next = matrix.curent_tree_events[matrix.tree].next;
						matrix.num = 0;
						matrix.select_node = 'right';
						$.jstree._focused().deselect_node("#tree_"+matrix.tree);
						$("#tree_"+next).jstree("set_focus");
						$.jstree._focused().select_node("#tree_"+next);
					} else if (checknextwindow != 'no'){
						gallery.nextwindow.open('right');
					}
				}
			}
		},
		// смещаемся на ряд ниже
		num_down : function() {
			var new_num = matrix.num + scroll.row_count;
			if (new_num < scroll.cell_count*scroll.row_count) {
				if (matrix.mode == 'preview') {
					if (!$('#cell_'+new_num).hasClass('show')){
						sp = Math.floor(new_num / scroll.row_count) * scroll.row_count;
						scroll.updateposition(sp);
						scroll.setposition(sp);
					}
					$('#cell_'+matrix.num).removeClass('active');
					$('#cell_'+new_num).addClass('active');
					matrix.num = new_num;
				} else if (matrix.mode == 'detail'){
					matrix.num = new_num;
					matrix.loaddetailsrc();
				}
			}else {
				if (matrix.curent_tree_events[matrix.tree].next) {
					var checknextwindow = gallery.cookie.get('checknextwindow');
					if (checknextwindow == 'yes') {
						next = matrix.curent_tree_events[matrix.tree].next;
						matrix.num = 0;
						matrix.select_node = 'right';
						$.jstree._focused().deselect_node("#tree_"+matrix.tree);
						$("#tree_"+next).jstree("set_focus");
						$.jstree._focused().select_node("#tree_"+next);
					} else if (checknextwindow != 'no'){
						gallery.nextwindow.open('right');
					}
				}
			}
		},
		// обновляем позицию скрола и перестраиваем матрицу
		updateposition : function(sp, force) {
			if (scroll.position != sp || force == true) {
				scroll.position = sp;
				matrix.update(sp);
			}
		},
		// обновляем позицию скрола и ползунка, перестраиваем матрицу
		setposition : function(sp) {
			scroll.position = sp;
			var t = Math.floor(sp/scroll.row_count*(scroll.height-scroll.polzh)/scroll.cell_count);
			$(scroll.id + ' .scroll_polz_v').css({top:t});
			matrix.update(sp);
		}
};
// элемент масштаба предварительного просмотра
var scale = {
	id : '#scale', // ид элемента
	width: 300, // ширина
	min : 0, // минимальное значение
	max : 20, // максимальное значение
	position : 0, // текущая позиция
	// уменьшение масштаба
	click_min : function() {
		var sp = scale.position - 1;
		if (sp < 0) {
			sp = 0;
		}
		scale.setposition(sp);
	},
	// увеличение масштаба
	click_max : function() {
		var sp = scale.position + 1;
		if (sp > scale.max) {
			sp = scale.max;
		}
		scale.setposition(sp);
	},
	// обновление текущей позиции ползунка
	setposition : function(sp) {
		var t = scale.width/scale.max*sp;
		$(scale.id + ' .scale_polz').css({left:t});
		scale.updateposition(sp);
	},
	// обновление текущей позиции масштаба и обновление элементов матрицы
	updateposition : function(sp) {
		scale.position = sp;
		gallery.cookie.set('scale', sp);
		matrix.cell_width = matrix.config.min_cell_width + Math.floor((matrix.config.max_cell_width - matrix.config.min_cell_width)*sp/scale.max);
		matrix.cell_height = matrix.config.min_cell_height + Math.floor((matrix.config.max_cell_height - matrix.config.min_cell_height)*sp/scale.max);
		matrix.resize();
	},
	// обновление элемента масштаба
	reload : function(width) {
		var sp = Math.floor(scale.position * width/matrix.config.max_cell_width)
		if (sp > scale.max) {
			sp=scale.max;
		}
		var t = Math.floor(scale.width/scale.max*sp);
		$(scale.id + ' .scale_polz').css({left:t});
		scale.position = sp;
	},
	
	init : function() {
		// обработка нажатия уменьшения масштаба
		$(scale.id + ' .scale_min').unbind('click');
		$(scale.id + ' .scale_min').click(function() {
			scale.click_min();
		});
		// обработка нажатия увеличение масштаба
		$(scale.id + ' .scale_max').unbind('click');
		$(scale.id + ' .scale_max').click(function() {
			scale.click_max();
		});
		// обработка перепещения ползунка
		$(scale.id + ' .scale_polz').unbind('mousedown');
		$(scale.id + ' .scale_polz').mousedown(function(e){
			e.preventDefault();
			var start = e.pageX - $(this).offset().left; 
			var start_left = $(this).offset().left - $(this).position().left;
			$(document).mousemove(function(e){
				var left = e.pageX - start_left - start;
				if (left >= 0 && left <= $(scale.id + ' .scale_body').width()- $(scale.id + ' .scale_polz').width()) {
					$(scale.id + ' .scale_polz').css('left', left);
					var sp = Math.floor(scale.max/scale.width * left);
					if (sp != scale.position) {
						scale.updateposition(sp);
					}
				}
			})
			
		});
		$(document).mouseup(function(e){
			$(document).unbind('mousemove');
		});
		if (gallery.cookie.get('scale')) {
			scale.setposition(gallery.cookie.get('scale'));
		}
	}
}

// элемент масштаба детального просмотра
var scale2 = {
		id : '#scale2',
		width: 300,
		min : 0,
		max : 20,
		position : 0,
		click_min : function() {
			var self = this;
			var sp = self.position - 1;
			if (sp < 0) {
				sp = 0;
			}
			self.setposition(sp);
		},
		click_max : function() {
			var self = this;
			var sp = self.position + 1;
			if (sp > self.max) {
				sp = self.max;
			}
			self.setposition(sp);
		},
		setposition : function(sp) {
			var self = this;
			var t = self.width/self.max*sp;
			$(self.id + ' .scale_polz').css({left:t});
			self.updateposition(sp);
		},
		updateposition : function(sp) {
			var self = this;
			self.position = sp;
			$('#image_detail').attr('width', parseInt(self.min_width) + Math.floor((self.max_width - self.min_width)*sp/self.max));
			$('#image_detail').attr('height', parseInt(self.min_height) + Math.floor((self.max_height - self.min_height)*sp/self.max));
			gallery.cookie.set('scale2', sp);
		},
		reload : function() {
			var self = this;
			self.updateposition(self.position);
		},
		init : function() {
			var self = this;
			$(self.id + ' .scale_min').unbind('click');
			$(self.id + ' .scale_min').click(function() {
				self.click_min();
			});
			$(self.id + ' .scale_max').unbind('click');
			$(self.id + ' .scale_max').click(function() {
				self.click_max();
			});
			
			$(self.id + ' .scale_polz').unbind('mousedown');
			$(self.id + ' .scale_polz').mousedown(function(e){
				e.preventDefault();
				var start = e.pageX - $(this).offset().left; 
				var start_left = $(this).offset().left - $(this).position().left;
				$(document).mousemove(function(e){
					var left = e.pageX - start_left - start;
					if (left >= 0 && left <= $(self.id + ' .scale_body').width()- $(self.id + ' .scale_polz').width()) {
						$(self.id + ' .scale_polz').css('left', left);
						var sp = Math.floor(self.max/self.width * left);
						
						if (sp != self.position) {
							self.updateposition(sp);
						}
					}
				})
				
			});
			$(document).mouseup(function(e){
				$(document).unbind('mousemove');
			});
			
			if (gallery.cookie.get('scale2')) {
				self.setposition(gallery.cookie.get('scale2'));
			}
			
		}
	}

