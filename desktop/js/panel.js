
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
autosizeCamWidget();

function autosizeCamWidget(){
  var col = Math.ceil(12 / NB_COLUMN)
  $('#div_displayObject .eqLogic-widget').wrap('<div class="col-lg-'+col+' col-sm-12"></div>');
  $('#div_displayObject .eqLogic-widget').width('100%');
}
