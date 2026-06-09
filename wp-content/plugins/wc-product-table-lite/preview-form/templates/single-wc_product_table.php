<?php
/**
 * Template Name: Product Table Full Width
 * Template Post Type: wc_product_table
 * 
 * A full-width template for displaying product tables
 */

get_header();
?>

<style>
  .wcpt-full-width-template {
    margin: 0;
    margin: 50px auto;
    padding: 0;
    width: 95%;
    max-width: var(--wcpt-preview-template-max-width, 1400px);
  }

  .wcpt-full-width-template .content-area {
    float: none;
    width: 100%;
    max-width: 100%;
    margin: 0;
    padding: 0;
  }

  .wcpt-full-width-template .site-main {
    margin: 0;
    padding: 0;
  }

  .wcpt-full-width-template .entry-content {
    margin: 0;
    padding: 0;
  }

  /* Hide sidebar and comments */
  .wcpt-full-width-template .sidebar,
  .wcpt-full-width-template .comments-area {
    display: none;
  }

  /* Responsive adjustments */
  @media screen and (max-width: 768px) {
    .wcpt-full-width-template {
      margin: 30px auto;
      padding: 0 15px;
      width: 95%;
    }
  }

  @media screen and (max-width: 480px) {
    .wcpt-full-width-template {
      margin: 20px auto;
      padding: 0 10px;
      width: 100%;
    }
  }
</style>

<script>
  jQuery(document).ready(function ($) {
    if ($('body').hasClass('wcpt-preview-template')) {
      $('.wcpt-full-width-template').parents().css({
        width: '100%',
        maxWidth: '100%',
        minWidth: '100%',
        margin: '0 auto',
        padding: '0',
        display: 'block',
      });
    }
  });
</script>

<div class="wcpt-full-width-template">
  <div id="primary" class="content-area">
    <main id="main" class="site-main">
      <?php
      while (have_posts()):
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
          </header>
          <div class="entry-content">
            <?php
            the_content();
            ?>
          </div>
        </article>
        <?php
      endwhile;
      ?>
    </main>
  </div>
</div>

<?php
get_footer();