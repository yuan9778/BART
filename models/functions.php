<?php
     /**
     * functions.php
     *
     *
     * Sanqing Yuan
     * https://github.com/yuan9778/BART
     *
     * Database and BART API queries related functions.
     */
    

    // BART API public key
    define('KEY', 'MW9S-E7SL-26DU-VV8V');

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)   {
        $servername = "localhost";
	$username = "root";
	$password = "19810925";
	$myDB = "bart";
        
        try {
		//connect to server and the chosen database
		$conn = new PDO("mysql:host=$servername;dbname=$myDB", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
		//echo "Connected successfully"; 
	}
	catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
   
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // prepare SQL statement
        $statement = $conn->prepare($sql);
        if ($statement === false) {
            // trigger (big, orange) error
            trigger_error($conn->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            return false;
        }
    }

    /**
     * get a specific route informations (name, color and stations along the route)
     * from cache (MySQL database) based on route number.
     */
    function query_route($route_number) {
        // get a specific route informations (name, color and stations along the route)
        // from cache (MySQL database) based on route number. Store in array $query
        $query = query("SELECT * FROM routes WHERE number = ?", $route_number);

        // iterate each row, there is actually only 1 row in $query.
        // 4 row in $query[0]: number, name, color and config
        foreach ($query[0] as $key => $value) {
            if ($key === 'config') {
                // return an array of strings, each of which is a substring of string $value
                // formed by splitting it by ',', each element in the array is a station along
                // the route
                $config = explode(',', $value);

                foreach ($config as $station_abbr) {
                    // query current station
                    $query = query("SELECT * FROM stations WHERE abbr = ?", $station_abbr);

                    // build associative array
                    $station = [];
                    foreach($query[0] as $key => $value) {
                        $station[$key] = $value;
                    }

                    $route['config'][] = $station;
                }
            }
            else {
		// $route['numbr'] =.., $route['color'] =..,$route['name'] =..,
                $route[$key] = $value;
            }
        }
		// $route is an array with 3 basic elements and 1 element('config') that itself is a big array
        return $route;
    }

    /**
     * Queries real-time estimate time departure from BART API.
     * Since it is real-time, we can't get it from cache.
     */
    function query_etd($station_abbr) {
        // load BART API etd xml
        $xml = simplexml_load_file("http://api.bart.gov/api/etd.aspx?cmd=etd&orig=$station_abbr&key=" . KEY);
        if ($xml === false) {
            return false;
        }

        $station = $xml->station;
        return $station;
    }
?>
