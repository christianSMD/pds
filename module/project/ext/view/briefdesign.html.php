<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content">
    <div class="main-header">
        <h2><?php echo $lang->project->briefdesign;?></h2>
    </div>
  <form class='form-condensed' method='post' target='hiddenwin'>
      <p class='help-block'><?php echo $lang->project->briefdesigndesc; ?></p>
    <table class='table table-form'>
      <tr>
        <th class='text-left'><?php echo $lang->comment;?></th>
        <td><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton($lang->send) ?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../../common/view/footer.html.php';?>