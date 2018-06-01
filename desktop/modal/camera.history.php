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
	if ($file == 'movie_temp/' || strpos($file, '.mkv')) {
		continue;
	}
	$date = explode('_', str_replace('.mp4', '', str_replace('.jpg', '', $file)));
	$time = $date[count($date) - 1];
	$date = $date[count($date) - 2];
	if ($date == '') {
		continue;
	}
	if (!isset($files[$date])) {
		$files[$date] = array();
	}
	$files[$date][$time] = $file;
}
krsort($files);
?>
<div id='div_cameraRecordAlert' style="display: none;"></div>
<?php
echo '<a class="btn btn-danger bt_removeCameraFile pull-right" data-all="1" data-filename="' . $camera->getId() . '/*"><i class="fa fa-trash-o"></i> {{Tout supprimer}}</a>';
echo '<a class="btn btn-success  pull-right" target="_blank" href="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/*') . '" ><i class="fa fa-download"></i> {{Tout télécharger}}</a>';
?>
<?php
$i = 0;
foreach ($files as $date => &$file) {
	$cameraName = str_replace(' ', '-', $camera->getName());
	echo '<div class="div_dayContainer">';
	echo '<legend>';
	echo '<a class="btn btn-xs btn-danger bt_removeCameraFile" data-day="1" data-filename="' . $camera->getId() . '/' . $cameraName . '_' . $date . '*"><i class="fa fa-trash-o"></i> {{Supprimer}}</a> ';
	echo '<a class="btn btn-xs btn-success" target="_blank"  href="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $cameraName . '_' . $date . '*') . '" ><i class="fa fa-download"></i> {{Télécharger}}</a> ';
	echo $date;
	echo ' <a class="btn btn-xs btn-default toggleList"><i class="fa fa-chevron-down"></i></a> ';
	echo '</legend>';
	echo '<div class="cameraThumbnailContainer">';
	krsort($file);
	foreach ($file as $time => $filename) {
		$fontType = 'fa-camera';
		if (strpos($filename, '.mp4')) {
			$fontType = 'fa-video-camera';
			$i++;
		}
		echo '<div class="cameraDisplayCard" style="background-color: #e7e7e7;padding:5px;height:167px;">';
		echo '<center><i class="fa ' . $fontType . ' pull-right"></i>  ' . str_replace('-', ':', $time) . '</center>';
		if (strpos($filename, '.mp4')) {
			echo '<video class="displayVideo" width="150" height="100" controls loop data-src="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $filename) . '" style="cursor:pointer"><source src="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $filename) . '">Your browser does not support the video tag.</video>';
		} else {
			echo '<center><img class="img-responsive cursor displayImage lazy" src="plugins/camera/core/img/no-image.png" data-original="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $filename) . '" width="150"/></center>';
		}
		echo '<center style="margin-top:5px;"><a target="_blank" href="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $filename) . '" class="btn btn-success btn-xs" style="color : white"><i class="fa fa-download"></i></a>';
		echo ' <a class="btn btn-danger bt_removeCameraFile btn-xs" style="color : white" data-filename="' . $camera->getId() . '/' . $filename . '"><i class="fa fa-trash-o"></i></a></center>';
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
}
?>
<script>
	$('.cameraThumbnailContainer').packery({gutter : 5});
	$('.displayImage').on('click', function() {
		$('#md_modal2').dialog({title: "Image"});
		$('#md_modal2').load('index.php?v=d&plugin=camera&modal=camera.displayImage&src='+ $(this).attr('src')).dialog('open');
	});
	$('.displayVideo').on('click', function() {
		$('#md_modal2').dialog({title: "Vidéo"});
		$('#md_modal2').load('index.php?v=d&plugin=camera&modal=camera.displayVideo&src='+ $(this).attr('data-src')).dialog('open');
	});
	$('.bt_removeCameraFile').on('click', function() {
		var filename = $(this).attr('data-filename');
		var card = $(this).closest('.cameraDisplayCard');
		if($(this).attr('data-day') == 1){
			card = $(this).closest('.div_dayContainer');
		}
		if($(this).attr('data-all') == 1){
			card = $('.div_dayContainer');
		}
		$.ajax({
			type: "POST",
			url: "plugins/camera/core/ajax/camera.ajax.php",
			data: {
				action: "removeRecord",
				file: filename,
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error,$('#div_cameraRecordAlert'));
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_cameraRecordAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				card.remove();
				$(".cameraThumbnailContainer").slideToggle(1);
				$('.cameraThumbnailContainer').packery({gutter : 5});
				$(".cameraThumbnailContainer").slideToggle(1);
			}
		});
	});

	$(".cameraThumbnailContainer").slideToggle(1);
	$(".cameraThumbnailContainer").eq(0).slideToggle(1);
	$('.toggleList').on('click', function() {
		$(this).closest('.div_dayContainer').find(".cameraThumbnailContainer").slideToggle("slow");
	});

	$("img.lazy").lazyload({
		container: $("#md_modal")
	});
</script>