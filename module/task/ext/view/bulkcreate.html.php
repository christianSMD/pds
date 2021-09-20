<?php include '../../../common/view/header.html.php';?>

<div id="mainContent" class="main-content">
    <div class="center-block">
        <div class="main-header">
            <h2><?php echo $lang->task->bulkCreate ?></h2>
            <div class="pull-right btn-toolbar">
                
            </div>
        </div>
        <?php
                $taskGroup = array('design' => 'Design Tasks',
                                   'devel' => 'Product Development Tasks');
                $currentDept = '';
                foreach($taskList as $task):
                    
                    if($task->dept != $currentDept) {
                        echo '<h5>' . $taskGroup[$task->dept] . '</h5>';
                    }
                    
                    $currentDept = $task->dept; 
                    
                    ?>
                      <div class='group-item'>
                          <div class="checkbox-primary checkbox-inline">
                              <input type="checkbox" id="task-<?php echo $task->id ?>" value="<?php echo $task->taskName ?>" data-type="<?php echo $currentDept ?>" data-hours="<?php echo $task->estHours ?>" class="bulkTask" />
                          <label> <?php echo $task->taskName ?></label>
                      </div>
                    <?php
                    
                endforeach;
            ?>
        <form class='form-indicator main-form form-ajax' method='post' action="/task-batchCreate-<?php echo $projectID ?>--0.html" target='hiddenwin' id='dataform'>
            <div class="hidden-fields">
                
            </div>
            <div class='text-center'>
                <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
                <?php echo html::backButton('', '', 'btn btn-wide');?>
            </div>
        </form>
    </div>
</div>
<?php include '../../../common/view/footer.html.php';?>
