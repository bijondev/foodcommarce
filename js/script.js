jQuery(document).ready(function($){
 'use strict';
    // menu ajax
jQuery(document).on('click', '#upload-menu', function(e) {
    // We'll pass this variable to the PHP function example_ajax_request
    var fruit = 'Banana';
    var form_data = {};
    $('#csvmenu').each(function(){
        form_data[this.name] = $('#csvmenu').val();
    });
    // This does the ajax request
    var dt=readTextFile(form_data);
    console.log(dt);
    $.ajax({
        url: ajaxurl,
        data: {
            'action':'restmenu_ajax_request',
            'fruit' : fruit
        },
        success:function(data) {
            // This outputs the result of the ajax request
            console.log(data);
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });
});
function readTextFile(file)
{
    var rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, false);
    rawFile.onreadystatechange = function ()
    {
        if(rawFile.readyState === 4)
        {
            if(rawFile.status === 200 || rawFile.status == 0)
            {
                var allText = rawFile.responseText;
                alert(allText);
            }
        }
    }
    rawFile.send(null);
}
    var cpi_image_frame;
    var p = $(this);
    var pluginsurl=$("#plugins-url").val();
    
        
    //Choose/upload image
    //$('.add-cat-image').click(function(e) {
    $(document).on('click', '.add-cat-image', function(e) {
       
      
      e.preventDefault();
        var i = 0;
        var that = $(this);
        var row = that.parent().parent().parent().parent();
        var i = row.data("catindex");
        $("#hidden-index").val(i);
        var cat_image='#cat_image_'+i;
        console.log(i);
      if ( cpi_image_frame ) {
        cpi_image_frame.open();
        return;
      }

      // Sets the media manager's title and button text
      cpi_image_frame = wp.media.frames.cpi_image_frame = wp.media({
        title: "Catagory Image",
        button: { text:  "Add Image", value: i }
      });

      // Runs when an image is selected
      cpi_image_frame.on('select', function() {
            
        // Grabs the attachment selection and creates a JSON representation of the model.
        var media_attachment = cpi_image_frame.state().get('selection').first().toJSON();
 
        var media_id = media_attachment.id;
        var media_thumbnail = media_attachment.sizes.thumbnail.url;
                
        // Sends the attachment URL to our custom image input field.
        //console.log(that);
        var id=$("#hidden-index").val();
        $('#cat_image_'+id).val(media_id);
        $('#image-box-'+id).html('<img src="' + media_thumbnail + '" width="100" ><div class="box-inner"><button type="button" data-btndelimg="'+i+'" class="btn btn-danger btn-xs del-cat-image">  <i  class="glyphicon glyphicon-remove"></i></div>');
        // that.parent().parent().parent().parent().find(cat_image).val(media_id);
        // that.parent().parent().parent().parent().find('#image-box-'+i).html('<img src="' + media_thumbnail + '" width="100" ><div class="box-inner"><button type="button" data-btndelimg="'+i+'" class="btn btn-danger btn-xs del-cat-image">  <i  class="glyphicon glyphicon-remove"></i></div>');
 
      });
            
      // Opens the media library frame
      cpi_image_frame.open(); 
    });
 
    // Button to unset current image
//$('.del-cat-image').click(function(e) {
$(document).on('click', '.del-cat-image', function(e) {
 var indx=$(this).data("btndelimg");
e.preventDefault();
//alert("remove");
$('#cat_image_'+indx).val('');
$('#image-box-'+indx).html('<img src="'+pluginsurl+'/img/no-image-available.jpg" alt=""><div class="box-inner"><button type="button" data-btnaddimg="1" class="btn btn-warning btn-xs add-cat-image"> <i class="glyphicon glyphicon-plus"></i></div>');
 
   });
var last_order=0;
$("input[name='cat_order[]']").each(function() {
 last_order= $(this).val();

});
var indx=0;
if(last_order !=0){
    indx=last_order;
}

 $(document).on('click', '.cat-add', function(e){
    indx++;
    var uid=uniqueid();
    var html ="";
    html +='<tr id="row-'+indx+'" data-catindex="'+indx+'" ><input type="hidden" id="cat_uid_'+indx+'" data-catuid="'+indx+'" value="'+uid+'" name="cat_id[]" >';
    html +='<td><input type="text" data-catname="'+indx+'" placeholder="Catagory Name" name="cat_name[]"></td>';
    html +='<td><input type="text" data-catdisc="'+indx+'" placeholder="Discription" name="cat_disc[]"></td>';
    html +='<td>';
    html +='<input type="hidden" id="cat_image_'+indx+'" data-catimg="'+indx+'" name="cat_image[]" >';
    html +='<div class="image-box" id="image-box-'+indx+'">';
    html +='<img src="'+pluginsurl+'/img/no-image-available.jpg" alt="">';
    html +='<div class="box-inner">';
    html +='<button type="button" data-btnaddimg="'+indx+'" class="btn btn-warning btn-xs add-cat-image"> <i class="glyphicon glyphicon-plus"></i>';
    html +='</div>';
    html +='</div>';
    html +='</td>';
    html +='<td>';

    html +='<div class="checkbox">';
    html +='<label>';
    html +='<input type="checkbox" name="cat_status[]" value="active"> active';
    html +='</label>';
    html +='</div>';
    
    html +='</td>';
    html +='<td><input type="text" class="order" data-catorder="'+indx+'" placeholder="order number" value="'+indx+'" name="cat_order[]"></td>';
    html +='<td><button data-btnrmv="'+indx+'" type="button" class="btn btn-danger btn-xs del-cat-row"> <i class="glyphicon glyphicon-remove"></i> </button></td>';
    html +='</tr>';
    
    $("#cat-append-table").append(html);
 })
 /***********************DELET CAT ROW********************************/
 $(document).on('click', '.del-cat-row', function(e){
    var i = $(this).data("btnrmv");
    $('#row-'+i).fadeOut("slow", function() { $('#row-'+i).remove();});
 });
 /***************************************ITEM SECTION**********************************************/
 var last_item_order=0;
$("input[name='item_order[]']").each(function() {
 last_item_order= $(this).val();

});
 var item_indx=0;
if(last_item_order !=0){
    item_indx=last_item_order;
}

 $(document).on('click', '.item-add', function(e){
    item_indx++;
    var uid=uniqueid();
    var html ="";
    html +='<tr id="item-row-'+item_indx+'" data-itemindex="'+item_indx+'" ><input type="hidden" id="item_uid_'+item_indx+'" data-itemuid="'+item_indx+'" value="'+uid+'" name="item_id[]" >';
    html +='<td><input type="text" data-itemname="'+item_indx+'" placeholder="Item Name" name="item_name[]"></td>';
    html +='<td><input type="text" data-itemdisc="'+item_indx+'" placeholder="Discription" name="item_disc[]"></td>';
    html +='<td><select id="item-cat-'+item_indx+'" name="item-cat[]"><option>Select catagory</option></select></td>';
    html +='<td>';
    html +='<input type="hidden" id="item_image_'+item_indx+'" data-itemimg="'+item_indx+'" name="item_image[]" >';
    html +='<div class="image-box" id="item-image-box-'+item_indx+'">';
    html +='<img src="'+pluginsurl+'/img/no-image-available.jpg" alt="">';
    html +='<div class="box-inner">';
    html +='<button type="button" data-btnaddimg="'+item_indx+'" class="btn btn-warning btn-xs add-item-image"> <i class="glyphicon glyphicon-plus"></i>';
    html +='</div>';
    html +='</div>';
    html +='</td>';
    html +='<td>';

    html +='<div class="checkbox">';
    html +='<label>';
    html +='<input type="checkbox" name="item_status[]" value="active"> active';
    html +='</label>';
    html +='</div>';
    html +='</td>';
    html +='<td><input type="text" class="item-price" data-itemprice="'+item_indx+'" placeholder="Price" name="item_price[]"></td>';
    html +='<td><input type="text" class="order" data-itemorder="'+item_indx+'" placeholder="order number" value="'+item_indx+'" name="item_order[]"></td>';
    html +='<td><button data-btnrmv="'+item_indx+'" type="button" class="btn btn-danger btn-xs del-item-row"> <i class="glyphicon glyphicon-remove"></i> </button></td>';
    html +='</tr>';
    
    $("#item-append-table").append(html);

    var item_cats=$.parseJSON($("#item_cats").val());

    $.each(item_cats, function(key, value) {  
     console.log(value);
     $('#item-cat-'+item_indx)
          .append($('<option>', { value : value.cat_id })
          .text(value.cat_name)); 
});

 })

/***********************************ADD MENU ITEM IMAGE ************************************/
$(document).on('click', '.add-item-image', function(e) {
       
      
      e.preventDefault();
        var i = 0;
        var that = $(this);
        var row = that.parent().parent().parent().parent();
        var i = row.data("itemindex");
        $("#hidden-index").val(i);
      if ( cpi_image_frame ) {
        cpi_image_frame.open();
        return;
      }

      // Sets the media manager's title and button text
      cpi_image_frame = wp.media.frames.cpi_image_frame = wp.media({
        title: "Menu Item Image",
        button: { text:  "Add Image", value: i }
      });

      // Runs when an image is selected
      cpi_image_frame.on('select', function() {
            
        // Grabs the attachment selection and creates a JSON representation of the model.
        var media_attachment = cpi_image_frame.state().get('selection').first().toJSON();
 
        var media_id = media_attachment.id;
        var media_thumbnail = media_attachment.sizes.thumbnail.url;
                
        // Sends the attachment URL to our custom image input field.
        //console.log(that);
        var id=$("#hidden-index").val();
        $('#item_image_'+id).val(media_id);
        $('#item-image-box-'+id).html('<img src="' + media_thumbnail + '" width="100" ><div class="box-inner"><button type="button" data-btndelimg="'+i+'" class="btn btn-danger btn-xs del-item-image">  <i  class="glyphicon glyphicon-remove"></i></div>');
        // that.parent().parent().parent().parent().find(cat_image).val(media_id);
        // that.parent().parent().parent().parent().find('#image-box-'+i).html('<img src="' + media_thumbnail + '" width="100" ><div class="box-inner"><button type="button" data-btndelimg="'+i+'" class="btn btn-danger btn-xs del-cat-image">  <i  class="glyphicon glyphicon-remove"></i></div>');
 
      });
            
      // Opens the media library frame
      cpi_image_frame.open(); 
    });
/***********************Remove item Image***********************************/
$(document).on('click', '.del-item-image', function(e) {
 var indx=$(this).data("btndelimg");
 //alert(indx);
e.preventDefault();
//alert("remove");
$('#item_image_'+indx).val('');
$('#item-image-box-'+indx).html('<img src="'+pluginsurl+'/img/no-image-available.jpg" alt=""><div class="box-inner"><button type="button" data-btnaddimg="1" class="btn btn-warning btn-xs add-item-image"> <i class="glyphicon glyphicon-plus"></i></div>');
 
   });
/*/////////////////////Delet item Row////////////////////////////*/
 $(document).on('click', '.del-item-row', function(e){
    var i = $(this).data("btnrmv");
    $('#item-row-'+i).fadeOut("slow", function() { $('#item-row-'+i).remove();});
 });

 function uniqueid() {
    var d = new Date();
    var n = d.valueOf();
    return n;
}
});
// function bindClicks() {
//     alert("delet");
// }

//jQuery(document).ready(bindClicks);
