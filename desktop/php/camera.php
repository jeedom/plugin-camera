<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('camera');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add" >
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br/>
				<span class="text-cursor">{{Configuration}}</span>
			</div>
			<div class="cursor logoSecondary" id="bt_discoverCam">
				<i class="fas fa-search"></i>
				<br/>
				<span>{{Découverte}}</span>
			</div>
			<div class="cursor logoSecondary" id="bt_healthCamera">
				<i class="fas fa-medkit"></i>
				<br/>
				<span>{{Santé}}</span>
			</div>
		</div>
		<legend><i class="fas fa-video"></i> {{Mes caméras}}</legend>
		<div class="input-group" style="margin:5px;">
			<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic"/>
			<div class="input-group-btn">
				<a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
			</div>
		</div>
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '" >';
				if ($eqLogic->getConfiguration('device') != ""){
					if (camera::getImgFilePath($eqLogic->getConfiguration('device')) !== false) {
						echo '<img class="lazy" src="plugins/camera/core/config/devices/' . camera::getImgFilePath($eqLogic->getConfiguration('device')) . '"/>';
					} else {
						echo '<img src="' . $plugin->getPathImgIcon() . '" />';
					}
				}else{
					echo '<img src="' . $plugin->getPathImgIcon() . '" />';
				}
				echo "<br/>";
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs">  {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#displaytab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-image"></i> {{Image}}</a></li>
			<li role="presentation"><a href="#capturetab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-video"></i> {{Capture}}</a></li>
			<li role="presentation"><a href="#alimtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-power-off"></i> {{Alimentation}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<div class="row">
					<div class="col-lg-7">
						<form class="form-horizontal">
							<fieldset>
								<legend><i class="fas fa-wrench"></i> {{Général}}</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
										<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement caméra}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" >{{Objet parent}}</label>
									<div class="col-xs-11 col-sm-7">
										<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
											<option value="">{{Aucun}}</option>
											<?php
											$options = '';
											foreach ((jeeObject::buildTree(null, false)) as $object) {
												$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
											}
											echo $options;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
	<label class="col-sm-3 control-label">{{Catégorie}}</label>
	<div class="col-sm-9">
		<?php
		foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
			echo '<label class="checkbox-inline">';
			echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
			echo '</label>';
		}
		?>
	</div>
</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Options}}</label>
									<div class="col-xs-11 col-sm-7">
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
									</div>
								</div>

								<br/>
								<legend><i class="fas fa-cogs"></i> {{Paramètres}}</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{IP}}</label>
									<div class="col-xs-11 col-sm-7">
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
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onvif_port"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Nom d'utilisateur}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr form-control"  data-l1key="configuration" data-l2key="username" placeholder="{{Nom d'utilisateur}}"/>
									</div>
								</div>
									<div class="form-group">
									<label class="col-sm-3 control-label">{{Mot de passe}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="password" autocomplete="new-password" class="eqLogicAttr form-control"  data-l1key="configuration" data-l2key="password" placeholder="{{Mot de passe}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{URL de snaphot}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="urlStream" placeholder="{{URL de capture}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{URL du flux}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cameraStreamAccessUrl" placeholder="{{URL du flux, RTSP}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Stream du flux RTSP}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="streamRTSP"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Option flux video}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="rtsp_option" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Position sur le panel}}</label>
									<div class="col-xs-11 col-sm-7">
										<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="panel::position" />
									</div>
								</div>
								<br/>
							</fieldset>
						</form>
					</div>

					<div class="col-lg-5">
						<form class="form-horizontal">
							<fieldset>
								<legend><i class="fas fa-info"></i> {{Informations}}</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Modèle}}</label>
									<div class="col-xs-11 col-sm-7">
										<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="device">
											<option value="">{{Aucun}}</option>
											<?php
											$manufacturers = array();
											foreach (camera::devicesParameters() as $id => &$info) {
												if(!isset($info['manufacturer'])){
													$info['manufacturer'] = __('Aucun',__FILE__);
												}
												if(!isset($manufacturers[$info['manufacturer']])){
													$manufacturers[$info['manufacturer']] = array();
												}
												$manufacturers[$info['manufacturer']][$id] = $info;
											}
											foreach ($manufacturers as $manufacturer => $devices) {
												echo '<optgroup label="'.$manufacturer.'">';
												foreach ($devices as $id => $info) {
													echo '<option value="' . $id . '" data-img="'.camera::getImgFilePath($id).'">' . $info['manufacturer'].' - '.$info['name'] . '</option>';
												}
												echo '</optgroup>';
											}
											?>
										</select>
									</div>
								</div>
