<?php

function getCommonTasks() {
    
    $result = $this->dao->select('*')
                        ->from(TABLE_COMMON_TASKS)
                        ->where('visible')->eq(1)
                        ->orderBy('dept, created')
                        ->fetchAll();
        
    return $result;
    
}

function bulkCreateTasks($projectID) {
        
    $tasks = $_POST['tasks'];
    $postObj = array(); // Empty object to be sent via cURL
    // Special object arrays for the POST object
    $module = array();
    $parent = array();
    $name = array();
    $color = array();
    $type = array();
    $estimate = array();
    $estStarted = array();
    $deadline = array();
    $desc = array();
    $pri = array();    
    
    $i = 0;
    foreach($tasks as $key => $task) {
        $theTask = $this->getCommonTaskById($key);
        $module[$i] = 0;
        $parent[$i] = 0;
        $name[$i] = $theTask->taskName;
        $color[$i] = '';
        $type[$i] = $theTask->dept;
        $estimate[$i] = $theTask->estHours;
        $estStarted[$i] = '';
        $deadline[$i] = '';
        $desc[$i] = '';
        $pri[$i] = 3;
        $postObj["storyEstimate{$i}"] = '';
        $postObj["storyDesc{$i}"] = '';
        $postObj["storyPri{$i}"] = '';
        
        $i++;
    }
    
    $postObj['module'] = $module;
    $postObj['parent'] = $parent;
    $postObj['name'] = $name;
    $postObj['color'] = $color;
    $postObj['type'] = $type;
    $postObj['estimate'] = $estimate;
    $postObj['estStarted'] = $estStarted;
    $postObj['deadline'] = $deadline;
    $postObj['desc'] = $desc;
    $postObj['pri'] = $pri;
    
    $this->doPost($postObj, $projectID);
    
    return true;
    
}

function doPost($fields, $projectID) {
    
    $url = "/task-batchCreate-$projectID--0.html";
    $fields_string = str_replace('%5D', ']', str_replace('%5B', '[', http_build_query($fields)));
    /*
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, '&');*/
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    //execute post
    $result = curl_exec($ch);
    print_r($fields_string);
    var_dump($result);
    exit;

    //close connection
    curl_close($ch);
    
}

function getCommonTaskById($id) {
    
    $task = $this->dao->select('*')->from(TABLE_COMMON_TASKS)
            ->where('id')->eq($id)
            ->fetch();
    
    return $task;
    
}