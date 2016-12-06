<?php
session_start();
/*
Plugin Name: Food Commarce
Plugin URI: http://bmsplugins.blogspot.com/
Version: 1.0
Author: Bijon Krishna Bairagi
Description: What does your plugin do and what features does it offer...
*/
include "post-type-restaurent.php";
include "fc-menu.php";
include "bms-post-type-order.php";
function FontAwesome_icons() {
    echo '<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css"  rel="stylesheet">';
    echo '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"  rel="stylesheet">';
    wp_enqueue_style( 'admin_css_restaurant', plugin_dir_url( __FILE__ ) . 'css/style.css', false, '1.0.0' );
    wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'js/script.js' );
}
add_action('admin_footer', 'FontAwesome_icons');

// order page shortcode
add_filter( 'page_template', 'bms_fc_order_page' );
function bms_fc_order_page( $page_template )
{
    if ( is_page( 'order-page' ) ) {
        $page_template = plugin_dir_path( __FILE__ ) . 'template/order-page.php';
    }
    return $page_template;
}

function order_page(){
    //$_SESSION['delivery_fee']=$delivery_fee = $_POST['delivery_fee'];
    //$_SESSION['vat_included']=$vat_included = $_POST['vat_included'];
    $_SESSION['res_id']=$res_id = $_POST['res_id'];
    $_SESSION['service_fee']=$service_fee = $_POST['service_fee'];
    $_SESSION['min_order']=$min_order = $_POST['min_order'];
    //$_SESSION['totalvat']=$totalvat = $_POST['totalvat'];
    $_SESSION['totalprice']=$totalprice = $_POST['totalprice'];
    //$_SESSION['neetamount']=$neetamount = $_POST['neetamount'];
    ?>
    <form action="<?php echo home_url(); ?>/order-confirm" method="post" >
<table class="table table-striped" >
<tr>
    <th>Item Name</the>
    <td>Item Quantity</td>
    <td>Price</td>
</tr>
    <?php
    for($i=0;$i<count($_POST["itemname"]);$i++)
          {
            $items[] = [
                      'itemid'=>$_POST['itemid'][$i],
                      'itemqty' => $_POST['itemqty'][$i],
                      'itemname' => $_POST['itemname'][$i],
                      'itemprice' => $_POST['itemprice'][$i],
                      'itemitotal'=>$_POST['item-i-total'][$i],
                  ];
            ?>
<tr>
    <td>
    <input type="hidden" name="itemid[]" value="<?php echo $_POST['itemid'][$i]; ?>">
    <input type="hidden" name="itemname[]" value="<?php echo $_POST['itemname'][$i]; ?>">
    <input type="hidden" name="itemqty[]" value="<?php echo $_POST['itemqty'][$i]; ?>">
    <input type="hidden" name="itemprice[]" value="<?php echo $_POST['itemprice'][$i]; ?>">
    <input type="hidden" name="itemitotal[]" value="<?php echo $_POST['item-i-total'][$i]; ?>">
    <?php echo $_POST['itemname'][$i]; ?></td>
    <td><?php echo $_POST['itemqty'][$i]; ?></td>
    <td><?php echo $_POST['itemprice'][$i]; ?></td>
</tr>
            <?php
          }
?>
<tr>
    <td>Delivery Fee</td>
    <td></td>
    <td><?php echo $delivery_fee; ?></td>
</tr>
<tr>
    <td>Total Vat</td>
    <td></td>
    <td><?php echo $totalvat; ?></td>
</tr>
<tr>
    <td>Total Price</td>
    <td></td>
    <td><?php echo $totalprice; ?></td>
</tr>
</table>
<!--Order form-->

<input type="hidden" name="delivery_fee" value="<?php echo $delivery_fee; ?>">
<input type="hidden" name="totalvat" value="<?php echo $totalvat; ?>">
<input type="hidden" name="totalprice" value="<?php echo $totalprice; ?>">
<input type="hidden" name="neetamount" value="<?php echo $neetamount; ?>">
<input type="hidden" name="res_id" value="<?php echo $res_id; ?>">
  <div class="form-group">
    <label>Full Name</label>
    <input type="text" name="full_name" class="form-control" placeholder="Full Name">
  </div>
  <div class="form-group">
    <label>Email</label>
    <input type="email" name="email_address" class="form-control" placeholder="Emil Address">
  </div>
  <div class="form-group">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control" placeholder="Phone Number">
  </div>
    <div class="form-group">
    <label>Delivery Address</label>
    <textarea name="delivery_address" name="delivery-address" class="form-control" rows="3"></textarea>
  </div>
  <div class="form-group">
    <label>If Any Comments</label>
    <textarea name="delivery_comments" name="order-comments" class="form-control" rows="3"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php
}
add_shortcode("order_page", "order_page");

