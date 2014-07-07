<?php

class ControllerPaymentRipple extends Controller {
  private $error = array();
  private $payment_module_name = "ripple";

  // 入口
  public function index() {
    $this->load->language('payment/'.$this->payment_module_name);
    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
      $this->model_setting_setting->editSetting($this->payment_module_name, $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }
    $post_check = array(
        'ripple_wallet' => '',
        'payment_description' => 'Checkout securely through Ripple',
        'payment_expiration' => '3600',
        'rpc_host' => 's1.ripple.com',
        'rpc_port' => '51234',
        'rpc_ssl' => '1',
        'rpc_user' => '',
        'rpc_pass' => '',
        'cron_secret' => sha1(sha1(time().lcg_value().lcg_value().lcg_value().lcg_value().lcg_value().lcg_value().lcg_value())),
        'verifing_status_id' => '1',
        'confirmed_status_id' => '7',
        'invalid_status_id' => '10',
        'status' => '',
        'sort_order' => ''
      );
    foreach ($post_check as $key => $value) {
      if (isset($this->error[$key])) {
        $this->data['error_'.$key] = $this->error[$key];
      } else {
        $this->data['error_'.$key] = '';
      }
    }

    if (isset($this->error['warning'])) {
      $this->data['error_warning'] = $this->error['warning'];
    } else {
      $this->data['error_warning'] = '';
    }

    $this->data['breadcrumbs'] = array();

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_payment'),
      'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => '::'
    );

    $this->data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('payment/'.$this->payment_module_name, 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => '::'
    );

    $this->document->settitle($this->language->get('heading_title'));

    $set_language = array(
      'heading_title',

      'text_enabled',
      'text_disabled',
      'text_all_zones',
      'text_yes',
      'text_no',

      'entry_ripple_wallet',
      'entry_payment_description',
      'entry_payment_expiration',
      'entry_rpc_host',
      'entry_rpc_port',
      'entry_rpc_ssl',
      'entry_rpc_user',
      'entry_rpc_pass',
      'entry_cron_secret',
      'entry_verifing_status',
      'entry_confirmed_status',
      'entry_invalid_status',
      'entry_status',
      'entry_sort_order',

      'text_ripple_wallet',
      'text_payment_description',
      'text_payment_expiration',
      'text_rpc_host',
      'text_rpc_port',
      'text_rpc_ssl',
      'text_rpc_user',
      'text_rpc_pass',
      'text_cron_secret',

      'button_save',
      'button_cancel',

      'tab_general'
    );

    foreach ($set_language as $value) {
      $this->data[$value]  = $this->language->get($value);
    }

    $this->data['action'] = $this->url->link('payment/'.$this->payment_module_name, 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


    foreach ($post_check as $key => $value) {
      if (isset($this->request->post[$this->payment_module_name.'_'.$key])) {
        $this->data[$this->payment_module_name.'_'.$key] = $this->request->post[$this->payment_module_name.'_'.$key];
      } else {
        if (!$this->config->get($this->payment_module_name.'_'.$key)) {
          $this->data[$this->payment_module_name.'_'.$key] = $value;
        } else {
          $this->data[$this->payment_module_name.'_'.$key] = $this->config->get($this->payment_module_name.'_'.$key);
        }
      }
    }

    $this->template = 'payment/'.$this->payment_module_name.'.tpl';
    $this->children = array (
      'common/header',
      'common/footer'
    );

    $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  }

  private function validate() {
    if (!$this->user->hasPermission('modify', 'payment/'.$this->payment_module_name)) {
      $this->error['warning'] = $this->language->get('error_permission');
    }
    $post_check = array(
        'ripple_wallet',
        'payment_description',
        'payment_expiration',
        'rpc_host',
        'rpc_port',
        'rpc_ssl',
        'cron_secret'
      );
    foreach ($post_check as $value) {
      if (!$this->request->post[$this->payment_module_name.'_'.$value]) {
        $this->error[$value] = $this->language->get('error_'.$value);
      }
    }
    if (!$this->error) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}