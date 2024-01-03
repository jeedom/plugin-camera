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

function initCameraPanel(_object_id) {
  if(typeof jeedomUtils.setBackgroundImage == 'function'){
    jeedomUtils.setBackgroundImage('plugins/camera/core/img/panel.jpg');
  }
  jeedom.object.all({
    onlyHasEqLogic : 'camera',
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (objects) {
      var li = ' <ul data-role="listview">';
      li += '<li></span><a href="#" class="link" data-page="panel" data-plugin="camera" data-title="Caméra" data-option=""><span><i class="fas fa-globe"></i></span>{{Tous}}</a></li>'
      for (var i in objects) {
        if (objects[i].isVisible == 1) {
          var icon = '';
          if (isset(objects[i].display) && isset(objects[i].display.icon)) {
            icon = objects[i].display.icon;
          }
          li += '<li></span><a href="#" class="link" data-page="panel" data-plugin="camera" data-title="' + icon.replace(/\"/g, "\'") + ' ' + objects[i].name + '" data-option="' + objects[i].id + '"><span>' + icon + '</span> ' + objects[i].name + '</a></li>';
        }
      }
      li += '</ul>';
      jeedomUtils.loadPanel(li);
    }
  });
  displayCamera(_object_id);
  
  $(window).on("resize", function (event) {
    jeedomUtils.setTileSize('.eqLogic');
    $('#div_displayEquipementCamera').packery({gutter : 0});
    $('#div_displayEquipementCamera').packery({gutter : 0});
  });
}

function displayCamera(_object_id) {
  $.showLoading();
  $.ajax({
    type: 'POST',
    url: 'plugins/camera/core/ajax/camera.ajax.php',
    data: {
      action: 'getCamera',
      object_id: _object_id,
      version: 'mobile'
    },
    dataType: 'json',
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (data.state != 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        })
        return;
      }
      $('#div_displayEquipementCamera').empty();
      for (var i in data.result.eqLogics) {
        $('#div_displayEquipementCamera').append(data.result.eqLogics[i]).trigger('create');
      }
      jeedomUtils.setTileSize('.eqLogic');
      $('.eqLogic-widget').addClass('displayObjectName');
      $('#div_displayEquipementCamera').packery({gutter : 0});
      $('#div_displayEquipementCamera').packery({gutter : 0});
      $.hideLoading();
    }
  });
}