add_filter( 'single_template', 'include_reviews_template', 1 );
function include_reviews_template(){
    if ( get_post_type() == 'restaurant' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array( 'single-restaurant.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . 'template/single-restaurant.php';
            }
        }
    }
    return $template_path;
}

// order confirm page shortcode
add_filter( 'page_template', 'bms_fc_order_confirm_page' );
function bms_fc_order_confirm_page( $page_template )
{
    if ( is_page( 'order-confirm' ) ) {
        $page_template = plugin_dir_path( __FILE__ ) . 'template/order-page.php';
    }
    return $page_template;
}
function order_confirm(){
    $uid=time();
    echo "<h1>Thanks for Order</h1>";
    echo "<h1>Order ID # ".$uid."</h1>";

    $full_name=$_POST['full_name'];
    $email_address=$_POST['email_address'];
    $phone=$_POST['phone'];
    $delivery_address=$_POST['delivery_address'];
    $delivery_comments=$_POST['delivery_comments'];

    $delivery_fee = $_POST['delivery_fee'];
    $res_id = $_POST['res_id'];
    $totalvat = $_POST['totalvat'];
    $totalprice = $_POST['totalprice'];
    $neetamount = $_POST['neetamount'];
$html="";

    for($i=0;$i<count($_POST["itemname"]);$i++)
          {
            $items[] = [
                      'itemid'=>$_POST['itemid'][$i],
                      'itemqty' => $_POST['itemqty'][$i],
                      'itemname' => $_POST['itemname'][$i],
                      'itemprice' => $_POST['itemprice'][$i],
                      'itemitotal'=>$_POST['itemitotal'][$i],
                  ];
          $html.=$_POST['itemname'][$i]."-".$_POST['itemqty'][$i]."pcs -".$_POST['itemprice'][$i]."tk. <br />";
          }
$html.="delivery fee :".$delivery_fee."<br />";
$html.="vat :".$totalvat."<br />";
$html.="Total Price :".$totalprice."<br />";
$new_post = array(
'post_title'    => $full_name,
'post_content'  => "",
'post_status'   => 'publish',          
'post_type'     => 'orders' 
);

//insert the the post into database by passing $new_post to wp_insert_post
//store our post ID in a variable $pid
$pid = wp_insert_post($new_post);
$message="";
$message .="Name :".$full_name."<br />";
$message .="Email :".$email_address."<br />";
$message .="Phone :".$phone."<br />";
$message .="Address :".$delivery_address."<br />";
$message .="Comment :".$delivery_comments."<br />";
$message  .=$html;
//we now use $pid (post id) to help add out post meta data
$to="order@lepizzariabd.com";
$subject="lepizzaria-new Order";
$headers = array('Content-Type: text/html; charset=UTF-8');
$attachments="";
wp_mail( $to, $subject, $message, $headers, $attachments );

$cat_json=json_encode($items);
add_post_meta($pid, 'bms_order_items', $cat_json, true);
add_post_meta($pid, 'bms_order_res_id', $res_id, true);
add_post_meta($pid, 'bms_order_delivery_fee', $delivery_fee, true);
add_post_meta($pid, 'bms_order_totalvat', $totalvat, true);
add_post_meta($pid, 'bms_order_neetamount', $neetamount, true);
add_post_meta($pid, 'bms_order_totalprice', $totalprice, true);


add_post_meta($pid, 'bms_fc_order_id', $uid, true);
add_post_meta($pid, 'bms_fc_order_email_address', $email_address, true);
add_post_meta($pid, 'bms_fc_order_phone', $phone, true);
add_post_meta($pid, 'bms_fc_order_delivery_address', $delivery_address, true);
add_post_meta($pid, 'bms_fc_order_delivery_comments', $delivery_comments, true);
date_default_timezone_set('Asia/Dhaka');
$cur_time=date("Y-m-d H:i:s");
$duration='+60 minutes';
$delivery_time=date('Y-m-d H:i:s', strtotime($duration, strtotime($cur_time)));
add_post_meta($pid, 'bms_fc_order_time', $cur_time, true);
add_post_meta($pid, 'bms_fc_order_delivery_time', $delivery_time, true);

add_post_meta($pid, 'bms_fc_order_select_location', $_SESSION['location'], true);
add_post_meta($pid, 'bms_fc_order_select_menu', $_SESSION['select_menu'], true);


}
add_shortcode("order-confirm", "order_confirm");

