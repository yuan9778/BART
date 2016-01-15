<?php
	
     /**
     * route.php
     *
     *
     * Sanqing Yuan
     * https://github.com/yuan9778/BART
     *
     * Outputs JSON infos about a specified route.
     */

    // configuration
    require('../models/functions.php');

    if (isset($_GET['route_number'])) {
        // set MIME type
        header('Content-type: application/json');

        $route = query_route($_GET['route_number']);

        // output JSON
        print(json_encode($route));
    }
    else {
        print('Please specify a route');
    }

?>
