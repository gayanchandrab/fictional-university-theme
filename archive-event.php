<?php 
  get_header();
  pageBanner( array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world.'
  ));
?>

<div class="container container--narrow page-section">

<?php 
    if( have_posts() ){
        while( have_posts() ){
            the_post();
            get_template_part('template-parts/event');
        }

    echo paginate_links();
  }
?>
<hr class="section-break">

<p>Looking for a recap of our past events? Check out our <a href="<?php echo site_url("/past-events"); ?>">past events archive</a>.</p>

</div>

<?php
  get_footer();
?>