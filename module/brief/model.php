<?php

class briefModel extends model {
    
    /**
     * Archive
     * 
     * Marks a brief as archived. 
     * @param int $briefID
     * @return array
     */
    public function archive($briefID) {
                        
        $oldBrief   = $this->getById($briefID);
        $now        = helper::now();
        $brief = fixer::input('post')
                ->setDefault('archived', 1)
                ->remove('comment')->get();
        
        $this->dao->update(TABLE_BRIEFS)->data($brief)
                  ->autoCheck()
                  ->where('id')->eq((int)$briefID)
                  ->exec();
        
        if(!dao::isError()) return common::createChanges($oldBrief, $brief);
        
    }
    
    /**
     * Accept
     * 
     * Marks a brief as accepted. 
     * @param int $briefID
     * @return array
     */
    public function accept($briefID) {
                
        $this->notifyTeam($briefID, 'accepted', $this->post->comment);
        
        $oldBrief   = $this->getById($briefID);
        $now        = helper::now();
        $brief = fixer::input('post')
                ->setDefault('status', 'accepted')
                ->remove('comment')->get();
        
        
        $this->briefToProject($briefID);
        
        $this->dao->update(TABLE_BRIEFS)->data($brief)
                  ->autoCheck()
                  ->where('id')->eq((int)$briefID)
                  ->exec();        
        
        if(!dao::isError()) return common::createChanges($oldBrief, $brief);
        
    }
    
    /**
     * Brief To Project
     * 
     * Creates a new project by performing a POST to the Project controller
     * @param int $briefID
     * @return int $projectID
     */
    public function briefToProject($briefID) {
       
        $brief = $this->getById($briefID);
        
        $project = array(
            'name' => $brief->name,
            'code' => md5(strtolower(str_replace(' ', '-', $brief->name))),
            'end'  => $brief->deadline,
            'type' => 'sprint',
            'desc' => $this->_generate_project_desc($brief),
            'acl' => 'custom',
            'whitelist' => join(',', array(1,16,14)),
            'uid' => md5(date('U'))
        );
        
        $briefTeam = $this->getTeamMembers($briefID);
                        
        // Function stolen from projects/model.php        
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->create->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique', "deleted='0'")
            ->check('code', 'unique', "deleted='0'")
            ->exec();
        
        $projectID = $this->dao->lastInsertId();
        
        // Copy the current brief team to the project. 
        $this->copyBriefTeam($briefID, $projectID);
        $this->copyBriefFiles($briefID, $projectID);
        
        $briefData = array(
                        'projectID' => $projectID,
                        'modified' => date('Y-m-d H:i:s'),
                        'modifiedBy' => $this->app->user->id
                    );
        
        $this->dao->update(TABLE_BRIEFS)
                  ->data($briefData)
                  ->where('id')->eq($briefID)
                  ->limit(1)
                  ->exec();
        
    }
    
    private function copyBriefTeam($briefID, $projectID) {
        
        $briefTeam = $this->getTeamMembers($briefID);
        $projectTeam = array();
        
        foreach($briefTeam as $briefTeamMember) {
            // Remove unnecessary fields
            unset($briefTeamMember->id, $briefTeamMember->realname, $briefTeamMember->totalHours);
            // Update existing data
            $briefTeamMember->join = date('Y-m-d');
            $briefTeamMember->root = $projectID;
            $briefTeamMember->type = 'project';
            // Insert team member into db
            $this->dao->insert(TABLE_TEAM)->data($briefTeamMember)
                        ->exec();
            
        }
        
    }
    
    private function copyBriefFiles($briefID, $projectID) {
        
        $files = $this->dao->select('*')
                           ->from(TABLE_FILE)
                           ->where('objectType')->eq('brief')
                           ->andWhere('objectID')->eq($briefID)
                           ->fetchAll();
        
        if($files == '') {
            return false;
        }
                
        foreach($files as $f) {
            
            $fileObj = array(
                'pathname' => $f->pathname,
                'title' => $f->title,
                'extension' => $f->extension,
                'size' => $f->size,
                'objectType' => 'project',
                'objectID' => $projectID,
                'addedby' => $f->addedBy,
                'addedDate' => $f->addedDate,
                'downloads' => 0,
                'extra' => '',
                'deleted' => 0
            );
            
            $this->dao->insert(TABLE_FILE)->data($fileObj)->exec();
            
        }
                
    }
    
