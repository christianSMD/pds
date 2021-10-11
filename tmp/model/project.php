<?php
global $app;
helper::cd($app->getBasePath());
helper::import('module\project\model.php');
helper::cd();
class extprojectModel extends projectModel 
{
/**
* Process burndown datas when the sets is smaller than the itemCounts.
*
* @param  array   $sets
* @param  int     $itemCounts
* @param  date    $begin
* @param  date    $end
* @param  string  $mode
* @access public
* @return array
*/
function processBurnData($sets, $itemCounts, $begin, $end, $mode = 'noempty')
{        
   if($end != '0000-00-00')
   {
       $period = helper::diffDate($end, $begin) + 1;
       $counts = $period > $itemCounts ? $itemCounts : $period;
   }
   else
   {
       $counts = $itemCounts;
       $period = $itemCounts;
       $end    = date(DT_DATE1, strtotime("+$counts days", strtotime($begin)));
   }

   $current  = $begin;
   $endTime  = strtotime($end);
   $preValue = 0;
   $todayTag = 0;

   foreach($sets as $date => $set)
   {
       if($begin > $date) unset($sets[$date]);
   }

   for($i = strtotime('2018-01-01'); $i < $period; $i++)
   {
       /*
       echo $i;
       echo ' ';
       echo $period;
       echo '<br/>';*/
       $currentTime = strtotime($current);
       if($currentTime > $endTime) break;
       if(isset($sets[$current])) $preValue = $sets[$current]->value;
       if($currentTime > time() and !$todayTag)
       {
           $todayTag = $i + 1;
           break;
       }

       if(!isset($sets[$current]) and $mode == 'noempty')
       {
           $sets[$current]  = new stdclass();
           $sets[$current]->name  = $current;
           $sets[$current]->value = $preValue;
       }
       $nextDay = date(DT_DATE1, $currentTime + 24 * 3600);
       $current = $nextDay;
   }
   ksort($sets);

   if(count($sets) <= $counts) return $sets;
   if($endTime <= time()) return array_slice($sets, -$counts, $counts);
   if($todayTag <= $counts) return array_slice($sets, 0, $counts);
   if($todayTag > $counts) return array_slice($sets, $todayTag - $counts, $counts);
}

function closeAllTasks($projectID) {
    
    // Close All Tasks
    $tasks = $this->dao->select('*')
                   ->from(TABLE_TASK)
                   ->where('project')->eq($projectID)
                   ->fetchAll();

    foreach($tasks as $task) {

        $taskID = $task->id;

        $this->loadModel('action');
        $changes = $this->loadModel('task')->close($taskID);
        if(dao::isError()) die(js::error(dao::getError()));

        if($this->post->comment != '' or !empty($changes))
        {
            $actionID = $this->action->create('task', $taskID, 'Closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }


    }
    
}
//**//
}