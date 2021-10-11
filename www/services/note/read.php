<?php 
  include_once '../config/config.php';
  include_once '../model/note.php';

  $tid = $_GET['tid'];

  $database = new Database();
  $db = $database->connect();

  $note = new Note($db);
  $note->tid = $tid;
  $data = $note->read_single();

  $num = $data->rowCount();

  $arr = array();

  if($num > 0) {

    while($row = $data->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $results = array(
        'note_id' => $note_id,
        'tid' => $tid,
        'text' => $text,
        'created' => $created
      );
      
      // Push to "data"
      array_push($arr, $results);
    }

    $msg = 'success';

  } else {
    $results = array(
        'note_id' => '',
        'tid' => '',
        'text' => '',
        'created' => ''
      );
    array_push($arr, $results);
    $msg = '0';
  }

   // Turn to JSON & output
   echo json_encode(['results' => [
    'rows' => $num,
    'list'      =>  $arr,
    'message'   =>  $msg
  ]]);