<?php

	session_start();
	
	//niszczy cala sesje i wszystkie zmienne zwiazane z sesja
	session_unset();
	
	header('Location: index.php');

?>