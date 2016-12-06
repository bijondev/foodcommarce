<?php
// Our custom post type function
function bms_create_posttype_restaurant() {

 register_post_type( 'restaurant',
 // CPT Options
  array(
   'labels' => array(
    'name' => __( 'Restaurants' ),
    'singular_name' => __( 'Restaurant' )
   ),
   'public' => true,
   'has_archive' => true,
   'rewrite' => array('slug' => 'restaurant'),
   'menu_icon' => 'dashicons-image-filter',
   'supports'           => array( 'title', 'editor', 'thumbnail' )
  )
 );
 flush_rewrite_rules( false );
}
// Hooking up our function to theme setup
add_action( 'init', 'bms_create_posttype_restaurant' );
/**
 * Register meta box(es).
 */
function bms_register_meta_boxes_restaurant_menu_catagory() {
    add_meta_box( 'meta-box-cat', __( 'Restaurant Menu Catagory', 'textdomain' ), 'bms_menu_cat_callback', 'restaurant' );
    add_meta_box( 'meta-box-item', __( 'Restaurant Menu Item', 'textdomain' ), 'bms_menu_item', 'restaurant' );
}
add_action( 'add_meta_boxes', 'bms_register_meta_boxes_restaurant_menu_catagory' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function bms_menu_cat_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
    $fooc_cat = json_decode(get_post_meta($post->ID, 'fooc_cat', true));
    //print_r($fooc_cat);
   ?>
   <div class="table-responsive">
   <input type="hidden" name="plugins-url" value="<?php echo plugins_url(); ?>/foodcommarce" id="plugins-url" >
   <button type="button" class="btn btn-primary btn-xs cat-add"> Add Catagory <i class="glyphicon glyphicon-plus"></i> </button>
   <input type="hidden" id="hidden-index" value="" >
      <table class="table table-hover">
      <?php if ($fooc_cat){ foreach ($fooc_cat as $key => $value){ ?>
        <?php
        $p = (object) $value;
        //$feat_image_url = wp_get_attachment_url( $p->cat_image, 'thumbnail' );
        $cat_image_url = wp_get_attachment_image_src( $p->cat_image, $size='thumbnail' );
      ?>
      <tr id="row-<?php echo $p->cat_order; ?>" data-catindex="<?php echo $p->cat_order; ?>">
      <td><input type="hidden" id="cat_uid_<?php echo $p->cat_order; ?>" data-catuid="<?php echo $p->cat_order; ?>" value="<?php echo $p->cat_id; ?>" name="cat_id[]" >
        <input type="text" name="cat_name[]" value="<?php echo $p->cat_name; ?>" placeholder="Catagory Name" data-catname="<?php echo $p->cat_order; ?>">
      </td>
      <td>
        <input type="text" name="cat_disc[]" placeholder="Discription" value="<?php echo $p->cat_disc; ?>" data-catdisc="<?php echo $p->cat_order; ?>">
      </td>
      <td>
        <input type="hidden" name="cat_image[]" data-catimg="<?php echo $p->cat_order; ?>" id="cat_image_<?php echo $p->cat_order; ?>" value="<?php echo $p->cat_image; ?>">
        <?php if($cat_image_url[0] !=""){ ?>
        <div id="image-box-<?php echo $p->cat_order; ?>" class="image-box"><img width="100" src="<?php echo $cat_image_url[0]; ?>">
          <div class="box-inner">
            <button class="btn btn-danger btn-xs del-cat-image" data-btndelimg="<?php echo $p->cat_order; ?>" type="button">
            <i class="glyphicon glyphicon-remove"></i></button>
          </div>
        </div>
        <?php } else { ?>
          <div id="image-box-<?php echo $p->cat_order; ?>" class="image-box"><img width="100" src="<?php echo plugins_url(); ?>/foodcommarce/img/no-image-available.jpg">
          <div class="box-inner">
            <button class="btn btn-danger btn-xs add-cat-image" data-btndelimg="<?php echo $p->cat_order; ?>" type="button">
            <i class="glyphicon glyphicon-plus"></i></button>
          </div>
        </div>
        <?php } ?>
      </td>
      <td>
        <div class="checkbox"><label><input type="checkbox" <?php if($p->cat_status=='active'){ echo 'checked';} ?> name="cat_status[]" value="active"> active</label></div>
      </td>
      <td><input type="text" class="order" name="cat_order[]" placeholder="order number" value="<?php echo $p->cat_order; ?>" data-catorder="<?php echo $p->cat_order; ?>"></td>
      <td><button class="btn btn-danger btn-xs del-cat-row" type="button" data-btnrmv="<?php echo $p->cat_order; ?>"> <i class="glyphicon glyphicon-remove"></i> </button>
      </td>
    </tr>
   <?php } } ?>
   </table>
   
   <table class="table table-hover">

   <tbody id="cat-append-table"></tbody>
   </table>
   </div>
   <?php
}
 
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wpdocs_save_meta_box( $post_id ) {
      if(isset($_POST['cat_name'])) {

        for($i=0;$i<count($_POST["cat_name"]);$i++)
          {
            $rows[] = [
                      'cat_id'=>$_POST['cat_id'][$i],
                      'cat_name' => $_POST['cat_name'][$i],
                      'cat_disc' => $_POST['cat_disc'][$i],
                      'cat_image' => $_POST['cat_image'][$i],
                      'cat_status' => $_POST['cat_status'][$i],
                      'cat_order' => $_POST['cat_order'][$i],
                  ];
            //$cat_row[] = "{'".$_POST["cat_name"][$i]."'".$_POST["cat_disc"][$i]."'".$_POST["cat_image"][$i]."'".$_POST["cat_status"][$i]."'}";
          }

        $cat_json=json_encode($rows);
      update_post_meta($post_id, 'fooc_cat', $cat_json);

    } else {
      delete_post_meta($post_id, 'fooc_cat');
    }
}
add_action( 'save_post', 'wpdocs_save_meta_box' );

