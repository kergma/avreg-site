/**
 * @file offline/gallery/js/jquery.checkbox.js
 * @brief Реализует контрол типа чекбокс
 */
jQuery(document).ready(function(){

/**
 *  при клике на чекбоксе меняем его вид и значение 
 */
jQuery(".niceCheck").mousedown(

function() {

     changeCheck(jQuery(this));
     
});

/**
 *  при загрузке страницы нужно проверить какое значение имеет чекбокс и в соответствии с ним выставить вид 
 */
jQuery(".niceCheck").each(
function() {
     
     changeCheckStart(jQuery(this));
     
});

                                        });
/** 
*функция смены вида и значения чекбокса
*el - span контейнер дял обычного чекбокса
*input - чекбокс
*/
function changeCheck(el)
{
     var el = el,
          input = el.find("input").eq(0);
   	 if(!input.attr("checked")) {
		el.css("background-position","0 -14px");	
		input.attr("checked", true)
	} else {
		el.css("background-position","0 0");	
		input.attr("checked", false)
	}
     return true;
}

function changeCheckStart(el)
/** 
*	если установлен атрибут checked, меняем вид чекбокса
*/
{
var el = el,
		input = el.find("input").eq(0);
      if(input.attr("checked")) {
		el.css("background-position","0 -14px");	
		}
     return true;
}

		
