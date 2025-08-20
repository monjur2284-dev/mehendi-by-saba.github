
<?php
/**
 * Plugin Name: Yoga bKash & Nagad Gateway + Orders Admin
 * Description: Adds bKash/Nagad WooCommerce payment gateways with a Cash-Out checkbox, admin Orders dashboard (CSV export, Invoice PDF via jsPDF), and email notifications.
 * Version: 1.0.0
 * Author: ChatGPT for Monjur
 * Text Domain: yoga-bkash-nagad
 */

if ( ! defined( 'ABSPATH' ) ) exit;
define('YBNG_PATH', plugin_dir_path(__FILE__));
define('YBNG_URL', plugin_dir_url(__FILE__));

// Settings
add_action('admin_menu', function(){
  add_menu_page('Yoga Payments','Yoga Payments','manage_options','ybng-settings','ybng_settings_page','dashicons-feedback',56);
  add_submenu_page('ybng-settings','Orders Dashboard','Orders Dashboard','manage_woocommerce','ybng-orders','ybng_orders_page');
});

function ybng_settings_page(){
  if(isset($_POST['ybng_save'])){
    update_option('ybng_phone', sanitize_text_field($_POST['ybng_phone']));
    update_option('ybng_bkash_merchant', sanitize_text_field($_POST['ybng_bkash_merchant']));
    update_option('ybng_nagad_merchant', sanitize_text_field($_POST['ybng_nagad_merchant']));
    update_option('ybng_testmode', isset($_POST['ybng_testmode']) ? 'yes':'no');
    echo '<div class="updated"><p>Saved.</p></div>';
  }
  $phone = get_option('ybng_phone','01797850441');
  $bkash = get_option('ybng_bkash_merchant','');
  $nagad = get_option('ybng_nagad_merchant','');
  $test = get_option('ybng_testmode','yes');
  echo '<div class="wrap"><h1>Yoga Payments Settings</h1><form method="post">';
  echo '<table class="form-table"><tr><th>Default Phone</th><td><input name="ybng_phone" value="'.esc_attr($phone).'" class="regular-text"/></td></tr>';
  echo '<tr><th>bKash Merchant ID</th><td><input name="ybng_bkash_merchant" value="'.esc_attr($bkash).'" class="regular-text"/></td></tr>';
  echo '<tr><th>Nagad Merchant ID</th><td><input name="ybng_nagad_merchant" value="'.esc_attr($nagad).'" class="regular-text"/></td></tr>';
  echo '<tr><th>Test Mode</th><td><label><input type="checkbox" name="ybng_testmode" '.checked($test,'yes',false).'/> Enable Sandbox (simulated)</label></td></tr></table>';
  submit_button('Save','primary','ybng_save');
  echo '</form></div>';
}

// Gateways
add_filter('woocommerce_payment_gateways', function($gws){
  require_once YBNG_PATH.'includes/class-gateway-bkash.php';
  require_once YBNG_PATH.'includes/class-gateway-nagad.php';
  $gws[] = 'WC_Gateway_bKash_Demo';
  $gws[] = 'WC_Gateway_Nagad_Demo';
  return $gws;
});

// Add Cash-Out checkbox fee
add_action('woocommerce_review_order_before_payment', function(){
  echo '<div style="margin:12px 0"><label><input type="checkbox" id="ybng_cashout" /> Cash Out (bKash/Nagad) +৳15</label></div>';
});
add_action('wp_footer', function(){
  if( is_checkout() ){
    echo "<script>
    document.addEventListener('change', function(e){
      if(e.target && e.target.id==='ybng_cashout'){
        jQuery.post('".admin_url('admin-ajax.php')."', {
          action:'ybng_cashout_fee', add: e.target.checked ? 1 : 0
        }, function(){ jQuery('body').trigger('update_checkout'); });
      }
    });
    </script>";
  }
});
add_action('wp_ajax_ybng_cashout_fee','ybng_cashout_fee_cb');
add_action('wp_ajax_nopriv_ybng_cashout_fee','ybng_cashout_fee_cb');
function ybng_cashout_fee_cb(){
  WC()->session->set('ybng_cashout', isset($_POST['add']) && $_POST['add']=='1');
  wp_die();
}
add_action('woocommerce_cart_calculate_fees', function(){
  if( WC()->session && WC()->session->get('ybng_cashout') ){
    WC()->cart->add_fee('Cash Out Fee', 15);
  }
});

