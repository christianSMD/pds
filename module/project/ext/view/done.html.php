<?php
/**
 * The suspend file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     project 
 * @version     $Id: suspend.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        https://www.zentao.pm
 */
?>
<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $project->id;?></strong></span>
      <?php echo isonlybody() ? ("<span title='$project->name'>" . $project->name . '</span>') : html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name, '_blank');?>
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
<?php include '../../../common/view/footer.html.php';?>
