
<?php /* Template Name: Home (Yoga Landing) */ get_header(); ?>
<main>
  <section class="hero" id="hero">
    <div class="container wrap">
      <div>
        <div class="badge">New • Yoga Essentials</div>
        <h1>Find Your Flow — Yoga Gear that Feels Good</h1>
        <p>Minimal, comfy, and performance-ready. Curated for your daily practice.</p>
        <div style="display:flex;gap:12px;margin-top:12px">
          <a class="btn accent" href="<?php echo wc_get_page_permalink('shop'); ?>">Shop Yoga Collection</a>
          <a class="btn ghost" href="#contact">Contact</a>
        </div>
        <div class="notice" style="margin-top:14px">
          bKash/Nagad quick checkout available. Cash-out checkbox at payment step.
        </div>
      </div>
      <img src="<?php echo get_template_directory_uri(); ?>/assets/hero-yoga.jpg" alt="Yoga hero">
    </div>
  </section>

  <section class="section" id="collections">
    <div class="container">
      <h2>Yoga Collection</h2>
      <div class="grid">
        <?php
        $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>false,'number'=>3]);
        foreach($cats as $cat){
          $thumb_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
          $img = $thumb_id ? wp_get_attachment_url($thumb_id) : get_template_directory_uri().'/assets/cat.jpg';
          echo '<a class="card" href="'.get_term_link($cat).'">';
          echo '<img src="'.$img.'" alt="'.esc_attr($cat->name).'">';
          echo '<div class="p"><h3>'.$cat->name.'</h3><p>'.wp_kses_post($cat->description).'</p></div>';
          echo '</a>';
        }
        ?>
      </div>
    </div>
  </section>

  <section class="section" id="products">
    <div class="container">
      <h2>Products Collection</h2>
      <div class="grid">
        <?php
        echo do_shortcode('[products limit="6" columns="3" orderby="date"]');
        ?>
      </div>
    </div>
  </section>

  <section class="section" id="features">
    <div class="container">
      <h2>Why Shop With Us</h2>
      <div class="grid">
        <div class="card"><div class="p"><h3>Breathable Fabrics</h3><p>Soft, sweat-wicking, and durable for daily practice.</p></div></div>
        <div class="card"><div class="p"><h3>Free Returns</h3><p>Hassle-free 7-day returns on unused items.</p></div></div>
        <div class="card"><div class="p"><h3>bKash/Nagad Pay</h3><p>Local payment, instant confirmation (demo).</p></div></div>
      </div>
    </div>
  </section>

  <section class="section" id="image-with-text">
    <div class="container">
      <div class="card" style="display:grid;grid-template-columns:1fr 1.2fr;gap:0">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/mat.jpg" alt="Mat">
        <div class="p">
          <h2>Grip &amp; Flow</h2>
          <p>Our ProGrip mat keeps you steady from sun salutations to savasanas.</p>
          <a class="btn" href="<?php echo wc_get_page_permalink('shop'); ?>">Explore Mats</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="tabs">
    <div class="container">
      <h2>Shop by Need</h2>
      <div class="tabs" id="shop-tabs">
        <button class="tab active" data-filter="new">New</button>
        <button class="tab" data-filter="bestseller">Bestsellers</button>
        <button class="tab" data-filter="budget">Budget Picks</button>
      </div>
      <div id="tab-products"><?php echo do_shortcode('[products limit="3" columns="3" visibility="visible"]'); ?></div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="banner">
        <div>
          <h3 style="margin:0">Summer Yoga Sale</h3>
          <p style="margin:6px 0 0">Up to 30% off selected sets.</p>
        </div>
        <a class="btn accent" href="<?php echo wc_get_page_permalink('shop'); ?>">Shop Deals</a>
      </div>
    </div>
  </section>

  <section class="section" id="blog">
    <div class="container">
      <h2>From the Blog</h2>
      <div class="grid">
        <?php
        $posts = get_posts(['numberposts'=>3]);
        foreach($posts as $p){
          $img = get_the_post_thumbnail_url($p->ID,'large') ?: get_template_directory_uri().'/assets/blog.jpg';
          echo '<a class="card" href="'.get_permalink($p).'"><img src="'.$img.'" alt=""><div class="p"><h3>'.$p->post_title.'</h3><p>'.wp_trim_words($p->post_content, 18).'</p></div></a>';
        }
        ?>
      </div>
    </div>
  </section>

  <section class="section" id="testimonials">
    <div class="container">
      <h2>Testimonials</h2>
      <div class="grid">
        <div class="card"><div class="p"><p>"Quality is top-notch. Payment was super easy."</p><strong>- Ayesha</strong></div></div>
        <div class="card"><div class="p"><p>"The mat changed my practice—great grip!"</p><strong>- Rafi</strong></div></div>
        <div class="card"><div class="p"><p>"Fast delivery and helpful support."</p><strong>- Tania</strong></div></div>
      </div>
    </div>
  </section>

  <section class="section" id="contact">
    <div class="container">
      <h2>Contact</h2>
      <form class="contact-form" method="post" action="#contact">
        <input class="input" name="name" placeholder="Your Name" required/>
        <input class="input" name="email" type="email" placeholder="Email" required/>
        <input class="input" name="phone" placeholder="Phone"/>
        <textarea name="message" placeholder="Message"></textarea>
        <button class="btn">Send</button>
      </form>
      <?php if($_SERVER['REQUEST_METHOD']==='POST'){ echo '<p class="notice">Thanks! We will contact you shortly.</p>'; } ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
