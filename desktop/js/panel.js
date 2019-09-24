
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
$('.eqLogic-widget').addClass('displayObjectName');
autosizeCamWidget(NB_COLUMN,NB_LINE);

function autosizeCamWidget(nbCamByLine,nbCamByColumn){
  var margin = 4;
  if(jeedom.theme && jeedom.theme['widget::margin']){
    margin = jeedom.theme['widget::margin'];
  }
  var totalWidth = $('#div_displayObject').width();
  var camWidth = (totalWidth / nbCamByLine) - margin * 2 ;
  $('#div_displayObject .eqLogic-widget').width(camWidth);
  var totalHeight = $(window).outerHeight() - $('header').outerHeight() - $('#div_alert').outerHeight()-25;
  var camHeight = (totalHeight / nbCamByColumn) - (2 * nbCamByColumn);
  $('#div_displayObject .eqLogic-widget').height(camHeight);
  $('#div_displayObject .eqLogic-widget:not(.jeedomAlreadyPosition),.scenario-widget:not(.jeedomAlreadyPosition)').css('margin',margin+'px');
  $('#div_displayObject .eqLogic-widget,.scenario-widget').addClass('jeedomAlreadyPosition');
  $('#div_displayObject .eqLogic-widget .directDisplay img').css('max-width',$('#div_displayObject .eqLogic-widget').width());
  $('#div_displayObject').each(function(){
    var container = $(this).packery({
      itemSelector: ".eqLogic-widget",
      gutter : 0,
    });
  });
  $('#div_displayObject .eqLogic-widget').trigger('resize');
}
