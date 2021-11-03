<?php
class trafficModel extends model {
    
    public function getTasks($projectID, $type = 'assignedTo', $limit = 0, $pager = null, $orderBy="id_desc") {
        
        if(!$this->loadModel('common')->checkField(TABLE_TASK, $type)) return array();
                
        $tasks = $this->dao->select('t1.*, t2.id as projectID, t2.name as projectName, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, t4.type AS jobType, t4.id AS briefID')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftjoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftjoin(TABLE_BRIEFS)->alias('t4')->on('t2.id = t4.projectID')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t4.type')->notIn(array('createNewProduct', 'modifyProduct', 'newConcept'))
            ->andWhere('t1.status')->ne('done')
            ->andWhere('t1.project')->eq($projectID)
            ->beginIF($type == 'assignedTo')->andWhere('t1.status')->ne('closed')->fi()
            //->beginIF($type != 'all')->andWhere("t1.`$type`")->eq($account)->fi()
            ->orderBy($orderBy)
            // ->beginIF($limit > 0)->limit($limit)->fi()
            ->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task');

        if($tasks) return $this->loadModel('task')->processTasks($tasks);
        return array();
        
    }
    
    /**
     * Get project stats.
     *
     * @param  string $status
     * @param  int    $productID
     * @param  int    $itemCounts
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getProjectStats($status = 'undone', $productID = 0, $branch = 0, $itemCounts = 30, $orderBy = 'order_desc', $pager = null)
    {
        /* Init vars. */
        $projects = $this->getList($status, 0, $productID, $branch);
        foreach($projects as $projectID => $project)
        {
            if(!$this->checkPriv($project)) unset($projects[$projectID]);
        }
        $projects = $this->dao->select('*, t2.id AS id, t2.status AS status, t4.id AS briefID')->from(TABLE_PROJECT)->alias('t2')
            ->leftjoin(TABLE_BRIEFS)->alias('t4')->on('t2.id = t4.projectID')
            ->where('t2.id')->in(array_keys($projects))
            ->andWhere('t4.type')->notIn(array('createNewProduct', 'modifyProduct', 'newConcept'))
            ->orderBy('t2.' . $orderBy)
            ->page($pager)
            ->fetchAll('id');

        $projectKeys = array_keys($projects);
        $stats       = array();
        $hours       = array();
        $emptyHour   = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, project, estimate, consumed, `left`, status, closedReason')
            ->from(TABLE_TASK)
            ->where('project')->in($projectKeys)
            ->andWhere('parent')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('project', 'id');

        /* Compute totalEstimate, totalConsumed, totalLeft. */
        foreach($tasks as $projectID => $projectTasks)
        {
            $hour = (object)$emptyHour;
            foreach($projectTasks as $task)
            {
                if($task->status != 'cancel')
                {
                    $hour->totalEstimate += $task->estimate;
                    $hour->totalConsumed += $task->consumed;
                }
                if($task->status != 'cancel' and $task->status != 'closed') $hour->totalLeft += $task->left;
            }
            $hours[$projectID] = $hour;
        }

