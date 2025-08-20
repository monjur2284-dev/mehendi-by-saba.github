
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Gateway_Nagad_Demo extends WC_Payment_Gateway {
  public function __construct(){
    $this->id                 = 'ybng_nagad';
    $this->method_title       = 'Nagad (Demo)';
    $this->method_description = 'Demo Nagad gateway with Sandbox toggle and Cash-Out fee checkbox.';
    $this->icon               = YBNG_URL.'assets/nagad.png';
    $this->has_fields         = true;
    $this->supports           = ['products'];
    $this->init_form_fields();
    $this->init_settings();
    $this->title = $this->get_option('title', 'Nagad');
    $this->enabled = $this->get_option('enabled', 'yes');
    add_action('woocommerce_update_options_payment_gateways_'.$this->id, [$this,'process_admin_options']);
  }
  public function init_form_fields(){
    $this->form_fields = [
      'enabled' => ['title'=>'Enable','type'=>'checkbox','label'=>'Enable Nagad','default'=>'yes'],
      'title' => ['title'=>'Title','type'=>'text','default'=>'Nagad']
    ];
  }
  public function payment_fields(){
    $phone = esc_attr( get_option('ybng_phone','01797850441') );
    echo '<p>Pay with Nagad. Test Mode: <strong>'.(get_option('ybng_testmode','yes')==='yes'?'ON':'OFF').'</strong></p>';
    echo '<p><label>Phone <input name="ybng_phone" value="'.$phone.'" required></label></p>';
    echo '<p><label>Transaction ID <input name="ybng_trx" placeholder="eg. TX12345" required></label></p>';
    echo '<p><small>Demo only. Implement real API calls with your credentials in includes/api-nagad.php.</small></p>';
  }
  public function validate_fields(){
    if( empty($_POST['ybng_phone']) || empty($_POST['ybng_trx']) ){
      wc_add_notice('Phone and Transaction ID are required','error'); return false;
    }
    return true;
  }
  public function process_payment($order_id){
    $order = wc_get_order($order_id);
    // DEMO: mark as processing; in real-case, verify trx via Nagad API.
    $order->payment_complete();
    WC()->cart->empty_cart();
    return ['result'=>'success','redirect'=>$this->get_return_url($order)];
  }
}
