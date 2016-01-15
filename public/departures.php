<?php
	/**
     * departures.php
     *
     *
     * Sanqing Yuan
     * https://github.com/yuan9778/BART
     *
	 * Outputs JSON infos about the next departures of a specified station
     */


    // configuration
    require('../models/functions.php');

    if (isset($_GET['station_abbr']))
    {
        $station = query_etd($_GET['station_abbr']);
        if ($station === false)
        {
            trigger_error('Could not connect to BART API', E_USER_ERROR);
        }

        // build associative array from SimpleXML object
        $departures = [];
        foreach ($station->etd as $etd)
        {
            $departure = [];
            foreach ($etd->estimate as $estimate)
            {
                $departure['destination'] = (string) $etd->destination;
                $departure['minutes'] = (string) $estimate->minutes;

                $departures[] = $departure;
            }
        }

        // sort array by each departure's minutes
        $minutes = [];
        foreach ($departures as $key => $row)
        {
            $minutes[$key] = $row['minutes'];
        }
        array_multisort($minutes, SORT_NUMERIC, $departures);

        // render departures html
        extract(['departures' => $departures, 'station' => $station]);
        require('../views/departures.php');
    }
    else
    {
        print('Could not query departures');
    }

?>
