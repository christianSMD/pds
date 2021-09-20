<?php

include '../../control.php';

class myProject extends project {

    public function all($status = 'undone', $projectID = 0, $orderBy = 'order_desc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $briefIds = array();
        if($this->projects)
        {
            $project   = $this->commonAction($projectID);
            $projectID = $project->id;
        }

        $this->session->set('projectList', $this->app->getURI(true));

        $projectData = array();

        foreach($this->projects as $key => $project) {
            
            $brief     = $this->loadModel('brief')->getBriefByProjectID($key);
            $projectData[$key] = new stdClass();
            $projectData[$key]->type = $this->lang->brief->typeList[$brief->type];
            
        }
        
        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->loadLang('my');
        $this->view->title         = $this->lang->project->allProject;
        $this->view->position[]    = $this->lang->project->allProject;
        $this->view->projectStats  = $this->project->getProjectStats($status == 'byproduct' ? 'all' : $status, $productID, 0, 30, $orderBy, $pager);
        $this->view->products      = array(0 => $this->lang->product->select) + $this->loadModel('product')->getPairs();
        $this->view->productID     = $productID;
        $this->view->projectID     = $projectID;
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->status        = $status;
        $this->view->projectData   = $projectData;

        $this->display();
    }

}