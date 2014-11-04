
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

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

$('body').delegate('#bt_getFromMarket', 'click', function() {
    $('#md_modal').dialog({title: "{{Partager sur le market}}"});
    $('#md_modal').load('index.php?v=d&modal=market.list&type=camera').dialog('open');
});

$('body').delegate('#bt_shareOnMarket', 'click', function() {
    var logicalId = $('.eqLogicAttr[data-l1key=configuration][data-l2key=device]').value();
    if (logicalId == '') {
        $('#div_alert').showAlert({message: '{{Vous devez d\'abord séléctioner un équipement}}', level: 'danger'});
        return;
    }
    $('#md_modal').dialog({title: "{{Partager sur le market}}"});
    $('#md_modal').load('index.php?v=d&modal=market.send&type=camera&logicalId=' + encodeURI(logicalId) + '&name=' + encodeURI($('.eqLogicAttr[data-l1key=configuration][data-l2key=device] option:selected').text())).dialog('open');
});

$('#bt_uploadConfCam').fileupload({
    replaceFileInput: false,
    dataType: 'json',
    done: function(e, data) {
        if (data.result.state != 'ok') {
            $('#div_alert').showAlert({message: data.result.result, level: 'danger'});
            return;
        }
        if (modifyWithoutSave) {
            $('#div_alert').showAlert({message: '{{Fichier ajouté avec succes. Vous devez rafraichir pour vous en servir}}', level: 'success'})
        } else {
            window.location.reload();
        }
    }
});

function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    if (isset(_cmd.configuration.recordState) && _cmd.configuration.recordState == 1) {
        return;
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<div class="row">';
    tr += '<div class="col-lg-6">';
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icone</a>';
    tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
    tr += '</div>';
    tr += '<div class="col-lg-6">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
    tr += '</div>';
    tr += '</div>';
    tr += '<input class="cmdAttr" data-l1key="id"  style="display : none;">';
    tr += '<input class="cmdAttr" data-l1key="type" value="action" style="display : none;">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="subType" value="other" style="display : none;">';
    tr += '</td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="request" />';
    tr += '<td><input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="stopCmd" /> {{Stop commande}} ';
    tr += '<input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/> {{Afficher}}';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="timeout" style="display : inline-block;width : 100px;margin-left : 5px;" placeholder="timeout"/>';
    tr += '</div>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    initTooltips();
}