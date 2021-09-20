<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content">
    <div class="main-header">
        <h2><?php echo $lang->smdproduct->sendtoteam;?></h2>
    </div>
    
  <form class='form-condensed sendtoteam form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      
        <p class="help-block"><?php echo $lang->smdproduct->ttsendtoteam ?></p>
    <table class='table table-form'>
      <tr>
        <th class='text-left'><?php echo $lang->comment;?></th>
        <td><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
      </tr>
      <tr>
          <th class="text-left"><?php echo $lang->smdproduct->upload ?></th>
          <td><?php echo $this->fetch('file', 'buildForm') ?></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton($lang->smdproduct->sendtoteam) . html::linkButton($lang->goback, $this->session->taskList); ?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>