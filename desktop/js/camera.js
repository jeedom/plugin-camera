
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
 $('#bt_healthCamera').on('click', function () {
    $('#md_modal').dialog({title: "{{Santé Caméras}}"});
    $('#md_modal').load('index.php?v=d&plugin=camera&modal=health').dialog('open');
});

 $('#bt_discoverCam').on('click', function () {
    $('#md_modal').dialog({title: "{{Santé Caméras}}"});
    $('#md_modal').load('index.php?v=d&plugin=camera&modal=discover.cam').dialog('open');
});

 $('.eqLogicAttr[data-l1key=configuration][data-l2key=doNotCompressImage]').on('change', function () {
    if($(this).value() == 1){
        $('.compressOpt').prop('disabled', true);
    }else{
       $('.compressOpt').prop('disabled', false);
   }
});

 $("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

 $(".eqLogicAttr[data-l1key=configuration][data-l2key=device]").html($(".eqLogicAttr[data-l1key=configuration][data-l2key=device] option").sort(function (a, b) {
    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
}))


 $('.eqLogicAttr[data-l1key=configuration][data-l2key=device]').on('change', function () {
  if($('.li_eqLogic.active').attr('data-eqlogic_id') != ''){
    $('#img_device').attr("src", 'plugins/camera/core/config/devices/'+$(this).value()+'.jpg');
}else{
    $('#img_device').attr("src",'plugins/camera/plugin_info/camera_icon.png');
}
if($(this).value() == 'onvif'){
    $('.onvifgOnly').show();
}else{
    $('.onvifgOnly').hide();
}
});

 $(".listCmdActionOther").on('click', function () {
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd: {type: 'action',subType : 'other'}}, function (result) {
        el.closest('.input-group').find('input').value(result.human);
    });
});

 function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    if (isset(_cmd.logicalId) && 
        (_cmd.logicalId == 'browseRecord' || _cmd.logicalId == 'recordState' || _cmd.logicalId == 'recordCmd' || _cmd.logicalId == 'stopRecordCmd' ||  _cmd.logicalId == 'takeSnapshot' || _cmd.logicalId == 'sendSnapshot' ||  _cmd.logicalId == 'urlFlux' || _cmd.logicalId == 'on' || _cmd.logicalId == 'off')) {
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
tr += '<td>';
tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
tr += '</td>';
tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="request" style="margin-bottom : 5px;" placeholder="{{URL de la commande}}"/>';
tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="stopCmdUrl" placeholder="{{URL de la commande de stop de mouvement (caméra motirisée)}}" />';
tr += '<input class="cmdAttr form-control input-sm" data-l1key="logicalId" />';
tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateValue" placeholder="{{Valeur retour d\'état}}" style="width : 30%; display : inline-block;margin-top : 5px;margin-right : 5px;" />';
tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateTime" placeholder="{{Durée avant retour d\'état (min)}}" style="width : 30%; display : inline-block;margin-top : 5px;margin-right : 5px;" />';
tr += '<td>';
tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span>';
tr += '</div>';
tr += '</td>';
tr += '<td>';
if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
}
tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
tr += '</tr>';
$('#table_cmd tbody').append(tr);
$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

$('#table_cmd tbody').on('change','.cmd .cmdAttr[data-l1key=type]',function(){
    var cmd = $(this).closest('.cmd');
    if($(this).value() == 'info'){
        cmd.find('.cmdAttr[data-l1key=configuration][data-l2key=request]').hide();
        cmd.find('.cmdAttr[data-l1key=configuration][data-l2key=stopCmdUrl]').hide();
        cmd.find('.actionMode').hide();
    }else{
     cmd.find('.cmdAttr[data-l1key=logicalId]').hide();
     cmd.find('.cmdAttr[data-l1key=configuration][data-l2key=request]').show();
     cmd.find('.cmdAttr[data-l1key=configuration][data-l2key=stopCmdUrl]').show();
     cmd.find('.actionMode').show();
 }
});


$('#bt_removeAllCapture').on('click',function(){
   bootbox.confirm('{{Etes-vous sur de vouloir supprimer toutes les captures de la caméra ?}}', function (result) {
    if (result) {
     $.ajax({
        type: "POST", 
        url: "plugins/camera/core/ajax/camera.ajax.php", 
        data: {
            action: "removeAllSnapshot",
            id : $('.eqLogicAttr[data-l1key=id]').value()
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { 
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: '{{Supression réussie}}', level: 'success'});
        }
    });
 }
});
});

function prePrintEqLogic(){
    $('.eqLogicAttr[data-l1key=configuration][data-l2key=doNotCompressImage]').value()
}