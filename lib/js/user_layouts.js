/***
 * Работа с пользовательскими раскладками (храняться на клиенте)
 */

$(function(){
	user_layouts.init();
});


 var user_layouts = {

	//Локальное хранилище
	local_storage : false,
	//Название переменной хранилища
	storage_name : "CLIENT_LAYOUTS",
	//Клиентские раскладки
	client_layouts : undefined,
	//Клиентские раскладки в формате json
	client_layouts_json : false,
	
 	//инициализация
 	init : function(){
 		var self = this;
 		if(!this.isLocalStorageAvailable()){
 			return;
 //			alert("Ошибка сохранения пользовательской раскладки.\nЛокальное храниилище недоступно.");
 		}else{
 			self.local_storage = window['localStorage'];
 		}
 		
 		self.client_layouts_json = self.local_storage[self.storage_name];
 		
 		if(self.client_layouts_json!==undefined){
 			try{
 				self.client_layouts = JSON.parse(self.client_layouts_json);
 			}catch(e){
 				self.local_storage.removeItem(self.storage_name);
 				var error_text = 'Ошибка загрузки пользовательских раскладок\n\n'+ e.message;
 				alert(error_text);
 			}
 		}
 	},
 	

 	//перейти на страницу редактирования
 	to_tune_mode : function(layout_nr, redirect_url){
 		var self = this;
 		//переадресуем на страницу редактирования
 		self.redirect(redirect_url, true);
 		return false;
 	},
 	
 	//сохранить отредактированую клиентскую раскладку
 	tune_save : function(layout_nr, redirect_url){
 		var self = this;
	 	//данные раскладки
 		var layout_data = {};
 		
 		//Валидация заполнения формы
 		if(!validate()) return false;
		//Сохранить?
 		confirm("Сохранить изменения пользовательской раскладки № "+layout_nr+" ?");
 		
 		//номер раскладки
 		//layout_data.MON_NR = parseInt($('input[name=mon_nr]').attr('value'));
 		var MON_NR = parseInt(layout_nr);
 		
 		//название
 		layout_data.n = $('input[name=mon_name]').attr('value');
 		//тип
 		layout_data.t = $('input[name=mon_type]').attr('value');
 		//по умолчанию?
 		layout_data.d = false; //TODO correct the setting
 		//выводить названия камер? 		
 		layout_data.cn = $('input[name=PrintCamNames]').prop('checked');
 		//сохранять пропорции ?
 		layout_data.p = $('#AspectRatio option:selected').attr('value');
 		//таймаут реконнекта
 		layout_data.rt = $('#ReconnectTimeout option:selected').attr('value');
 		
 		//дата
 		var dt = new Date();
 		var m = dt.getMonth()+1;
 		if(m.toString().length==1)m='0'+m;
 		var d = dt.getDate();
 		if(d.toString().length==1)d='0'+d;
 		var h = dt.getHours();
 		if(h.toString().length==1)h='0'+h;
		var min = dt.getMinutes();
 		if(min.toString().length==1)min='0'+min;
 		var s = dt.getSeconds();
 		if(s.toString().length==1)s='0'+s;
 		
 		layout_data.dd = dt.getFullYear()+'-'+m+'-'+d+' '+h+':'+min+':'+s;
		//логин пользователя
 		layout_data.u = this.client_layouts[MON_NR].u;
 		//собираем данные раскладки
 		var wins_data = {};
		$('.layout').each(function(i, val){
			var id = $(val).attr('id');
 			var win_nr = parseInt(id.replace('win_',''));
			//номер камеры в ячейке
			var cam_nr = $('.mon_wins option:selected',val).attr('value');
			//тип источника
			var mon_type = $('.mon_wins_type option:selected',val).attr('value');
			//Записываем только ячейки с установлеными камерами
			if(mon_type ==undefined) return;
 			var data = [cam_nr,mon_type];
 			wins_data[win_nr] = data; 
 		});
 		layout_data.w = JSON.stringify(wins_data);
 		
		if(this.client_layouts==undefined){
			this.client_layouts=new Object();
		}

 		this.client_layouts[MON_NR] = layout_data;
 		
 		var json_str = JSON.stringify(this.client_layouts);
 		self.client_layouts_json = json_str;
 		
 		self.local_storage.setItem(self.storage_name, json_str );
 		
 		//переадресуем 
 		self.redirect(redirect_url, true);
 		return false;
 	},

 	//Удалить пользовательскую расскладку
 	remove : function(layout_nr, redirect_url){
		var self = this;

		confirm("Удалить пользовательскую раскладку № "+layout_nr+" ?");

		try{
		//Удаляем раскладку
		delete this.client_layouts[layout_nr];
		}catch(e){}
		//Если нет ни одной пользовательской раскладки - удаляем переменную локального хранилища
		var objlen = 0;
		for(var key in this.client_layouts){
			objlen++;
		}
		if(objlen==0){
			self.local_storage.removeItem(self.storage_name);
			self.client_layouts_json = undefined;
		}else{
	 		var json_str = JSON.stringify(this.client_layouts);
	 		self.client_layouts_json = json_str;
	 		self.local_storage.setItem(self.storage_name, json_str );
		}

		//переадресуем на онлайн просмотр
 		self.redirect(redirect_url, true);
 		
 		return false;
 	},
 	
 	
 	
 	//Добавить новую пользовательскую расскладку
 	add_new : function(){
		var self = this;
	 	//данные раскладки
 		var layout_data = {};
 		
 		//Валидация заполнения формы
 		if(!validate()) return false;

 		//номер раскладки
 		//layout_data.MON_NR = parseInt($('input[name=mon_nr]').attr('value'));
 		var MON_NR = parseInt($('input[name=mon_nr]').attr('value'));
 		
 		//название
 		layout_data.n = $('input[name=mon_name]').attr('value');
 		//тип
 		layout_data.t = $('input[name=mon_type]').attr('value');
 		//по умолчанию?
 		layout_data.d = false; //TODO correct the setting
 		//выводить названия камер? 		
 		layout_data.cn = $('input[name=PrintCamNames]').prop('checked');
 		//сохранять пропорции ?
 		layout_data.p = $('#AspectRatio option:selected').attr('value');
 		//таймаут реконнекта
 		layout_data.rt = $('#ReconnectTimeout option:selected').attr('value');
		//дата
 		var dt = new Date();
 		var m = dt.getMonth()+1;
 		if(m.toString().length==1)m='0'+m;
 		var d = dt.getDate();
 		if(d.toString().length==1)d='0'+d;
 		var h = dt.getHours();
 		if(h.toString().length==1)h='0'+h;
		var min = dt.getMinutes();
 		if(min.toString().length==1)min='0'+min;
 		var s = dt.getSeconds();
 		if(s.toString().length==1)s='0'+s;
 		
 		layout_data.dd = dt.getFullYear()+'-'+m+'-'+d+' '+h+':'+min+':'+s;
		//логин пользователя
 		layout_data.u = user_login;
 		
 		//собираем данные раскладки
 		var wins_data = {};
		$('.layout').each(function(i, val){
			var id = $(val).attr('id');
 			var win_nr = parseInt(id.replace('win_',''));
			//номер камеры в ячейке
			var cam_nr = $('.mon_wins option:selected',val).attr('value');
			//тип источника
			var mon_type = $('.mon_wins_type option:selected',val).attr('value');
			//Записываем только ячейки с установлеными камерами
			if(mon_type ==undefined) return;
 			var data = [cam_nr,mon_type];
 			wins_data[win_nr] = data; 
 		});
 		layout_data.w = JSON.stringify(wins_data);
 		
		if(this.client_layouts==undefined){
			this.client_layouts=new Object();
		}
 		this.client_layouts[MON_NR] = layout_data;
 		
 		var json_str = JSON.stringify(this.client_layouts);
 		self.client_layouts_json = json_str;
 		self.local_storage.setItem(self.storage_name, json_str );
 		
 		//переадресуем на онлайн просмотр
 		self.redirect(location.protocol+'//'+location.hostname+WwwPrefix+'/online/view.php', true);
 		
 		return false;
 	},

 	//перенаправить на страницу 
 	//layouts:bool - передавать раскладки?
 	redirect : function(url, layouts){
 		layouts = layouts || false;
		//Передаем параметры пользовательских раскладок 
		if(layouts && user_layouts.isLocalStorageAvailable() && user_layouts.client_layouts_json){
			if(url.indexOf('?')==-1){
		   		url+='?layouts='+user_layouts.client_layouts_json;
		   	}else{
		   		url+='&layouts='+user_layouts.client_layouts_json;
		   	}
		}
		top.document.location.href = url;
 	},
 	
 	

 	//проверяет доступность локального хранилища
 	isLocalStorageAvailable : function(){
	    try {
	        return window['localStorage'] !== null;
	    } catch (e) {
	        return false;
	    }
	}
 	
 	
 };