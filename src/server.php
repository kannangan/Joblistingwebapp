<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');


$jobs = array(
    array("id"=>"1", "company"=>"Photosnap", "position"=>"Senior frontend developer", "location"=> "USA Only", "department"=>"A", "contract"=>"full_time"),
    array("id"=>"2", "company"=>"Manage", "position"=>"Fullstack developer", "location"=> "place 2", "department"=>"B", "contract"=>"part_time"),
);

$url = parse_url($_SERVER['REQUEST_URI']);
$requestMethod = $_SERVER['REQUEST_METHOD'];

#echo $requestMethod;
#print_r($urlArr);

if ($requestMethod === "GET" && $url["path"] === "/jobs") {
    if (!isset($url["query"])) {
        echo(json_encode($jobs));
        return;
    }
    // this filtering is easy when we use a database with a WHERE query
    $filters = array();
    $results = array();

    parse_str($url["query"], $filters);

    // filter by department
    if (isset($filters["department"])) {
        $results = array_filter($jobs, function($arr) use ($filters) {
            return isset($arr["department"]) && $arr["department"] === $filters["department"];
        });
    }

    // filter by location
    if (isset($filters["location"])) {
        $results = array_filter($results, function($arr) use ($filters) {
            return isset($arr["location"]) && $arr["location"] === $filters["location"];
        });
    }

    // filter by type
    if (isset($filters["type"])) {
        $results = array_filter($results, function($arr) use ($filters) {
            return isset($arr["type"]) && $arr["type"] === $filters["type"];
        });
    }

    echo(json_encode($results));
    return;
}


http_response_code(404);
echo json_encode("Not found");
