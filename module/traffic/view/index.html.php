<?php
/**
 * The html template file of all method of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a(inlink("all", "status=all&projectID=$project->id&orderBy=$orderBy&productID=$productID"),       "<span class='text'>{$lang->project->all}</span>", '', "class='btn btn-link' id='allTab'");?>
    <?php echo html::a(inlink("all", "status=undone&projectID=$project->id&orderBy=$orderBy&productID=$productID"),    "<span class='text'>{$lang->project->undone}</span>", '', "class='btn btn-link' id='undoneTab'");?>
    <?php echo html::a(inlink("all", "status=wait&projectID=$project->id&orderBy=$orderBy&productID=$productID"),      "<span class='text'>{$lang->project->statusList['wait']}</span>", '', "class='btn btn-link' id='waitTab'");?>
    <?php echo html::a(inlink("all", "status=doing&projectID=$project->id&orderBy=$orderBy&productID=$productID"),     "<span class='text'>{$lang->project->statusList['doing']}</span>", '', "class='btn btn-link' id='doingTab'");?>
    <?php echo html::a(inlink("all", "status=suspended&projectID=$project->id&orderBy=$orderBy&productID=$productID"), "<span class='text'>{$lang->project->statusList['suspended']}</span>", '', "class='btn btn-link' id='suspendedTab'");?>
    <?php echo html::a(inlink("all", "status=closed&projectID=$project->id&orderBy=$orderBy&productID=$productID"),    "<span class='text'>{$lang->project->statusList['closed']}</span>", '', "class='btn btn-link' id='closedTab'");?>
    <div class='input-control space w-180px'>
      <?php // echo html::select('product', $products, $productID, "class='chosen form-control' onchange='byProduct(this.value, $projectID, \"$status\")'");?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php // common::printLink('project', 'export', "status=$status&productID=$productID&orderBy=$orderBy", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'")?>
    <?php // common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn btn-primary'")?>
  </div>
</div>
<div id='mainContent' ng-app="app" ng-controller="notesCtrl">
  <?php $canOrder = false; // (common::hasPriv('project', 'updateOrder') and strpos($orderBy, 'order') !== false) ?>
  <form class='main-table' id='projectsForm' method='post' action='<?php echo inLink('batchEdit', "projectID=$projectID");?>' data-ride='table'>
    <table class='table has-sort-head table-fixed' id='projectList'>
      <?php $vars = "status=$status&projectID=$projectID&orderBy=%s&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class="w-50px">Brief ID</th>
          <th class="w-200px"><?php common::printOrderLink('name', $orderBy, $vars, $this->lang->traffic->name);?></th>
          <th class="w-90px"><?php echo $lang->traffic->assignedTo ?></th>
          <!-- <th class="w-90px">For</th> -->
          <!-- <th class='w-100px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->traffic->code);?></th> -->
          <!-- <th class='w-100px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->traffic->PM);?></th> -->
          <th class='w-90px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->traffic->end);?></th>
          <th class='w-90px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->traffic->status);?></th>
          <th class='w-200px'>Notes</th>
          <th class='w-70px'><?php echo $lang->traffic->totalEstimate;?></th>
          <!-- <th class='w-70px'><?php echo $lang->traffic->totalConsumed;?></th> -->
          <th class='w-70px'><?php echo $lang->traffic->totalLeft;?></th>
          <th class='w-150px'><?php echo $lang->traffic->progress;?></th>
          <?php if(common::hasPriv('brief', 'done')): ?>
          <th class="text-center w-60px">Done</th>
          <?php endif; ?>
          <?php if($canOrder):?>
          <th class='w-20px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->project->updateOrder);?></th>
          <?php endif;?>
        </tr>
      </thead>
      <?php $canBatchEdit = common::hasPriv('project', 'batchEdit'); ?>
      <tbody id='projectTableList'>
        <?php foreach($projectStats as $project): ?>
        <tr data-id='<?php echo $project->id ?>' data-order='<?php echo $project->order ?>'>
          <td class='c-id'>
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='projectIDList[<?php echo $project->id;?>]' value='<?php echo $project->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $project->id);?>
          </td>
          <td style="color: green;">
            <?php echo $project->briefID;?>
          </td>
          <td class='text-left c-name <?php if(!empty($project->tasks)) echo 'has-child'; ?>' title='<?php echo $project->name?>'>
            <?php
            if(isset($project->delay)) echo "<span class='label label-danger label-badge'>{$lang->traffic->delayed}</span> ";
            if(!empty($project->tasks)):
                echo '<a class="task-toggle collapsed" data-id="' . $project->id . '"><i class="icon icon-caret-down"></i></a>';
            endif;
            echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name);
            ?>
          </td>
          <td class="c-assignedTo">
              <?php 
              // If there are tasks assigned to the project, loop through
              // all tasks until an assignee is found
              if(!empty($project->tasks)): 
                  foreach($project->tasks as $task):
                    $user = $this->loadModel('user')->getById($task->assignedTo);
                    if($user != false) {
                        echo $user->realname;
                        break;
                    }
                  endforeach;
              endif;
              ?>
          </td>
          <!-- <td class='text-left'><?php echo $project->code;?></td> -->
          <!-- <td><?php echo $users[$project->PM];?></td> -->
          <td><?php echo $project->end;?></td>
          <td class='c-status' title='<?php echo zget($lang->project->statusList, $project->status);?>'>
            <span class="status-<?php echo $project->status?>">
              <span class="label label-dot"></span>
              <span class='status-text'><?php echo zget($lang->project->statusList, $project->status);?></span>
            </span>
          </td>
          <td>
            <textarea ng-model="note<?php echo $project->id;?>" class="smd-notes" rows="2" ng-init="getLastNote(<?php echo $project->id;?>)" id="<?php echo $project->id;?>" placeholder="Add new notes"></textarea>
            <a ng-click="saveNote(note<?php echo $project->id;?>, <?php echo $project->id;?>)">
               <i ng-show="!loading" class='icon-check text-success'></i>
               <span ng-show="loading">&#8987;</span>
            </a>
          </td>
          <td><?php echo $project->hours->totalEstimate;?></td>
          <!-- <td><?php echo $project->hours->totalConsumed;?></td> -->
          <td><?php echo $project->hours->totalLeft;?></td>
          <td class="c-progress">
            <div class="progress progress-text-left">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->hours->progress;?>%">
              <span class="progress-text"><?php echo $project->hours->progress;?>%</span>
              </div>
            </div>
          </td>
           <?php if(common::hasPriv('brief', 'done')): 
                        if($brief->status != 'new' && $brief->status != 'revert'): ?>
                    <td class="text-center"><?php common::printIcon('project', 'done',     "projectID=$project->id", '', '', '', '', 'iframe', true); ?></td>
                    <?php endif; 
                    endif;?>
          <?php if($canOrder):?>
          <td class='sort-handler'><i class="icon icon-move"></i></td>
          <?php endif;?>
        </tr>
        <?php if(!empty($project->tasks)): ?>
        <?php foreach($project->tasks as $key => $child): 
            $taskLink = helper::createLink('task', 'view', "taskID=$child->id"); ?>
          <?php $class  = $key == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($key + 1 == count($project->tasks)) ? ' table-child-bottom' : '';?>
        <tr class='table-children<?php echo $class;?> parent-<?php echo $project->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>' data-consumed='<?php echo $child->consumed?>' data-left='<?php echo $child->left?>'>
            <td class='t-id'>
                <div class="checkbox-primary" style="visibility: hidden">
                    <input type='checkbox' name='projectIDList[<?php echo $project->id;?>]' value='<?php echo $project->id;?>' />
                    <label></label>
                </div>
                <?php printf('%03d', $child->id);?>
            </td>
            <td>
                <?php echo html::a($taskLink, $child->name, null, "style='color: $child->color'"); ?>
            </td>
            <td>
                <?php if($child->deadline > 0):
                    echo $child->deadline;
                endif;
                ?>
            </td>
            <td><span class="status-<?php echo $child->status;?>"><span class="label label-dot"></span> <?php echo $lang->task->statusList[$child->status];?></span></td>
            <td><?php echo $child->estimate ?></td>
            <td><?php echo $child->consumed ?></td>
            <td><?php echo $child->left ?></td>
            <td colspan="2" class="has-btn text-left">
                <?php
                $btnTextClass   = '';
                    $assignedToText = zget($users, $child->assignedTo);
                    $btnTextClass   = '';
                    if(empty($child->assignedTo))
                    {
                        $btnTextClass = 'text-primary';
                        $assignedToText = $this->lang->task->noAssigned;
                    } else if($child->assignedTo == $account) $btnTextClass = 'text-red';
                    $btnClass = $assignedToText == 'closed' ? ' disabled' : '';
                    
                    echo html::a(helper::createLink('task', 'assignTo', "projectID=$project->id&taskID=$child->id", '', true), "<i class='icon icon-hand-right'></i> <span class='{$btnTextClass}'>{$assignedToText}</span>", '', "class='iframe btn btn-icon-left btn-sm {$btnClass}'");
                ?>
            </td>
        </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($projectStats):?>
    <div class='table-footer'>
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->traffic->batchEdit, '', 'btn');?></div>
      <?php endif;?>
      <?php //if(!$canOrder and common::hasPriv('project', 'updateOrder')) echo html::a(inlink('all', "status=$status&projectID=$projectID&order=order_desc&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"), $lang->traffic->updateOrder, '', "class='btn'");?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>

<script>$("#<?php echo $status;?>Tab").addClass('btn-active-text');</script>

<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>

