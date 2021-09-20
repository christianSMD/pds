<?php

include '../../control.php';

class myProject extends project {
    
    public function closeall($projectID = 0) {
        
        $this->project->closeAllTasks($projectID);
        
        if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
        die(js::locate($this->createLink('project', 'view', "projectID=$projectID"), 'parent'));
        
    }
    
}