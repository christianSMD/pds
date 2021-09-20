<?php

include '../../control.php';

class myProject extends project {
    /**
     * View a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function view($projectID)
    {

        if(!empty($_FILES) || !empty($_POST)) {

            $projectFiles = fixer::input('post')->remove('files')
            ->remove('labels')
            ->get();

            $file = $this->loadModel('file')->saveUpload('project', $projectID);

            $url = $this->createLink('project', 'view', "projectID=$projectID");
            die(js::locate($url, 'parent'));
        } else {

            $project = $this->project->getById($projectID, true);
            if(!$project) die(js::error($this->lang->notFound) . js::locate('back'));

            $products = $this->project->getProducts($project->id);
            $linkedBranches = array();
            foreach($products as $product)
            {
                if($product->branch) $linkedBranches[$product->branch] = $product->branch;
            }

            /* Set menu. */
            $this->project->setMenu($this->projects, $project->id);
            $this->app->loadLang('bug');

            list($dateList, $interval) = $this->project->getDateList($project->begin, $project->end, 'noweekend', 0, 'Y-m-d');
            $chartData = $this->project->buildBurnData($projectID, $dateList, 'noweekend');

            $this->view->title      = $this->lang->project->view;
            $this->view->position[] = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
            $this->view->position[] = $this->view->title;
            
            $files = $this->loadModel('file')->getByObject('project', $projectID);
            $filecount = count($files);

            while(count($files) > 7) {
                array_pop($files);
            }

            $this->view->project      = $project;
            $this->view->products     = $products;
            $this->view->branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), '', $linkedBranches);
            $this->view->planGroups   = $this->project->getPlans($products);
            $this->view->groups       = $this->loadModel('group')->getPairs();
            $this->view->actions      = $this->loadModel('action')->getList('project', $projectID);
            $this->view->dynamics     = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager = null, 'all', $projectID);
            $this->view->users        = $this->loadModel('user')->getPairs('noletter');
            $this->view->teamMembers  = $this->project->getTeamMembers($projectID);
            $this->view->docLibs      = array(); // $this->loadModel('doc')->getLibsByObject('project', $projectID);
            $this->view->statData     = $this->project->statRelatedData($projectID);
            $this->view->files        = $files;
            $this->view->filecount    = $filecount;
            $this->view->chartData    = $chartData;        

            $this->display();
        }
    }
}
