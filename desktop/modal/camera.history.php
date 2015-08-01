<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('id') == '') {
	throw new Exception(__('L\'id ne peut etre vide', __FILE__));
}
$camera = camera::byId(init('id'));
if (!is_object($camera)) {
	throw new Exception(__('L\'équipement est introuvable : ', __FILE__) . init('id'));
}
if ($camera->getEqType_name() != 'camera') {
	throw new Exception(__('Cet équipement n\'est pas de type camera : ', __FILE__) . $camera->getEqType_name());
}
$dir = calculPath(config::byKey('recordDir', 'camera')) . '/' . $camera->getId();
$files = array();
foreach (ls($dir, '*') as $file) {
	$date = explode('_', str_replace('.jpg', '', $file));
	$time = $date[1];
	$date = $date[0];
	if ($date == '') {
		continue;
	}
	if (!isset($files[$date])) {
		$files[$date] = array();
	}
	$files[$date][$time] = $file;
}
?>
<?php
foreach ($files as $date => $file) {
	echo '<legend>' . $date . '</legend>';
	echo '<div class="cameraThumbnailContainer">';
	foreach ($file as $time => $filename) {
		echo '<div class="cameraDisplayCard" style="background-color: #e7e7e7;padding:5px;">';
		echo '<center>' . $time . '</center>';
		echo '<center><img class="img-responsive cursor displayImage" src="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $filename) . '" width="150"/></center>';
		echo '<center style="margin-top:5px;"><a href="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $filename) . '" class="btn btn-success btn-xs" style="color : white"><i class="fa fa-download"></i></a>';
		echo ' <a class="btn btn-danger removeCameraFile btn-xs" style="color : white" data-filename="' . $camera->getId() . '/' . $filename . '"><i class="fa fa-trash-o"></i></a></center>';
		echo '</div>';
	}
	echo '</div>';
}
?>
<script>
    $('.cameraThumbnailContainer').packery({gutter : 5});
    $('.displayImage').on('click', function() {
        $('#md_modal2').dialog({title: "Image"});
        $('#md_modal2').load('index.php?v=d&plugin=camera&modal=camera.displayImage&src='+ $(this).attr('src')).dialog('open');
    });
    $('.removeCameraFile').on('click', function() {
        var filename = $(this).attr('data-filename');
        var card = $(this).closest('.cameraDisplayCard');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/camera/core/ajax/camera.ajax.php", // url du fichier php
            data: {
                action: "removeRecord",
                file: filename,
            },
            dataType: 'json',
            global: false,
            error: function(request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            card.remove();
            $('.cameraThumbnailContainer').packery({gutter : 5});
        }
    });
    });
</script>