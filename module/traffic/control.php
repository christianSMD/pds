<?php

class traffic extends control
{
    public $projects;
    /**
     * Construct function, load model of task, bug, my.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
        
        $this->loadModel('traffic');
        
    }
    
    public function index ($status = 'undone', $projectID = 0, $orderBy = 'id_desc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1) {
        
        /*$this->view->title      = $this->lang->traffic->index;
        
        /* Load pager.
        $this->app->loadClass('pager', $static = true);
        $this->app->loadConfig('brief', '', false);
        $this->app->loadLang('brief');
       // if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort.
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Assign.
        $this->view->position[] = $this->lang->my->task;
        $this->view->tabID      = 'task';
        $this->view->tasks      = $this->traffic->getTasks($type, 0, $pager, $sort);
        $this->view->type       = $type;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->view->orderBy    = $orderBy;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager      = $pager;
        $this->display();*/
        
        $this->app->loadClass('pager', $static = true);
        $this->app->loadConfig('brief', '', false);
        $this->app->loadLang('brief');
        
        if($this->projects)
        {
            $project   = $this->commonAction($projectID);
            $projectID = $project->id;
        }
        $this->session->set('projectList', $this->app->getURI(true));

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->app->loadLang('my');
        $this->view->title         = $this->lang->traffic->index;
        $this->view->position[]    = $this->lang->project->allProject;
        $this->view->projectStats  = $this->traffic->getProjectStats($status == 'byproduct' ? 'all' : $status, $productID, 0, 30, $orderBy, $pager);
        
        
        $this->view->products      = array(0 => $this->lang->product->select) + $this->loadModel('product')->getPairs();
        $this->view->productID     = $productID;
        $this->view->projectID     = $projectID;
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->status        = $status;
        
        foreach($this->view->projectStats as $project) {
            
            $project->tasks = $this->traffic->getTasks($project->id);
        }
        
        $this->display();
        
    }
    
}