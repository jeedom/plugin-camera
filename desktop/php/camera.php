<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.fileupload/jquery.ui.widget', 'js');
include_file('3rdparty', 'jquery.fileupload/jquery.iframe-transport', 'js');
include_file('3rdparty', 'jquery.fileupload/jquery.fileupload', 'js');
sendVarToJS('eqType', 'camera');
$eqLogics = eqLogic::byType('camera');
?>
<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une caméra}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
}
?>
           </ul>
       </div>
   </div>
    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
  <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
  <div class="eqLogicThumbnailContainer">
   <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
     <center>
      <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
    </center>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Ajouter}}</center></span>
  </div>
	<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
        <center>
        <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
      </center>
      <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
      </div>
      <div class="cursor" id="bt_healthCamera" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
        <center>
        <i class="fa fa-medkit" style="font-size : 6em;color:#767676;"></i>
      </center>
      <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>
      </div>
</div>
   <legend><i class="fa fa-video-camera"></i>  {{Mes caméras}}</legend>
    <div class="eqLogicThumbnailContainer">
<?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo "<center>";
	if (file_exists(dirname(__FILE__) . '/../../core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg')) {
		echo '<img class="lazy" src="plugins/camera/core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg" height="105" width="95" />';
	} else {
		echo '<img class="lazy" src="plugins/camera/doc/images/camera_icon.png" height="105" width="95" />';
	}
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
	echo '</div>';
}
?>
</div>
</div>

<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
    <div class="row">
        <div class="col-sm-6">
            <form class="form-horizontal">
                <fieldset>
                    <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}<i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Nom de l'équipement caméra}}</label>
                        <div class="col-sm-6">
                            <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                            <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement caméra}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                        <div class="col-sm-6">
                            <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                <option value="">{{Aucun}}</option>
                                <?php
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                           </select>
                       </div>
                   </div>
                   <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                     <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>
                     <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
                 </div>
             </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">{{IP}}</label>
                <div class="col-sm-3">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" placeholder="{{IP}}"/>
                </div>
                <label class="col-sm-2 control-label">{{Port}}</label>
                <div class="col-sm-2">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" placeholder="{{Port}}"/>
                </div>
                <div class="col-sm-2">
                    <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="protocole">
                        <option value='http'>HTTP</option>
                        <option value='https'>HTTPS</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Nom d'utilisateur}}</label>
                <div class="col-sm-3">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username" placeholder="{{Nom d'utilisateur}}"/>
                </div>
                <label class="col-sm-2 control-label">{{Mot de passe}}</label>
                <div class="col-sm-2">
                    <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" placeholder="{{Mot de passe}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{URL de capture}}</label>
                <div class="col-sm-7">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="urlStream" placeholder="{{URL de capture}}"/>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="refreshDelay" placeholder="{{Rafraichissement (s)}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Commande d'alerte (mail, slack...)}}</label>
                <div class="col-sm-5">
                <div class="input-group">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="alertMessageCommand" placeholder="{{Commande mail pour l'envoi d'une capture}}"/>
                        <span class="input-group-btn">
                            <a class="btn btn-default listCmdActionMessage" id="bt_selectActionMessage"><i class="fa fa-list-alt"></i></a>
                        </span>
                    </div>
                </div>
                <label class="col-sm-3 control-label">{{Nombre de captures}}</label>
                <div class="col-sm-1">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="alertMessageNbSnapshot"/>
                </div>
            </div>
             <!-- <div class="form-group">
                <label class="col-sm-3 control-label">{{Rotation de l'image}}</label>
                <div class="col-sm-2">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="imageRotate" placeholder="{{En ° positif}}"/>
                </div>
            </div> -->
        </fieldset>
    </form>
</div>
<div class="col-sm-6">
    <form class="form-horizontal">
        <fieldset>
            <legend><i class="fa fa-wrench"></i>  {{Configuration}}</legend>
            <div class="form-group">
                <label class="col-sm-2 control-label">{{Modèle}}</label>
                <div class="col-sm-5">
                    <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="device">
                        <option value="">{{1 - Aucun}}</option>
                        <?php
foreach (camera::devicesParameters() as $id => $info) {
	echo '<option value="' . $id . '">' . $info['name'] . '</option>';
}
?>
                   </select>
               </div>
           </div>
           <center>
            <img src="core/img/no_image.gif" data-original=".jpg" id="img_device" class="img-responsive" style="max-height : 250px;" onerror="this.src='plugins/camera/doc/images/camera_icon.png'"/>
        </center>
    </fieldset>
</form>
</div>
</div>
<form class="form-horizontal">
    <fieldset>
        <div class="form-actions" align="right">
            <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        </div>
    </fieldset>
</form>
<legend><i class="fa fa-camera"></i>  {{Caméra}}</legend>
<a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une commande}}</a><br/><br/>
<table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th style="width : 70px;">{{#}}</th>
            <th style="width : 300px;">{{Nom}}</th>
            <th style="width : 150px;">{{Type}}</th>
            <th >{{Requête}}</th>
            <th style="width : 150px;">{{Options}}</th>
            <th style="width : 150px;"></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<form class="form-horizontal">
    <fieldset>
        <div class="form-actions">
            <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        </div>
    </fieldset>
</form>
</div>
</div>

<?php include_file('desktop', 'camera', 'js', 'camera');?>
<?php include_file('core', 'plugin.template', 'js');?>
