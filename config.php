<?php
    // Row per page
    $rowperpage = 10;

	// Opening a SQLite3 database using a object-oriented (PDO) approach
	$db = new PDO('sqlite:nespresso.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
