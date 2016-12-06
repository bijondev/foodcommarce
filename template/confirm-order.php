<?php 
/*
Theme Name: Confirm Order
*/
get_header(); ?>
<div class="container margin-top-100">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="row">
		<div class="col-md-2">
		
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image-logo">
				<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>" class="img-rounded">
			</div>
		<?php endif; ?>
		</div>
		<div class="col-md-8">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<?php the_content(); ?>
		</div>
		

	</div>
<?php endwhile;	?>
</div>
<?php get_footer(); ?>