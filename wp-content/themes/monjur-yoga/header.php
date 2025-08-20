
<?php wp_head(); ?>
<header class="site-header">
  <div class="container" style="display:flex;justify-content:space-between;align-items:center;height:64px">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <span class="dot"></span> Monjur Yoga
    </a>
    <nav class="nav">
      <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'items_wrap'=>'%3$s']); ?>
      <a href="<?php echo wc_get_page_permalink('shop'); ?>">Shop</a>
      <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Cart (<?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>)</a>
      <a class="btn" href="#contact">Contact</a>
    </nav>
  </div>
</header>
