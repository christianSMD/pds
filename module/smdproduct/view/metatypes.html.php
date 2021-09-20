<?php include '../../common/view/header.html.php';
      include '../../common/view/sparkline.html.php';
      include '../../common/view/sortable.html.php'; ?>

<div id="mainMenu" class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="page-title">
            <span class="btn btn-link btn-active-text">
                <span class="text">
                    <?php echo $lang->smdproduct->metaTypes;?>
                </span>
            </span>
        </div>
    </div>
    <div class="btn-toolbar pull-right">
        <?php if(common::hasPriv('smdproduct', 'editmetatype')): ?>
        <?php common::printIcon('smdproduct', 'editmetatype', "", '', 'button', '', '', 'iframe btn btn-primary', true);?>
        <?php endif; ?>
    </div>
</div>
<div id="mainContent">
    <?php $canOrder = (common::hasPriv('brief', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
<?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
<form class="main-table" id="metaTypesForm" method="post" action="<?php echo inLink('batchEdit') ?>" data-ride="table">
<table class='table table-fixed tablesorter table-datatable table-selectable' id='metaTypesList'>
    <thead>
          <tr>
              <th class='w-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
              <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->smdproduct->namemeta);?></th>
              <th><?php common::printOrderLink('desc', $orderBy, $vars, $lang->smdproduct->descmeta); ?></th>

              <?php if($canOrder):?>
              <th class='w-60px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->smdproduct->updateOrder);?></th>
              <?php endif;?>
          </tr>
  </thead>
  <tbody class='sortable' id='metaFieldTableList'>
      <?php foreach($metaTypes as $metaType): ?>
          <tr class='text-center' data-id='<?php echo $brief->id ?>' data-order='<?php echo $brief->order ?>'>
              <td class='cell-id'>
                  <?php echo html::a($this->createLink('smdproduct', 'editmetatype', 'metaTypeID=' . $metaType->id, '', 'iframe', true), sprintf('%03d', $metaType->id), '', "class='iframe'" );?>
              </td>
              <td class='text-left' title='<?php echo $metaType->name?>' <?php if($metaType->parentId > 0): ?>style="padding-left: 30px"<?php endif; ?>>
                  <?php echo html::a($this->createLink('smdproduct', 'editmetatype', 'metaTypeID=' . $metaType->id, '', 'iframe', true), $metaType->name, '', "class='iframe'");?></td>
              <td class='text-left'><i><?php echo $metaType->desc ?></i></td>
              <?php if($canOrder):?>
                <td class='sort-handler'><i class="icon icon-move"></i></td>
              <?php endif;?>
          </tr>
      <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='<?php echo $canOrder ? 4 : 3?>'>
        <div class='table-actions clearfix'>
          <?php if($canBatchEdit and !empty($briefs)):?>
          <?php echo html::selectButton();?>
          <?php echo html::submitButton($lang->brief->batchEdit);?>
          <?php endif;?>
          <?php if(!$canOrder and common::hasPriv('brief', 'updateOrder')) echo html::a(inlink('all', "order=order_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"), $lang->smdproduct->updateOrder, '', "class='btn'");?>
        </div>
        <?php $pager->show();?>
      </td>
    </tr>
  </tfoot>
</table>
</form>
</div>
<?php include '../../common/view/footer.html.php';


