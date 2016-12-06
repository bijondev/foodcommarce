<?php get_header(); ?>
<div class="container margin-top-120">

	<?php while ( have_posts() ) : the_post(); ?>
	<div class="row">
	<div class="col-md-9">
		<div class="row">
			<!-- <div class="col-md-4  col-xs-6 col-sm-3 ">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image-logo">
				<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>" class="img-rounded">
			</div>
		<?php endif; ?>
		</div> -->
		<div class="col-md-8">
		<?php
		$_SESSION['location']=$location=$_POST['location'];
		$_SESSION['select_menu']=$rest_menu=$_POST['rest_menu'];
		?>
			<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<div class="mobile-cart" > <button class="btn btn-default crt-btn" type="submit"><i class="fa fa-shopping-cart" ></i></button> </div>
		</div>
		</div>
		<div class="row">
		<?php include( plugin_dir_path( __FILE__ ) . 'tp-menu.php'); ?>

		</div>	
	</div>	
		<div class="col-md-3 pull-right menu-cart">
			
			<!--****************************ORDER CART*****************************************-->
			<div class="cart__inner" data-spy="affix" data-offset-top="0" >
                    <div class="cart__content">
                        <div class="cart__header">
                            <div class="cart__header__title">
                                Your order
                            </div>
                        </div>
                        <form action="<?php echo home_url(); ?>/order-page" method="post">
                            <div class="row">
                                <div class="col-md-12" id="cart-box" >

                                </div>
                            </div>
                            <?php ?>


                            <input type="hidden" name="res_id" value="<?php echo $post->ID; ?>" />

                            <input type="hidden" id="vat_included" name="vat_included" value="0" >
                            <input type="hidden" id="vat_visibal" name="vat_visibal" value="1" >
                            <!----<input type="hidden" id="vat_diesable" name="vat_diesable" value="" >-->
                            <input type="hidden" id="delivery_fee" name="delivery_fee" value="50" >
                            <input type="hidden" id="service_fee" name="service_fee" value="100" >
                            <input type="hidden" id="min_order" name="min_order" value="300" >

                            <div class="col-md-12" id="cart-total-price" >
                            <div class="cart__empty cart__empty--inside">
                                <ul class="cart__empty__elements" style="list-style-type: none">
                                    <li class="cart__empty__element cart__empty__element--minimum-delivery-fee">
                                        
                                    </li>
                                    <li class="cart__empty__element cart__empty__element--minimum-delivery-time">
                                        Est. Delivery Time: 60 Min.
                                    </li>
                                    <<!-- li class="cart__empty__element cart__empty__element--minimum-order-amount">
                                        Delivery Minimum: 300 Tk
                                    </li> -->
                                </ul>
                            </div>
                            </div>
                            <div class="cart__checkout">
                                <button id="btn-checkout" type="submit" class="btn btn-success process-to-checkout">
                                    Proceed to checkout
                                </button>
                        </div>
                        </form>
                    </div>
                </div>
                <!--////////////////////////////ORDER CART////////////////////////////////////-->
		</div>
	</div>
	<?php endwhile;	?>
<?php get_footer(); ?>