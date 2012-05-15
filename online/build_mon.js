/**
 * @file online/build_mon.js
 * @brief Реализует функции, осуществляющие проверку корректности установки камер в раскладке
 * 
 * Вызовы данных функций осуществляются в online/build_mon.php
 * 
 */


/**
 * @function validate
 * Проверяет наличие хотя бы одной установленной камеры в раскладке ,
 * в противном случае выводит соотв. сообщение
 * @returns {Boolean} true - OK, false - не установленна ни одна камера
 */

function validate(){
   var cams_selects = document.getElementsByName('cams_in_wins[]');
   if ( typeof(cams_selects) == 'undefined' )
      return false;
   var cams_select=null;
   var i;
   var choised=0;
   for(i=0;i<cams_selects.length;i++) {
      cams_select = cams_selects[i];
      if (cams_select.selectedIndex>0 )
         choised++;
   }
   if (choised>0) {
      $('#buildform').attr('target', $(':input[name=OpenInBlankPage]').attr('checked') ? '_blank' : '_parent');
      return true;
   } else {
      alert('Сначала Вы должны выбрать камеры для просмотра в форме.');
      return false;
   }
}

/**
 * @function sel_change
 * Осуществляет проверку - не повторяется ли повторная установка в раскладке одна и та же камера,
 * в случае повтора - выводит соответствующее сообщение и сбрасывает установенную камеру
 * @param sel - элемент select текущей ячейки раскладки камер, в кот. осуществляется установка камеры
 * @returns {Boolean} true - камера не выбрана , false - в остальных случаях
 */
function sel_change(sel) {
   if (sel.selectedIndex==0)
      return true;
   var cams_selects = document.getElementsByName('cams_in_wins[]');
   if (typeof(cams_selects) == 'undefined') 
      return false;
   var cams_select=null;
   var i;
   var choised=0;
   for(i=0;i<cams_selects.length;i++) {
      cams_select = cams_selects[i];
      if (cams_select != sel)
      {
         if ( cams_select.selectedIndex == sel.selectedIndex ) {
            alert('Камера №' + sel.options[sel.selectedIndex].text + ' уже выбрана  в другом окне!' );
            sel.selectedIndex=0;
            break;
         }
      }
   }  
   return false;
}

/*
$(document).ready( function() {
      $(':input[name=OpenInBlankPage]').change(function () {
         $('#buildform').attr('target', this.checked?'_blank':'_parent');
         }).trigger('change');
      });
*/
