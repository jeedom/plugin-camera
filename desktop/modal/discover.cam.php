<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$discover_cameras = camera::discoverCam();
?>

<div id='div_cameraDicovery' style="display: none;"></div>

<table class="table table-bordered table-condensed">
	<thead>
		<tr>
			<th>{{IP}}</th>
			<th>{{Découverte}}</th>
			<th>{{Type}}</th>
			<th>{{Nom d'utilisateur}}</th>
			<th>{{Mot de passe}}</th>
			<th>{{Action}}</th>
		</tr>
	</thead>
	<tbody>
		<?php
foreach ($discover_cameras as $cam) {
	echo '<tr>';
	echo '<td>';
	echo '<span class="camDiscoverAttr" data-l1key="ip">' . $cam['ip'] . '</span>';
	echo '</td>';
	echo '<td>';
	echo '<span class="camDiscoverAttr" data-l1key="discover">' . $cam['discover'] . '</span>';
	echo '</td>';
	echo '<td>';
	echo $cam['type'];
	echo '</td>';
	echo '<td>';
	if ($cam['exist'] === false) {
		echo '<input class="camDiscoverAttr" data-l1key="username" />';
	} else {
		echo 'NA';
	}
	echo '</td>';
	echo '<td>';
	if ($cam['exist'] === false) {
		echo '<input type="password" class="camDiscoverAttr" data-l1key="password" />';
	} else {
		echo 'NA';
	}
	echo '</td>';
	echo '<td>';
	if ($cam['exist'] === false) {
		echo '<a class="btn btn-success btn-sm bt_addDiscoverCam"><i class="fa fa-plus"> {{Ajouter}}</a>';
	}
	echo '</td>';
	echo '</tr>';
}
?>
	</tbody>
</table>

<script>
	$('.bt_addDiscoverCam').on('click',function(){
		var tr = $(this).closest('tr');
		$.ajax({
			type: "POST",
			url: "plugins/camera/core/ajax/camera.ajax.php",
			data: {
				action: "addDiscoverCam",
				config: json_encode(tr.getValues('.camDiscoverAttr')[0]),
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error,$('#div_cameraDicovery'));
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_cameraDicovery').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#md_modal').load('index.php?v=d&modal=discover.cam&plugin=camera',function(){
					$('#div_cameraDicovery').showAlert({message: '{{Création réussie}}', level: 'success'});
				}).dialog('open');
			}
		});
	});
</script>
