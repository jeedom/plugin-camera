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
                <a class="btn btn-default btn-sm tooltips" id="bt_getFromMarket" title="Récuperer du market" style="width : 100%;"><i class="fa fa-shopping-cart"></i> {{Market}}</a>
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une caméra}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes caméras}}
            <span style="font-size: 0.7em;color:#c5c5c5">
                Vous devez être connecté à internet pour voir les prévisualisation
            </span>
        </legend>
        <?php
        if (count($eqLogics) == 0) {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez aucune caméra. Cliquez sur le bouton camera à droite pour ajouter une camera</span></center>";
        } else {
            ?>
            <div class="eqLogicThumbnailContainer">
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                    echo "<center>";
                    $urlPath = config::byKey('market::address') . '/market/camera/images/' . $eqLogic->getConfiguration('device') . '.jpg';
                    $urlPath2 = config::byKey('market::address') . '/market/camera/images/' . $eqLogic->getConfiguration('device') . '_icon.png';
                    $urlPath3 = config::byKey('market::address') . '/market/camera/images/' . $eqLogic->getConfiguration('device') . '_icon.jpg';
                    echo '<img class="lazy" src="plugins/camera/doc/images/camera_icon.png" data-original3="' . $urlPath3 . '" data-original2="' . $urlPath2 . '" data-original="' . $urlPath . '" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
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
                            <div class="col-sm-1">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/> Activer 
                                </label>
                            </div>
                            <label class="col-sm-1 control-label"></label>
                            <div class="col-sm-1">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/> Visible 
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Gestion du proxy d'accès à la caméra :}}</label>
                            <div class="col-sm-5">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="proxy_mode">
                                    <option value="nginx">Jeedom (http et nginx seulement)</option>
                                    <option value="manuel">Manuel</option>
                                </select>
                            </div>
                        </div>
                        <div class="proxy_mode nginx">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{IP}}</label>
                                <div class="col-sm-5">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip_cam" placeholder="{{IP}}"/>
                                </div>
                                <label class="col-sm-2 control-label">{{Port}}</label>
                                <div class="col-sm-2">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port_cam" placeholder="{{Port}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="proxy_mode manuel">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{IP}}</label>
                                <div class="col-sm-5">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" placeholder="{{IP}}"/>
                                </div>
                                <label class="col-sm-2 control-label">{{Port}}</label>
                                <div class="col-sm-2">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" placeholder="{{Port}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{IP Externe}}</label>
                                <div class="col-sm-5">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip_ext" placeholder="{{IP}}"/>
                                </div>
                                <label class="col-sm-2 control-label">{{Port externe}}</label>
                                <div class="col-sm-2">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port_ext" placeholder="{{Port}}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Nom d'utilisateur}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username" placeholder="{{Nom d'utilisateur}}"/>
                            </div>
                            <label class="col-sm-3 control-label">{{Mot de passe}}</label>
                            <div class="col-sm-3">
                                <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" placeholder="{{Mot de passe}}"/>
                            </div>
                        </div>
                    </fieldset> 
                </form>
            </div>
            <div class="col-sm-6">
                <legend>{{Configuration}}</legend>
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Modèle de caméra}}</label>
                            <div class="col-sm-5">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="device">
                                    <option value="">{{Aucun}}</option>
                                    <?php
                                    foreach (camera::devicesParameters() as $id => $info) {
                                        echo '<option value="' . $id . '">' . $info['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <a class="btn btn-warning" id="bt_shareOnMarket"><i class="fa fa-cloud-upload"></i> {{Partager}}</a>

                            </div>
                        </div>
                        <div class="form-group expertModeVisible">
                            <label class="col-sm-3 control-label">{{Envoyer une configuration}}</label>
                            <div class="col-sm-5">
                                <input id="bt_uploadConfCam" type="file" name="file" data-url="plugins/camera/core/ajax/camera.ajax.php?action=uploadConfCam">
                            </div>
                            <div class="col-sm-4">
                                <a class="btn btn-success eqLogicAction" data-action="export"><i class="fa fa-cloud-download"></i> {{Exporter}}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{URL du flux}}</label>
                            <div class="col-sm-6">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="urlStream" placeholder="{{URL du flux}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Protocole d'accès}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="protocole">
                                    <option value='http'>HTTP</option>
                                    <option value='https'>HTTPS</option>
                                    <option value='rtsp'>RTSP</option>
                                </select>
                            </div>
                            <label class="col-sm-3 control-label">{{Protocole commande}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="protocoleCommande">
                                    <option value='http'>HTTP</option>
                                    <option value='https'>HTTPS</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Méthode d'affichage}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="displayProtocol">
                                    <option value='image'>Standard</option>
                                    <option value='vlc'>VLC</option>
                                    <option value='jpeg'>JPEG</option>
                                </select>
                            </div>
                            <div class="displayProtocol jpeg">
                                <label class="col-sm-3 control-label">{{Fréquence (s)}}</label>
                                <div class="col-sm-3">
                                    <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="jpegRefreshTime" />
                                </div>
                            </div>
                        </div>
                    </fieldset> 
                </form>
            </div>
        </div>

        <legend>{{Caméra}}</legend>
        <a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une commande}}</a><br/><br/>
        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>{{Nom}}</th>
                    <th>{{Type}}</th>
                    <th>{{Requête/Durée enregistrement (secondes)}}</th>
                    <th>{{Options}}</th>
                    <th></th>
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

<?php include_file('desktop', 'camera', 'js', 'camera'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