    /**
     * Generate Project Desc
     * 
     * Creates a text block for the project description based on the brief
     * @param object $brief
     * @return string
     */
    private function _generate_project_desc($brief) {
        
        $string = '';
        
        $string .= "{$this->lang->brief->type}: {$this->lang->brief->typeList[$brief->type]}\r\n";
        
        if($brief->range != '') { $string .= "{$this->lang->brief->range}: {$brief->range}\r\n"; }
        
        if($brief->lsm != '') { 
            $lsms = explode(',', $brief->lsm);
            foreach($lsms as $k => $lsm) {
                $lsms[$k] = $this->lang->brief->lsmList[$lsm];
            }
            
            $string .= "{$this->lang->brief->lsm}: " . implode(', ', $lsms) . "\r\n";
        }
        
        if($brief->packaging != '') {
            $packaging = explode(',', $brief->packaging);
            foreach($packaging as $k => $package) {
                $packaging[$k] = $this->lang->brief->packagingList[$package];
            }
            $string .= "{$this->lang->brief->packaging}: " . implode(', ', $packaging) . "\r\n";
        }
        
        if($brief->brand != '') {
            $brand = $this->loadModel('smdproduct')->getBrandById($brief->brand);            
            $string .= "{$this->lang->brief->brand}: $brand->name\r\n";
        }
        
        if($brief->collected != '') {
            $string .= "{$this->lang->brief->collection}: $brief->collected\r\n";
        }
        
        if($brief->angles != '') {
            $angles = explode(',', $brief->angles);
            foreach($angles as $k => $angle) {
                $angles[$k] = $this->lang->brief->photoList[$angle];
            }
            $string .= "{$this->lang->brief->photos}: " . implode(', ', $angles) . "\r\n";
        }
        
        if($brief->resolution != '') {
            $resolutions = explode(',', $brief->resolution);
            foreach($resolutions as $k => $resolution) {
                $resolutions[$k] = $this->lang->brief->resolutionList[$resolution];
            }
            $string .= "{$this->lang->brief->resolution}: " . implode(', ', $resolutions) . "\r\n";
        }
        
        if($brief->dimensions != '') {
            $string .= "{$this->lang->brief->dimensions}: {$brief->dimensions}\r\n";
        }
        
        if($brief->measurements != '') {
            $string .= "{$this->lang->brief->measurements}: {$brief->measurements}\r\n";
        }
        
        if($brief->paperSize != '') {
            $string .= "{$this->lang->brief->paperSize}: {$brief->paperSize}\r\n";
        }
        
        if($brief->orientation != '') {
            $orientations = explode(',', $brief->orientation);
            foreach($orientations as $k => $orientation) {
                $orientations[$k] = $this->lang->brief->orientationList[$orientation];
            }
            $string .= "{$this->lang->brief->orientation}: " . implode(', ', $orientations) . "\r\n";
        }
        
        if($brief->finishingDetails != '') {
            $string .= "{$this->lang->brief->finishingDetails}: $brief->finishingDetails\r\n";
        }
        
        if($brief->printing != '' && $brief->printing == 1) {
            $string .= "{$this->lang->brief->requiresPrinting}: Yes\r\n";
            $string .= "{$this->lang->brief->printQty}: $brief->printQty\r\n";
        }
        
        if($brief->desc != '') {
            $string .= "\r\n{$brief->desc}\r\n\r\n";
        }
        
        if($brief->links != '') {
            $string .= "{$this->lang->brief->links}: $brief->links\r\n";
        }
        
        if($brief->products != '') {
            
            $string .= $this->get_product_links($brief->products);
            
        }
        
        if($brief->functions != '') {
            $string .= "<b>{$this->lang->brief->functions}:</b>\r\n";
            $string .= "{$brief->functions}\r\n";
        }
        
        if($brief->lookAndFeel != '') {
            $string .= "<b>{$this->lang->brief->lookAndFeel}:</b>\r\n";
            $string .= "{$brief->lookAndFeel}\r\n";
        }
        
        if($brief->techSpec != '') {
            $string .= "<b>{$this->lang->brief->techSpec}:</b>\r\n";
            $string .= "{$brief->techSpec}\r\n";
        }
        
        if($brief->funcSpec != '') {
            $string .= "<b>{$this->lang->brief->funcSpec}:</b>\r\n";
            $string .= "{$brief->funcSpec}\r\n";
        }
        
        if($brief->size != '') {
            $string .= "{$this->lang->brief->size}: {$brief->size}\r\n";
        }
        
        if($brief->client != '') {
            $string .= "{$this->lang->brief->client}: {$brief->client}\r\n";
        }
        
        if($brief->targetPrice != '') {
            $string .= "{$this->lang->brief->targetPrice}: {$brief->targetPrice}\r\n";
        }
        
        if($brief->quantity != '') {
            $string .= "{$this->lang->brief->quantity}: {$brief->quantity}\r\n";
        }
        
        if($brief->timeAllocation != '') {
            $string .= "{$this->lang->brief->time}: {$this->lang->brief->timeAllocation[$brief->timeAllocation]}\r\n";
        }
        
        if($brief->dateOrdered != '') {
            $string .= "{$this->lang->brief->dateOrdered}: {$brief->dateOrdered}\r\n";
        }
        
        return $string;
        
    }
    
