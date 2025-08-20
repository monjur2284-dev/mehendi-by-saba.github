
<footer class="footer">
  <div class="container" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px">
    <div>
      <h4>Monjur Yoga</h4>
      <p>College Road, Rangpur • 01797850441</p>
      <p>Email: hello@example.com</p>
    </div>
    <div>
      <h4>Menu</h4>
      <ul>
        <li><a href="/about">About</a></li>
        <li><a href="/blog">Blog</a></li>
        <li><a href="<?php echo wc_get_page_permalink('shop'); ?>">Shop</a></li>
      </ul>
    </div>
    <div>
      <h4>Payments</h4>
      <p>bKash • Nagad • Cash on Delivery</p>
    </div>
  </div>
  <div class="container" style="margin-top:14px;border-top:1px solid #1f2937;padding-top:14px;font-size:14px">
    © <?php echo date('Y'); ?> Monjur Yoga. All rights reserved.
  </div>
</footer>
<?php wp_footer(); ?>
</body></html>
