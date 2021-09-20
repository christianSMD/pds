<?php
/**
 * TODO:
 * Permissions
 * Notifications + emails when status changes
 */

class brief extends control {
    
    public $brief = array();
    
    public function __construct() {
        
        parent::__construct();
        
        $this->brief->install();
        
        if(dao::isError()) die(js::error(dao::getError()));
        
    }
    
    /**
     * Index
     * 
     * Brief home view, shows all active briefs and their current statuses
     */
    public function index($status = 'all', $briefID = 0, $orderBy = 'id_desc', $productID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1, $search = null) {

        // $this->brief->assignTeam(8);
        
        if($this->briefs)
        {
            $brief   = $this->commonAction($briefID);
            $briefID = $brief->id;
        }
        
        if($_POST) {
            $searchData = fixer::input('post')
                    ->remove('submitSearch')
                    ->get();
            
            $search = $searchData->briefSearch;
            
        }
        
        $this->session->set('briefList', $this->app->getURI(true));
        
        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        
        $this->view->title         = $this->lang->brief->allBrief;
        $this->view->position[]    = $this->lang->brief->allBrief;
        $this->view->briefID       = $briefID;
        $this->view->briefs        = $this->brief->select($pager, $orderBy, $search);
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->status        = $status;
        $this->view->search        = $search;
        
        $this->display();
        
    }
    
