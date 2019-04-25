
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

$('.div_displayEquipement').disableSelection();
$( "input").click(function() { $(this).focus(); });
$( "textarea").click(function() { $(this).focus(); });
autosizeCamWidget(NB_COLUMN,NB_LINE);

function autosizeCamWidget(nbCamByLine,nbCamByColumn){
  var totalWidth = $('#div_displayObject').width();
  var camWidth = (totalWidth / nbCamByLine) - (2 * nbCamByLine) - 2 - jeedom.theme['widget::step::width'];
  $('#div_displayObject .eqLogic-widget').width(camWidth);
  var totalHeight = $(window).outerHeight() - $('header').outerHeight() - $('#div_alert').outerHeight()-25;
  var camHeight = (totalHeight / nbCamByColumn) - (2 * nbCamByColumn);
  $('#div_displayObject .eqLogic-widget').height(camHeight);
  positionEqLogic();
  $('#div_displayObject .eqLogic-widget .directDisplay img').css('max-width',$('#div_displayObject .eqLogic-widget').width());
  $('#div_displayObject').each(function(){
    var container = $(this).packery({
      itemSelector: ".eqLogic-widget",
      gutter : 0,
    });
  });
  $('#div_displayObject .eqLogic-widget').trigger('resize');
}