/********************USER ROLL******************************/
$result = add_role( 'restaurant_manager', __(

'Restaurant Manager' ),

array(

'read' => false, // true allows this capability
'edit_posts' => false, // Allows user to edit their own posts
'edit_pages' => false, // Allows user to edit pages
'edit_others_posts' => false, // Allows user to edit others posts not just their own
'create_posts' => false, // Allows user to create new posts
'manage_categories' => false, // Allows user to manage post categories
'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode

)

);
function remove_menus_for_restaurant_manager(){
  
  $user = wp_get_current_user();
$allowed_roles = array('restaurant_manager');
if( array_intersect($allowed_roles, $user->roles ) ) { 
  show_admin_bar(false);


     remove_menu_page( 'index.php' );                  //Dashboard
      remove_menu_page( 'jetpack' );                    //Jetpack* 
      remove_menu_page( 'edit.php' );                   //Posts
      remove_menu_page( 'upload.php' );                 //Media
      remove_menu_page( 'edit.php?post_type=page' );    //Pages
      remove_menu_page( 'edit.php?post_type=restaurant' );    //Pages
      remove_menu_page( 'vc-welcome' );    //Pages
      remove_menu_page( 'edit-comments.php' );          //Comments
      remove_menu_page( 'themes.php' );                 //Appearance
      remove_menu_page( 'plugins.php' );                //Plugins
      remove_menu_page( 'users.php' );                  //Users
      remove_menu_page( 'tools.php' );                  //Tools
      remove_menu_page( 'options-general.php' );        //Settings
      remove_menu_page( 'profile.php' );        //Settings
      remove_menu_page( 'post-new.php' );        //Settings
      remove_menu_page( 'post-new.php?post_type=orders' );        //Settings
}
  
}
add_action( 'admin_menu', 'remove_menus_for_restaurant_manager' );
function remove_admin_bar_links() {                           
    $user = wp_get_current_user();
    $allowed_roles = array('restaurant_manager');
    if( array_intersect($allowed_roles, $user->roles ) ) { 
      global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('updates');
    $wp_admin_bar->remove_menu('new-content'); 
  }
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
// ajax request
function restmenu_ajax_request() {

  // The $_REQUEST contains all the data sent via ajax 
  if ( isset($_REQUEST) ) {
  
    $fruit = $_FILES['data'];
     
    // Now we'll return it to the javascript function
    // Anything outputted will be returned in the response
    var_dump($fruit);
    
    // If you're debugging, it might be useful to see what was sent in the $_REQUEST
    // print_r($_REQUEST);
  
  }
  
  // Always die in functions echoing ajax content
   die();
}

add_action( 'wp_ajax_restmenu_ajax_request', 'restmenu_ajax_request' );

// If you wanted to also use the function for non-logged in users (in a theme for example)
// add_action( 'wp_ajax_nopriv_example_ajax_request', 'example_ajax_request' );

?>