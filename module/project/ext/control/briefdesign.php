<?php

include '../../control.php';

class myProject extends project {
    
    public function briefdesign($projectID) {
        
        $project = $this->commonAction($projectID);
        $projectID = $project->id;
        
        if(!empty($_POST)) {
            
            // Make Traffic a team member
            $this->addTrafficToTeam($projectID);
            $changes = null;
            // Log Action
            $this->loadModel('action');
            $actionID = $this->action->create('project', $projectID, 'Briefed ', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
            
        }
        
        $this->display();
        
    }
    
    private function addTrafficToTeam($projectID) {
        // Load in Brief config file to get Traffic group ID
        $this->app->loadConfig('brief');
        $groupID = TRAFFIC_ID;
        // Get all team members for the TRAFFIC group
        $members = $this->dao->select('t2.account AS account')
                             ->from(TABLE_USERGROUP)->alias('t1')
                             ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
                             ->where('`group`')->eq($groupID)
                            ->fetchAll();
        
        $added_members = array();
        // Loop through TRAFFIC TEAM and add them to the database TEAM table as
        // line items
        foreach($members as $m) {
            
            $isMember = $this->dao->select('id')
                                  ->from(TABLE_TEAM)
                                  ->where('root')->eq($projectID)
                                  ->andWhere('account')->eq($m->account)
                                  ->andWhere('type')->eq('project')
                                  ->limit(1)
                                  ->fetchAll();
                        
            if(count($isMember) < 1) {
                // Add member
                $member = new stdclass();
                $member->root    = $projectID;
                $member->account = $m->account;
                $member->role    = ''; // $this->lang->user->roleList[$this->app->user->role];
                $member->join    = date('Y-m-d');
                $member->type    = 'project';
                $member->days    = $this->config->brief->defaultWorkDays;
                $member->hours   = $this->config->brief->defaultWorkhours;
               // $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                
                $added_members[] = $m->account;
                
            }
            
        }
        
    }
    
}

