<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_ripple_wallet; ?></td>
          <td><input type="text" name="ripple_ripple_wallet" value="<?php echo $ripple_ripple_wallet; ?>" style="width:300px;" />
            <?php if ($error_ripple_wallet) { ?>
            <span class="error"><?php echo $error_ripple_wallet; ?></span>
            <?php } ?><div><?php echo $text_ripple_wallet; ?></div></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_payment_description; ?></td>
          <td><input type="text" name="ripple_payment_description" value="<?php echo $ripple_payment_description; ?>" style="width:300px;" />
            <?php if ($error_payment_description) { ?>
            <span class="error"><?php echo $error_payment_description; ?></span>
            <?php } ?><div><?php echo $text_payment_description; ?></div></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_payment_expiration; ?></td>
          <td><input type="text" name="ripple_payment_expiration" value="<?php echo $ripple_payment_expiration; ?>" style="width:300px;" />
            <?php if ($error_rpc_host) { ?>
            <span class="error"><?php echo $error_payment_expiration; ?></span>
            <?php } ?><div><?php echo $text_payment_expiration; ?></div></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_rpc_host; ?></td>
          <td><input type="text" name="ripple_rpc_host" value="<?php echo $ripple_rpc_host; ?>" style="width:300px;" />
            <?php if ($error_rpc_host) { ?>
            <span class="error"><?php echo $error_rpc_host; ?><div><?php echo $text_rpc_host; ?></div></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_rpc_port; ?></td>
          <td><input type="text" name="ripple_rpc_port" value="<?php echo $ripple_rpc_port; ?>" style="width:300px;" />
            <?php if ($error_rpc_port) { ?>
            <span class="error"><?php echo $error_rpc_port; ?></span>
            <?php } ?><div><?php echo $text_rpc_port; ?></div></td>
        </tr>        <tr>
          <td><span class="required">*</span> <?php echo $entry_rpc_ssl; ?></td>
          <td><select name="ripple_rpc_ssl"> 
              <?php if ($ripple_rpc_ssl) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select><div><?php echo $text_rpc_ssl; ?></div></td>
        </tr>        <tr>
          <td><?php echo $entry_rpc_user; ?></td>
          <td><input type="text" name="ripple_rpc_user" value="<?php echo $ripple_rpc_user; ?>" style="width:300px;" />
            <?php if ($error_rpc_user) { ?>
            <span class="error"><?php echo $error_rpc_user; ?><div><?php echo $text_rpc_user; ?></div></span>
            <?php } ?></td>
        </tr>        <tr>
          <td><?php echo $entry_rpc_pass; ?></td>
          <td><input type="text" name="ripple_rpc_pass" value="<?php echo $ripple_rpc_pass; ?>" style="width:300px;" />
            <?php if ($error_rpc_pass) { ?>
            <span class="error"><?php echo $error_rpc_pass; ?><div><?php echo $text_rpc_pass; ?></div></span>
            <?php } ?></td>
        </tr>        <tr>
          <td><span class="required">*</span> <?php echo $entry_cron_secret; ?></td>
          <td><input type="text" name="ripple_cron_secret" value="<?php echo $ripple_cron_secret; ?>" style="width:300px;" />
            <?php if ($error_cron_secret) { ?>
            <span class="error"><?php echo $error_cron_secret; ?></span>
            <?php } ?><div><?php echo $text_cron_secret; ?></div></td>
        </tr>
          <tr>
            <td><?php echo $entry_verifing_status; ?></td>
            <td><select name="ripple_verifing_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $ripple_confirmed_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_confirmed_status; ?></td>
            <td><select name="ripple_confirmed_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $ripple_confirmed_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_invalid_status; ?></td>
            <td><select name="ripple_invalid_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $ripple_invalid_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="ripple_status"> 
              <?php if ($ripple_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="ripple_sort_order" value="<?php echo $ripple_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</div>
<?php echo $footer; ?>
