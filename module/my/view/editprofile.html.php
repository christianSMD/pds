<?php
/**
 * The edit view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.pm
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><i class='icon-pencil'></i> <?php echo $lang->my->editProfile;?></h2>
  </div>
  <form method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <caption><?php echo $lang->my->form->lblBasic;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->user->realname;?></th>
        <td><?php echo html::input('realname', $user->realname, "class='form-control' autocomplete='off'");?></td>
        <th class='w-90px'><?php echo $lang->user->email;?></th>
        <td><?php echo html::input('email', $user->email, "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->gender;?></th>
        <td><?php echo html::radio('gender', $lang->user->genderList, $user->gender);?></td>
        <th><?php echo $lang->user->birthyear;?></th>
        <td><?php echo html::input('birthday', $user->birthday,"class='form-date form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->join;?></th>
        <td class='text-middle'>
          <?php echo formatTime($user->join);?>
          <?php echo html::hidden('join',$user->join);?>
        </td>
      </tr>
    </table>
    <table class='table table-form'>
      <caption><?php echo $lang->my->form->lblAccount;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->user->account;?></th>
        <td style='width:33%'><?php echo html::input('account', $user->account, "class='form-control' readonly='readonly' autocomplete='off'");?></td>
        <th class='w-90px'><?php echo $lang->user->commiter;?></th>
        <td><?php echo html::input('commiter', $user->commiter, "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->password;?></th>
      <td>
        <input type='password' style="display:none"> <!-- Disable input password by browser automatically. -->
        <span class='input-group'>
          <?php echo html::password('password1', '', "class='form-control disabled-ie-placeholder' autocomplete='off' onmouseup='checkPassword(this.value)' onkeyup='checkPassword(this.value)' placeholder='" . (!empty($config->safe->mode) ? $lang->user->placeholder->passwordStrength[$config->safe->mode] : '') . "'");?>
          <span class='input-group-addon' id='passwordStrength'></span>
        </span>
      </td>
        <th><?php echo $lang->user->password2;?></th>
        <td><?php echo html::password('password2', '', "class='form-control'");?></td>
      </tr>
    </table>
    <table class='table table-form'>
      <caption><?php echo $lang->my->form->lblContact;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->user->skype;?></th>
        <td><?php echo html::input('skype', $user->skype, "class='form-control' autocomplete='off'");?></td>
        <th class='w-90px'><?php echo $lang->user->qq;?></th>
        <td><?php echo html::input('qq', $user->qq, "class='form-control' autocomplete='off'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->yahoo;?></th>
        <td><?php echo html::input('yahoo', $user->yahoo, "class='form-control' autocomplete='off'");?></td>
        <th><?php echo $lang->user->gtalk;?></th>
        <td><?php echo html::input('gtalk', $user->gtalk, "class='form-control' autocomplete='off'");?></td>
      </tr>  
       <tr>
        <th><?php echo $lang->user->wangwang;?></th>
        <td><?php echo html::input('wangwang', $user->wangwang, "class='form-control' autocomplete='off'");?></td>
        <th><?php echo $lang->user->mobile;?></th>
        <td><?php echo html::input('mobile', $user->mobile, "class='form-control' autocomplete='off'");?></td>
      </tr>  
       <tr>
        <th><?php echo $lang->user->phone;?></th>
        <td><?php echo html::input('phone', $user->phone, "class='form-control' autocomplete='off'");?></td>
        <th><?php echo $lang->user->address;?></th>
        <td><?php echo html::input('address', $user->address, "class='form-control' autocomplete='off'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->zipcode;?></th>
        <td><?php echo html::input('zipcode', $user->zipcode, "class='form-control' autocomplete='off'");?></td>
        <td></td>
      </tr>
    </table>
    <table class='table table-form'>
      <caption><?php echo $lang->user->verify;?></caption>
      <tr>
        <th class='w-verifyPassword'><?php echo $lang->user->verifyPassword;?></th>
        <td>
          <div class="required required-wrapper"></div>
          <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
        </td>
      </tr>
    </table>
    <div class='text-center form-actions'><?php echo html::submitButton('', '', 'btn btn-wide btn-primary') . html::backButton('', '', 'btn btn-wide');?></div>
  </form>
  <?php echo html::hidden('verifyRand', $rand);?>
</div>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<script>
function checkPassword(password)
{
    $('#passwordStrength').html(password == '' ? '' : passwordStrengthList[computePasswordStrength(password)]);
    $('#passwordStrength').css('display', password == '' ? 'none' : 'table-cell');
}
</script>
<?php include '../../common/view/footer.html.php';?>