        /* Compute totalReal and progress. */
        foreach($hours as $hour)
        {
            $hour->totalEstimate = round($hour->totalEstimate, 1) ;
            $hour->totalConsumed = round($hour->totalConsumed, 1);
            $hour->totalLeft     = round($hour->totalLeft, 1);
            $hour->totalReal     = $hour->totalConsumed + $hour->totalLeft;
            $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 3) * 100 : 0;
        }

        /* Get burndown charts datas. */
        $burns = $this->dao->select('project, date AS name, `left` AS value')
            ->from(TABLE_BURN)
            ->where('project')->in($projectKeys)
            ->orderBy('date desc')
            ->fetchGroup('project', 'name');

        foreach($burns as $projectID => $projectBurns)
        {
            /* If projectBurns > $itemCounts, split it, else call processBurnData() to pad burns. */
            $begin = $projects[$projectID]->begin;
            $end   = $projects[$projectID]->end;
            $projectBurns = array(); // $this->processBurnData($projectBurns, $itemCounts, $begin, $end);

            /* Shorter names.  */
            foreach($projectBurns as $projectBurn)
            {
                $projectBurn->name = substr($projectBurn->name, 5);
                unset($projectBurn->project);
            }

            ksort($projectBurns);
            $burns[$projectID] = $projectBurns;
        }

        /* Process projects. */
        foreach($projects as $key => $project)
        {
            // Process the end time.
            $project->end = date(DT_DATE1, strtotime($project->end));
            /* Judge whether the project is delayed. */
            //if($project->status != 'done' and $project->status != 'closed' and $project->status != 'suspended')
            //{
                $delay = helper::diffDate(helper::today(), $project->end);
                if($delay > 0) $project->delay = $delay;
            //}

            /* Process the burns. */
            $project->burns = array();
            $burnData = isset($burns[$project->id]) ? $burns[$project->id] : array();
            foreach($burnData as $data) $project->burns[] = $data->value;

            /* Process the hours. */
            $project->hours = isset($hours[$project->id]) ? $hours[$project->id] : (object)$emptyHour;

            $stats[] = $project;
        }

        return $stats;
    }
    
    public function getList($status = 'all', $limit = 0, $productID = 0, $branch = 0)
    {
        if($status == 'involved') return $this->getInvolvedList($status, $limit, $productID, $branch);

        if($productID != 0)
        {
            return $this->dao->select('t2.*, t2.id AS id, t2.status AS status, t4.id AS briefID')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->leftjoin(TABLE_BRIEFS)->alias('t4')->on('t2.id = t4.projectID')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.iscat')->eq(0)
                ->andWhere('t4.type')->notIn(array('createNewProduct', 'modifyProduct', 'newConcept'))
                ->beginIF($status == 'undone')->andWhere('t2.status')->ne('done')->andWhere('t2.status')->ne('closed')->fi()
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($status == 'isdoing')->andWhere('t2.status')->ne('done')->andWhere('t2.status')->ne('suspended')->andWhere('t2.status')->ne('closed')->fi()
                ->beginIF($status != 'all' and $status != 'isdoing' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->orderBy('order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select('*, t2.id AS id, t2.status AS status, IF(INSTR(" done,closed", t2.status) < 2, 0, 1) AS isDone, t4.id AS briefID')->from(TABLE_PROJECT)->alias('t2')
                ->leftjoin(TABLE_BRIEFS)->alias('t4')->on('t2.id = t4.projectID')
                ->where('iscat')->eq(0)
                ->andWhere('t4.type')->notIn(array('createNewProduct', 'modifyProduct', 'newConcept'))
                ->beginIF($status == 'undone')->andWhere('t2.status')->ne('done')->andWhere('t2.status')->ne('closed')->fi()
                ->beginIF($status == 'isdoing')->andWhere('t2.status')->ne('done')->andWhere('t2.status')->ne('suspended')->andWhere('t2.status')->ne('closed')->fi()
                ->beginIF($status != 'all' and $status != 'isdoing' and $status != 'undone')->andWhere('t2.status')->in($status)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy('t2.order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
    }
    
    public function checkPriv($project)
    {
        /* If is admin, return true. */
        if($this->app->user->admin) return true;

        $acls = $this->app->user->rights['acls'];
        if(!empty($acls['projects']) and !in_array($project->id, $acls['projects'])) return false;

        /* If project is open, return true. */
        if($project->acl == 'open') return true;

        /* Get all teams of all projects and group by projects, save it as static. */
        static $teams;
        if(empty($teams)) $teams = $this->dao->select('root, account')->from(TABLE_TEAM)->where('type')->eq('project')->fetchGroup('root', 'account');
        $currentTeam = isset($teams[$project->id]) ? $teams[$project->id] : array();

        /* If project is private, only members can access. */
        if($project->acl == 'private')
        {
            return isset($currentTeam[$this->app->user->account]);
        }

        /* Project's acl is custom, check the groups. */
        if($project->acl == 'custom')
        {
            if(isset($currentTeam[$this->app->user->account])) return true;
            $userGroups    = $this->app->user->groups;
            $projectGroups = explode(',', $project->whitelist);
            foreach($userGroups as $groupID)
            {
                if(in_array($groupID, $projectGroups)) return true;
            }
            return false;
        }
    }
}

