(function ($) { $(function () {
 		//Установка плеера в элемент
//		$('#win_bot_detail').addPlayer({'src':'/avreg/media/cam_02/2010-05/03/00_00_00.jpg'});
    	//Установка общих параметров
//		$.aplayer.init({'height':200, 'width':300});
		//Вывод конфигурации
//		$.aplayer.config.Show();
		//Получить информацию о браузере
//		$.browserInfo.getInfo();
		//Вывод инфо о браузере
//		$.browserInfo.Show();

//		$.aplayer.config.Show();

});})(jQuery);









//---------------------------------------------------- Player ------------------------------------


(function($){

	//Установка плеера в заданные эл-ты html
	//settings: src , type , config{}
	$.fn.addPlayer = function(settings){
				if(settings.src==null)
				{
//					alert('aplayer:\nError: src = undefined');
					return this;
				}
			else $(this).each(function(){
				$.aplayer.play(this, settings);
			});
		return this;
	};

	//Закрытие плеера
	$.fn.aplayerClose=function(){
		$(this).empty();
		return this;
	};

	//Смена src изображения
	$.fn.aplayerSetImgSrc = function(imageSource){
		var pl =  $(this).children('[id ^=' + $.aplayer.idContainer + ']');
			$(pl).removeAttr('t','s').unbind('click');
		
		$(pl).children(':first-child').attr('src',imageSource);//.attr('title', 'image');
		$.aplayer.draggable(pl);
		return this;
	};

	//Медиаэлемент использует размеры источника 
	$.fn.aplayerSetSrcSizes = function(){
		$(this).children('[id ^=' + $.aplayer.idContainer + ']').children(':first-child').removeAttr('height').removeAttr('width').css({'height':'', 'width':'' });
		return this;
	};

	
    //Изменение размеров медиаэлемента
    $.fn.aplayerSetSizeMediaElt = function(Sizes) {
    	var Owner = this;
	    $(this).children('[id ^=' + $.aplayer.idContainer + ']').each(function () {
    	    var Cont = $(this);
            var H = $(Cont).height();
	        var W = $(Cont).width();
            $(Cont).children('video, audio, object, embed, img, .'+$.aplayer.classMediaCont).each(function () {
	           if(!( $(this).hasClass($.aplayer.classMediaCont))) {
	            	$(this).height(Sizes.height).width(Sizes.width);
	           }
		       $(this).children('video, audio, embed').each(function () {
		       		$(this).height(Sizes.height).width(Sizes.width);
		       });
            });
            $.aplayer.draggable(Cont);
        });
        return this;
    };


    //Установка размеров плеера
    $.fn.aplayerSetSize = function(Sizes) {

	    $(this).children('div[id ^=' + $.aplayer.idContainer + ']').each(function () {
	    $(this).height(Sizes.height);
	    $(this).width(Sizes.width);
	    var Cont = $(this);

	    $(Cont).children('object, embed, img').each(function () {
	        $(this).height(Sizes.height).width(Sizes.width);
	        $(this).children('embed').each(function () {
	        $(this).height(Sizes.height).width(Sizes.width);
	        });
        }).end().children('.'+$.aplayer.classMediaCont).children('video, audio').each(function () { //Установка размера суб-элемента и вложенного медиа-элемента НЕ ПРОВЕРЯЛАСЬ!!!
	        $(this).height(Sizes.height - $(this).next('div[id ^=' + $.aplayer.idControlPanel + ']').height() - 7).width(Sizes.width - 6);
	        });
        });
        return this;
    };


    //Установка размеров внутренних контейнеров плеера(без медиа элемента) в соответствии с размерами его контейнера
    $.fn.aplayerResizeContanerOnlyToParent = function () {
	    $(this).children('div[id ^=' + $.aplayer.idContainer + ']').each(function () {
	    $(this).height($(this).parent().height() - 5);
	    $(this).width($(this).parent().width() - 5);
	    var Cont = $(this)

		    $(Cont).children('.'+$.aplayer.classMediaCont).height(function(){
	        	return $(Cont).height()-$(this).next('div[id ^=' + $.aplayer.idControlPanel + ']').height();
	        });
	        $(Cont).find('.logoPlay').removeAttr('style').css({'position':'relative', 'top': ($(Cont).height()- $(Cont).find('.logoPlay').height())/2 });
        });
        
    	return this;
    };

    //Установка размеров плеера в соответствии с размерами его контейнера
    $.fn.aplayerResizeToParent = function () {
	    $(this).children('div[id ^=' + $.aplayer.idContainer + ']').each(function () {
	    $(this).height($(this).parent().height() - 2);
	    $(this).width($(this).parent().width() - 2);
	    var Cont = $(this);

	$(Cont).children('object, embed, img').each(function () {
	        $(this).height($(Cont).height() - 1).width($(Cont).width() - 1);
	        $(this).children('embed').each(function () {
	        $(this).height($(Cont).height() - 1).width($(Cont).width() - 1);
	        });
	        //отображение лого-плей
	        $(Cont).find('.logoPlay').removeAttr('style').css({'position':'relative', 'top': ($(Cont).find('.logoPlay').parent().height()- $(Cont).find('.logoPlay').height())/2 });
	        
        }).end().children('.'+$.aplayer.classMediaCont).height(function(){
        	return $(Cont).height()-$(this).next('div[id ^=' + $.aplayer.idControlPanel + ']').height();
        }).children('video, audio').each(function () { //Установка размера суб-элемента и вложенного медиа-элемента НЕ ПРОВЕРЯЛАСЬ!!!
	        $(this).height($(Cont).height() - $(this).parent().next('div[id ^=' + $.aplayer.idControlPanel + ']').height() - 7).width($(Cont).width() - 6);
	        });
        });
        return this;
    };

    //Является ли медиа-элемент картинкой?
    $.fn.aplayerIsImage = function(){
  		var el = $(this).children('div').children(':first-child');
  		if($(el).attr('name')=='img' && $(this).children('div').attr('t')== undefined) return true;
  		else return false;
    };
    
    
    //Является ли медиа-элемент embed || object?
    $.fn.aplayerIsEmbededObject = function(){
  		if($(this).find('embed').size()>0) return true;
  		else return false;
    };
    
    
    
    //Скрыть плеер
    $.fn.aplayerHide = function(){
  		$(this).children('[id^='+$.aplayer.idContainer+']').hide();
    };
    
    //Отобразить плеер
    $.fn.aplayerShow = function(){
  		$(this).children('[id^='+$.aplayer.idContainer+']').show();
    };

	//Объект, инкапсулирующий свойства и методы плеера
	$.aplayer = {

		draggable : function(Container){
			var H = $(Container).height();
	        var W = $(Container).width();
			
            $(Container).children('video, audio, object, embed, img, .'+$.aplayer.classMediaCont).each(function () {
	           if(!( $(this).hasClass($.aplayer.classMediaCont))) {
	           	
	     //      	$(this).addClass('show_detail');
		       		//Проверяем на перетаскиваемость
					if($(this).height() > $(this).parent().height() || $(this).width() > $(this).parent().width()) 
					{
						$(this).draggable({
							drag: function(event, ui){

								var imgWidth = $(this).width();
								var imgHeight = $(this).height();
								if(imgWidth>matrix.width) {
									if(ui.position.left>0){
										ui.position.left = 0;
								}
								if(W-ui.position.left>imgWidth)
									ui.position.left = W - imgWidth;
								} else {
									ui.position.left = 0;
								}
	
								if(imgHeight>H) {
									if(ui.position.top>0)
										ui.position.top = 0;
									if(H-ui.position.top>imgHeight)
										ui.position.top = H - imgHeight;
								} else {
									ui.position.top = 0;
								}
							}
						}).addClass('ui-corner-all ui-widget');
					}
					else
					{
						$(this).draggable('destroy').css({'position':'relative', 'top':'0'});
					}
	           }
		       $(this).children('video, audio, embed').each(function () {
		       	
		//       	$(this).addClass('show_detail');
		       		//Проверяем на перетаскиваемость
					if($(this).height() > $(this).parent().height() || $(this).width() > $(this).parent().width()) 
					{
							$(this).draggable({
								drag: function(event, ui){
								
									var imgWidth = $(this).width();
									var imgHeight = $(this).height();
									if(imgWidth>matrix.width) {
										if(ui.position.left>0){
											ui.position.left = 0;
									}
									if(W-ui.position.left>imgWidth)
										ui.position.left = W - imgWidth;
									} else {
										ui.position.left = 0;
									}
		
									if(imgHeight>H) {
										if(ui.position.top>0)
											ui.position.top = 0;
										if(H-ui.position.top>imgHeight)
											ui.position.top = H - imgHeight;
									} else {
										ui.position.top = 0;
									}
								}
							}).addClass('ui-corner-all ui-widget');
					}
					else{
						$(this).draggable('destroy').css({'position':'relative', 'top':'0'});
					}
			   });

			});
          	
           if($(Container)!=null && $(Container).height()!=null && $(Container).width()!=null ) $(Container).height(H).width(W).css({'overflow':'hidden'});
    	},

		
		
		
		//Установка общей для всех плееров конфигурации
		//GlobalSettings: config{}
		init : function(GlobalSettings){
			$.extend($.aplayer.config, GlobalSettings);
		},


			//типы файлов
			extTypes:{
				image:['.png', '.jpg','.gif', '.bmp', 'jpeg'],
				video:['.mp4', '.ogg', '.ogv', '.webm'],
				audio:['.oga','.mp3', '.m4a', '.wav', '.mpeg'],
				application:['.avi']
			},

			//Расширения и соответствующие MIME types
			MIMEtypes:{
				swf		:'application/x-shockwave-flash',
				flv		:'video/x-flv',
				aif 	:'audio/x-aiff',
				aifc 	:'audio/x-aiff',
				aiff 	:'audio/x-aiff',
				au 		:'audio/basic',
				avi 	:'video/x-msvideo',
				dv 		:'video/x-dv',
				m3u 	:'audio/x-mpegurl',
				m4a 	:'audio/mp4a-latm',
				m4b 	:'audio/mp4a-latm',
				m4p 	:'audio/mp4a-latm',
				m4u 	:'video/vnd.mpegurl',
				m4v 	:'video/x-m4v',
				mid 	:'audio/midi',
				midi 	:'audio/midi',
				mov 	:'video/quicktime',
				movie 	:'video/x-sgi-movie',
				mp2 	:'audio/mpeg',
				mp3 	:'audio/mpeg',
				mp4 	:'video/mp4',
				mpe 	:'video/mpeg',
				mpeg 	:'video/mpeg',
				mpg 	:'video/mpeg',
				mpga 	:'audio/mpeg',
				mxu 	:'video/vnd.mpegurl',
				snd 	:'audio/basic',
				wav 	:'audio/x-wav',
				wmv		:'video/x-ms-wmv',
				ogv		:'video/ogg',
				oga		:'audio/ogg',
				ogg 	:'application/ogg'
			},

			//метод определения mime type для воспроизведения файла
			setApplicationType: function(extension, mediaType, settings){
				if(extension==null){
					var reg = new RegExp('\\.\\w{3,4}\\s*', 'i');
					 extension=settings.src.match(reg);
					 extension=extension[extension.length-1].slice(1);
				}
				if($.aplayer.MIMEtypes[extension]!=null)
				{
					mediaType=$.aplayer.MIMEtypes[extension] + '" application ="true';
				}
				else mediaType='application/'+extension;

				return mediaType;
			},


			//Определение и установка типа
			setType: function(settings){
				if(settings.type!=null)
					{
						if(settings.type.indexOf('image')!=-1 || settings.type.indexOf('application')!=-1 ) return settings;
						if(settings.type.indexOf('video')!=-1 && $.browserInfo.HTML5_Video) return settings;
						if(settings.type.indexOf('audio')!=-1 && $.browserInfo.HTML5_Audio) return settings;
					}

				var ext, mediaType;
				var src = settings.src;

				$.each($.aplayer.extTypes, function(i, type){
					$.each(type, function(index, value){
						if(src.indexOf(value)!=-1)
						{
							mediaType = i;
							ext = value.slice(1);
							if(ext == 'ogv')ext ='ogg';
						}
					});
				});

				if(mediaType==null)mediaType = $.aplayer.setApplicationType(ext, mediaType,settings);
				else if(mediaType.indexOf('image')!=-1) mediaType=mediaType+'/'+ext;
				else if(mediaType.indexOf('video')!=-1 && $.browserInfo.HTML5_Video)
				{
					if(
						(ext == 'ogg' && $.browserInfo.video_ogg=='probably')||
						(ext == 'mp4' && $.browserInfo.video_mp4=='probably')||
						(ext == 'webm' && $.browserInfo.video_webm=='probably')
							)mediaType=mediaType+'/'+ext;
					else {
						if(
						(ext == 'ogg' && $.browserInfo.video_ogg=='maybe')||
						(ext == 'mp4' && $.browserInfo.video_mp4=='maybe')||
						(ext == 'webm' && $.browserInfo.video_webm=='maybe')
							)mediaType=mediaType+'/'+ext;
						else mediaType = $.aplayer.setApplicationType(ext, mediaType, settings);
					}
				}
				else if(mediaType.indexOf('audio')!=-1 && $.browserInfo.HTML5_Audio)
				{
					if(ext == 'mp3'|| ext == 'mp2' || ext == 'mpga')
					{	if($.browserInfo.audio_mpeg=='probably')mediaType = 'audio/mpeg';
						else if($.browserInfo.audio_mpeg=='maybe')mediaType = 'audio/mpeg';
						else mediaType = 'audio/mpeg" application ="true';
					}
					if(ext == 'ogg')
					{	if($.browserInfo.audio_ogg=='probably')mediaType ='audio/ogg';
						else if($.browserInfo.audio_ogg=='maybe')mediaType ='audio/ogg';
						else mediaType = 'audio/ogg" application ="true';
					}
					if(ext == 'm4a')
					{	if($.browserInfo.audio_x_m4a=='probably')mediaType ='audio/x-m4a';
						else mediaType = 'audio/x-m4a" application ="true';
					}
					if(ext == 'wav')
					{	if($.browserInfo.audio_wav=='probably') mediaType = 'audio/wav'; //" application ="true'; //mediaType ='audio/wav';
						else mediaType = 'audio/wav" application ="true';
					}
				}
				else
				{
					mediaType = $.aplayer.setApplicationType(ext, mediaType, settings);
				}

				$.extend(settings, {'type':mediaType})
				return settings;
			},

			//Метод установки плеера
			play:function(element, settings){
				//сбор информации о браузере
				$.browserInfo.getInfo();
				//Установка параметров переданных через $.fn.add()
				var sets = $.extend({}, $.aplayer.config, settings);

				//Определение и установка типа
				sets = $.aplayer.setType(sets);

				//корректировка типа воспроизведения в зависимости от версии браузера
				sets.type = $.aplayer.browserVersionSettings(sets.type, settings);

				
				//Установка размеров плеера ('Inherit' - установка размеров родительского эл-та)
				try{
				if(sets.height.indexOf('Inherit')!=-1)sets.height = $(element).height();
				}catch(err){};
				try{
				if(sets.width.indexOf('Inherit')!=-1)sets.width = $(element).width();
				}catch(err){};

				//Создание контейнера для плеера
				$.aplayer.aplayerNo++;
				var container = $('<div style="overflow:hiden; " ></div>'); //text-align:center;
                //$(container).height($(element).height()).width($(element).width());
				$(container).attr('id', $.aplayer.idContainer+$.aplayer.aplayerNo);
				//Установка дополнительного класса
				 $(container).addClass(settings['class']).addClass('aplayer');

				$(element).html(container);

				if(sets.type.indexOf('image')!=-1){
					//Вызов метода вывода изображения
					$.aplayer.showImage(container, sets);
				}
				else if(sets.logoPlay != undefined && sets.logoPlay.indexOf('true')!=-1)
				{
					var setLogoPlay = $.extend({}, sets, {'src': $.aplayer.ControlBar.controlsImg +$.aplayer.logo_play });
					//создаем субконтейнер для логотипа плей
					var lgp = $('<div />').height($(container).height()).width($($(container).width()));

					//Вызов метода вывода изображения
					var im = $('<img class="logoPlay" src="'+$.aplayer.ControlBar.controlsImg +$.aplayer.logo_play+'">')
					.appendTo(container);
					
					$(container).css({'text-align':'center'})
					.find('.logoPlay').css({'top': ($(container).height()- $(container).find('.logoPlay').height())/2 });

					
					var t = sets.type;
					var s = sets.src;
					
					$(container).attr({'t':t,'s':s}) .click(function(){
						$(this).parent().addPlayer({'src': $(this).attr('s'), 'type':'"'+$(this).attr('t')+'"' ,'controls':'mini' }).click()
						.end().removeAttr('t').removeAttr('s').unbind('click');
					});
				}
				//Если задано значение application - воспроизводить как внедренный объект // || (settings.application!=null && settings.application.indexOf('true')!=-1)
				else if(sets.type.indexOf('application')!=-1 ){
//					console.log(settings.type +"  "+settings.application)
					//Вызов метода для использования плагина
					$.aplayer.showObject(container, sets);
				}
				else if(sets.type.indexOf('video')!=-1){
					//Вызов метода для использования HTML5 video
					$.aplayer.showVideo(container, sets);
				}
				else if(sets.type.indexOf('audio')!=-1){
					//Вызов метода для использования HTML5 audio
					$.aplayer.showAudio(container, sets);
				}
				else{alert('Error: undefined type')}
				
				$.aplayer.draggable(container);
		},

		//корректировка типа воспроизведения в зависимости от версии браузера
		browserVersionSettings: function(srcType ,settings){
			
		//Вывод версии браузера и тип открываемого файла
//			alert('Browser\'s version: '+$.browser.version+'\nSource type: '+ settings.type);
			
			//Блокировка использования HTML5 в Chrome для указанных форматов
			if( $.browser.safari==true && (srcType=='audio/wav' || srcType=='video/mp4')) // || srcType=='audio/mpeg'))
				{
					srcType+='" application ="true';
					$.extend(settings, {'application':'true'});
				}
			//Блокировка использования HTML5 в Mozilla Firefox для указанных форматов
			if( $.browser.mozilla ==true && (srcType=='audio/wav'))
				{
					srcType+='" application ="true';
					$.extend(settings, {'application':'true'});
				}
				
				
			switch($.browser.version)
			{
/*				case '535.2':
				case '535.11': //Google Chrome v. 535.2
					if(srcType=='audio/wav' || srcType=='video/mp4' || srcType=='audio/mpeg')
					{
						$.extend(settings, {'application':'true'});
					}
				break;
*/				
				case '10.0':
				case '11.0':
				case '9.0.1': //Mozilla Firefox v. 9.0.1
					if(srcType=='audio/wav')
					{
						$.extend(settings, {'application':'true'});
					}
				break;
			}
			return srcType;
		},


			//Метод вывода изображения
			showImage:function(container, settings){
/*
				var im = new Image();
				im.src = settings.src;
				//ошбика при загрузке картинки
				im.onerror = function(){
					im.src = $.aplayer.ControlBar.controlsImg + $.aplayer.logo_error;

					im.onerror = function(){

						$(container).append('<div style="font-size:14; color:red;">Image\'s loading failed </div>');
						return;
					}
				}
*/			
				if(settings.useImageSize!=null && settings.useImageSize=='true')
				{
					//  title="'+settings.type+'"
					$('<img  src="'+settings.src+'" name="img"/>').appendTo(container);
					return;
				}
				
				var size = 'style="width:'+settings.width+'px; height:'+settings.height+'px; "';
				// title="'+settings.type+'"
				var im = $('<img  src="'+settings.src+'" '+size+' name="img"/>').attr({'height':settings.height, 'width':settings.width });
				$(im).appendTo(container);
			},

			//Метод для использования плагина
			showObject:function(container, settings){

					// Не добавляет высоту для аудио
					//if(!(settings.type.indexOf('audio')!=-1))size = 'style="width:'+settings.width+'px;'

             //Создаем object
             var obj;
               //QuickTime
               if(settings.type.indexOf($.aplayer.MIMEtypes.mp4)!=-1 || 
               		settings.type.indexOf($.aplayer.MIMEtypes.mov)!=-1) obj = $.aplayer.createObj_QuickTime(settings);
               //SWF
               else if(settings.type.indexOf($.aplayer.MIMEtypes.swf)!=-1 || settings.type.indexOf($.aplayer.MIMEtypes.flv)!=-1) obj = $.aplayer.createObj_SWF(settings);
               //wmv - в IE глюки
               else if(settings.type.indexOf($.aplayer.MIMEtypes.wmv)!=-1) obj = $.aplayer.createObj_WMV(settings);
               // audio/mp3
               else if(settings.type.indexOf($.aplayer.MIMEtypes.mp3)!=-1) obj = $.aplayer.createObj_MP3(settings);
               // audio/wav
               else if(settings.type.indexOf($.aplayer.MIMEtypes.wav)!=-1) obj = $.aplayer.createObj_WAV(settings);
               // video AVI
               else if(settings.type.indexOf($.aplayer.MIMEtypes.avi)!=-1) obj = $.aplayer.createObj_AVI(settings);
               // audio wav
               else if(settings.type.indexOf($.aplayer.MIMEtypes.wav)!=-1) obj = $.aplayer.createObj_WAV(settings);
               else obj = $.aplayer.create_Embed(settings);

               $(container).height(settings.height);
               
 //              $(obj).attr({'width': settings.width, 'height': settings.height});

//              $(obj).append($('<noembed>Your browser does not support video!!!!!!!!!!!!!!!!!!!!! </noembed>'));

               $(container).html(obj);
        	},

            //create EMBED
            create_Embed:function(settings){
//                var size = 'width="'+settings.width+'" height="'+settings.height+'"';  // '+size+'
                return $('<embed type="'+settings.type+'" play="false" autostart="false" auto="false" autoplay="false" allowfullscreen="true" allowScriptAccess="always"  />'
                    ).attr({'src':settings.src , wmode:"window" })
                    .width(settings.width)
                    .height(settings.height); //.html('<noembed>Your browser does not support video</noembed>'); //'Your browser does not support video');
           },

           //Create video AVI  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
           createObj_AVI: function(settings){
			var obj = $('<object type="video/avi" data="'+settings.src+'" autoplay="false"> </object>')
				.append('<param name="src" value="'+settings.src+'" />')
				.append('<param name="controller" value="true" />')
				.append('<param name="autoplay" value="false" />')
				.append('<param name="autostart" value="0" />')
				.append('<param name="wmode" value="window" >')
				.append('<param name="play" value="false" >');
				$(obj).width(settings.width).height(settings.height);
            $(obj).append( $($.aplayer.create_Embed(settings)));
            return obj;
           },

           //Create audio/wav
           createObj_WAV: function(settings){
           	   var obj;

               if($.browser.msie){
                    obj = $('<object data="'+settings.src+'" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab"> </object>');
                }
                else{
					obj = $('<object type="audio/x-wav" data="'+settings.src+'" autoplay="false"> </object>')
                }
           	
//			var obj = $('<object type="audio/x-wav" data="'+settings.src+'" autoplay="false"> </object>')
				$(obj).append('<param name="src" value="'+settings.src+'" />')
				.append('<param name="controller" value="true" />')
				.append('<param name="autoplay" value="false" />')
				.append('<param name="autostart" value="0" />');
				$(obj).append('<param name="wmode" value="window" >')
				.append('<param name="play" value="false" >');
			$(obj).append($($.aplayer.create_Embed(settings)));
            return obj;
           },

           // Create object audio/mp3
           createObj_MP3:function(settings){
			var obj = $('<object type="audio/x-mpeg" data="'+settings.src+'" autoplay="false"> </object>')
				.append('<param name="src" value="'+settings.src+'" />')
				.append('<param name="controller" value="true" />')
				.append('<param name="autoplay" value="false" />')
				.append('<param name="autostart" value="0" />');
				$(obj).append('<param name="wmode" value="window" >')
				.append('<param name="play" value="false" >');
				$(obj).width(settings.width).height(settings.height);
			$(obj).append($($.aplayer.create_Embed(settings)));
            return obj;
           },

            // Create object QuickTime
            createObj_QuickTime:function(settings){
               var obj = $('<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab"></object>');
               $(obj).append('<param name="controller" value="true" />')
               	.append('<param name="src" value="'+settings.src+'" />');
               $(obj).append('<param name="wmode" value="window" >')
				.append('<param name="play" value="false" >');
				$(obj).width(settings.width).height(settings.height);
				$(obj).append($($.aplayer.create_Embed(settings)).attr({ "TYPE":"image/x-macpaint", 'Height':settings.height}));
               return obj;
            },

             // Create object SWF
            createObj_SWF:function(settings){
               var obj;

               if($.browser.msie){
                    obj = $('<object type="application/x-shockwave-flash data="'+settings.src+'" ></object>');
                }
                else{
                   obj = $('<object type="application/x-shockwave-flash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"></object>');
                }
               $(obj).append('<param name="movie" value="'+settings.src+'" />');
               $(obj).append('<param name="quality" value="high" >');
               $(obj).append('<param name="play" value="0" >');
               $(obj).append('<param name="loop" value="0" >');
               $(obj).append('<param name="wmode" value="window" >');
               $(obj).append('<param name="scale" value="showall" >');
               $(obj).append('<param name="menu" value="1" >')
				.append('<param name="play" value="false" >');
//               $(obj).append('<param name="devicefont" value="false" />');
//			    $(obj).append('<param name="salign" value="" />');
//				$(obj).append('<param name="allowScriptAccess" value="sameDomain" />');
				$(obj).width(settings.width)
                .height(settings.height);
               $(obj).append($($.aplayer.create_Embed(settings)).removeAttr('type') );
             //   $(obj).append('<div><h4>Content on this page requires a newer version of Adobe Flash Player.</h4><p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif"alt="Get Adobe Flash player" width="112" height="33" /></a></p></div>');
               return obj;
            },

            // Create object WMV
            createObj_WMV:function(settings){
            	//  type="application/x-oleobject"
               var obj = $('<object  type="video/x-ms-asf" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6"  url="'+settings.src+'" data="'+settings.src+'" ></object>');
               $(obj).append('<param name="url" value="'+settings.src+'" />'
               		).append('<param name="filename" value="'+settings.src+'" />'
               		).append('<param name="autostart" value="0">'
               		).append('<param name="uiMode" value="full" />'
               		).append('<param name="autosize" value="1">'
               		).append('<param name="playcount" value="1">');
			 $(obj).append('<param name="wmode" value="window" >')
				.append('<param name="play" value="false" >');
				$(obj).width(settings.width)
                .height(settings.height);
			   $(obj).append('<embed type="audio/wav" play="false" wmode="window" PLUGINSPAGE="http://www.microsoft.com/windows/windowsmedia/download/" src="'+settings.src+'" style="width:'+settings.width+'px; height:'+settings.height+'px;" autostart="false" showcontrols="true"></embed>');
               return obj;
            },
            
            
            
            
            /*
             // Create object SWF
            createObj_SWF:function(settings){
               var obj;

               if($.browser.msie){
                    obj = $('<object type="application/x-shockwave-flash data="'+settings.src+'" ></object>');
                }
                else{
                   obj = $('<object type="application/x-shockwave-flash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"></object>');
                }
               $(obj).append('<param name="movie" value="'+settings.src+'" />');
               $(obj).append('<param name="quality" value="high" >');
               $(obj).append('<param name="play" value="0" >');
               $(obj).append('<param name="loop" value="0" >');
               $(obj).append('<param name="wmode" value="window" >');
               $(obj).append('<param name="scale" value="showall" >');
               $(obj).append('<param name="menu" value="1" >')
				.append('<param name="play" value="false" >');
//               $(obj).append('<param name="devicefont" value="false" />');
//			    $(obj).append('<param name="salign" value="" />');
//				$(obj).append('<param name="allowScriptAccess" value="sameDomain" />');
				$(obj).width(settings.width)
                .height(settings.height);
               $(obj).append($($.aplayer.create_Embed(settings)).removeAttr('type') );
             //   $(obj).append('<div><h4>Content on this page requires a newer version of Adobe Flash Player.</h4><p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif"alt="Get Adobe Flash player" width="112" height="33" /></a></p></div>');
               return obj;
            },
            
            */
            
            
            
            
            
            
            
            
            
            
            
			//-----------------------------------

			//Метод для использования HTML5 video
			showVideo:function(container, settings){
				var vid = document.createElement("video");
				$(vid).attr({
                    width:[settings.width],
					height:[settings.height],
					src:[settings.src],
					type:[settings.type],
					id:[$.aplayer.idElMedia+$.aplayer.aplayerNo],
					'class':$.aplayer.classElMedia
				}).appendTo(container);

				$.aplayer.setControls(container, settings);
			},
			//-----------------------------------

			//Mетод для использования HTML5 audio
			showAudio:function(container, settings){
				var audio = document.createElement("audio");
				$(audio).attr(
					{ width:[settings.width],
				//	height:[settings.height], //для аудио высоту не задаем
					src:[settings.src],
					type:[settings.type],
					id:[$.aplayer.idElMedia+$.aplayer.aplayerNo],
					'class':$.aplayer.classElMedia
				}).appendTo(container);

				$.aplayer.setControls(container, settings);
			},

			//Общая для всех плееров конфигурация
			config:{
				height:'Inherit',
				width:'Inherit',
				
				dictionery:{
					stop:'Stop',
					pause:'Pause',
					play: 'Play',
					soundOn:'Sound on',
					soundOff: 'Sound off',
					volume:'Volume',
					search:'Search',
					currentTime:'Current Time',
					duration:'Duration'
				},
				
				//Отображает содержимое объекта config
				Show:function(){
					var str='';
					for(var p in $.aplayer.config){
						if ($.aplayer.config[p].toString().indexOf('function')==-1){
							str += p +" = " +$.aplayer.config[p]+'\n';
							
							if(p.toString().indexOf('dictionery')!=-1){
								str+='{\n';
								for(var tip in $.aplayer.config.dictionery){
									str +='\t'+ tip +" = " +$.aplayer.config.dictionery[tip]+'\n';	
								}
								str+='}\n';
							}
						}
						else str += p +" = " + 'function(){}'+'\n';
					}
					alert(str);
				}
			},


			//------------------------ Установка контролов

            //Номер текущего при установке элемента
			aplayerNo:0,
            //Базовая часть идентификатора эл-та (без номера)
			idElMedia:'elMedia_',
			classElMedia:'ElMedia',
			classMediaCont:'MediaCont',
			idContainer:'aplayerNo_',
			idControlPanel:'controlPanel_',
			idPlay: 'cntrlPlay_',
			idPause: 'cntrlPause_',
			idSearch: 'cntrSearch_',
			idDuration: 'cntrDuration_',
			idCurrentTime:'cntrCurrentTime_',
			idStop:'cntrStop_',
			idSoundOff:'cntrSoundOff_',
			idSoundOn:'cntrSoundOn_',
			idVolume:'cntrVolume_',

			logo_error: 'error.jpg',
			logo_play:'logo_play.png',
			
			
			
        ControlBar:{
			//Control's images location
        	// controlsImg:'aplayerControls/',
        	controlsImg:'gallery/img/aplayerControls/',


			//Базовая разметка контролов
            panel:'<div style="cursor:default;"></div>',

			play: '<div > <img style="height:100%;" />  </div>',
            pause:'<div style="display:none;"> <img style="height:100%;" />  </div>',
			stop: '<div > <img style="height:100%;" /> </div>',

            search:'<div style="padding:1px;" ><div /></div>',

			duration:'<div  style="height:12px; font-family:Verdana; font-size:10px; font-weight:bold; overflow:hidden; text-align:right;">0:00:00</div>',
			currentTime:'<div  style="height:12px; font-family:Verdana;   font-size:10px; font-weight:bold; overflow:hidden; text-align:right;" >0:00:00</div>',
			soundOff:'<div><img style="height:100%;" /></div>',
			soundOn:'<div style="display:none;"><img style="height:100%; " /></div>',

			volume:'<div> <div class="divSlider" /> </div>',


 			/*Стили субКонтейнеров для элементов*/
			ControlsContainers: {
				'float':'left',
				'height':'25px'
	        },


            /*Стили слайдеров*/
	        Search_handle: {
	            'top': '-2px',
	            'margin-left': '-1px',
                'z-index': '2',
	            'width': '2px',
    	        'height': '7px',
                'background-color': 'Darkred',
	            'cursor': 'default',
	            'border': '1px solid Darkred'
	        },
            Search_line: {
                'height':'5px',
                'padding-left':'5px',
				'width':'99.25%',
	            'border': '1px solid Blue',
	            'background-color': 'Lightblue'
	        },

	         Volume_handle: {
                'top': '0px',
	            'margin-left': '-1px',
                'z-index': '2',
	            'width': '2px',
    	        'height': '8px',
                'background-color': 'Darkred',
	            'cursor': 'default',
	            'border': '1px solid Darkred'

	        },
            Volume_line: {
            	'top':'5px',
            	'width':'60px',
                'height':'10px',
                'padding-left':'5px',
	            'border': '1px solid Blue',
	            'background-color': 'Lightblue',
	            'left':'2px'
	        },


			//Обработчики событий медиа элемента
			elMediaOnTimeUpdate:function(ElNum){
                if($('#'+$.aplayer.idSearch+ElNum).attr('isPlaying')!= undefined) return;
                var MediaElt = $('#'+$.aplayer.idElMedia+ElNum)[0];
                if(MediaElt==undefined) return;
				var tDur = MediaElt.currentTime;
				if( tDur >= $('#'+$.aplayer.idElMedia+ElNum)[0].duration)
				{
					$('#'+$.aplayer.idElMedia+ElNum).each(function(){
						this.pause();
						this.currentTime = 0;
						$.aplayer.ControlBar.elMediaOnCanPlay(ElNum);
					});
					return;
				}
				$('#'+$.aplayer.idSearch+ElNum).slider('value', tDur*10);
				Math.round(tDur);
				var tStr = $.aplayer.ControlBar.FormatTime(tDur);

                $('#'+$.aplayer.idCurrentTime+ElNum).html(tStr);

			},


			elMediaOnCanPlay:function(ElNum){
				var MediaElt = $('#'+$.aplayer.idElMedia+ElNum)[0];
				if(MediaElt==undefined) return;
				var tDur = Math.round(MediaElt.duration);
				var tStr = $.aplayer.ControlBar.FormatTime(tDur);

                $('#'+$.aplayer.idDuration+ElNum).html(tStr);
				$('#'+$.aplayer.idSearch+ElNum).slider({'max': tDur*10 });
				tDur = Math.round($('#'+$.aplayer.idElMedia+ElNum)[0].currentTime);
				tStr = $.aplayer.ControlBar.FormatTime(tDur);
				$('#'+$.aplayer.idCurrentTime+ElNum).html(tStr);
			},

			elMediaOnPlay:function(ElNum){
                var me = $('#'+$.aplayer.idElMedia+ElNum);
                me.volume = $('#'+$.aplayer.idVolume+ElNum).slider('value')/40;

				$('#'+$.aplayer.idPlay+ElNum).hide();
				$('#'+$.aplayer.idPause+ElNum).show();
			},
			elMediaOnPause:function(ElNum){
                if($('#'+$.aplayer.idSearch+ElNum).attr('isPlaying')!=undefined) return;
				$('#'+$.aplayer.idPlay+ElNum).show();
				$('#'+$.aplayer.idPause+ElNum).hide();
			},
			elMediaOnEnded:function(ElNum){
				$('#'+$.aplayer.idPlay+ElNum).show();
				$('#'+$.aplayer.idPause+ElNum).hide();
			},
			elDurationChanged:function(ElNum){
				var tDur = Math.round($('#'+$.aplayer.idElMedia+ElNum)[0].duration);
				var tStr = $.aplayer.ControlBar.FormatTime(tDur);
			},


            //Обработчики событий контролов
			soundOnClickHandler: function(ElNum){
               $('#'+$.aplayer.idElMedia+ElNum).each(function(){ this.muted=false; });
			   $('#'+$.aplayer.idSoundOff+ElNum).show();
			   $('#'+$.aplayer.idSoundOn+ElNum).hide();
            },

			soundOffClickHandler: function(ElNum){
               $('#'+$.aplayer.idElMedia+ElNum).each(function(){ this.muted=true; });
			   $('#'+$.aplayer.idSoundOff+ElNum).hide();
			   $('#'+$.aplayer.idSoundOn+ElNum).show();
            },

            playClickHandler: function(ElNum){
               $('#'+$.aplayer.idElMedia+ElNum).each(function(){ this.play(); });
            },

            pauseClickHandler:function(ElNum){
               $('#'+$.aplayer.idElMedia+ElNum).each(function(){ this.pause(); });
            },

			stopClickHandler:function(ElNum){
               $('#'+$.aplayer.idElMedia+ElNum).each(function(){
					this.pause();
					try{
					this.currentTime = 0;
					}catch(error){
						console.log(error.message);
					}
					$.aplayer.ControlBar.elMediaOnCanPlay(ElNum);
			   });
            },


			searchOnStartHandler:function(Elem){
                var me = $('#'+$.aplayer.idElMedia+$(Elem).attr('No'))[0];
                if(!me.paused) $(Elem).attr({'isPlaying':'true'});
			    else $(Elem).attr({'isPlaying':'false'});
				me.pause();
            },


			searchOnStopHandler:function(Elem){
                var me = $('#'+$.aplayer.idElMedia+$(Elem).attr('No'))[0];
				$(me).each(function(){
					var setTime = ($(Elem).slider('value'))/10;
					this.currentTime = setTime;
					var tStr = $.aplayer.ControlBar.FormatTime(setTime);
				$('#'+$.aplayer.idCurrentTime+$(Elem).attr('No')).html(tStr);
			   });
               $(me).each(function(){
					if($(Elem).attr('isPlaying')=='true')me.play();
					$(Elem).removeAttr('isPlaying');
			   });
            },

			searchOnSlideHandler:function(Elem){
                var me = $('#'+$.aplayer.idElMedia+$(Elem).attr('No'))[0];
				$(me).each(function(){
					var setTime = ($(Elem).slider('value'))/10;
					this.currentTime = setTime;
					var tStr = $.aplayer.ControlBar.FormatTime(setTime);
				$('#'+$.aplayer.idCurrentTime+$(Elem).attr('No')).html(tStr);
			   });
            },

            //формирование строки времени
            FormatTime:function(Seconds){
                return  (Math.round(Seconds/(60*60)))+':'+((Math.round(
						Seconds/60)%60)<10? '0'+(Math.round(Seconds/60)%60):(Math.round(
						Seconds/60)%60))+':'+(Math.round(Seconds%60)<10? '0'+Math.round(
						Seconds%60):Math.round(Seconds%60));
            },

			volumeSlideHandler:function(Elem){
                var me = $('#'+$.aplayer.idElMedia+$(Elem).attr('No'))[0];
                me.volume = $(Elem).slider('value')/40;
            },

            volumeStopHandler:function(Elem){
                var me = $('#'+$.aplayer.idElMedia+$(Elem).attr('No'))[0];
                me.volume = $(Elem).slider('value')/40;
                $(Elem).hide();
            }


        },

		//Установка контролов
		setControls:function(container, settings)
		{
			//Контролы не отображаются
			if(settings.controls=='none') return;
			//Использовать контролы браузера
			else if(settings.controls=='browser'){
				$(container).find('.'+$.aplayer.classElMedia).attr('controls', 'controls');
				return;
			}

			var Volume = $($.aplayer.ControlBar.volume).css($.aplayer.ControlBar.ControlsContainers).attr({'title':$.aplayer.config.dictionery.volume});
			$(Volume).find('.divSlider').slider({
            	range: "min",
                min : 0,
                max : 40,
			    value: 30,
                orientation: 'horizontal',
                slide:function(event, ui){$.aplayer.ControlBar.volumeSlideHandler(this);},
                change:function(event, ui){$.aplayer.ControlBar.volumeSlideHandler(this);}
			}).attr({
                'id': $.aplayer.idVolume+$.aplayer.aplayerNo,
                'No':$.aplayer.aplayerNo
                }).find('.ui-slider-range').addClass('ui-corner-all').attr({'left':'2px'}); 

                
            //Установка стилей ползунка
            $(Volume).find('.ui-slider-handle').css($.aplayer.ControlBar.Volume_handle).removeClass('ui-state-default');
            //Установка стилей полосы поиска
            $(Volume).find('.ui-slider').css($.aplayer.ControlBar.Volume_line);


			var soundOn = $($.aplayer.ControlBar.soundOn).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.soundOnClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idSoundOn+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'SndOn.png'}).end()
				.attr({'title':$.aplayer.config.dictionery.soundOn,'alt': $.aplayer.config.dictionery.soundOn});


			var soundOff = $($.aplayer.ControlBar.soundOff).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.soundOffClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idSoundOff+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'SndOff.png'}).end()
				.attr({'title':$.aplayer.config.dictionery.soundOff,'alt': $.aplayer.config.dictionery.soundOff });


			var Stop = $($.aplayer.ControlBar.stop).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.stopClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idStop+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'Stop.png'}).end()
				.attr({'title':$.aplayer.config.dictionery.stop,'alt': $.aplayer.config.dictionery.stop });

			var CurrentTime = $($.aplayer.ControlBar.currentTime).attr(
				{'id':$.aplayer.idCurrentTime+$.aplayer.aplayerNo, 'title':$.aplayer.config.dictionery.currentTime});

			var Duration = $($.aplayer.ControlBar.duration).attr(
				{'id':$.aplayer.idDuration+$.aplayer.aplayerNo, 'title':$.aplayer.config.dictionery.duration});


			var Times = $('<div />').css($.aplayer.ControlBar.ControlsContainers).append(CurrentTime).append(Duration);


			var Search = $($.aplayer.ControlBar.search).attr({'title':$.aplayer.config.dictionery.search});;
			$(Search).find(':first-child').slider({
			    range: "min",
			    value: 0,
			    start:function(event, ui){$.aplayer.ControlBar.searchOnStartHandler(this);},
                stop:function(event, ui){$.aplayer.ControlBar.searchOnStopHandler(this);},
                slide:function(event, ui){$.aplayer.ControlBar.searchOnSlideHandler(this);}
			}).attr({
                'id': $.aplayer.idSearch+$.aplayer.aplayerNo,
                'No':$.aplayer.aplayerNo
                }).find('.ui-slider-range').addClass('ui-corner-all').attr({'left':'2px'}); 

            //Установка стилей ползунка
            $(Search).find('.ui-slider-handle').css($.aplayer.ControlBar.Search_handle).removeClass('ui-state-default');
            //Установка стилей полосы поиска
            $(Search).find('.ui-slider').css($.aplayer.ControlBar.Search_line);

			var Play = $($.aplayer.ControlBar.play).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.playClickHandler("'+$.aplayer.aplayerNo+'")',
					id: $.aplayer.idPlay+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'Play.png'}).end()
				.attr({'title':$.aplayer.config.dictionery.play,'alt': $.aplayer.config.dictionery.play });


			var Pause = $($.aplayer.ControlBar.pause).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.pauseClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idPause+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'Pause.png'}).end()
				.attr({'title':$.aplayer.config.dictionery.pause,'alt': $.aplayer.config.dictionery.pause });

			//Создаем панель контролов
            var ControlBar = $($.aplayer.ControlBar.panel).height(37);
            
            $('#'+$.aplayer.idContainer+ $.aplayer.aplayerNo).height($('#'+$.aplayer.idContainer+ $.aplayer.aplayerNo).parent().parent().height());

            var meHeight =  $('#'+$.aplayer.idContainer+ $.aplayer.aplayerNo).parent().height()-$(ControlBar).height()-10;
            
			
			//автоматическая подгонка панели контролов под размеры плеера = {controls:'auto'}
			if(settings.controls==null || settings.controls=='auto'){
				$(ControlBar).css({ 'width':'99.5%' }); 
				
				//вставляем в панель управления элементы управления
				$(ControlBar).attr('id',$.aplayer.idControlPanel+$.aplayer.aplayerNo)
				.append(Search).append(Play).append(Pause).append(Stop).append(Times)
				.append(soundOff).append(soundOn).append(Volume);
			}
            
			//автоматическая подгонка панели контролов под размеры плеера = {controls:'auto'}
			if(settings.controls=='mini'){
				$(ControlBar).css({ 'width':'99.3%'  }); 
				
				//вставляем в панель управления элементы управления
				$(ControlBar).attr('id',$.aplayer.idControlPanel+$.aplayer.aplayerNo).css({ 'text-align':'center' })
				.append(Search).append(Play).append(Pause).append(Stop).append(soundOff).append(soundOn);
			}
            
            //Создаем субконтейнер для медиа-элемента, вставлем в него медиа элемент и помещаем в контейнер плеера
			var SubCont = $('<div></div>').addClass($.aplayer.classMediaCont).css({'overflow':'hidden'}).height(meHeight);

			$('#'+$.aplayer.idElMedia + $.aplayer.aplayerNo).height(meHeight).appendTo($(SubCont));

			//Устанавливаем субконтейнер медиа-элемента и панель контролов в медиа плеер HTML5
            $('#'+$.aplayer.idContainer + $.aplayer.aplayerNo).append($(SubCont)).append($(ControlBar));
			$(ControlBar).css({'border':'2px solid #333333', 'background-color':'#333333'});

			//Устанавливаем на медиа эл-т обработчики событий
			$('#'+$.aplayer.idElMedia + $.aplayer.aplayerNo).attr({
				ontimeupdate:'$.aplayer.ControlBar.elMediaOnTimeUpdate('+$.aplayer.aplayerNo+')',
				oncanplay: '$.aplayer.ControlBar.elMediaOnCanPlay('+$.aplayer.aplayerNo+')',
				onplay: '$.aplayer.ControlBar.elMediaOnPlay('+$.aplayer.aplayerNo+')',
				onpause:'$.aplayer.ControlBar.elMediaOnPause('+$.aplayer.aplayerNo+')',
				onended:'$.aplayer.ControlBar.elMediaOnEnded('+$.aplayer.aplayerNo+')',
				ondurationchanged:'$.aplayer.ControlBar.elDurationChanged('+$.aplayer.aplayerNo+')'
			});

		}

    };



})(jQuery);