    public function get_product_links($products) {
        
        $products = explode(",", $products);
        $productLinks = array();
        
        foreach($products as $productID) {
            
            $product = $this->loadModel('smdproduct')->getById($productID);
            
            $browseLink = "/smdproduct-view-{$productID}.html";
            
            $productLinks[] = html::a($browseLink, $product->name);
            
        }
        
        return implode(',', $productLinks);
        
    }
    
    /**
     * Decline
     * 
     * Marks a brief as declined. 
     * @param int $briefID
     * @return array
     */
    public function decline($briefID) {
        
        $this->notifyTeam($briefID, 'declined', $this->post->comment);
        
        $oldBrief   = $this->getById($briefID);
        $now        = helper::now();
        $brief = fixer::input('post')
                ->setDefault('status', 'declined')
                ->remove('comment')->get();
        
        $this->dao->update(TABLE_BRIEFS)->data($brief)
                  ->autoCheck()
                  ->where('id')->eq((int)$briefID)
                  ->exec();
        
        if(!dao::isError()) return common::createChanges($oldBrief, $brief);
        
    }
    
    /**
     * Revert
     * 
     * Marks a brief as reverted. 
     * @param int $briefID
     * @return array
     */
    public function revert($briefID) {
        
        $this->notifyTeam($briefID, 'reverted', $this->post->comment);
        
        $oldBrief   = $this->getById($briefID);
        $now        = helper::now();
        $brief = fixer::input('post')
                ->setDefault('status', 'reverted')
                ->remove('comment')->get();
        
        $this->dao->update(TABLE_BRIEFS)->data($brief)
                  ->autoCheck()
                  ->where('id')->eq((int)$briefID)
                  ->exec();
        
        if(!dao::isError()) return common::createChanges($oldBrief, $brief);
        
    }
    
    /**
     * Update Brief
     * 
     * Upserts a brief depending on whether a briefID is present. Returns the
     * last insert ID or the updated brief ID.
     * @param int $briefID
     * @return int
     */
    public function updateBrief($briefID = null) {
                    
        $this->cleanFields();
        
        $whitelist = array(1,16);
                
        if(strpos($this->lang->brief->typeList[$this->post->type], 'Design')) {
            
            $whitelist[] = 14;
            
        }
                                        
        $brief = fixer::input('post')
                ->setDefault('status', 'new')
                ->join('products', ',')
                ->join('lsm', ',')
                ->join('packaging', ',')
                ->join('angles', ',')
                ->join('resolution', ',')
                ->join('orientation', ',')
                ->setDefault('created', date('Y-m-d H:i:s'))
                ->setDefault('creatorId', $this->app->user->id)
                ->setDefault('whitelist', join(',', $whitelist))
                ->setIF(strlen($this->post->onBehalfOf) > 0, 'onBehalfOf', $this->getOnBehalfOfId(filter_input(INPUT_POST, 'onBehalfOf')))
                ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
                ->remove('uid')
                ->remove('briefID')
                ->remove('files')
                ->remove('labels')
                ->get();
        
        $brief = $this->cleanTags($brief);
                
        if($briefID == null) {
            // Create
            $this->dao->insert(TABLE_BRIEFS)
                      ->data($brief)
                      ->exec();
                        
            $briefID = $this->dao->lastInsertID();
            
            $this->assignTeam($briefID);
            
        } else {
            // Update method            
            $this->dao->update(TABLE_BRIEFS)
                      ->data($brief)
                      ->where('id')->eq($briefID)
                      ->limit(1)
                      ->exec();
        }
                
        // Handle file uploads
        $file = $this->loadModel('file')->saveUpload('brief', $briefID);
                   
        return $briefID;
       
    }
    
