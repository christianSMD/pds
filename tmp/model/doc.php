<?php
global $app;
helper::cd($app->getBasePath());
helper::import('module\doc\model.php');
helper::cd();
class extdocModel extends docModel 
{
/**
     * Get grant libs by doc.
     * 
     * @access public
     * @return array
     */
    function getPrivLibsByDoc()
    {
        static $libs;
        if($libs === null)
        {
            $libs = array();
            $stmt = $this->dao->select('`lib`,`groups`,`users`')->from(TABLE_DOC)->where('acl')->ne('open')->andWhere("(`groups` != '' or `users` != '')")->query();

            $account    = ",{$this->app->user->account},";
            $userGroups = $this->app->user->groups;
            while($lib = $stmt->fetch())
            {
                if(strpos(",$lib->users,", $account) !== false)
                {
                    $libs[$lib->lib] = $lib->lib;
                }
                else
                {
                    foreach($userGroups as $groupID)
                    {
                        if(strpos(",$lib->groups,", ",$groupID,") !== false)
                        {
                            $libs[$lib->lib] = $lib->lib;
                            break;
                        }
                    }
                }
            }
        }
        return $libs;
    }
//**//
}