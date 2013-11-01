/**
 * Объект, инкапсулирующий свойства и методы контролов управления
 * vlc plugin
 * @type {{intervalId: {}, - Сохраняет id setInterval обновление времени ролика
 * time: {}, - Время, которое необходимо установить после перетаскивания слайдера
 * slideBegin: {}, - начало перетаскивания слайдера
 * Примечание: слайдер работает корректно только тогда, когда правильно определяется
 * длительность ролика vlc
 * Для ActiveX Объекта IE тестировалось на Videolan.VlcPlugin.2
 * У ActiveX с версией ниже (axvlc.cab к примеру 0.8.6) работает некорректно (проблемы самого плагина)
 * Для Windows тестировалось на версиях VLC 2.0.1-2.0.5
 * ActiveX VLC 2.0.6 подгружаться в IE отказался
 */

vlcPlayer = {
    intervalId: {},
    time: {},
    slideBegin: {},
    setEvents: {},
    clickPause: {},
    sliderValueBegin: {},

    /**
     * Добавление обработчика события VLC
     * @param event - Имя события (без on)
     * @param handler - Функция-обработчик
     * @param numPlayer - Номер плеера
     */
    registerVLCEvent: function (event, handler, numPlayer) {
        var vlc = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
        if (vlc) {
            if (vlc.attachEvent) {
                // Microsoft
                vlc.attachEvent(event, handler);
            } else if (vlc.addEventListener) {
                // Mozilla: DOM level 2
                vlc.addEventListener(event, handler, false);
            } else {
                // DOM level 0
                vlc["on" + event] = handler;
            }
        }
    },

    /**
     * Очищает объект
     */
    resetInstancePlayer: function (numPlayer, actions) {
        if (actions === undefined) {
            if (numPlayer !== undefined) {
                for (var i in vlcPlayer.intervalId) {
                    if (i === numPlayer) {
                        vlcPlayer.stopClickHandler(numPlayer);
                        vlcPlayer.removeIntervalHandler(numPlayer);
                    }
                }
            }
            if (numPlayer === undefined || vlcPlayer.intervalId.length === 0) {
                vlcPlayer.removeIntervalHandler(numPlayer);
                vlcPlayer.setEvents = {};
                vlcPlayer.intervalId = {};
            }
            delete vlcPlayer.slideBegin;
            delete vlcPlayer.time;
            delete vlcPlayer.sliderValueBegin;
            vlcPlayer.slideBegin = {};
            vlcPlayer.time = {};
            vlcPlayer.sliderValueBegin = {};
        } else {
            vlcPlayer.stopClickHandler(numPlayer);
            delete vlcPlayer.time[numPlayer];
            delete vlcPlayer.slideBegin[numPlayer];
            delete vlcPlayer.sliderValueBegin[numPlayer];
            vlcPlayer.removeIntervalHandler(numPlayer);
        }
    },

    retIdMediaElem: function (numElem) {
        return (typeof $('#' + $.aplayer.embedObj_idname + numElem).attr('id') !== 'undefined') ?
            ($.aplayer.embedObj_idname + numElem) :
            ('activX_' + numElem);
    },

    /**
     * Начало/продолжение воспроизведения
     * @param numPlayer - Номер плеера
     */
    playClickHandler: function (numPlayer) {
        $('#' + $.aplayer.idPlay + numPlayer).hide();
        $('#' + $.aplayer.idPause + numPlayer).show();
        var isPlay = true;
        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
        delete vlcPlayer.clickPause[numPlayer];
        try {
            vlcObj.play();
            if (typeof vlcPlayer.setEvents[numPlayer] === 'undefined') {
                vlcPlayer.intervalId[numPlayer] = setInterval("vlcPlayer.updateTimeOlderPlugin(" + numPlayer + ')', 300);
                vlcPlayer.setEvents[numPlayer] = 'install';
            }
        } catch (e) {
            try {
                vlcObj.playlist.play();
                if (typeof vlcPlayer.setEvents[numPlayer] == 'undefined') {
                    // Привязываю обработчики на элемент
                    vlcPlayer.intervalId[numPlayer] = setInterval("vlcPlayer.updateTime(" + numPlayer + ')', 300);
                    vlcPlayer.setEvents[numPlayer] = 'install';
                }
            } catch (e) {
                isPlay = false;
            }
        }
        // Если воспроизведение не началось
        if (!isPlay) {
            $('#' + $.aplayer.idPlay + numPlayer).show();
            $('#' + $.aplayer.idPause + numPlayer).hide();
        }
    },


    //удаление обработчиков интервала
    removeIntervalHandler: function (numPlayer) {
        clearInterval(vlcPlayer.intervalId[numPlayer]);
        delete vlcPlayer.intervalId[numPlayer];
        delete vlcPlayer.setEvents[numPlayer];
    },


    /**
     * Пауза
     * @param numPlayer - Номер плеера
     */
    pauseClickHandler: function (numPlayer) {
        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
        vlcPlayer.removeIntervalHandler(numPlayer);
        vlcPlayer.clickPause[numPlayer] = true;
        try {
            vlcObj.pause();
        } catch (e) {
            // Если используется старый plugin
            try {
                vlcObj.playlist.togglePause();
            } catch (e) {
                delete vlcPlayer.clickPause[numPlayer];
            }
        }
        $('#' + $.aplayer.idPlay + numPlayer).show();
        $('#' + $.aplayer.idPause + numPlayer).hide();
    },

    /**
     * Получение длительности ролика
     * @param numPlayer - номер плеера
     * @returns int - длительно ролика или 0 если длительность не удалось определить
     */
    retLen: function (numPlayer) {
        var len = 0;
        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
        try {
            len = vlcObj.input.length;
        } catch (e) {
            // Если используется старый plugin
            len = vlcObj.Length;
        }
        return len;
    },

    /**
     * Остановить воспроизведение
     * @param numPlayer - номер плеера
     */
    stopClickHandler: function (numPlayer) {
//        try{
        delete vlcPlayer.clickPause[numPlayer];
//        }catch(e){}
        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
        try {
            vlcObj.playlist.stop();
        } catch (e) {
            // Если используется старый plugin
            vlcObj.stop();
            vlcPlayer.removeIntervalHandler(numPlayer);
        }

        if (typeof vlcPlayer.setEvents[numPlayer] !== 'undefined') {
            vlcPlayer.removeIntervalHandler(numPlayer);
        }

        vlcPlayer.installCurrentTime(numPlayer, $.aplayer.ControlBar.FormatTimeReally(0, numPlayer));
        $('#' + $.aplayer.idSearch + numPlayer).slider('value', 0);
        $('#' + $.aplayer.idPlay + numPlayer).show();
        $('#' + $.aplayer.idPause + numPlayer).hide();
    },

    /**
     * Получение длительности из значения в БД
     * @param duration - продолжительность в БД
     * @returns {number} - Продолжительность в секундах
     */
    retDuration: function (duration) {
        var dur = new Array();
        dur = duration.split(':');
        return ((3600 * parseInt(dur[0]) + 60 * parseInt(dur[1]) + parseInt(dur[2])) * 1000);
    },

    /**
     * Обновление времени во время воспроизвдения
     * @param numPlayer - Номер плеера
     */
    updateTime: function (numPlayer) {
        if ($('#' + vlcPlayer.retIdMediaElem(numPlayer)).length == 0) {
            vlcPlayer.removeIntervalHandler(numPlayer);
            return;
        }

        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));

        var duration = vlcObj.input.time / 1000;
        var cel_nr = parseInt($('#aplayerNo_' + numPlayer).parents('.content_item').attr('id').replace('cell_', ''));
        var len = (vlcPlayer.retLen(numPlayer) > 0) ? vlcPlayer.retLen(numPlayer) : vlcPlayer.retDuration(matrix.events[cel_nr][8]);
        var step = len / 1000 / 100;
        var sliderValue = (Math.round(duration / step));
        $('#' + $.aplayer.idSearch + numPlayer).slider('value', sliderValue);

        if (vlcObj.input.state === 3) {
            vlcPlayer.installCurrentTime(numPlayer, $.aplayer.ControlBar.FormatTimeReally(duration, numPlayer));
        }

        if (vlcObj.input.state < 1 || vlcObj.input.state > 4) {
            vlcPlayer.removeIntervalHandler(numPlayer);
            $('#' + $.aplayer.idPlay + numPlayer).show();
            $('#' + $.aplayer.idPause + numPlayer).hide();
            if (vlcObj.input.state !== 4) {
                vlcPlayer.installCurrentTime(numPlayer, $.aplayer.ControlBar.FormatTimeReally(0, numPlayer));
                $('#' + $.aplayer.idSearch + numPlayer).slider('value', 0);
                if (!vlcPlayer.slideBegin[numPlayer]) {
                    vlcPlayer.stopClickHandler(numPlayer);
                }
            }
        } else {
            vlcPlayer.installCurrentTime(numPlayer, $.aplayer.ControlBar.FormatTimeReally(duration, numPlayer));
        }
    },

    updateTimeOlderPlugin: function (numPlayer) {
        try {
            var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
            var duration = vlcObj.Time / 1000;
            var len = (vlcPlayer.retLen(numPlayer) > 0) ? vlcPlayer.retLen(numPlayer) : vlcPlayer.retDuration(matrix.events[numPlayer][8]);
            var step = (len / 1000) / 100;
            var sliderValue = (Math.round(duration / step));
            $('#' + $.aplayer.idSearch + numPlayer).slider('value', sliderValue);
            vlcPlayer.installCurrentTime(numPlayer, $.aplayer.ControlBar.FormatTimeReally(duration, numPlayer));
            if (!vlcObj.Playing) {
                vlcPlayer.removeIntervalHandler(numPlayer);
                if (typeof vlcPlayer.clickPause[numPlayer] === 'undefined') {
                    vlcPlayer.stopClickHandler(numPlayer);
                }
            }
        } catch (e) {
            vlcPlayer.removeIntervalHandler(numPlayer);
            vlcPlayer.stopClickHandler(numPlayer);
            $($.aplayer.idSearch + numPlayer).slider('value', 0);
            return;
        }
    },

    /**
     * Получение времени в зависимости от позиции слайдера во время перетаскивания
     * @param numPlayer - Номер плеера
     * @returns {number} -
     */
    searchTimeSlider: function (numPlayer) {
        var len = (vlcPlayer.retLen(numPlayer) > 0) ? vlcPlayer.retLen(numPlayer) : vlcPlayer.retDuration(matrix.events[numPlayer][8]);
        var step = len / 1000 / 100;
        return $('#' + $.aplayer.idSearch + numPlayer).slider('value') * step;
    },

    /**
     * Перетаскиваем слайдер
     * @param container
     * @param numPlayer - Номер плеера
     */
    searchOnSlideHandler: function (container) {
        var numPlayer = $(container).attr('no');
        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
        var statusCheck = true;
        try {
            if (vlcObj.input.state != 0 && vlcObj.input.length) {
                if (vlcObj.playlist.isPlaying) {
                    vlcPlayer.pauseClickHandler(numPlayer);
                }
                var time = vlcPlayer.searchTimeSlider(numPlayer);
                vlcPlayer.time[numPlayer] = time * 1000;
                vlcObj.input.time = time * 1000;
                if (vlcObj.input.isPlaying) {
                    vlcPlayer.pauseClickHandler(numPlayer);
                }
                vlcPlayer.installCurrentTime(numPlayer, $.aplayer.ControlBar.FormatTimeReally(time, numPlayer));
            }
        } catch (e) {
            // Если используется старый plugin
//            if (vlcObj.input.state!=0 && vlcObj.input.Length) {
            if (vlcObj.Length) {
                if (vlcObj.Playing) {
                    vlcPlayer.pauseClickHandler(numPlayer);
                }
                var time = vlcPlayer.searchTimeSlider(numPlayer);
                if (vlcObj.Playing) {
                    vlcPlayer.pauseClickHandler(numPlayer);
                }
                vlcPlayer.time[numPlayer] = time;
                //vlcObj.shuttle(time);
                vlcObj.Position = $('#' + $.aplayer.idSearch + numPlayer).slider('value') / 100;
                vlcPlayer.installCurrentTime(numPlayer, $.aplayer.ControlBar.FormatTimeReally(time, numPlayer));
            }
        }
    },

    /**
     * Останавливаем перетаскивание
     * @param container
     * @param numPlayer - Номер плеера
     */
    searchOnStopHandler: function (container) {
        var numPlayer = $(container).attr('no');
        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));

        if ($.browser.msie) {
            if (vlcObj.Length > 0) {
                vlcPlayer.searchOnSlideHandler(container);
                delete vlcPlayer.clickPause[numPlayer];
//            $('#' + $.aplayer.idSearch + numPlayer).slider('value', vlcPlayer.sliderValueBegin[numPlayer]);
                delete vlcPlayer.sliderValueBegin[numPlayer];
//                vlcPlayer.playClickHandler(numPlayer, vlcPlayer.time[numPlayer]);
                vlcPlayer.playClickHandler(numPlayer);
                vlcPlayer.time[numPlayer] = null;
                vlcPlayer.slideBegin[numPlayer] = false;
            } else {
                $('#' + $.aplayer.idSearch + numPlayer).slider('value', '0');
//            vlcPlayer.playClickHandler(numPlayer);
            }
        } else {
            if (vlcObj.input.state != 0 && vlcObj.input.length > 0) {
                vlcPlayer.searchOnSlideHandler(container);
                delete vlcPlayer.clickPause[numPlayer];
                delete vlcPlayer.sliderValueBegin[numPlayer];
                vlcPlayer.playClickHandler(numPlayer);
                vlcPlayer.time[numPlayer] = null;
                vlcPlayer.slideBegin[numPlayer] = false;
            } else {
                $('#' + $.aplayer.idSearch + numPlayer).slider('value', '0');
            }
        }

    },

    /**
     * Начало перетаскивания плеера
     * @param container
     * @param numPlayer - Номер плеера
     */
    searchOnStartHandler: function (container) {
        var numPlayer = $(container).attr('no');
        vlcPlayer.slideBegin[numPlayer] = true;
        vlcPlayer.sliderValueBegin[numPlayer] = $('#' + $.aplayer.idSearch + numPlayer).slider('value');
        vlcPlayer.pauseClickHandler(numPlayer);
    },

    /**
     * Установка текущего времени в панель тулбаров
     * @param numPlayer - Номер плеера
     * @param time - время
     */
    installCurrentTime: function (numPlayer, time) {
        $('#' + $.aplayer.idCurrentTime + numPlayer).html(time[0]);
        $('#' + $.aplayer.idDuration + numPlayer).html(time[1]);
    },

    /**
     * Включение/Выключение звука
     * @param numPlayer - номер плеера
     */
    VolumeHandler: function (numPlayer) {
        var vlcObj = document.getElementById(vlcPlayer.retIdMediaElem(numPlayer));
        try {
            vlcObj.audio.mute = !vlcObj.audio.mute;
        } catch (e) {
            // Если используется старый плагин
            try {
                vlcObj.toggleMute();
            } catch (e) {
            }
        }
    },

    /**
     * Включение звука
     * @param numPlayer - номер плеера
     */
    soundOnClickHandler: function (numPlayer) {
        $('#' + $.aplayer.idSoundOn + numPlayer).hide();
        $('#' + $.aplayer.idSoundOff + numPlayer).show();
        vlcPlayer.VolumeHandler(numPlayer);
    },

    /**
     * Выключение звука
     * @param numPlayer - Номер плеера
     */
    soundOffClickHandler: function (numPlayer) {
        $('#' + $.aplayer.idSoundOn + numPlayer).show();
        $('#' + $.aplayer.idSoundOff + numPlayer).hide();
        vlcPlayer.VolumeHandler(numPlayer);
    }
};
