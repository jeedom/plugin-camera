
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

 $('body').delegate('#bt_getFromMarket', 'click', function () {
    $('#md_modal').dialog({title: "{{Partager sur le market}}"});
    $('#md_modal').load('index.php?v=d&modal=market.list&type=camera').dialog('open');
});

 $('body').delegate('#bt_shareOnMarket', 'click', function () {
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
    done: function (e, data) {
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

 $('.eqLogic').on('change switchChange.bootstrapSwitch','.eqLogicAttr[data-l1key=configuration][data-l2key=proxy_mode]', function () {
    if($(this).value() == 1){
        $('#div_proxy_mode_off').hide();
        $('#div_proxy_mode_on').show();
    }else{
        $('#div_proxy_mode_off').show();
        $('#div_proxy_mode_on').hide();
    }
});

 $('.eqLogicAttr[data-l1key=configuration][data-l2key=displayProtocol]').on('change', function () {
    $('.displayProtocol').hide();
    $('.displayProtocol.' + $(this).value()).show();

});

 $('.eqLogicAttr[data-l1key=configuration][data-l2key=protocoleFlux]').on('change', function () {
     if($(this).value() == 'rtsp'){
       $('.eqLogicAttr[data-l1key=configuration][data-l2key=displayProtocol]').value('vlc');
       $('.eqLogicAttr[data-l1key=configuration][data-l2key=displayProtocol]').prop('disabled',true);
   }else{
    $('.eqLogicAttr[data-l1key=configuration][data-l2key=displayProtocol]').prop('disabled',false);
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
    tr += '<span class="cmdAttr" data-l1key="id"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<div class="row">';
    tr += '<div class="col-sm-6">';
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icone</a>';
    tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
    tr += '</div>';
    tr += '<div class="col-sm-6">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
    tr += '</div>';
    tr += '</div>';
    tr += '</td>';
    tr += '<td class="expertModeVisible">';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="request" />';
    tr += '<td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-l1key="configuration" data-label-text="{{Stop commande}}" data-l2key="stopCmd" data-size="small" /> ';
    tr += '<input type="checkbox" class="cmdAttr bootstrapSwitch" data-l1key="isVisible" data-label-text="{{Afficher}}" data-size="small" checked/> ';
    tr += '<input type="checkbox" class="cmdAttr bootstrapSwitch" data-l1key="configuration" data-l2key="useCurlDigest" data-label-text="{{Curl digest}}" data-size="small" /> ';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="timeout" style="display : inline-block;width : 100px;margin-left : 5px;" placeholder="timeout"/>';
    tr += '</div>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
    initTooltips();
}