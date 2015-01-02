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
$dir = calculPath(config::byKey('recordDir', 'camera'));
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
?>
<table id="table_cameraRecord" class="table table-bordered table-condensed tablesorter">
    <thead>
        <tr>
            <th>{{Fichier}}</th>
            <th>{{Taille}}</th>
            <th>{{Action}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (ls($dir, $camera->getId() . '_*') as $file) {
            echo '<tr>';
            echo '<td class="filename">' . $file . '</td>';
            echo '<td>' . sizeFormat(filesize($dir . '/' . $file)) . '</td>';
            echo '<td>';
            echo '<a href="core/php/downloadFile.php?pathfile=' . urlencode($dir . '/' . $file) . '" class="btn btn-success btn-xs pull-right" style="color : white"><i class="fa fa-download"></i> {{Télécharger}}</a>';
            echo '<a class="btn btn-danger removeCameraFile btn-xs pull-right" style="color : white"><i class="fa fa-trash-o"></i> Supprimer</a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>


<script>
    initTableSorter();

    $('#table_cameraRecord .removeCameraFile').on('click', function() {
        var tr = $(this).closest('tr');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/camera/core/ajax/camera.ajax.php", // url du fichier php
            data: {
                action: "removeRecord",
                file: tr.find('.filename').text(),
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
                tr.remove();
            }
        });
    });
</script>