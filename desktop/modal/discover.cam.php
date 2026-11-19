<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div id='div_cameraDicovery' style="display: none;"></div>

<table class="table table-bordered table-condensed" id="div_cameraDiscover">
	<thead>
		<tr>
			<th>{{Protocole}}</th>
			<th>{{IP}}</th>
			<th>{{Port}}</th>
			<th>{{Découverte}}</th>
			<th>{{Type}}</th>
			<th>{{Nom d'utilisateur}}</th>
			<th>{{Mot de passe}}</th>
			<th>{{Action}}</th>
		</tr>
	</thead>
	<tbody>
		<?php
foreach (camera::discoverCam() as $cam) {
	echo '<tr>';
	echo '<td>';
	echo '<span class="camDiscoverAttr" data-l1key="protocole">' . $cam['protocole'] . '</span>';
	echo '</td>';
	echo '<td>';
	echo '<span class="camDiscoverAttr" data-l1key="ip">' . $cam['ip'] . '</span>';
	echo '</td>';
	echo '<td>';
	echo '<span class="camDiscoverAttr" data-l1key="port">' . $cam['port'] . '</span>';
	echo '</td>';
	echo '<td>';
	echo '<span class="camDiscoverAttr" data-l1key="discover">' . $cam['discover'] . '</span>';
	echo '</td>';
	echo '<td>';
	echo $cam['type'];
	echo '</td>';
	echo '<td><input class="camDiscoverAttr" data-l1key="username" /></td>';
	echo '<td><input type="password" autocomplete="new-password" class="camDiscoverAttr" data-l1key="password" /></td>';
	if ($cam['exist'] === false) {
		echo '<td><a class="btn btn-success btn-sm bt_addDiscoverCam"><i class="fas fa-plus"></i> {{Ajouter}}</a></td>';
	} else {
		echo '<td><a class="btn btn-warning btn-sm bt_addDiscoverCam" title="{{Caméra éxistante}}"><i class="fas fa-plus"></i> {{Ajouter}}</a></td>';
	}
	echo '</tr>';
}
?>
	</tbody>
</table>

<script>
document.getElementById('div_cameraDiscover').addEventListener('click', function(event) {
  let _target = null
  if (_target = event.target.closest('.bt_addDiscoverCam')) {
    if (_tr = _target.closest('tr')) {
      domUtils.ajax({
        type: 'POST',
        url: 'plugins/camera/core/ajax/camera.ajax.php',
        data: {
          action: 'addDiscoverCam',
          config: json_encode(_tr.getJeeValues('.camDiscoverAttr')[0])
        },
        dataType: 'json',
        error: function(request, status, error) {
          domUtils.handleAjaxError(request, status, error)
        },
        success: function (data) {
          if (data.state != 'ok') {
            jeedomUtils.showAlert({
              title: "Camera",
              message: data.result,
              level: 'danger'
            });
            return
          }
          window.location.href='index.php?v=d&m=camera&p=camera&id=' + data.result + '&saveSuccessFull=1'
        }
      });
    }
  }
})
</script>