function bms_menu_item( $post ){
 ?>
<div class="table-responsive">
<?php 
$fooc_cat = json_decode(get_post_meta($post->ID, 'fooc_cat', true));
//print_r($fooc_cat);
if ($fooc_cat){ foreach ($fooc_cat as $key => $value){
  $p = (object) $value;
    $item_catagories[]=[
                        'cat_id'=>$p->cat_id,
                        'cat_name'=> $p->cat_name,
                        ];
   
  } }
   $cat_cats=json_encode($item_catagories);
?>
   <input type='hidden' id="item_cats" name='item_cats' value='<?php echo $cat_cats; ?>'>


      <table class="table table-hover">
      <!--///////////////////////Existing ITEM///////////////////////////-->
            <?php 
            $bms_fc_item = json_decode(get_post_meta($post->ID, 'bms_fc_item', true));
            if ($fooc_cat){ foreach ($bms_fc_item as $key => $value){ ?>
        <?php
        $item = (object) $value;
        //$item_image_url = wp_get_attachment_url( $item->item_image, 'thumbnail' );
        $item_image_url = wp_get_attachment_image_src( $item->item_image, $size='thumbnail' );
      ?>
          <tr data-itemindex="<?php echo $item->item_order; ?>" id="item-row-<?php echo $item->item_order; ?>">
       <input type="hidden" name="item_id[]" value="<?php echo $item->item_id; ?>" data-itemuid="<?php echo $item->item_order; ?>" id="item_uid_<?php echo $item->item_order; ?>">
       <td><input type="text" name="item_name[]" placeholder="Item Name" value="<?php echo $item->item_name; ?>" data-itemname="<?php echo $item->item_order; ?>"></td>
       <td><input type="text" name="item_disc[]" placeholder="Discription" value="<?php echo $item->item_disc; ?>" data-itemdisc="<?php echo $item->item_order; ?>"></td>
       <td>
          <select name="item-cat[]" id="item-cat-<?php echo $item->item_order; ?>">
             <option>Select catagory</option>
             <?php 
              $fooc_cat = json_decode(get_post_meta($post->ID, 'fooc_cat', true));
              //print_r($fooc_cat);
              if ($fooc_cat){ foreach ($fooc_cat as $key => $value){
                $p = (object) $value;
                ?>
                <option <?php if($p->cat_id==$item->item_cat){ echo  "selected"; } ?> value="<?php echo $p->cat_id; ?>"><?php echo $p->cat_name; ?></option>
                <?php
                
                } }
                 
              ?>
          </select>
       </td>
       <td>
          <input type="hidden" name="item_image[]" value="<?php echo $item->item_image; ?>" data-itemimg="8" id="item_image_<?php echo $item->item_order; ?>">
          <?php if($item_image_url[0] !=""){ ?>
        <div id="item-image-box-<?php echo $item->item_order; ?>" class="image-box"><img width="100" src="<?php echo $item_image_url[0]; ?>">
          <div class="box-inner">
            <button class="btn btn-danger btn-xs del-item-image" data-btndelimg="<?php echo $item->item_order; ?>" type="button">
            <i class="glyphicon glyphicon-remove"></i></button>
          </div>
        </div>
        <?php } else { ?>
          <div id="item-image-box-<?php echo $item->item_order; ?>" class="image-box"><img width="100" src="<?php echo plugins_url(); ?>/foodcommarce/img/no-image-available.jpg">
          <div class="box-inner">
            <button class="btn btn-danger btn-xs add-item-image" data-btndelimg="<?php echo $item->item_order; ?>" type="button">
            <i class="glyphicon glyphicon-plus"></i></button>
          </div>
        </div>
        <?php } ?>

       </td>
       <td>
          <div class="checkbox"><label><input type="checkbox" <?php if($item->item_status=='active'){ echo 'checked';} ?> name="item_status[]" value="active"> active</label></div>
       </td>
       <td><input type="text" name="item_price[]" placeholder="Price" value="<?php echo $item->item_price; ?>" data-itemprice="<?php echo $item->item_order; ?>" class="item-price"></td>
       <td><input type="text" name="item_order[]" value="<?php echo $item->item_order; ?>" placeholder="order number" data-itemorder="<?php echo $item->item_order; ?>" class="order"></td>
       <td><button class="btn btn-danger btn-xs del-item-row" type="button" data-btnrmv="<?php echo $item->item_order; ?>"> <i class="glyphicon glyphicon-remove"></i> </button></td>
    </tr>
    <?php
      }
    }
    ?>
      <!--////////////////////END EXISTING ITEM////////////////////////////-->


   </table>
   
   <table class="table table-hover">
   <tbody id="item-append-table"></tbody>
   </table>
       <button type="button" class="btn btn-primary btn-xs item-add"> Add Menu Item <i class="glyphicon glyphicon-plus"></i> </button>
    <!--<form id="ff" action="">
    <input type="file" name="csv-menu" id="csvmenu" >
    <button type="button" id="upload-menu" class="btn btn-primary btn-xs"> Upload menu <i class="glyphicon glyphicon-plus"></i> </button>
    </form>-->
   </div>
 <?php
}
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function bms_save_menu_item_meta_box( $post_id ) {
      if(isset($_POST['item_name'])) {

        for($i=0;$i<count($_POST["item_name"]);$i++)
          {
            $rows[] = [
                      'item_id'=>$_POST['item_id'][$i],
                      'item_name' => $_POST['item_name'][$i],
                      'item_disc' => $_POST['item_disc'][$i],
                      'item_cat' => $_POST['item-cat'][$i],
                      'item_image' => $_POST['item_image'][$i],
                      'item_status' => $_POST['item_status'][$i],
                      'item_price' => $_POST['item_price'][$i],
                      'item_order' => $_POST['item_order'][$i],
                  ];
            //$cat_row[] = "{'".$_POST["cat_name"][$i]."'".$_POST["cat_disc"][$i]."'".$_POST["cat_image"][$i]."'".$_POST["cat_status"][$i]."'}";
          }

        $cat_json=json_encode($rows);
      update_post_meta($post_id, 'bms_fc_item', $cat_json);

    } else {
      delete_post_meta($post_id, 'bms_fc_item');
    }
}
add_action( 'save_post', 'bms_save_menu_item_meta_box' );
?>