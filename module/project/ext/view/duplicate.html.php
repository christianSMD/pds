<?php 
include '../../../common/view/header.html.php';
include '../../../common/view/kindeditor.html.php';
?>
<div id="mainContent" class="main-content">
    <div class="main-header">
        <h2><?php echo $lang->project->duplicate; ?></h2>
    </div>
    <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
        
        <tr>
            <th class='text-left'><?php echo $lang->project->name;?></th>
            <td><?php echo html::input('name', $oldname, "class='form-control'");?>
                <div class="help-block">Old Name: "<?php echo $oldname ?>"</div>
            </td>
        </tr>
        <tr>
            <th class="text-left"><?php echo $lang->project->desc; ?></th>
            <td>
                <?php echo html::textarea('desc', $olddesc, "rows='6' class='form-control'"); ?>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td><?php echo html::submitButton($lang->project->duplicate) ?></td>
        </tr>
    </form>
</div>
<?php include '../../../common/view/footer.html.php';?>