<br/>
								<div class="form-group">
									<label class="col-sm-3"></label>
									<div class="col-sm-7 text-center">
										<img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" style="max-width:160px;" id="img_device"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<div role="tabpanel" class="tab-pane" id="displaytab">
				<br/>
				<form class="form-horizontal">
					<fieldset>
					<legend><i class="fas fa-users-cog"></i> {{Configuration}}</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{Ne pas compresser ou redimensionner l'image}}</label>
						<div class="col-xs-11 col-sm-7">
							<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="doNotCompressImage" />
						</div>
					</div>
					<br/>
					<legend><i class="fas fa-photo-video"></i> {{Miniature}}</legend>
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
								<td><input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="thumbnail::refresh" /></td>
								<td><input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="thumbnail::mobilerefresh" /></td>
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
				</fieldset>
				</form>
			</div>

			<div role="tabpanel" class="tab-pane" id="capturetab">
				<br/>
			<form class="form-horizontal">
				<fieldset>
				<legend><i class="fas fa-film"></i> {{Vidéo}}</legend>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{Durée maximum d'un enregistrement (s)}}</label>
					<div class="col-xs-11 col-sm-7">
						<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="maxReccordTime" />
					</div>
				</div>
					<div class="form-group">
					<label class="col-sm-3 control-label">{{Toujours faire une video}}</label>
					<div class="col-xs-11 col-sm-7">
						<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="preferVideo" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{Nombre d'images par seconde de la vidéo}}</label>
					<div class="col-xs-11 col-sm-7">
						<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="videoFramerate" />
					</div>
				</div>
					<div class="form-group">
					<label class="col-sm-3 control-label">{{Seuil de détection mouvement (0-100)}}</label>
					<div class="col-xs-11 col-sm-7">
						<input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="moveThreshold" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{Supprimer toutes les captures de la caméra}}</label>
					<div class="col-xs-11 col-sm-7">
						<a class="btn btn-danger" id="bt_removeAllCapture"><i class="fas fa-trash"></i> {{Supprimer}}</a>
					</div>
				</div>
			</fieldset>
			</form>
		</div>

		<div role="tabpanel" class="tab-pane" id="alimtab">
		</br>
		<form class="form-horizontal">
			<fieldset>
			<div class="form-group">
				<label class="col-sm-3 control-label">{{Commande ON}}</label>
				<div class="col-xs-11 col-sm-7">
					<div class="input-group">
						<input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="commandOn"/>
						<span class="input-group-btn">
							<a class="btn btn-default listCmdActionOther roundedRight"><i class="fas fa-list-alt"></i></a>
						</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">{{Commande OFF}}</label>
				<div class="col-xs-11 col-sm-7">
					<div class="input-group">
						<input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="commandOff"/>
						<span class="input-group-btn">
							<a class="btn btn-default listCmdActionOther roundedRight"><i class="fas fa-list-alt"></i></a>
						</span>
					</div>
				</div>
			</div>
		</fieldset>
		</form>
	</div>


	<div role="tabpanel" class="tab-pane" id="commandtab">
		<a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>
				<br/><br/>
				<table id="table_cmd" class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th style="width : 70px;">{{Id}}</th>
					<th style="width : 300px;">{{Nom}}</th>
					<th style="width : 150px;">{{Type}}</th>
					<th >{{Requête}}</th>
					<th style="width : 150px;">{{Options}}</th>
					<th style="width : 150px;">{{Action}}</th>
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
