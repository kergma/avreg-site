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



});})(jQuery);









//---------------------------------------------------- Player ------------------------------------


(function($){

	//Установка плеера в заданные эл-ты html
	//settings: src , type , config{}
	$.fn.addPlayer = function(settings){
				if(settings.src==null)
				{
					alert('aplayer:\nError: src = undefined');
					return this;
				}
			else $(this).each(function(){
				$.aplayer.play(this, settings);
			});
		return this;
	};
	//-----------------------------------

	//Закрытие плеера
	$.fn.aplayerClose=function(){
		$(this).empty();
	};
	//-----------------------------------
	//Смена src изображения
	$.fn.aplayerSetImgSrc = function(imageSource){
		$(this).children('[id ^=' + $.aplayer.idContainer + ']').children('img').attr('src',imageSource);
	};
	//-----------------------------------
    //Изменение размеров медиаэлемента
    $.fn.aplayerSetSizeMediaElt = function(Sizes) {
	    $(this).children('[id ^=' + $.aplayer.idContainer + ']').each(function () {
    	    var Cont = $(this);
            var H = $(Cont).height();
	        var W = $(Cont).width();
            $(Cont).children('video, audio, object, embed, img, .'+$.aplayer.classMediaCont).each(function () {
	           if(!( $(this).hasClass($.aplayer.classMediaCont))) {
	            	$(this).height(Sizes.height).width(Sizes.width);

	            	//Проверяем на перетаскиваемость
	            	if(Sizes.height > H || Sizes.width > W) $(this).draggable().addClass('ui-corner-all ui-widget');
	            	else $(this).draggable('destroy').css({'position':'relative', 'top':'0'});
	           }
		       $(this).children('video, audio, embed').each(function () {
		       		$(this).height(Sizes.height).width(Sizes.width);
					//Проверяем на перетаскиваемость
	            	if($(this).height() > $(this).parent().height() || $(this).width() > $(this).parent().width()) $(this).draggable().addClass('ui-corner-all ui-widget');
	            	else $(this).draggable('destroy').css({'position':'relative', 'top':'0'});

		       }); //.css({'z-index':'0'});

            });
            $(Cont).height(H).width(W).css({'overflow':'hidden'});
        });

//      $(container).children(':first-child').draggable().addClass('ui-corner-all ui-widget');
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

        });
    };

    //Установка размеров плеера в соответствии с размерами его контейнера
    $.fn.aplayerResizeToParent = function () {
	    $(this).children('div[id ^=' + $.aplayer.idContainer + ']').each(function () {
	    $(this).height($(this).parent().height() - 5);
	    $(this).width($(this).parent().width() - 5);
	    var Cont = $(this);

	$(Cont).children('object, embed, img').each(function () {
	        $(this).height($(Cont).height() - 6).width($(Cont).width() - 6);
	        $(this).children('embed').each(function () {
	        $(this).height($(Cont).height() - 5).width($(Cont).width() - 6);
	        });
        }).end().children('.'+$.aplayer.classMediaCont).height(function(){
        	return $(Cont).height()-$(this).next('div[id ^=' + $.aplayer.idControlPanel + ']').height();
        }).children('video, audio').each(function () { //Установка размера суб-элемента и вложенного медиа-элемента НЕ ПРОВЕРЯЛАСЬ!!!
	        $(this).height($(Cont).height() - $(this).parent().next('div[id ^=' + $.aplayer.idControlPanel + ']').height() - 7).width($(Cont).width() - 6);
	        }).css({border:'2px solid green'});
        });
    };


	//Объект, инкапсулирующий свойства и методы плеера
	$.aplayer = {
		//Установка общей для всех плееров конфигурации
		//GlobalSettings: config{}
		init : function(GlobalSettings){
			$.extend($.aplayer.config, GlobalSettings);
		},
			//-----------------------------------


			//типы файлов
			extTypes:{
				image:['.png', '.jpg','.gif', '.bmp' ],
				video:['.mp4', '.ogg', '.ogv', '.webm'],
				audio:['.oga','.mp3', '.m4a', '.wav', '.mpeg'],
				application:['.avi']
			},
			//-----------------------------------
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
					var reg = new RegExp('\\.\\w{3,4}\\s*$', 'i');
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
			//-----------------------------------

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
			//-----------------------------------

			//Метод установки плеера
			play:function(element, settings){
				//сбор информации о браузере
				$.browserInfo.getInfo();
				//Установка параметров переданных через $.fn.add()
				var sets = $.extend({}, $.aplayer.config, settings);

				//Определение и установка типа
				sets = $.aplayer.setType(sets);

				//корректировка типа воспроизведения в зависимости от версии браузера
				settings = $.aplayer.browserVersionSettings(sets.type, settings);


				//Установка размеров плеера ('Inherit' - установка размеров родительского эл-та)
				try{
				if(sets.height.indexOf('Inherit')!=-1)sets.height = $(element).height();
				}catch(err){};
				try{
				if(sets.width.indexOf('Inherit')!=-1)sets.width = $(element).width();
				}catch(err){};

				//Создание контейнера для плеера
				$.aplayer.aplayerNo++;
				var container = $('<div style="overflow:hiden; text-align:center; " ></div>');
                //$(container).height($(element).height()).width($(element).width());
				$(container).attr('id', $.aplayer.idContainer+$.aplayer.aplayerNo);
				//Установка дополнительного класса
				 $(container).addClass(settings['class']);//.addClass('ui-draggable');

				$(element).html(container);

				if(sets.type.indexOf('image')!=-1){
					//Вызов метода вывода изображения
					$.aplayer.showImage(container, sets);
				}
				//Если задано значение application - воспроизводить как внедренный объект
				else if(sets.type.indexOf('application')!=-1 || (settings.application!=null && settings.application.indexOf('true')!=-1)){
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
		},

		//корректировка типа воспроизведения в зависимости от версии браузера
		browserVersionSettings: function(srcType ,settings){
			//Вывод версии браузера и тип открываемого файла
		//	alert('Browser\'s version: '+$.browser.version+'\nSource type: '+ settings.type);
			switch($.browser.version)
			{
				case '535.2': //Google Chrome v. 535.2
					if(srcType=='audio/wav' || srcType=='video/mp4' || srcType=='audio/mpeg')
					{
						$.extend(settings, {'application':'true'});
					}
				break;
				case '9.0.1': //Mozilla Firefox v. 9.0.1
					if(srcType=='audio/wav')
					{
						$.extend(settings, {'application':'true'});
					}
				break;
			}
			return settings;
		},


			//-----------------------------------

			//Метод вывода изображения
			showImage:function(container, settings){

				var size = 'style="width:'+settings.width+'px; height:'+settings.height+'px; "';
				$('<img title="'+settings.type+'" src="'+settings.src+'" '+size+' />').attr({'height':settings.height, 'width':settings.width }).appendTo(container);
			},

			//-----------------------------------
			//Метод для использования плагина
			showObject:function(container, settings){

					// Не добавляет высоту для аудио
					//if(!(settings.type.indexOf('audio')!=-1))size = 'style="width:'+settings.width+'px;'

             //Создаем object
             var obj;
               //QuickTime
               if(settings.type.indexOf($.aplayer.MIMEtypes.mp4)!=-1) obj = $.aplayer.createObj_QuickTime(settings);
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
               else obj = $.aplayer.create_Embed(settings);

               $(obj).attr({'width': settings.width, 'height': settings.height});

              $(obj).append($('<noembed>Your browser does not support video!!!!!!!!!!!!!!!!!!!!! </noembed>'));

               $(container).html(obj);
        	},

            //create EMBED
            create_Embed:function(settings){
                var size = 'width="'+settings.width+'" height="'+settings.height+'"';
                return $('<embed type="'+settings.type+'" autostart="false" auto="false" autoplay="false" allowfullscreen="true" allowScriptAccess="always" '+size+' />'
                    ).attr({'width': settings.width, 'height': settings.height, 'src':settings.src }); //.html('<noembed>Your browser does not support video</noembed>'); //'Your browser does not support video');
           },

           //Create video AVI  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
           createObj_AVI: function(settings){
			var obj = $('<object type="video/avi" data="'+settings.src+'" autoplay="false"> </object>'
				).append('<param name="src" value="'+settings.src+'" />'
				).append('<param name="controller" value="true" />'
				).append('<param name="autoplay" value="false" />'
				).append('<param name="autostart" value="0" />');
			$(obj).append( $($.aplayer.create_Embed(settings))	);
            return obj;
           },

           //Create audio/wav
           createObj_WAV: function(settings){
			var obj = $('<object type="audio/x-wav" data="'+settings.src+'" autoplay="false"> </object>'
				).append('<param name="src" value="'+settings.src+'" />'
				).append('<param name="controller" value="true" />'
				).append('<param name="autoplay" value="false" />'
				).append('<param name="autostart" value="0" />');
			$(obj).append($($.aplayer.create_Embed(settings)));
            return obj;
           },

           // Create object audio/mp3
           createObj_MP3:function(settings){
			var obj = $('<object type="audio/x-mpeg" data="'+settings.src+'" autoplay="false"> </object>'
				).append('<param name="src" value="'+settings.src+'" />'
				).append('<param name="controller" value="true" />'
				).append('<param name="autoplay" value="false" />'
				).append('<param name="autostart" value="0" />');

			$(obj).append($($.aplayer.create_Embed(settings)));
            return obj;
           },

            // Create object QuickTime
            createObj_QuickTime:function(settings){
               var obj = $('<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab"></object>');
               $(obj).append('<param name="controller" value="true" />').append('<param name="src" value="'+settings.src+'" />');
               $(obj).append($($.aplayer.create_Embed(settings)).attr({ "TYPE":"image/x-macpaint"}));
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
               $(obj).append('<param name="menu" value="1" >');
//               $(obj).append('<param name="devicefont" value="false" />');
//			    $(obj).append('<param name="salign" value="" />');
//				$(obj).append('<param name="allowScriptAccess" value="sameDomain" />');

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

			   $(obj).append('<embed type="audio/wav" PLUGINSPAGE="http://www.microsoft.com/windows/windowsmedia/download/" src="'+settings.src+'" width="'+settings.width+'" height="'+settings.height+'" autostart="false" showcontrols="true"></embed>');
               return obj;
            },

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

				if(settings.controls==null)$.aplayer.setControls(container);
				else $(vid).attr('controls', 'controls');
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

				if(settings.controls==null)$.aplayer.setControls(container);
				else $(audio).attr('controls', 'controls');
			},
			//-------------------------------------

			//Общая для всех плееров конфигурация
			config:{
				height:'Inherit',
				width:'Inherit',

				//Отображает содержимое объекта config
				Show:function(){
					var str='';
					for(var p in $.aplayer.config){
						if ($.aplayer.config[p].toString().indexOf('function')==-1)
							str += p +" = " +$.aplayer.config[p]+'\n';
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

        ControlBar:{
			//Control's images location
        	// controlsImg:'aplayerControls/',
        	controlsImg:'gallery/img/aplayerControls/',


			//Базовая разметка контролов
            panel:'<div style="cursor:default;"></div>',

			play: '<div > <img style="height:100%;"  title="Play" alt="Play" />  </div>',
            pause:'<div style="display:none;"> <img style="height:100%;" title="Pause" alt="Pause" />  </div>',
			stop: '<div > <img style="height:100%;" title="Stop" alt="Stop" /> </div>',

            search:'<div style="padding:1px;" ><div /></div>',

			duration:'<div  style="height:12px; font-family:Verdana; font-size:10px; font-weight:bold; overflow:hidden; text-align:right;">0:00:00</div>',
			currentTime:'<div  style="height:12px; font-family:Verdana;   font-size:10px; font-weight:bold; overflow:hidden; text-align:right;" >0:00:00</div>',
			soundOff:'<div><img style="height:100%;" title="Sound Off" alt="Sound Off" /></div>',
			soundOn:'<div style="display:none;"><img style="height:100%; " title="Sound On" alt="Sound On" /></div>',

			volume:'<div> <div class="divSlider" /> </div>',

//            volume:'<div  style="float:left; height:22px "> <img style="height:100%" title="Set Volume" alt="Set Volume" src="Controls/VolumeSet.png" /> <div class="divSlider" /> </div>',


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
	            'background-color': 'Lightblue'
	        },

	        /* Звук - вертикальный слайдер
            Volume_handle: {
                'margin-bottom':'-1px',
	            'left': '-1px',
                'margin-top':'20px',
                'z-index': '2',
	            'width': '8px',
    	        'height': '2px',
                'background-color': 'Darkred',
	            'cursor': 'default',
	            'border': '1px solid Darkred',

	        },
            Volume_line: {
                'width':'8px',
	            'border': '1px solid Blue',
	            'background-color': 'Lightblue',
                'position':'relative',
                'top':'-122px',
                'left':'25%',
                'padding':'0',
	        },
			*/




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
					this.currentTime = 0;
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
		setControls:function(container)
		{

			var Volume = $($.aplayer.ControlBar.volume).css($.aplayer.ControlBar.ControlsContainers);
			$(Volume).find('.divSlider').slider({
            	range: "min",
                min : 0,
                max : 40,
			    value: 30,
                orientation: 'horizontal',
//                stop:function(event, ui){$.aplayer.ControlBar.volumeStopHandler(this);},
                slide:function(event, ui){$.aplayer.ControlBar.volumeSlideHandler(this);},
                change:function(event, ui){$.aplayer.ControlBar.volumeSlideHandler(this);}
			}).attr({
                'id': $.aplayer.idVolume+$.aplayer.aplayerNo,
                'No':$.aplayer.aplayerNo
                }); 	//.css({'display':'none',}).end().attr(
                		//{ : '$.aplayer.ControlBar.volumeOnClickHandler("'+$.aplayer.aplayerNo+'")',});



            //Установка стилей ползунка
            $(Volume).find('.ui-slider-handle').css($.aplayer.ControlBar.Volume_handle);
            //Установка стилей полосы поиска
            $(Volume).find('.ui-slider').css($.aplayer.ControlBar.Volume_line);


			var soundOn = $($.aplayer.ControlBar.soundOn).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.soundOnClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idSoundOn+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'SndOn.png'}).end();

			var soundOff = $($.aplayer.ControlBar.soundOff).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.soundOffClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idSoundOff+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'SndOff.png'}).end();


			var Stop = $($.aplayer.ControlBar.stop).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.stopClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idStop+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'Stop.png'}).end();

			var CurrentTime = $($.aplayer.ControlBar.currentTime).attr(
				{'id':$.aplayer.idCurrentTime+$.aplayer.aplayerNo});

			var Duration = $($.aplayer.ControlBar.duration).attr(
				{'id':$.aplayer.idDuration+$.aplayer.aplayerNo});

			var Times = $('<div />').css($.aplayer.ControlBar.ControlsContainers).append(CurrentTime).append(Duration);


			var Search = $($.aplayer.ControlBar.search);
			$(Search).find(':first-child').slider({
			    range: "min",
			    value: 0,
			    start:function(event, ui){$.aplayer.ControlBar.searchOnStartHandler(this);},
                stop:function(event, ui){$.aplayer.ControlBar.searchOnStopHandler(this);},
                slide:function(event, ui){$.aplayer.ControlBar.searchOnSlideHandler(this);}
			}).attr({
                'id': $.aplayer.idSearch+$.aplayer.aplayerNo,
                'No':$.aplayer.aplayerNo
                });

            //Установка стилей ползунка
            $(Search).find('.ui-slider-handle').css($.aplayer.ControlBar.Search_handle);
            //Установка стилей полосы поиска
            $(Search).find('.ui-slider').css($.aplayer.ControlBar.Search_line);




			var Play = $($.aplayer.ControlBar.play).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.playClickHandler("'+$.aplayer.aplayerNo+'")',
					id: $.aplayer.idPlay+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'Play.png'}).end();

			var Pause = $($.aplayer.ControlBar.pause).css($.aplayer.ControlBar.ControlsContainers).attr({
					onclick: '$.aplayer.ControlBar.pauseClickHandler("'+$.aplayer.aplayerNo+'")',
					id:$.aplayer.idPause+$.aplayer.aplayerNo
				}).children('img').attr({'src': $.aplayer.ControlBar.controlsImg+'Pause.png'}).end();

            var ControlBar = $($.aplayer.ControlBar.panel).height(37).css({ 'width':'100%'  }); //, 'position':'relative', 'bottom':'0px'});


            $('#'+$.aplayer.idContainer+ $.aplayer.aplayerNo).height($('#'+$.aplayer.idContainer+ $.aplayer.aplayerNo).parent().parent().height());






            var meHeight =  $('#'+$.aplayer.idContainer+ $.aplayer.aplayerNo).parent().height()-$(ControlBar).height()-10;



			//вставляем в панель управления элементы управления
			$(ControlBar).attr('id',$.aplayer.idControlPanel+$.aplayer.aplayerNo).append(Search).append(Play).append(Pause).append(
				Stop).append(Times).append(soundOff).append(
                soundOn).append(Volume);

            //Создаем субконтейнер для медиа-элемента, вставлем в него медиа элемент и помещаем в контейнер плеера
			var SubCont = $('<div></div>').addClass($.aplayer.classMediaCont).css({'overflow':'hidden'}).height(meHeight);

			$('#'+$.aplayer.idElMedia + $.aplayer.aplayerNo).height(meHeight).appendTo($(SubCont));

//            $('#'+$.aplayer.idElMedia + $.aplayer.aplayerNo).height(meHeight-5);

			//Устанавливаем субконтейнер медиа-элемента и панель контролов в медиа плеер HTML5
            $('#'+$.aplayer.idContainer + $.aplayer.aplayerNo).append($(SubCont)).append($(ControlBar));
			$(ControlBar); //.css({'border':'1px solid silver', 'background-color':'#333333'});

//			           $('#'+$.aplayer.idContainer+ $.aplayer.aplayerNo).parent().parent().css({'border':'5px solid magenta'});;


//			$('#'+$.aplayer.idElMedia + $.aplayer.aplayerNo)[0].muted = false;

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
		//-----------------------------------




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