    /**
     * Assign Team
     * 
     * Assigns a particular set of top level staff to an incoming brief
     * @param object $brief
     * @param string $type
     */
    public function assignTeam($briefID) {
        
        $members= array();
        
        $brief = $this->getById($briefID);
        
        $creator = new stdClass();
        $creator->account = $this->loadModel('user')->getById($brief->creatorId, 'id')->account;
        
        $members[] = $creator;
        
        if($brief->creatorId != $brief->onBehalfOf && $brief->onBehalfOf > 0) {
            $onBehalfOf = new stdClass();
            $onBehalfOf->account = $this->loadModel('user')->getById($brief->onBehalfOf, 'id')->account;
            $members[] = $onBehalfOf;
        }
                
        if(in_array($brief->type, array('createNewProduct', 'modifyProduct', 'newConcept')) !== false) {
            // Brief is for Product Dev
            $groupID = PRODEV_ID;            
        } else {
            // Brief is for Design Traffic
            $groupID = TRAFFIC_ID;
        }
                
        $result = $this->dao->select('t2.account AS account')
                            ->from(TABLE_USERGROUP)->alias('t1')
                            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
                            ->where('`group`')->eq($groupID)
                            ->fetchAll();
        
        $members = array_merge($members, $result);
        
        $added_members = array();
                
        foreach($members as $m) {
            
            if($m->account !== '' && in_array($m->account, $added_members) === false) {
            
                $member = new stdclass();
                $member->root    = $brief->id;
                $member->account = $m->account;
                $member->role    = ''; // $this->lang->user->roleList[$this->app->user->role];
                $member->join    = date('Y-m-d');
                $member->type    = 'brief';
                $member->days    = $this->config->brief->defaultWorkDays;
                $member->hours   = $this->config->brief->defaultWorkhours;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                
                $added_members[] = $m->account;
            }
        }
        
    }
    
    /**
     * Clean Fields
     * 
     * Resets all fields but those necessary for the insert / update
     * @return void
     */
    private function cleanFields() {
        // Get the brief type and its corresponding required fields
        $type = filter_input(INPUT_POST, 'type');
        $fields = $this->config->brief->typefields[$type];
        // Loop through POST var, removing not-found vars
                
        foreach($_POST as $k => $p) {
        
            if(!in_array($k, $fields)) {
                $_POST[$k] = '';
            }
        }
    }
    
    /**
     * Get `On Behalf Of` ID
     * 
     * Gets the ID of the user account being queried
     * @param type $account
     * @return type
     */
    public function getOnBehalfOfId($account) {
                
        $user = $this->loadModel('user')->getById($account);
        
        return $user->id;
        
        
    }
    
    /**
     * Get By ID
     * 
     * Returns a Brief object if the ID is available
     * @param int $briefID
     * @return object
     */
    public function getById($briefID) {
        
        $brief = $this->dao->findById((int)$briefID)->from(TABLE_BRIEFS)->fetch();
        if(!$brief) return false;
        
        // Get associated files
        $brief->files = $this->loadModel('file')->getByObject('brief', $briefID);
        
        return $brief;
        
    }
    
    /**
     * Get Briefs
     * 
     * Gets a page of briefs for the main Index view
     * @param string $status
     * @param int $briefID
     * @param int $branch
     * @param int $itemCounts
     * @param string $orderBy
     * @param int $pager
     * @return array
     */
    /*
    public function getBriefs($status = 'all', $briefID = 0, $branch = 0, $itemCounts = 30, $orderBy = 'created_desc', $pager = null) {
        
        
        
    }*/
    
    public function select($pager, $orderBy, $search = false) {
                
        $briefs = $this->getList();
        foreach($briefs as $briefID => $brief)
        {            
            if(!$this->checkPriv($brief)) unset($briefs[$briefID]);
        }
        
        $briefs = $this->dao->select('*')->from(TABLE_BRIEFS)
            ->where('id')->in(array_keys($briefs))
            ->andWhere('archived')->eq(0)
            ->beginIF($search != false)->andWhere('`name`', true)->like("%{$search}%")->orWhere('`desc`')->like("%{$search}%")->markRight(1)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
                
        return $briefs;
        
    }
    
