<?php include '../../common/view/header.html.php';
      include '../../common/view/sparkline.html.php';
      include '../../common/view/sortable.html.php'; ?>
<?php $vars = "search={$search}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&search={$search}"; ?>
<div id='mainMenu' class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="page-title">
            <span class="btn btn-link btn-active-text">
                <span class="text">
                    <?php echo $lang->smdproduct->index;?>
                </span>
            </span>
        </div>
        <div class='space'>
            <form action="/smdproduct-index" method="post" class="form">
                <input type="text" name="productSearch" id="productSearch" placeholder="Search by name or SKU" class="form-control inline-block" value="<?php echo $search ?>"/>
                <input type="submit" name="submitSearch" id="submitSearch" value="Search" class="btn inline-block" />
            </form>
        </div>
    </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printIcon('smdproduct', 'createproduct', "", '', 'button', '', '', 'iframe btn btn-primary', true); ?>
  </div>
</div>
<div id="mainContent">
    <?php $canOrder = (common::hasPriv('smdproduct', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
    <form class="main-table" id="smdproductsForm" method="post" action="<?php echo inLink('batchEdit', "smdproductID=$smdproductID");?>" data-ride='table'>
        <table class="table" id="smdproductsList">
            
            <thead>
                <tr>
                    <th class='w-id text-center'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
                    <th class="w-100px">Image</th>
                    <th class="text-center"><?php common::printOrderLink('name', $orderBy, $vars, $lang->smdproduct->productname);?></th>
                    <th class="text-center"><?php common::printOrderLink('sku', $orderBy, $vars, $lang->smdproduct->productsku);?></th>
                    <th class="text-center"><?php common::printOrderLink('brandId', $orderBy, $vars, $lang->smdproduct->productbrand);?></th>
                    <th class="text-center"><?php common::printOrderLink('metaTypeId', $orderBy, $vars, $lang->smdproduct->metatype);?></th>
                    <th class="text-center"><?php common::printOrderLink('modified', $orderBy, $vars, $lang->smdproduct->productmodified);?></th>
                    <th class="w-80px"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?>
                    <tr>
                        <td class='cell-id'>
                            <?php if($canBatchEdit):?>
                            <input type='checkbox' name='briefIDList[<?php echo $product->id;?>]' value='<?php echo $product->id;?>' /> 
                            <?php endif;?>
                            <?php echo html::a($this->createLink('smdproduct', 'view', 'smdproductID=' . $product->id), sprintf('%04d', $product->id));?>
                        </td>
                        <td>
                            <?php 
                            $imgclass = '';
                            if(strlen($product->sku) < 1) {
                                $imgclass = 'class="hidden"';
                            }
                            $img_arr = explode(',', $product->image);
                            $img = $img_arr[0];
                            ?>
                            <img src="<?php echo $img; ?>" <?php echo $imgclass; ?> />
                        </td>
                        <td class='text-left'><?php echo html::a($this->createLink('smdproduct', 'view', 'smdproductID=' . $product->id), $product->name); ?></td>
                        <?php if($product->hasChildren == 0): ?>
                        <td class='text-left'><?php echo html::a($this->createLink('smdproduct', 'view', 'smdproductID=' . $product->id), $product->sku); ?></td>
                        <?php else:
                            $skus = '';
                            foreach($product->children as $child) {
                                $skus .= $child->sku . '<br/>';
                            }
                        ?>
                        <td class='text-left'><?php echo html::a($this->createLink('smdproduct', 'view', 'smdproductID=' . $product->id), $skus); ?></td>
                        <?php endif; ?>
                        
                        <td class='text-left'><?php echo $this->smdproduct->getBrandById($product->brandId)->name ?></td>
                        <td class='text-left'><?php echo $this->smdproduct->getMetaTypeById($product->metaTypeId)->name ?></td>
                        <td class='text-left'><?php echo $product->modified ?></td>
                        <td class="text-center"><?php if($product->complete == 1): echo '<i class="icon-check"></i>'; else: echo '<i class="icon-close"></i>'; endif; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class='table-footer'>
            <?php if($canBatchEdit):?>
                <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
                <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->brief->batchEdit, '', 'btn');?></div>
            <?php endif;?>
            <?php $pager->show();?>
        </div>
    </form>
</div>

<?php include '../../common/view/footer.html.php';?>