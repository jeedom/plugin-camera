<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
if (substr_compare(init('src'), '.mp4', -strlen('.mp4')) !== 0) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
if (strpos(init('src'), 'camera') === false) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
echo '<video width="1180" height="664" controls autoplay loop>
  <source src="' . init('src') . '">
Your browser does not support the video tag.
</video>';