    public function selectArchived($pager, $orderBy, $search = false) {
        
        $briefs = $this->getList();
        foreach($briefs as $briefID => $brief)
        {            
            if(!$this->checkPriv($brief)) unset($briefs[$briefID]);
        }
        
        $briefs = $this->dao->select('*')->from(TABLE_BRIEFS)
            ->where('id')->in(array_keys($briefs))
            ->andWhere('archived')->eq(1)
            ->beginIF($search != false)->andWhere('`name`', true)->like("%{$search}%")->orWhere('`desc`')->like("%{$search}%")->markRight(1)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
           
        return $briefs;           
        
    }
        
    
    /**
     * Get List
     * 
     * @param string $status
     * @param int $limit
     * @param int $briefID
     * @param int $branch
     * @return array
     */
    public function getList($status = 'all', $limit = 0) {
        
        if($briefID != 0) {
            
        } else {
            return $this->dao->select('*')
                             ->from(TABLE_BRIEFS)
                             ->where('deleted')->eq(0)
                             ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
                             ->beginIF($limit)->limit($limit)->fi()
                             ->fetchAll('id');
        }
        
    }
    
    /**
     * Check Priv
     * 
     * Checks privileges required to view briefs
     * 
     * @staticvar type $teams
     * @param type $brief
     * @return boolean
     */
    public function checkPriv($brief)
    {
        /* If is admin, return true. */
        if($this->app->user->admin) return true;

        $acls = $this->app->user->rights['acls'];
                
        if(!empty($acls['briefs']) and !in_array($brief->id, $acls['briefs'])) return false;
        
        /* If project is open, return true. */
        if($brief->acl == 'open') return true;
        
        /* Get all teams of all projects and group by projects, save it as static. */
        static $teams;
        if(empty($teams)) $teams = $this->dao->select('root, account')->from(TABLE_TEAM)->where('type')->eq('brief')->fetchGroup('root', 'account');
        $currentTeam = isset($teams[$brief->id]) ? $teams[$brief->id] : array();

        /* If project is private, only members can access. */
        if($brief->acl == 'private')
        {
            return isset($currentTeam[$this->app->user->account]);
        }
        
        /* Project's acl is custom, check the groups. */
        if($brief->acl == 'custom')
        {
            if(isset($currentTeam[$this->app->user->account])) return true;
            $userGroups    = $this->app->user->groups;
            $briefGroups = explode(',', $brief->whitelist);
            foreach($userGroups as $groupID)
            {
                if(in_array($groupID, $briefGroups)) return true;
            }
            return false;
        }
    }
        
    public function setMenu($briefs, $briefID, $buildID = 0, $extras = '') {
                
        foreach($this->lang->brief->menu as $key => $menu)
        {
            
            common::setMenuVars($this->lang->brief->menu, $key, $briefID);
            
        }
        
    }
    
    /**
     * Get team members.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getTeamMembers($briefID)
    {
        
        return $this->dao->select("t1.*, t1.hours * t1.days AS totalHours, if(t2.deleted='0', t2.realname, t1.account) as realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$briefID)
            ->andWhere('t1.type')->eq('brief')
            ->fetchAll('account');
        
    }
    
    public function notifyTeam($briefID, $status, $reason) {
        
        $this->loadModel('mail');        
        $this->config->mail->turnon = true;
        
        $brief = $this->getById($briefID);
        $teamMembers = $this->getTeamMembers($briefID);
        $teamAccounts = $this->my_arrayColumn($teamMembers, 'account');      
        $teamNamesEmails = $this->loadModel('user')->getRealNameAndEmails($teamAccounts);
        $emails = $this->my_arrayColumn($teamNamesEmails, 'email');       
        
        $this->view->brief          = $brief;
        $this->view->status         = $status;
        $this->view->reason         = $reason;
                
        $modulePath = $this->app->getModulePath($appName = '', 'brief');
        $viewFile = $modulePath . 'view/notifyEmail.html.php';
        
        ob_start();
        include $viewFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        
        // $ccList = implode(',', $emails);
                
        $subject = 'Your Brief #' . $briefID . ' has been ' . $status;
        
        $mail = $this->mail->send($teamAccounts, $subject, $mailContent);
        
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
                
    }
    
    function my_arrayColumn($array,$column_name)
    {

        return array_map(function($element) use($column_name){return $element->$column_name;}, $array);

    }
    
    
    
    /*
     * public function sendmail($bugID, $actionID)
    {
        $this->loadModel('mail');
        $bug   = $this->getByID($bugID);
        $users = $this->loadModel('user')->getPairs('noletter');
     
        // Get action info.
        $action             = $this->loadModel('action')->getById($actionID);
        $history            = $this->action->getHistory($actionID);
        $action->history    = isset($history[$actionID]) ? $history[$actionID] : array();
        $action->appendLink = '';
        if(strpos($action->extra, ':')!== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = $extra;
            if($id)
            {
                $name  = $this->dao->select('title')->from(TABLE_BUG)->where('id')->eq($id)->fetch('title');
                if($name) $action->appendLink = html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink($action->objectType, 'view', "id=$id"), "#$id " . $name);
            }
        }

        // Get mail content.
        $modulePath = $this->app->getModulePath($appName = '', 'bug');
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        $sendUsers = $this->getToAndCcList($bug);
        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;
        $subject = $this->getSubject($bug);

        // Send it. 
        $this->mail->send($toList, $subject, $mailContent, $ccList);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }
     */
    