    /**
     * Create
     * 
     * Creates a new brief in the database
     */
    public function create() {
        // TODO:
        // Required fields
        // Deadline date is populated dynamically
        // Get all smdProducts to populate product dropdown
        // Get all smdBrands to populate brand dropdown
        if(!empty($_POST))
        {
            $briefID = $this->brief->updateBrief();
            
            if(dao::isError()) die(js::error(dao::getError()));
                        
            $this->loadModel('action')->create('brief', $briefID, 'created');
            
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "briefID=$briefID")));
            
        } 
        
        $this->view->title      = $this->lang->brief->create;        
        $this->view->poUsers    = $this->loadModel('user')->getPairs('nodeleted|pofirst|noclosed');
        $this->view->poProducts = array();
        if(is_file('../smdproduct/control.php')) {
            
            $this->view->poProducts = $this->loadModel('smdproduct')->getProductsList();
            
        }
        
        $this->view->poBrands       = $this->loadModel('smdproduct')->formatForSelect($this->loadModel('smdproduct')->getBrands());
        
        $this->display();
        
    }
    
    /**
     * View 
     * 
     * View an existing brief
     * @param int $briefID
     */
    public function view($briefID = '') {
        
        $brief = $this->brief->getById($briefID, true);
        $this->commonAction($briefID);
                
        $this->view->title          = $this->lang->brief->view;        
        $this->view->brief          = $brief;
        $this->view->actions        = $this->loadModel('action')->getList('brief', $briefID);
        $this->view->products       = $brief->products;
        
        $this->display();
        
    }
    
    /**
     * Edit
     * 
     * Edit an existing brief's information. To be used by traffic and/or by
     * brief creator when reverted.
     * @param int $briefID
     */
    public function edit($briefID = '') {
        
        if(!empty($_POST))
        {
            $oldBrief   = $this->brief->getById($briefID);
            $now        = helper::now();            
            $briefID = $this->brief->updateBrief($briefID);
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            $actionID = $this->loadModel('action')->create('brief', $briefID, 'edited');
            $url = $this->createLink('brief', 'view', "briefID=$briefID");     
            // Get new brief object and save changes
            $brief = $this->brief->getById($briefID, true);
            $changes = common::createChanges($oldBrief, $brief);
            $this->action->logHistory($actionID, $changes);
                        
            die(js::locate($url, 'parent'));
        } 
        
        $brief = $this->brief->getById($briefID, true);
        
        $this->view->title          = $this->lang->brief->edit;        
        $this->view->brief          = $brief;
        $this->view->products       = $brief->products;
        $this->view->poUsers        = $this->loadModel('user')->getPairs('nodeleted|pofirst|noclosed');
        $this->view->poProducts     = $this->loadModel('smdproduct')->getProductsList();
        
        $this->view->poBrands       = $this->loadModel('smdproduct')->formatForSelect($this->loadModel('smdproduct')->getBrands());
                
        $this->display();
        
    }
    
    /**
     * Common Action
     * 
     * Gets a brief object by ID and sets default View variables 
     * @param int $briefID
     * @param string $extra
     * @return object
     */
    public function commonAction($briefID = 0, $extra = '') {
        
        $this->loadModel('brief');
        
        $brief          = $this->brief->getById($briefID);
        $teamMembers    = $this->brief->getTeamMembers($briefID);
        
        /* Set menu. */
        $this->brief->setMenu($this->briefs, $briefID, $buildID = 0, $extra);
        
        $this->view->brief = $brief;
        $this->view->teamMembers = $teamMembers;
        
        return $brief;
        
    }
    
    /**
     * Archive
     * 
     * Archives the brief from the main view so it will no longer display in the
     * general overview.
     * @param int $briefID
     */
    public function archive($briefID) {
        
        $brief   = $this->commonAction($briefID);
        $briefID = $brief->id;
        
        if(!empty($_POST)) {
            $this->loadModel('action');
            $changes = $this->brief->archive($briefID);
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('brief', $briefID, 'Archived', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            die(js::reload('parent.parent'));
        }
        
        $this->view->title      = $this->lang->brief->archive;
        $this->view->actions    = $this->loadModel('action')->getList('brief', $briefID);
                
        $this->display();
    }
    
    /**
     * Accept
     * 
     * Sets a brief as accepted and logs an action in the audit trail.
     * @param int $briefID
     */
    public function accept($briefID) {
        
        $brief = $this->commonAction($briefID);
        $briefID = $brief->id;
        
        if(!empty($_POST)) {
            // Handle comment post
            $this->loadModel('action');
            $changes = $this->brief->accept($briefID);
            
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('brief', $briefID, 'Accepted', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            die(js::reload('parent.parent'));
        }
        
        $this->view->title      = $this->lang->brief->accept;
        $this->view->actions    = $this->loadModel('action')->getList('brief', $briefID);
                
        $this->display();
        
    }
    
    /**
     * Decline
     * 
     * Sets a brief as declined and logs an action in the audit trail.
     * @param int $briefID
     */
    public function decline($briefID) {
        
        $brief = $this->commonAction($briefID);
        $briefID = $brief->id;
        
        if(!empty($_POST)) {
            // Handle comment post
            $this->loadModel('action');
            $changes = $this->brief->decline($briefID);
                        
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('brief', $briefID, 'Declined', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            die(js::reload('parent.parent'));
        }
        
        $this->view->title      = $this->lang->brief->decline;
        $this->view->actions    = $this->loadModel('action')->getList('brief', $briefID);
                
        $this->display();
        
    }
    
    /**
     * Revert
     * 
     * Sets a brief as reverted and logs an action in the audit trail.
     * @param int $briefID
     */
    public function revert($briefID) {
        
        $brief = $this->commonAction($briefID);
        $briefID = $brief->id;
        
        if(!empty($_POST)) {
            // Handle comment post
            $this->loadModel('action');
            $changes = $this->brief->revert($briefID);
                        
            if(dao::isError()) die(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('brief', $briefID, 'Reverted', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            die(js::reload('parent.parent'));
        }
        
        $this->view->title      = $this->lang->brief->revert;
        $this->view->actions    = $this->loadModel('action')->getList('brief', $briefID);
                
        $this->display();
        
    }
    
    /**
     * Browse team of a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function team($briefID = 0)
    {
        $brief      = $this->commonAction($briefID);
        $briefID    = $brief->id;
        
        $title      = $brief->name . $this->lang->colon . $this->lang->brief->team;
        $position[] = html::a($this->createLink('brief', 'view', "briefID=$briefID"), $brief->name);
        $position[] = $this->lang->brief->team;
        
        $this->view->title    = $title;
        $this->view->position = $position;

        $this->display();
    }
    
    /**
     * View a list of Archived briefs
     * 
     * @access public
     * @return void 
     */
    public function archived($status = 'all', $briefID = 0, $orderBy = 'id_desc', $productID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1, $search = null) {
        
        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        
        $this->view->title          = $this->lang->brief->archived;
        $this->view->pager          = $pager;
        $this->view->briefs         = $this->brief->selectArchived($pager, $orderBy, $search);
        
        $this->display();
        
    }
    
    public function done($briefID) {
        
        $brief = $this->brief->getById($briefID, true);
        $this->commonAction($briefID);
        
        $projectID = $brief->projectID;
        
        if($_POST) {
            
            unset($_POST['confirmDone']);
            
            $this->loadModel('project')->closeAllTasks($projectID);
            
            // Archive Brief          
            $this->brief->archive($brief->id);
            
            // Close Project
            $this->loadModel('project')->close($projectID);        
        
            //if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('brief', 'index', ""), 'parent.parent'));
            
        }
        
        $this->display();
        
    }
    
    public function export() {
        
        /* Load pager and get tasks. */
        $recTotal = 0;
        $recPerPage = 999;
        $pageID = 0;
        $orderBy = 'id_desc';
                
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        
        $briefs        = $this->brief->select($pager, $orderBy);    
        $rows          = array();
                
        foreach($briefs as $brief) {
                                                
            $row = new stdClass();
            $row->id            = $brief->id;
            $row->name          = $brief->name;
            $row->type          = $this->lang->brief->typeList[$brief->type];
            $row->status        = $brief->status;
            $row->createdBy     = $this->loadModel('user')->getById($brief->creatorId, 'id')->realname;
            $row->onBehalfOf    = $this->loadModel('user')->getById($brief->onBehalfOf, 'id')->realname;
            $row->dateOrdered   = $brief->dateOrdered;
            $row->timeAllocation= $this->lang->brief->timeAllocation[$brief->timeAllocation];
            $row->deadline      = $brief->deadline;
            $row->created       = $brief->created;
                
            $rows[] = $row;
        }
        
        $this->post->set('fields', array('id'               => 'ID',
                                         'name'             => 'Name', 
                                         'type'             => 'Type',
                                         'status'           => 'Status',
                                         'createdBy'        => 'Creator',
                                         'onBehalfOf'       => 'On Behalf Of',
                                         'dateOrdered'      => 'Date Ordered',
                                         'timeAllocation'   => 'Time Allocation',
                                         'deadline'         => 'Deadline',
                                         'created'          => 'Date Created'));
        $this->post->set('rows', $rows);
        $this->post->set('kind', 'brief');
        $this->post->set('fileName', 'pds-briefs-' . date('Y-m-d'));
        $this->post->set('encode', 'utf-8');
                
        $this->fetch('file', 'export2CSV', $_POST);
        
        //die(js::locate($this->createLink('brief', 'index', ""), 'parent.parent'));
        
    }
    
    
}
