
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $brief->id;?></strong></span>
      <?php echo isonlybody() ? ("<span title='$brief->name'>" . $brief->name . '</span>') : html::a($this->createLink('project', 'view', 'project=' . $brief->id), $brief->name, '_blank');?>
      <?php if(!isonlybody()):?>
      <small> <?php echo $lang->arrow . $lang->project->close;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
      <input type="hidden" name="confirmDone" id="confirmDone" value="1" />
    <table class='table table-form'>
      <tbody>
        <tr>
            <td colspan="2"><p class="text-danger">This will close all outstanding Tasks, close the Job and archive the Brief.</p>
                <p class="text-danger">Do you want to continue?</p></td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='2'><?php echo html::submitButton('', '', 'btn btn-wide btn-primary') . html::linkButton($lang->goback, $this->session->taskList, 'self', '', 'btn btn-wide'); ?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
