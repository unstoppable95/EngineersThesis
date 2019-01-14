<?php

	session_start();
	$infoSave = $_SESSION['infoChangePasswordFirst'];
	session_unset(); //niszczy cala sesje i wszystkie zmienne zwiazane z sesja
	$_SESSION['infoChangePasswordFirst'] = $infoSave;
	header('Location: index.php');

?>