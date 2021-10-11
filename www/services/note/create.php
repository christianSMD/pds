<?php 

include_once '../config/config.php';
include_once '../model/note.php';

// Get current date and timezone
date_default_timezone_set("Africa/Johannesburg");

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$text = $_GET['text'];
$tid = $_GET['tid'];

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog post object
$note = new Note($db);

$note->tid = $tid;
$note->text = $text;
$note->created = date("y/m/d H:i");

($note->create()) ? $msg = 'Request successfully submitted' : $msg = 'Record updated';

// Turn to JSON & output
echo json_encode(['results' => [
    'note'      => $text,
    'tid'        => $tid,
    'message'   => $msg
]]);