<?php $mailTitle = 'BRIEF #' . $brief->id . ' ' . $brief->name;?>
<?php include $this->app->getModuleRoot() . 'common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php $color = empty($bug->color) ? '#333' : $bug->color;?>
          <?php echo html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink('brief', 'view', "briefID=$brief->id"), $mailTitle, '', "style='color: {$color}; text-decoration: underline;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
      <p>Brief has been <?php echo ucwords($status) ?>.</p>
      <p><?php echo $reason ?></p>
      <p><?php echo html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink('brief', 'view', "briefID=$brief->id"), 'View Your Brief', '', 'target="_blank"'); ?></p>
  </td>
</tr>
<?php include $this->app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
