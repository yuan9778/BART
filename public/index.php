<?php
	
	
    // load functions
    require('../models/functions.php');

	// return an array that contains route number and corresponding route name
	// this is used for drop-down menu that displays route number and name.
    $routes = query("SELECT number, name FROM routes");

    require('../views/index.php');

?>
