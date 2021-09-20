<?php 
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php';
?>
<div id="mainContent" class="main-content">
    <div class="main-header">
        <h2><?php echo $lang->smdproduct->addchild; ?></h2>
    </div>
    <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
        <p>Add a new child product to <?php echo $name ?></p><br/><br/>
        <table class='table table-form'>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->skuproduct;?></th>
                <td><?php echo html::input('sku', '', "class='form-control' placeholder='" . $sku . "' required");?></td>
            </tr>
            
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->barcodeproduct;?></th>
                <td><?php echo html::input('barcode', '', "class='form-control'");?></td>
            </tr>
            <tr>
                <th class='text-left w-110px'><?php echo $lang->smdproduct->coloursproduct;?></th>
                <td><?php echo html::select('colourId', $colours, '', "class='form-control chosen' required");?></td>
            </tr>

            <tr>
                <th></th>
                <td><?php echo html::submitButton($lang->smdproduct->saveproduct) . html::linkButton($lang->goback, $this->session->taskList); ?></td>
            </tr>
        </table>
    </form>
</div>
<?php include '../../common/view/footer.html.php';?>