<?php
/*
	Plugin Name: Skills Plugin
	Description: Plugin to display skills in graphic form
   	Plugin URI: http://abdellahaithadji.com
	Author: Abdellah Ait hadji
	Author URI: http://abdellahaithadji.com
	License: GPL2
	Version: 1.0.1
*/

/*  Copyright 2015 Abdellah Ait hadji (email : abdel@abdellahaithadji.com)

    This program is free software; you can redistribute it and/or modify.
  
*/

add_action("init", "skills_init");
add_action("add_meta_boxes", "skills_metaboxes");
add_action("save_post", "skills_savepost",10,2);
add_action("init", "create_posttype");
add_action( 'init', 'custom_post_type', 0 );

/* add menu */

add_action("init", "abdel_add_menu");

register_nav_menus(array(

	"primary" => __("Primary Menu"),
	"footer" => __("Footer Menu"),
));

function skills_init(){
 

		$labels = array(
			
			"name" => "Skill",
			"singular_name" => "Skill",
			"add_new" => "Ajouter un Skill",
			"add_new_item" => "Ajouter un nouveau Skill",
			"edit_item" => "Editer un skill",
			"new_item" => "Nouveau Skill",
			"view_item" => "Voir le Skill",
			"search_items" => "Rechercher un Skill",
			"not_found" => "Aucun Skill",
			"not_found_in_trash" => "Aucun Skill dans la corbeille",
			"parent_item_colon" => "",
			"menu_name" => "Skills",	

		);       
                register_post_type("skill", array(
                    "public" => true,
                    "publicly_queryable" => false,
                    "labels" => $labels,
                    "menu_position" => 9,
                    "capability_type" => "post",
                    "supports" => array("title", "thumbnail",)
                ));

}

function skills_metaboxes(){

	add_meta_box("skills", "Valeur en pourentage", "skills_metabox", "skill", "normal", "high");
}
function skills_metabox($object){
wp_nonce_field("skills", "skills_nonce");
	?>
	<div class="meta-box-item-title">
		<h4>Valeur</h4>
	</div>
	<div class="meta-box-item-content">	
		<input type="text" name="pourcentage" style="width:100%;" value="<?= esc_attr(get_post_meta($object->ID, 'testtest', true)); ?>">	
	</div>
	<?php

}

function skills_savepost($post_id, $post){

	if(!isset($_POST['pourcentage']) || !wp_verify_nonce($_POST["skills_nonce"], "skills")){
				
		return $post_id;	
	}
	$type = get_post_type_object($post->post_type);

	if(current_user_can($type->cap->edit_post)){
               
		return $post_id;
		
	}
	
	update_post_meta($post_id, 'testtest', $_POST["testtest"]); 
        
}

function skills_show($limit = 10){  
	
    //wp_enqueue_script("jquery");
    //wp_enqueue_script("jquery", "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js", array("jquery"),"5.6.4", true);
    // var_dump($_POST); die();
   add_action("wp_footer", "skills_script", 30);
	
    $skills = new WP_query("post_type=skill&posts_per_page=$limit");
    
    while($skills->have_posts()){
    $skills->the_post();
    
    global $post;
    //$custom = get_post_custom( $post->ID );
    // print_r($custom); die();
    
    echo '<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '.esc_attr(get_post_meta($post->ID, 'testtest', true)).'%">
   
    <span class="sr-only">'.the_title().'</span>'.esc_attr(get_post_meta($post->ID, 'testtest', true)).'%
  </div>
</div>
';

    }     
   
}
function get_post_meta_custom(){
    
   
    
}
function abdel_add_menu(){
    
    register_nav_menu("main_menu", "Menu secondaire");
    
}

function create_posttype() {

	register_post_type( 'movies',
	
		array(
			'labels' => array(
				'name' => __( 'Movies' ),
				'singular_name' => __( 'Movie' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'movies'),
		)
	);
}

/*
* Creating a function to create our CPT
*/

function custom_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Movies', 'Post Type General Name', 'twentysixty' ),
		'singular_name'       => _x( 'Movie', 'Post Type Singular Name', 'twentysixty' ),
		'menu_name'           => __( 'Movies', 'twentysixty' ),
		'parent_item_colon'   => __( 'Parent Movie', 'twentysixty' ),
		'all_items'           => __( 'All Movies', 'twentysixty' ),
		'view_item'           => __( 'View Movie', 'twentysixty' ),
		'add_new_item'        => __( 'Add New Movie', 'twentysixty' ),
		'add_new'             => __( 'Add New', 'twentysixty' ),
		'edit_item'           => __( 'Edit Movie', 'twentysixty' ),
		'update_item'         => __( 'Update Movie', 'twentysixty' ),
		'search_items'        => __( 'Search Movie', 'twentysixty' ),
		'not_found'           => __( 'Not Found', 'twentysixty' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentysixty' ),
	);
	
       // Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'movies', 'twentysixty' ),
		'description'         => __( 'Movie news and reviews', 'twentysixty' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'genres' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'movies', $args );
        
        /* Hook into the 'init' action so that the function
         * Containing our post type registration is not 
         * unnecessarily executed. 
        */
  
        add_action( 'init', 'custom_post_type', 0 );

}


function skills_script(){

	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
		jQuery('.progress').each(function(){
			//console.log(jQuery(this).find('.progress-bar-success'));
			jQuery(this).find('.progress-bar-success').animate({
				width:jQuery(this).attr('aria-valuenow')
			},6000);
		});
		});
	</script>
	<?php
}
?>