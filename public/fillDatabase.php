<?php

    /**
     * fillDatabase.php
     *
     *
     * Sanqing Yuan
     * https://github.com/yuan9778/BART
     *
     * Fills a MySQL database with routes and station informations
     * from BART API.
     */

    // configuration
    require('../models/model.php');

    // load stations xml
    $xml = simplexml_load_file('http://api.bart.gov/api/stn.aspx?cmd=stns&key=' . KEY);
    if ($xml === false)
    {
        trigger_error('Could not connect to BART API', E_USER_ERROR);
    }

    $stations = $xml->stations->station;

    // insert stations
    foreach ($stations as $station)
    {
        query("INSERT INTO stations (abbr, name, latitude, longitude) VALUES (?, ?, ?, ?)",
            $station->abbr, $station->name, $station->gtfs_latitude, $station->gtfs_longitude);
    }

    // load routes
    $xml = simplexml_load_file('http://api.bart.gov/api/route.aspx?cmd=routes&key=' . KEY);
    if ($xml === false)
    {
        trigger_error('Could not connect to BART API', E_USER_ERROR);
    }

    $routes = $xml->routes->route;

    foreach ($routes as $route)
    {

        // load routeinfo xml
        $routeinfo = simplexml_load_file('http://api.bart.gov/api/route.aspx?' .
            'cmd=routeinfo&route=' . $route->number . '&key=' . KEY);
        if ($routeinfo === false)
        {
            trigger_error('Could not connect to BART API', E_USER_ERROR);
        }

        // find route's configuration
        $stations = $routeinfo->routes->route->config->station;

        // build array with all stations on a specific route. 
        $config = [];
        foreach ($stations as $station)
        {
            $config[] = $station;
        }

        // insert route, the last value is created from array $config by separated all element
        // by comma
        query("INSERT INTO routes (number, name, color, config) VALUES (?, ?, ?, ?)",
            $route->number, $route->name, $route->color, implode(',', $config));
    }

?>
