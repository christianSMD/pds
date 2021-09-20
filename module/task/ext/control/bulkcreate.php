<?php

include '../../control.php';

class myTask extends task {
    
    public function __construct() {
        
        parent::__construct();
        
        define('TABLE_COMMON_TASKS', '`c_' . $this->config->db->prefix . 'common_tasks`');
    }
    
    public function bulkcreate($projectID = 0) {
        
        if($_POST) {
            
            $projectID = $this->task->bulkCreateTasks($projectID);
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            $this->loadModel('action')->create('project', $taskID, 'tasks created');
            
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "projectID=$projectID")));
            
        }
        
        $this->view->title      = $this->lang->task->bulkCreate;
        $this->view->taskList   = $this->task->getCommonTasks();
        $this->view->projectID  = $projectID;
                
        $this->display();
        
    }
    
}


    
