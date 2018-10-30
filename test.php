<?php
define("REST_URI", 'http://192.168.56.103');

function buildRequestUrl($resource, array $getVars)
{
    $itemHandler = null;
    $getBody = null;

    if (!empty($getVars)) {
        foreach ($getVars as $name => $value) {
            if ($name === 'id') {
                $itemHandler = urlencode($value);
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $valuePart) {
                    $getBody .= $name . '[]=' . urlencode($valuePart) . '&';
                }
            } else {
                $getBody .= $name . '=' . urlencode($value) . '&';
            }
        }
        $getBody = substr($getBody, 0, -1);
    }

    // Build url base
    $requestUrl = REST_URI . '/' . $resource;

    // Add item handler if we have it
    if ($itemHandler !== null) {
        $requestUrl .= '/' . $itemHandler . '/';
    }

    $requestUrl .= '?';

    if ($getBody !== null) {
        $requestUrl .= '&' . $getBody;
    }

    return $requestUrl;
}

function buildPostBody(array $bodyVars)
{
    $postBody = array();

    if (isset($bodyVars['filter'])) {
        $postBody['filter'] = $bodyVars['filter'];
        unset($bodyVars['filter']);
    }

    $postBody['content'] = $bodyVars;
    return $postBody;
}



function runQuery($resource, $method, array $getVars = array(), array $bodyVars = array()) {

    $url = buildRequestUrl($resource, $getVars);

    $opts = array(
        'http' => array(
            'method' => strtoupper($method),
            'header' => array(
                'Content-type: application/x-www-form-urlencoded',
                'X-Forwarded-For: ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')
            ),
            'content' => http_build_query(buildPostBody($bodyVars)),
            'timeout' => 60
        )
    );

    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    print_r($response);
}


function getUser() {

    $filter = [
        'name' => 'name1'
    ];
    $postVars = [
        //'filter' => json_encode($filter)
    ];
    runQuery('user', 'GET', [], $postVars);
}

function addUser() {

    $number = rand(1, 100);

    $data = [
        [
            'uid' => 'uid'. $number,
            'name' => 'name' . $number,
        ]
    ];
    runQuery('user', 'POST', [], $data);
}

function updateUser() {
    $number = rand(1, 100);
    $data = [
        [
            'id' => 23,
            'uid' => 'new_uid' . $number,
            'name' => 'new_name' . $number,
        ]

    ];
    runQuery('user', 'PUT', [], $data);
}

function deleteUser() {
    $data = [
        [
            'id' => 24,
        ]

    ];
    runQuery('user', 'DELETE', [], $data);
}


function getGift() {
    runQuery('gift', 'GET');
}

function sendGift() {

    $number = rand(1, 100);

    $data = [
        [
            'object' => 'gift'. $number,
            'giver' => 2,
            'recipient' => 1,
        ]
    ];
    runQuery('gift', 'POST', [], $data);
}

function takeGift() {
    $number = rand(1, 100);
    $data = [
        [
            'id' => 13,
            'recipient' => 1,
        ]

    ];
    runQuery('gift', 'PUT', [], $data);
}


///////////////////


function checkArgument($argument)
{
    global $argv;

    return in_array($argument, $argv);
}

function usage()
{
    global $argv, $allArgs;
    echo "Usage: " . $argv[0] . " {command} {param 1} ... {param n}" . PHP_EOL;
    echo "Possible parameters: " . implode(", ", $allArgs) . PHP_EOL;
}


$allArgs =
    [
        'get_user',//get list or one user
        'add_user',// add user
        'update_user',// update user params
        'delete_user',// delete user

        'get_gift',// get list or one gift
        'send_gift',// send gift to someone
        'take_gift',// take gift
    ];

global $argv;

print_r($argv);

$test = array_intersect($argv, $allArgs);
if (empty($test))
{
    usage();
    exit;
}

try
{
    if (checkArgument('get_user'))
    {
        getUser();
    }
    if(checkArgument('add_user'))
    {
        addUser();
    }
    if(checkArgument('update_user'))
    {
        updateUser();
        //unlinkObject($argv[2]);
    }
    if(checkArgument('delete_user'))
    {
        deleteUser();
    }
    if(checkArgument('get_gift'))
    {
        getGift();
    }
    if(checkArgument('send_gift'))
    {
        sendGift();
    }
    if(checkArgument('take_gift'))
    {
        takeGift();
    }
}
catch (Exception $ex)
{
    print_r($ex);
}
