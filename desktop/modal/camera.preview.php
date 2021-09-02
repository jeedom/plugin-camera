<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$eqLogic = eqLogic::byId(init('id'));
if (!is_object($eqLogic)) {
    throw new Exception(__('Equipement introuvable : ', __FILE__) . init('id'));
}
if ($eqLogic->getEqType_name() != 'camera') {
    throw new Exception(__('L\'équipement n\'est pas une camera', __FILE__));
}
echo $eqLogic->toHtml('dashboard', true);