//------------------------GetBrowserInfo

(function($){

	//Инкапсулирует данные о браузере
	$.browserInfo = {
		HTML5_Audio:false,
		HTML5_Video:false,

		video_mp4:false,
		video_ogg:false,
		video_webm:false,

		audio_ogg:false,
		audio_mpeg:false,
		audio_wav:false,
		audio_x_m4a:false,

		isDefined:false,

		//Выводит значения свойств browserInfo
		Show:function(){
			var str='';
			for(var p in $.browserInfo){
				if ($.browserInfo[p].toString().indexOf('function')==-1)
					str += p +" = " +$.browserInfo[p]+'\n';
			//	else str += p +" = " + 'function(){}'+'\n'; //отображение методов объекта
			}
			alert(str);
		},


		//Сформировать данные о браузере
		getInfo:function(){
			if(!$.browserInfo.isDefined){
			$.browserInfo.support_HTML5_Audio();
			$.browserInfo.support_HTML5_Video();
			$.browserInfo.isDefined = true;
			}
		},

		//Check HTML5_Audio
		support_HTML5_Audio:function(){
			try{
			var audio = document.createElement("audio");
			$.browserInfo.HTML5_Audio = audio instanceof HTMLAudioElement;
			}catch(e){};
			if($.browserInfo.HTML5_Audio)
			{
				$.browserInfo.support_audio_ogg(audio);
				$.browserInfo.support_audio_mpeg(audio);
				$.browserInfo.support_audio_wav(audio);
				$.browserInfo.support_audio_x_m4a(audio);
			}
		},

		//Check HTML5_Video
		support_HTML5_Video:function(){
			var video = document.createElement("video");
			try{
				$.browserInfo.HTML5_Video = video instanceof HTMLVideoElement;
			}catch(e){};
			if($.browserInfo.HTML5_Video)
			{
				//Вызов методов определения поддерживаемых типов
				$.browserInfo.support_video_mp4(video);
				$.browserInfo.support_video_ogg(video);
				$.browserInfo.support_video_webm(video);
			}
		},

		//Check type video/mp4
		support_video_mp4 :function(video){
			$.browserInfo.video_mp4 = video.canPlayType('video/mp4; codecs="avc1.42E01E"');
			if($.browserInfo.video_mp4=='')
				$.browserInfo.video_mp4 = video.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');
			if($.browserInfo.video_mp4=='')$.browserInfo.video_mp4 = video.canPlayType('video/mp4');
			if($.browserInfo.video_mp4=='')$.browserInfo.video_mp4=false;
		},

		//Check type video/ogg
		support_video_ogg :function(video){
			$.browserInfo.video_ogg = video.canPlayType('video/ogg; codecs="theora"');
			if($.browserInfo.video_ogg=='')$.browserInfo.video_ogg = video.canPlayType('video/ogg;');
			if($.browserInfo.video_ogg=='')$.browserInfo.video_ogg=false;
		},

		//Check type video/webm
		support_video_webm:function(video){
			$.browserInfo.video_webm = video.canPlayType('video/webm; codecs="vp8, vorbis"');
			if($.browserInfo.video_webm=='')$.browserInfo.video_webm = video.canPlayType('video/webm;');
			if($.browserInfo.video_webm=='')$.browserInfo.video_webm=false;
		},

		support_audio_ogg:function(audio){
			$.browserInfo.audio_ogg = audio.canPlayType('audio/ogg; codecs="vorbis"');
			if($.browserInfo.audio_ogg=='')$.browserInfo.audio_ogg = audio.canPlayType('audio/ogg');
			if($.browserInfo.audio_ogg=='')$.browserInfo.audio_ogg=false;
		},
		support_audio_mpeg:function(audio){
			$.browserInfo.audio_mpeg = audio.canPlayType('audio/mpeg;');
			if($.browserInfo.audio_mpeg=='')$.browserInfo.audio_mpeg = audio.canPlayType('audio/mpeg;');
			if($.browserInfo.audio_mpeg=='')$.browserInfo.audio_mpeg=false;
		},
		support_audio_wav:function(audio){
			$.browserInfo.audio_wav = audio.canPlayType('audio/wav; codecs="1"');
			if($.browserInfo.audio_wav=='')$.browserInfo.audio_wav = audio.canPlayType('audio/wav');
			if($.browserInfo.audio_wav=='')$.browserInfo.audio_wav=false;
		},
		support_audio_x_m4a:function(audio){
			$.browserInfo.audio_x_m4a = audio.canPlayType('audio/x-m4a;');
			if($.browserInfo.audio_x_m4a=='')$.browserInfo.audio_x_m4a = audio.canPlayType('audio/aac;');
			if($.browserInfo.audio_x_m4a=='')$.browserInfo.audio_x_m4a=false;
		}

	};

})(jQuery);

