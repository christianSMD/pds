<?php 
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php';
?>
<div id="mainContent" class="main-content">
    <div class="main-header">
        <h2><?php echo $lang->smdproduct->createproduct; ?></h2>
    </div>
    <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
        <table class='table table-form'>
            <tr>
                <th class='text-left w-110px'><?php echo $lang->smdproduct->brandproduct;?></th>
                <td><?php echo html::select('brandId', $brands, '', "class='form-control chosen'");?></td>
            </tr>
            <tr>
                <th class='text-left w-110px'><?php echo $lang->smdproduct->metatypecat;?></th>
                <td><?php echo html::select('metaTypeId', $metaTypes, '', "class='form-control chosen' onChange='metaTypeChange()'");?><p class="help-block"><?php echo $lang->smdproduct->ttMetaType ?></p></td>
            </tr>
            <tr class="metaTypeChild">
                <th class="text-left w-110px"><?php echo $lang->smdproduct->metatypecatchild ?></th>
                <td><?php echo html::select('metaTypeChildId', array(''), '', "class='form-control chosen'") ?></td>
            </tr>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->nameproduct;?></th>
                <td><?php echo html::input('name', '', "class='form-control'");?></td>
            </tr>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->skuproduct;?></th>
                <td><?php echo html::input('sku', '', "class='form-control'");?><p class="help-block"><?php echo $lang->smdproduct->ttSku ?></p></td>
            </tr>
            <tr>
                <th class="text-left"><?php echo $lang->smdproduct->coloursproduct ?></th>
                <td><?php echo html::select('meta-' . COLOURID . '[]', $colours, '', "class='form-control chosen' multiple");?><a href="javascript:addNewValue(<?php echo COLOURID ?>);">Add New Value</a></td>
            </tr>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->xcaproduct;?></th>
                <td><?php echo html::input('xca', '', "class='form-control'");?><p class="help-block"><?php echo $lang->smdproduct->ttXCA ?></p></td>
            </tr>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->barcodeproduct;?></th>
                <td><?php echo html::input('barcode', '', "class='form-control'");?></td>
            </tr>
            <tr>
                <th></th>
                <td><?php echo html::submitButton($lang->smdproduct->saveproduct) . html::linkButton($lang->goback, $this->session->taskList); ?></td>
            </tr>
        </table>
    </form>
</div>
<?php include '../../common/view/footer.html.php';?>