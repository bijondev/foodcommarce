<?php
// Our custom post type function
function bms_create_posttype_orders() {

 register_post_type( 'orders',
 // CPT Options
  array(
   'labels' => array(
    'name' => __( 'Orders' ),
    'singular_name' => __( 'Orders' )
   ),
   'public' => true,
   'has_archive' => true,
   'menu_icon' => '',
   'show_ui'               => true,
   'publicaly_queryable' => false,
   //'show_in_menu'          => 'fc-order-dashboad',
   'capabilities' => array(
    //'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
  ),
   'supports'           => array( 'title', 'thumbnail', 'comments' )
  )
 );

}
// Hooking up our function to theme setup
add_action( 'init', 'bms_create_posttype_orders' );


/**********************COLUMS****************************/
add_filter('manage_edit-orders_columns', 'my_columns');
function my_columns($columns) {
    $columns['deliverytime'] = 'Delivery Time';
    $columns['phone'] = 'phone';
    $columns['status'] = 'status';
    return $columns;
}
add_action( 'manage_orders_posts_custom_column', 'my_manage_movie_columns', 10, 2 );
function my_manage_movie_columns( $columns, $post_id ) {
  global $post;

  switch( $columns ) {
    case 'deliverytime' :
      $bms_fc_order_delivery_time = get_post_meta( $post_id, 'bms_fc_order_delivery_time', true );
      if ( empty( $bms_fc_order_delivery_time ) )
        echo __( 'Unknown' );
      else
        printf( $bms_fc_order_delivery_time );
      break;
    case 'phone' :
      $phone = get_post_meta( $post_id, 'bms_fc_order_phone', true );
      if ( !empty( $phone ) ) {
        echo $phone;     
      }
      else {
        _e( 'No phone' );
      }
      break;
      case 'status' :
      $status = get_post_meta( $post_id, 'bms_fc_order_status', true );
      if ( !empty( $status ) ) {
        echo $status;     
      }
      else {
        _e( '<span class="label label-danger">New</span>' );
      }
      break;
    default :
      break;
  }
}
/************************END COLUMS**********************/
function bms_fc_slideshow_redirect() {
    global $wp_query;

    // redirect from 'slideshow' CPT to home page
    if ( is_archive('orders') || is_singular('orders') ) :
        $url   = get_bloginfo('url');

        wp_redirect( esc_url_raw( $url ), 301 );
        exit();
    endif;
}

add_action ( 'template_redirect', 'bms_fc_slideshow_redirect', 1);

function bms_register_meta_boxes_order_items() {
    add_meta_box( 'meta-box-items-order', __( 'Order Items', 'bms-fc-order-items' ), 'bms_order_items', 'orders');
    add_meta_box( 'meta-box-item', __( 'Delivery Information', 'bms-fc-order-delivery-information' ), 'bms_fc_delivery_info', 'orders' );
}
add_action( 'add_meta_boxes', 'bms_register_meta_boxes_order_items' );


function bms_order_items($post){
  $bms_fc_order_items = json_decode(get_post_meta($post->ID, 'bms_order_items', true));
  ?>
<table class="table table-striped">
<tr>
  <th>Item Name</th>
  <th>Item Qty</th>
  <th>Item Price</th>
</tr>
  <?php

  if ($bms_fc_order_items){ foreach ($bms_fc_order_items as $key => $value){
    $item = (object) $value;
?>
<tr>
  <td><?php echo $item->itemname; ?></td>
  <td><?php echo $item->itemqty; ?></td>
  <td><?php echo $item->itemprice; ?></td>
</tr>
<?php
  } }
  ?>
<tr>
  <td>+Vat</td>
  <td></td>
  <td><?php echo get_post_meta($post->ID, 'bms_order_totalvat', true); ?></td>
</tr>
<tr>
  <td>Delivery Fee</td>
  <td></td>
  <td><?php echo get_post_meta($post->ID, 'bms_order_delivery_fee', true); ?></td>
</tr>
<tr>
  <td>Total</td>
  <td></td>
  <td><?php echo get_post_meta($post->ID, 'bms_order_totalprice', true); ?></td>
</tr>
  </table>
  <?php } ?>
<?php
  function bms_fc_delivery_info($post){
    ?>
<table class="table table-striped">
  <tr>
    <td>Order Id</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_id', true); ?></td>
  </tr>
  <tr>
    <td>Email Address</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_email_address', true); ?></td>
  </tr>
  <tr>
    <td>Phone</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_phone', true); ?></td>
  </tr>
<tr>
    <td>Location</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_select_location', true); ?></td>
  </tr>
  
  <tr>
    <td>Delivery Address</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_delivery_address', true); ?></td>
  </tr>
  <tr>
    <td>Customer Comments</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_delivery_comments', true); ?></td>
  </tr>
  <tr>
    <td>Order Time</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_time', true); ?></td>
  </tr>
  <tr>
    <td>Delivery Time</td>
    <td><?php echo get_post_meta($post->ID, 'bms_fc_order_delivery_time', true); ?></td>
  </tr>
</table>
    <?php
  }

function add_custom_meta_box_bms_fc_order_status()
{
    add_meta_box("bms-fc-order-status", "Order Status", "custom_meta_box_markup_fc_order_status", "orders", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box_bms_fc_order_status");
function custom_meta_box_markup_fc_order_status($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>

            <select name="meta-box-order-status">
                <?php 
                    $option_values = array("Processing", "Cancel", "Confirm", "Test Order");

                    foreach($option_values as $key => $value) 
                    {
                        if($value == get_post_meta($object->ID, "meta-box-dropdown", true))
                        {
                            ?>
                                <option selected><?php echo $value; ?></option>
                            <?php    
                        }
                        else
                        {
                            ?>
                                <option><?php echo $value; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>

        </div>
    <?php  
}
function bms_fc_save_meta_order_status( $post_id ) {
      if(isset($_POST['meta-box-order-status'])) {
        $order_status=$_POST['meta-box-order-status'];
      update_post_meta($post_id, 'bms_fc_order_status', $order_status);

    }
}
add_action( 'save_post', 'bms_fc_save_meta_order_status' );


function bms_fc_refresh() {
    echo '<script>
    setTimeout(function () {
        location.reload()
    }, 500000);
    </script>';

}
add_action('admin_footer', 'bms_fc_refresh');