<?php 
class ModelPaymentRipple extends Model {
  public function getMethod($address) {
    $this->load->language('payment/ripple');
    
    if ($this->config->get('ripple_status')) {
      $status = TRUE;
    } else {
      $status = FALSE;
    }
    
    $method_data = array();
    
    if ($status) {  
      $method_data = array( 
        'code'        => 'ripple',
        'title'       => $this->language->get('text_title'),
        'sort_order'  => $this->config->get('ripple_sort_order'),
      );
    }
   
    return $method_data;
  }
}
?>