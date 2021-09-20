<?php

include '../../control.php';

class myProject extends project {
    
    public function done($projectID = 0) {
        
        if($_POST) {
            
            unset($_POST['confirmDone']);
            
            $this->project->closeAllTasks($projectID);
            
            // Archive Brief
            $brief = $this->loadModel('brief')->getBriefByProjectID($projectID);            
            $this->loadModel('brief')->archive($brief->id);
            
            // Close Project
            $this->loadModel('action');
            $changes = $this->project->close($projectID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('project', $projectID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }          
        
            //if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            //die(js::locate($this->createLink('project', 'index', ""), 'parent.parent'));
            die(js::reload('parent.parent'));
            
        } else {
        
        
        
            $project = $this->commonAction($projectID);

            $this->view->project = $project;

            $this->display();
        }
    }
    
}