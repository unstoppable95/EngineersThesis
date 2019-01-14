<?php

	session_start();
	$infoSave = $_SESSION['infoChangePasswordFirst'];
	session_unset(); //niszczy cala sesje i wszystkie zmienne zwiazane z sesja
	$_SESSION['infoChangePasswordFirst'] = $infosSave;
	header('Location: index.php');

?>