    /**
     * Install
     * 
     * Installs the necessary tables. Keep this updated if the table structure
     * changes
     */
    public function install() {
        $result = $this->dao->query("CREATE TABLE IF NOT EXISTS `c_{$this->config->db->prefix}brief` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `creatorId` int(11) DEFAULT NULL,
            `onBehalfOf` int(11) DEFAULT NULL,
            `deadline` date DEFAULT NULL,
            `type` varchar(255) DEFAULT NULL,
            `desc` text,
            `products` text,
            `links` text,
            `lsm` text,
            `range` text,
            `packaging` text,
            `brand` varchar(255) DEFAULT NULL,
            `collected` date DEFAULT NULL,
            `angles` text,
            `resolution` text,
            `dimensions` varchar(255) DEFAULT NULL,
            `paperSize` varchar(255) DEFAULT NULL,
            `measurements` varchar(255) DEFAULT NULL,
            `orientation` text,
            `finishingDetails` varchar(255) DEFAULT NULL,
            `printing` tinyint(1) DEFAULT '0',
            `printQty` varchar(255) DEFAULT NULL,
            `functions` text,
            `lookAndFeel` text,
            `xcaCode` varchar(255) DEFAULT NULL,
            `techSpec` text,
            `funcSpec` text,
            `size` varchar(255) DEFAULT NULL,
            `additionalSpec` text,
            `client` varchar(255) DEFAULT NULL,
            `targetPrice` varchar(255) DEFAULT NULL,
            `quantity` varchar(255) DEFAULT NULL,
            `deliveryDate` date DEFAULT NULL,
            `status` varchar(32) DEFAULT NULL,
            `archived` tinyint(1) DEFAULT '0',
            `projectID` int(11) DEFAULT NULL,
            `modified` datetime DEFAULT NULL,
            `modifiedBy` int(11) DEFAULT NULL,
            `order` int(11) DEFAULT NULL,
            `acl` enum('open','private','custom') DEFAULT 'private',
            `created` datetime DEFAULT NULL,
            `deleted` tinyint(1) DEFAULT '0',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;"
        . "ALTER TABLE `{$this->config->db->prefix}team` CHANGE `type` ENUM('project','task','brief') CHARSET utf8 COLLATE utf8_general_ci DEFAULT 'project' NOT NULL;");
        
    }
    
    /**
     * Clean Tags
     * 
     * Strips a bunch of unnecessary tags out of the WYSIWYG editor. Configured 
     * inside the config.php file
     * 
     * @param object $brief
     * @return object
     */
    private function cleanTags($brief) {
                
        foreach($this->config->brief->cleanFields as $field) {
            $brief->$field      = trim(strip_tags(html_entity_decode($brief->$field), $this->config->brief->myAllowedTags));
        }
                        
        return $brief;
    }
    
    public function getBriefByProjectID($projectID) {
        
        $result = $this->dao->select('*')
                             ->from(TABLE_BRIEFS)
                             ->where('projectID')->eq($projectID)
                             ->limit(1)
                             ->fetch();
        
        return $result;
        
    }
}

