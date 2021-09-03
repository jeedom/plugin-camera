<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (!substr_compare(init('src'), '.jpg', -strlen('.jpg'))) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (strpos(init('src'), 'camera') === false) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
echo '<center><img class="img-responsive" src="' . init('src') . '" /></center>';
