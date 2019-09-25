<?php
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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<form class="form-horizontal">
	<fieldset>
		<div class="form-group">
			<label class="col-lg-3 control-label">{{moteur RTSP}}</label>
			<div class="col-lg-4">
				<select class="configKey form-control" data-l1key="rtsp::engine" >
					<option value="avconv">avconv</option>
					<option value="ffmpeg">ffmpeg</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">{{Chemin des enregistrements}}</label>
			<div class="col-lg-4">
				<input type="text" class="configKey form-control" data-l1key="recordDir" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">{{Taille maximum du dossier d'enregistrement (Mo)}}</label>
			<div class="col-lg-4">
				<input type="text" class="configKey form-control" data-l1key="maxSizeRecordDir" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">{{Le plugin caméra doit réagir aux interactions}}</label>
			<div class="col-lg-4">
				<textarea class="configKey form-control" data-l1key="interact::sentence"></textarea>
			</div>
		</div>
	</fieldset>
</form>
<form class="form-horizontal">
	<fieldset>
		<legend>{{Panel}}</legend>
		<div class="form-group">
			<label class="col-lg-3 control-label">{{Nombre de ligne}}</label>
			<div class="col-lg-2">
				<input type="number" class="configKey form-control" data-l1key="panel::nbLine" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">{{Nombre de colonne}}</label>
			<div class="col-lg-2">
				<input type="number" class="configKey form-control" data-l1key="panel::nbColumn" />
			</div>
		</div>
	</fieldset>
</form>
