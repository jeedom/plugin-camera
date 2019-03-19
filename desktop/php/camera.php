<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('camera');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>
<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay" style="padding-left: 25px;">
		<legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" >
				<center>
					<i class="fas fa-plus-circle"></i>
				</center>
				<span><center>{{Ajouter}}</center></span>
			</div>
			<div class="cursor eqLogicAction" data-action="gotoPluginConf">
				<center>
					<i class="fas fa-wrench"></i>
				</center>
				<span><center>{{Configuration}}</center></span>
			</div>
			<div class="cursor" id="bt_healthCamera">
				<center>
					<i class="fas fa-medkit"></i>
				</center>
				<span><center>{{Santé}}</center></span>
			</div>
		</div>
		<legend><i class="fa fa-video-camera"></i> {{Mes caméras}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" style="margin-bottom:4px;" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
				echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '" >';
				echo "<center>";
				if (file_exists(dirname(__FILE__) . '/../../core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg')) {
					echo '<img class="lazy" src="plugins/camera/core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg" height="105" width="95" />';
				} else {
					echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
				}
				echo "</center>";
				echo '<span class="name"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
				echo '</div>';
			}
			?>
		</div>
	</div>
	<div class="col-xs-12 eqLogic" style="padding-left: 25px;display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fa fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#displaytab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Image}}</a></li>
			<li role="presentation"><a href="#capturetab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-video-camera"></i> {{Capture}}</a></li>
			<li role="presentation"><a href="#alimtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-power-off"></i> {{Alimentation}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<div class="row">
					<div class="col-sm-6">
						<form class="form-horizontal">
							<fieldset>
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
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{IP}}</label>
									<div class="col-sm-9">
										<div class="input-group">
											<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="protocole">
												<option value='http'>{{HTTP}}</option>
												<option value='https'>{{HTTPS}}</option>
											</select>
											<span class="input-group-addon">://</span>
											<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" placeholder="{{IP}}"/>
											<span class="input-group-addon">:</span>
											<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" placeholder="{{Port}}"/>
										</div>
									</div>
								</div>
								<div class="form-group onvifgOnly">
									<label class="col-sm-3 control-label">{{Port ONVIF}}</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onvif_port"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Nom d'utilisateur}}</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" autocomplete="new-password" data-l1key="configuration" data-l2key="username" placeholder="{{Nom d'utilisateur}}"/>
									</div>
									<label class="col-sm-2 control-label">{{Mot de passe}}</label>
									<div class="col-sm-2">
										<input type="password" class="eqLogicAttr form-control" autocomplete="new-password" data-l1key="configuration" data-l2key="password" placeholder="{{Mot de passe}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{URL de snaphot}}</label>
									<div class="col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="urlStream" placeholder="{{URL de capture}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{URL du flux}}</label>
									<div class="col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraStreamAccessUrl" placeholder="{{URL du flux, RTSP}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Position sur le panel}}</label>
									<div class="col-sm-7">
										<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="panel::position" />
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="col-sm-6">
						<form class="form-horizontal">
							<fieldset>
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
									<img src="core/img/no_image.gif" data-original=".jpg" id="img_device" class="img-responsive" style="max-height : 250px;" onerror="this.src='plugins/camera/plugin_info/camera_icon.png'"/>
								</center>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			
			<div role="tabpanel" class="tab-pane" id="displaytab">
				<form class="form-horizontal">
					<legend>{{Configuration}}</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{Ne pas compresser ou redimensionner l'image}}</label>
						<div class="col-sm-7">
							<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="doNotCompressImage" />
						</div>
					</div>
					<legend>{{Miniature}}</legend>
					<table id="table_image" class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th style="width : 70px;"></th>
								<th style="width : 300px;">{{Dashboard}}</th>
								<th style="width : 300px;">{{Mobile}}</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><label>{{Rafraichissement (s)}}</label></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="thumbnail::refresh" /></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="thumbnail::mobilerefresh" /></td>
							</tr>
							<tr>
								<td><label>{{Qualité (%)}}</label></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="thumbnail::compress" /></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="thumbnail::mobilecompress" /></td>
							</tr>
							<tr>
								<td><label>{{Taille (% - 0 : automatique)}}</label></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="thumbnail::resize" /></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="thumbnail::mobileresize" /></td>
							</tr>
						</tbody>
					</table>
					<legend>{{Normal}}</legend>
					<table id="table_image" class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th style="width : 70px;"></th>
								<th style="width : 300px;">{{Dashboard}}</th>
								<th style="width : 300px;">{{Mobile}}</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><label>{{Rafraichissement (s)}}</label></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="normal::refresh" /></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="normal::mobilerefresh" /></td>
							</tr>
							<tr>
								<td><label>{{Qualité (%)}}</label></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="normal::compress" /></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="normal::mobilecompress" /></td>
							</tr>
							<tr>
								<td><label>{{Taille (% - 0 : automatique)}}</label></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="normal::resize" /></td>
								<td><input type="text" class="eqLogicAttr form-control compressOpt" data-l1key="configuration" data-l2key="normal::mobileresize" /></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			
			<div role="tabpanel" class="tab-pane" id="capturetab">
			</br>
			<form class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Durée maximum d'un enregistrement (s)}}</label>
					<div class="col-sm-2">
						<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="maxReccordTime" />
					</div>
					<label class="col-sm-2 control-label">{{Toujours faire une video}}</label>
					<div class="col-sm-1">
						<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="preferVideo" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Nombre d'images par seconde de la vidéo}}</label>
					<div class="col-sm-2">
						<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="videoFramerate" />
					</div>
					<label class="col-sm-2 control-label">{{Seuil de détection mouvement (0-100)}}</label>
					<div class="col-sm-2">
						<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="moveThreshold" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Supprimer toutes les captures de la caméra}}</label>
					<div class="col-sm-2">
						<a class="btn btn-danger" id="bt_removeAllCapture"><i class="fa fa-trash"></i> {{Supprimer}}</a>
					</div>
				</div>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="alimtab">
		</br>
		<form class="form-horizontal">
			<div class="form-group">
				<div class="form-group">
					<label class="col-sm-1 control-label">{{Commande ON}}</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commandOn"/>
							<span class="input-group-btn">
								<a class="btn btn-default listCmdActionOther"><i class="fa fa-list-alt"></i></a>
							</span>
						</div>
					</div>
					<label class="col-sm-1 control-label">{{OFF}}</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commandOff"/>
							<span class="input-group-btn">
								<a class="btn btn-default listCmdActionOther"><i class="fa fa-list-alt"></i></a>
							</span>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	
	<div role="tabpanel" class="tab-pane" id="commandtab">
		<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Ajouter une commande}}</a><br/><br/>
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
		
	</div>
</div>
</div>
</div>

<?php include_file('desktop', 'camera', 'js', 'camera');?>
<?php include_file('core', 'plugin.template', 'js');?>
