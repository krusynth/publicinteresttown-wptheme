<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package publicinteresttown
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
  <section class="jobs-rss">
    <h2>Jobs</h2>
    <?php pit_jobs_feed(); ?>
    <div class="notice">
      All job listings are sourced from Public Interest Town members.
    </div>
    <a class="button" href="https://mastodon.publicinterest.town/@jobs">View More</a>
  </section>
</aside><!-- #secondary -->
