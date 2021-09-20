<?php
include '../../control.php';

class myProject extends project {

    function duplicate($projectID) {

        $oldproject = $this->commonAction($projectID);

        $newproject = new stdClass();
        $newproject->code = md5(date('U') + rand(0, 1000));
        $newproject->begin = $oldproject->begin;
        $newproject->end   = $oldproject->end;
        $newproject->days = $oldproject->days;
        $newproject->team = $oldproject->team;
        $newproject->type = $oldproject->type;
        $newproject->desc = $oldproject->desc;
        $newproject->acl = $oldproject->acl;
        $newproject->whitelist = $oldproject->whitelist;

        if(!empty($_POST)) {
            $newproject->name = filter_input(INPUT_POST, 'name');
            $newproject->desc = filter_input(INPUT_POST, 'desc');
            
            // Check that the name is not the same as the old one
            if($newproject->name == $oldproject->name) {
                die(js::error('Please enter a unique name for this job'));
            }

            $newProjectID = $this->_createNewProject($newproject);

            if(dao::isError()) die(js::error(dao::getError()));

            $newtasks = $this->_getProjectTasks($projectID, $newProjectID);
            $newteam = $this->_getProjectTeam($projectID, $newProjectID);
            $newfiles = $this->_getProjectFiles($projectID, $newProjectID);

            //exit;*/

            $this->_duplicateTasks($newtasks);
            $this->_duplicateTeam($newteam);
            $this->_duplicateFiles($newfiles);

            if(dao::isError()) die(js::error(dao::getError()));

            $newLink = $this->createLink('project', 'view', "projectID=$newProjectID");

            die(js::locate($newLink, 'parent.parent'));

        }

        $this->view->oldname = $oldproject->name;
        $this->view->olddesc = $oldproject->desc;

        $this->display();

    }

    function _createNewProject($newproject) {

        $this->dao->insert(TABLE_PROJECT)->data($newproject)->exec();

        return $this->dao->lastInsertId();

    }

    function _duplicateTasks($newTasks) {

        if(empty($newTasks)) {
            return false;
        }

        foreach($newTasks as $task) {
            $this->dao->insert(TABLE_TASK)->data($task)->exec();
        }

    }

    function _duplicateTeam($newTeam) {

        if(empty($newTeam)) {
            return false;
        }

        foreach($newTeam as $team) {
            $this->dao->insert(TABLE_TEAM)->data($team)->exec();
        }
    }

    function _duplicateFiles($newFiles) {
        if(empty($newFiles)) {
            return false;
        }

        foreach($newFiles as $file) {
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
        }
    }

    function _getProjectTasks($projectID, $newProjectID) {

        $taskList = $this->dao->select("'$newProjectID' AS `project`,
                                        `name`, `type`,`pri`,`estimate`, `deadline`, `desc`, `openedBy`, `openedDate`, `assignedTo`, `assignedDate`")
                              ->from(TABLE_TASK)
                              ->where('project')
                              ->eq($projectID)
                              ->fetchAll();

        return $taskList;

    }

    function _getProjectTeam($projectID, $newProjectID) {

        $teamList = $this->dao->select("'$newProjectID' AS `root`, `type`, `account`, `role`, `limited`, `join`, `days`, `hours`")
                              ->from(TABLE_TEAM)
                              ->where('root')
                              ->eq($projectID)
                              ->andWhere('type')->eq('project')
                              ->fetchAll();

        return $teamList;

    }

    function _getProjectFiles($projectID, $newProjectID) {

        $files = $this->dao->select("`pathname`, `title`, `extension`, `size`, `objectType`, '$newProjectID' AS `objectID`, `addedBy`, `addedDate`, `downloads`")
                           ->from(TABLE_FILE)
                           ->where('objectType')->eq('project')
                           ->andWhere('objectID')->eq($projectID)
                           ->fetchAll();

        return $files;

    }
}