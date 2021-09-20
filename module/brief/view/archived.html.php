<?php include '../../common/view/header.html.php';
      include '../../common/view/sparkline.html.php';
      include '../../common/view/sortable.html.php'; ?>

<div id="mainMenu" class="clearfix">
    <div class="btn-toolbar pull-left">
        <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->brief->archived ?></span></span>
        <div class='space'>
            <form action="" method="post" class="form">
                <input type="text" name="briefSearch" id="briefSearch" placeholder="Search by name" class="form-control inline-block" value="<?php echo $search ?>"/>
                <input type="submit" name="submitSearch" id="submitSearch" value="Search" class="btn inline-block" />
            </form>
        </div>
        <span class='btn btn-link btn-active-text'><span class='text'><a href="/brief">View <?php echo $lang->brief->index ?></a></span></span>
    </div>
    <div class='btn-toolbar pull-right'>
        <?php if(!empty($app->user->admin) or empty($app->user->rights['rights']['my']['limited'])):
            if(common::hasPriv('brief', 'create')):
                common::printLink('brief', 'create', '', "<i class='icon-plus'></i> " . $lang->brief->create, '', "class='btn btn-primary create-brief-btn'");
            endif;
        endif; ?>
    </div>
</div>
<div id="mainContent">
    <?php // $canOrder = (common::hasPriv('project', 'updateOrder') and strpos($orderBy, 'order') !== false);
    $canOrder == false;
    $canBatchEdit == false; ?>
    <form class="main-table" id="briefsForm" method="post" action="<?php echo inLink('batchEdit', "projectID=$projectID");?>" data-ride='table'>
        <table class="table" id="briefList">
            <?php $vars = "status=$status&briefID=$briefID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
            <thead>
                <tr>
                    <th class='w-id text-center'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
                    <th class="text-center"><?php common::printOrderLink('name', $orderBy, $vars, $lang->brief->name);?></th>
                    <th class="text-center"><?php common::printOrderLink('type', $orderBy, $vars, $lang->brief->type); ?></th>
                    <th class="text-center"><?php common::printOrderLink('status', $orderBy, $vars, $lang->brief->status); ?></th>
                    <th class="text-center"><?php common::printOrderLink('creatorId', $orderBy, $vars, $lang->brief->creator); ?></th>
                    <th class="text-center"><?php common::printOrderLink('onBehalfOf', $orderBy, $vars, $lang->brief->onBehalf); ?></th>
                    <th class="text-center"><?php common::printOrderLink('dateOrdered', $orderBy, $vars, $lang->brief->dateOrdered); ?></th>
                    <th class="text-center"><?php common::printOrderLink('timeAllocation', $orderBy, $vars, $lang->brief->time); ?></th>
                    <th class="text-center"><?php common::printOrderLink('deadline', $orderBy, $vars, $lang->brief->deadline); ?></th>
                    <th class="text-center"><?php common::printOrderLink('created', $orderBy, $vars, $lang->brief->created); ?></th>
                </tr>
            </thead>
            <?php $canBatchEdit = common::hasPriv('project', 'batchEdit'); ?>
            <tbody class='' id='projectTableList'>
                <?php foreach($briefs as $brief):?>
                <tr class='text-center text-muted' data-id='<?php echo $brief->id ?>' data-order='<?php echo $brief->order ?>'>
                    <td class='cell-id'>
                        <?php // if($canBatchEdit):?>
                        <!-- <input type='checkbox' name='briefIDList[<?php echo $brief->id;?>]' value='<?php echo $brief->id;?>' /> -->
                        <?php //endif;?>
                        <?php echo html::a($this->createLink('brief', 'view', 'brief=' . $brief->id), sprintf('%03d', $brief->id));?>
                    </td>
                    <td class='text-left' title='<?php echo $brief->name?>'><?php echo html::a($this->createLink('brief', 'view', 'brief=' . $brief->id), $brief->name);?></td>
                    <td class='text-left'><?php echo $lang->brief->typeList[$brief->type] ?></td>
                    <td class='text-left <?php echo $brief->status ?>'><?php echo $lang->brief->statusList[$brief->status] ?> [Archived]</td>
                    <td class='text-left'><?php echo $this->loadModel('user')->getById($brief->creatorId, 'id')->realname; ?></td>
                    <td class='text-left'><?php echo $this->loadModel('user')->getById($brief->onBehalfOf, 'id')->realname; ?></td>
                    <td class="text-left"><?php echo $brief->dateOrdered ?></td>
                    <td class="text-left"><?php echo $lang->brief->timeAllocation[$brief->timeAllocation] ?></td>
                    <td class='text-left'><?php echo $brief->deadline ?></td>
                    <td class='text-left'><?php echo $brief->created ?></td>
                    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class='table-footer'>
            <?php if($canBatchEdit):?>
            <!-- <div class="checkbox-primary check-all"><label><?php // echo $lang->selectAll ?></label></div> -->
            <div class="table-actions btn-toolbar"><?php // echo html::submitButton($lang->brief->batchEdit, '', 'btn');?></div>
            <?php endif;?>
            <?php // if(!$canOrder and common::hasPriv('project', 'updateOrder')) echo html::a(inlink('all', "status=$status&briefID=$briefID&order=order_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"), $lang->brief->updateOrder, '', "class='btn'");?>
            <?php $pager->show('right', 'full');?>
        </div>
    </form>
</div>

<?php include '../../common/view/footer.html.php';?>
