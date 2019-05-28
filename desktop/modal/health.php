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

if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}
$plugin = plugin::byId('camera');
$eqLogics = eqLogic::byType($plugin->getId());
?>

<table class="table table-condensed tablesorter" id="table_healthcamera">
	<thead>
		<tr>
			<th>{{Image}}</th>
			<th>{{Module}}</th>
			<th>{{ID}}</th>
			<th>{{Statut}}</th>
			<th>{{Ip}}</th>
			<th>{{Protocole}}</th>
			<th>{{Modèle}}</th>
			<th>{{Vidéo}}</th>
			<th>{{Framerate}}</th>
			<th>{{Rafraichissement}}</th>
			<th>{{Zoom}}</th>
			<th>{{Max Enregistrement}}</th>
			<th>{{Date création}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	if ($eqLogic->getConfiguration('device') != ""){
		if (file_exists(dirname(__FILE__) . '/../../core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg')) {
			$img = '<img class="lazy" src="plugins/camera/core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg" height="65" width="55" style="' . $opacity . '"/>';
		} else {
			$img = '<img class="lazy" src="' . $plugin->getPathImgIcon() . '" height="65" width="55" style="' . $opacity . '"/>';
		}
	}else {
		$img = '<img class="lazy" src="' . $plugin->getPathImgIcon() . '" height="65" width="55" style="' . $opacity . '"/>';
	}
	
	echo '<tr><td>' . $img . '</td><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getId() . '</span></td>';
	$status = '<span class="label label-success" style="font-size : 1em; cursor : default;">{{OK}}</span>';
	if ($eqLogic->getStatus('state') == 'nok') {
		$status = '<span class="label label-danger" style="font-size : 1em; cursor : default;">{{NOK}}</span>';
	}
	echo '<td>' . $status . '</td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('ip') . '</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . ucfirst($eqLogic->getConfiguration('protocole')) . '</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . ucfirst($eqLogic->getConfiguration('device')) . '</span></td>';
	if ($eqLogic->getConfiguration('preferVideo') == 1) {
		$video = '<span class="label label-success" style="font-size : 1em; cursor : default;">{{OUI}}</span>';
	} else {
		$video = '<span class="label label-warning" style="font-size : 1em; cursor : default;">{{NON}}</span>';
	}
	echo '<td>' . $video . '</td>';
	$framerate = $eqLogic->getConfiguration('videoFramerate', 1);
	if ($framerate == 1) {
		echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $framerate . '/s</span></td>';
	} else if ($framerate <= 5) {
		echo '<td><span class="label label-success" style="font-size : 1em; cursor : default;">' . $framerate . '/s</span></td>';
	} else if ($framerate <= 10) {
		echo '<td><span class="label label-warning" style="font-size : 1em; cursor : default;">' . $framerate . '/s</span></td>';
	} else {
		echo '<td><span class="label label-danger" style="font-size : 1em; cursor : default;">' . $framerate . '/s</span></td>';
	}

	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('refreshDelaySlow') . '/s</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('refreshDelayFast') . '/s</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('maxReccordTime') . 's</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
}
?>
	</tbody>
</table>
