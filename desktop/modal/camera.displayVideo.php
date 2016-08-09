<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
echo '<video width="1280" height="720" controls>
  <source src="' . init('src') . '">
Your browser does not support the video tag.
</video>';
?>