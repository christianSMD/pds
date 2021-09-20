<?php 
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php'; ?>
<div id="mainContent" class="main-content">
    <div class="main-header">
        <h2><?php echo $lang->smdproduct->createmetatype;?></h2>
    </div>
    <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
        <table class='table table-form'>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->namemeta;?></th>
                <td><?php echo html::input('name', $metaType->name, "class='form-control'");?></td>
            </tr>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->descmeta;?></th>
                <td><?php echo html::textarea('desc', $metaType->desc, "rows='6' class='form-control'");?></td>
            </tr>
            <tr>
                <th class='text-left'><?php echo $lang->smdproduct->typefields;?></th>
                <td><?php echo html::select('fields[]', $metaFields, $metaType->fields, "class='form-control chosen' multiple");?></td>
            </tr>
            <tr>
                <th class="text-left"><?php echo $lang->smdproduct->metatypeparent ?></th>
                <td><?php echo html::select('parentId', $metaTypes, $metaType->parentId, "class='form-control chosen' onchange='updateMetaTypeFields()'") ?><p class="help-block"><?php echo $lang->smdproduct->ttmetatypeparent ?></p></td>
            </tr>
            <tr>
                <th></th>
                <td><?php echo html::submitButton($lang->smdproduct->savemetafield) . html::linkButton($lang->goback, $this->session->taskList); ?></td>
            </tr>
        </table>
    </form>
</div>
<?php include '../../common/view/footer.html.php';?>