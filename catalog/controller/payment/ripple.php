<?php
class ControllerPaymentRipple extends Controller {
  private $payment_module_name = 'ripple';
  protected function index() {
    $this->language->load('payment/'.$this->payment_module_name);
    $this->data['button_ripple_confirm'] = $this->language->get('button_ripple_confirm');
    $this->data['action_ripple_comfirm'] = HTTPS_SERVER . 'index.php?route=payment/ripple/checkout&order_id=' . $this->session->data['order_id'];
    //
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ripple.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/payment/ripple.tpl';
    } else {
      $this->template = 'default/template/payment/ripple.tpl';
    } 

    $this->render();

  }

  public function httpBuildQuery3986(array $params, $sep = '&')
  {
    $parts = array();
    foreach ($params as $key => $value) {
        $parts[] = sprintf('%s=%s', $key, rawurlencode($value));
    }
    return implode($sep, $parts);
  }
  // Check 
  public function checkout() {
    if (!isset($_GET['order_id'])) {
      exit('Access Denied');
    }
    $order_id = $_GET['order_id'];
    // Order status for Opencart
    $order_status = array(
      "Canceled"        => 7,
      "Canceled_Reversal"   => 9,
      "Chargeback"      => 13,
      "Complete"        => 5,
      "Denied"      => 8,
      "Failed"          => 10 ,
      "Pending"           => 1,
      "Processing"       => 2,
      "Refunded"            => 11,
      "Reversed"       => 12,
      "Shipped"         => 3
    );
    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($order_id);

    $urlFields = array();

    $urlFields['to'] = $this->config->get($this->payment_module_name.'_ripple_wallet');

    $urlFields['amount'] = round($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false),2) . '/' . $order_info['currency_code'];

    $urlFields['dt'] = $order_info['order_id'];

    $urlFields['invoiceid'] = $order_info['order_id'];

    $urlFields['name'] = $this->config->get('config_name');

    $urlFields['return_url'] = $this->url->link('checkout/success');

    $url   = "https://ripple.com//send";
    $query = $this->httpBuildQuery3986($urlFields);

    $jump_url = $url . '?' . $query;

    $this->model_checkout_order->confirm($order_id, $order_status['Pending']);

    header('location:'.$jump_url);

  }

  private function getRecentOrders() {
    $db = $this->registry->get('db');
    $query = $db->query("SELECT * FROM " . DB_PREFIX . "order" . " WHERE payment_method='Ripple Payment' && order_status_id=1");
    return $query;
  }

  public function getAccountInfo($account)
  {
    return $this->getClient()->account_info(array('account' => $account));
  }

  private function getAccountTx($account, $ledger_min = -1, $ledger_max = -1, $descending = false) {
    return $this->getClient()->account_tx(array(
      'account' => $account,
      'ledger_min' => $ledger_min,
      'ledger_max' => $ledger_max,
      'descending' => $descending
    ));
  }

  protected function getClient() {
    if (!$this->_client) {
    $ptcl = $this->config->get($this->payment_module_name.'_rpc_ssl') == 1 ? 'https://' : 'http://';
    $user = $this->config->get($this->payment_module_name.'_rpc_user');
    $pass = $this->config->get($this->payment_module_name.'_rpc_pass');
    $host = $this->config->get($this->payment_module_name.'_rpc_host');
    $port = $this->config->get($this->payment_module_name.'_rpc_port');

    $user_pass = '';
    if ($user && $pass) {
      $user_pass = $user . ':' . $pass . '@';
    }
      $uri = $ptcl . $user_pass . $host . ':' . $port . '/';
      try {
        require_once('JsonRpcClient.php');
        $this->_client = new Ripple_JsonRPCClient($uri);
      } catch (Exception $e) {
        throw new Exception('JSON-RPC could not be reached: ' . $e->getMessage());
      }
    }
  return $this->_client;
  }

  public function cronJob() {
    if (!isset($_GET['secret']) || $_GET['secret'] != $this->config->get($this->payment_module_name.'_cron_secret')) {
      exit('Access Denied!');
    }

    $this->load->model('checkout/order');
    $orders = (array) $this->getRecentOrders();
    if (isset($orders['rows'])) {
      $orders = $orders['rows'];
    } else {
      exit();
    }

    $order_status = array(
      "Canceled"        => 7,
      "Canceled_Reversal"   => 9,
      "Chargeback"      => 13,
      "Complete"        => 5,
      "Denied"      => 8,
      "Failed"          => 10 ,
      "Pending"           => 1,
      "Processing"       => 2,
      "Refunded"            => 11,
      "Reversed"       => 12,
      "Shipped"         => 3
    );

    $ledger_min = $this->config->get($this->payment_module_name.'_json_ledger');
    if (!$ledger_min) {
      $ledger_min = -1;
      $this->config->set($this->payment_module_name.'_json_ledger',-1);
    }

    if ($ledger_min > 10) {
      $ledger_min -= 10;
    }

    $account_tx = $this->getAccountTx($this->config->get($this->payment_module_name.'_ripple_wallet'), $ledger_min);
    if (!isset($account_tx['status']) || $account_tx['status'] != 'success') {
      return false;
    }

    $ledger_max = $account_tx['ledger_index_max'];
    if (!isset($account_tx['transactions']) || empty($account_tx['transactions'])) {
      $this->config->set($this->payment_module_name.'_json_ledger', $ledger_max);
      return true;
    }

    $txs = array();
    foreach ($account_tx['transactions'] as $key => $tx) {
      if (isset($tx['tx']['DestinationTag']) && $tx['tx']['DestinationTag'] > 0) {
        $dt = $tx['tx']['DestinationTag'];
        $txs[$dt] = $tx['tx'];
      }
    }

    foreach ($orders as $order_obj) {
      $id = $order_obj['order_id'];
      if (isset($txs[$id])) {
        $order = $this->model_checkout_order->getOrder($id);
        $transactionId = $txs[$id]['hash'];

        // Build order history note with link to sender
        $note = sprintf('Paid: %s/%s', $txs[$id]['Amount']['value'], $txs[$id]['Amount']['currency']);
        $note .= "\n" . sprintf('Ledger: %s', $txs[$id]['inLedger']);
        $note .= "\n" . sprintf('Account: https://ripple.com/graph/#%s', $txs[$id]['Account']);

        if ($txs[$id]['Amount']['value'] == (string) round($this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false),2) && $txs[$id]['Amount']['currency'] == $order['currency_code']) {
          $this->model_checkout_order->update($id, $order_status['Canceled'], $note, true);
          var_dump($id.'Canceled');
        }
        else {
          $this->model_checkout_order->update($id, $order_status['Failed'], $note, true);
          var_dump($id.'Failed');
        }
      }
    }
    $this->config->set($this->payment_module_name.'_json_ledger', $ledger_max);
  }
}

?>