<div class="col-md-3">
			<ul>
			<?php 
              $fooc_cat = json_decode(get_post_meta($post->ID, 'fooc_cat', true));
              //print_r($fooc_cat);
              if ($fooc_cat){ foreach ($fooc_cat as $key => $value){
                $p = (object) $value;
                ?>
                    <li><a href="#<?php echo $p->cat_id; ?>"><?php echo $p->cat_name; ?></a></li>
                <?php
                 
                } }
                 
              ?>
				
			</ul>
		</div>
		<div class="col-md-6">
			
			<?php 
              $fooc_cat = json_decode(get_post_meta($post->ID, 'fooc_cat', true));
              //print_r($fooc_cat);
              if ($fooc_cat){ foreach ($fooc_cat as $key => $value){
                $p = (object) $value;
                $feat_image_url = wp_get_attachment_url( $p->cat_image, 'thumbnail' );
                ?>
			<div class="menu-details" id="<?php echo $p->cat_id; ?>">
				<div class="row">
					<div class="col-md-12">
						<h3><?php echo $p->cat_name; ?></h3>
						<?php if($feat_image_url) { ?><div class="cat-image"><img src="<?php echo $feat_image_url; ?>"></div> <?php } ?>
					</div>
				</div>
				<?php 
            $bms_fc_item = json_decode(get_post_meta($post->ID, 'bms_fc_item', true));
            if ($fooc_cat){ foreach ($bms_fc_item as $key => $value){ ?>
		        <?php
		        $item = (object) $value;
		        $item_image_url = wp_get_attachment_image_src( $item->item_image, $size='thumbnail' );
		        if($item_image_url[0]==""){
		        	$item_image=plugin_dir_url( __FILE__ )."img/no-image-item.png";
		        }
		        else{
		        	$item_image=$item_image_url[0];
		        }
		        if($p->cat_id==$item->item_cat){
		      ?>
				<div class="row">
					<div class="col-xs-2 col-sm-2 col-md-2 ">
						<div class="food-image">
							<img src="<?php echo $item_image; ?>" class="img-rounded" />
						</div>
					</div>
				<div class="col-md-8 col-xs-8 col-sm-8 ">
					<div class="item-content">
						<h4><?php echo $item->item_name; ?></h4>
						<p><?php echo $item->item_disc; ?></p>
					</div>
					
				</div>
				<div class="col-md-2 col-xs-2 col-sm-2 ">
					<span class="item-price"><?php echo $item->item_price; ?> Tk.
					<i id="data-item-price-<?php echo $item->item_id; ?>" data-item-id="<?php echo $item->item_id; ?>" data-item-name-<?php echo $item->item_id; ?>="<?php echo $item->item_name; ?>" data-item-price-<?php echo $item->item_id; ?>="<?php echo $item->item_price; ?>" class="fa fa-plus-circle pull-right size2 btn-cart"></i></span>

				</div>
				</div>
				<?php } } } ?>
			
			</div>
                
			<?php }} ?>
              
		</div>
		
	</div>
<?php endwhile;	?>