// Orders Dashboard
function ybng_orders_page(){
  if( ! class_exists('WC_Order_Query') ){ echo '<div class="wrap"><h1>WooCommerce required</h1></div>'; return; }
  echo '<div class="wrap"><h1>Orders Dashboard</h1>';
  echo '<p><a class="button button-primary" href="'.esc_url(admin_url('admin.php?page=ybng-orders&export=csv')).'">Export CSV</a></p>';
  if( isset($_GET['export']) && $_GET['export']==='csv' ){
    $orders = wc_get_orders(['limit'=>-1, 'orderby'=>'date', 'order'=>'DESC']);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="orders.csv"');
    $out = fopen('php://output','w');
    fputcsv($out, ['Order','Date','Customer','Email','Total','Status','Payment']);
    foreach($orders as $o){
      fputcsv($out, [$o->get_id(), $o->get_date_created(), $o->get_formatted_billing_full_name(), $o->get_billing_email(), $o->get_total(), $o->get_status(), $o->get_payment_method_title()]);
    }
    fclose($out);
    exit;
  }
  echo '<table class="widefat"><thead><tr><th>ID</th><th>Date</th><th>Customer</th><th>Total</th><th>Status</th><th>Invoice</th></tr></thead><tbody>';
  $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
  $ppp = 20;
  $q = new WC_Order_Query(['limit'=>$ppp,'paginate'=>true,'page'=>$paged,'orderby'=>'date','order'=>'DESC']);
  $results = $q->get_orders();
  foreach($results->orders as $o){
    $invoice_link = admin_url('admin.php?page=ybng-orders&invoice='.$o->get_id());
    echo '<tr><td>'.$o->get_id().'</td><td>'.$o->get_date_created().'</td><td>'.$o->get_formatted_billing_full_name().'</td><td>'.$o->get_total().'</td><td>'.$o->get_status().'</td><td><a href="'.$invoice_link.'">View</a></td></tr>';
  }
  echo '</tbody></table>';
  // Pagination
  $total_pages = $results->max_num_pages;
  if($total_pages>1){
    echo '<p>';
    for($i=1;$i<=$total_pages;$i++){
      $url = add_query_arg('paged',$i, admin_url('admin.php?page=ybng-orders'));
      echo '<a class="button'.($i==$paged?' button-primary':'').'" style="margin-right:6px" href="'.$url.'">'.$i.'</a>';
    }
    echo '</p>';
  }
  // Invoice view
  if( isset($_GET['invoice']) ){
    $order = wc_get_order(intval($_GET['invoice']));
    if($order){
      echo '<hr/><h2>Invoice #'.$order->get_id().'</h2>';
      echo '<div id="ybng-invoice" style="background:#fff;padding:20px;max-width:800px">';
      echo '<h3>Monjur Yoga</h3><p>College Road, Rangpur • 01797850441</p>';
      echo '<p><strong>Bill To:</strong> '.$order->get_formatted_billing_full_name().' • '.$order->get_billing_email().'</p>';
      echo '<table class="widefat"><thead><tr><th>Item</th><th>Qty</th><th>Total</th></tr></thead><tbody>';
      foreach($order->get_items() as $item){
        echo '<tr><td>'.$item->get_name().'</td><td>'.$item->get_quantity().'</td><td>'.$item->get_total().'</td></tr>';
      }
      echo '</tbody></table>';
      echo '<p><strong>Grand Total:</strong> '.$order->get_total().'</p>';
      echo '</div>';
      echo '<p><button class="button" id="ybng-pdf">Download PDF</button></p>';
      echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
      <script>
        document.getElementById('"ybng-pdf"').addEventListener('click', function(){
          const { jsPDF } = window.jspdf; const doc = new jsPDF('p','pt','a4');
          doc.html(document.getElementById('"ybng-invoice"'), { callback: function (d) { d.save('"invoice-"'+". $order->get_id() ."+'.pdf'); } });
        });
      </script>";
    }
  }
  echo '</div>';
}

// Email notification on status change
add_action('woocommerce_order_status_changed', function($order_id, $old_status, $new_status){
  $order = wc_get_order($order_id);
  $to = get_option('admin_email');
  $subject = 'Order #'.$order_id.' status: '.$new_status;
  $body = 'Order total: '.$order->get_total().' | Customer: '.$order->get_formatted_billing_full_name();
  wp_mail($to, $subject, $body);
}, 10, 3);
