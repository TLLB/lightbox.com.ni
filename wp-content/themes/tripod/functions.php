<?php
	error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
	
    define('_LIMIT_' , 10 );
    define('BLOCK_TITLE_LEN' , 50 );
    
    define('DEFAULT_AVATAR'   , get_template_directory_uri()."/images/default_avatar.jpg" );
	define('DEFAULT_AVATAR_100'   , get_template_directory_uri()."/images/default_avatar_100.jpg" );
	define('DEFAULT_AVATAR_LOGIN'   , get_template_directory_uri()."/images/default_avatar_login.png" );
    define( '_TN_'      , wp_get_theme() );
    define('BRAND'      , '' );
	define('ZIP_NAME'   , 'tripod' );
	
	$content_width = 600;
	
    add_action('admin_bar_menu', 'de_cosmotheme');
     
    get_template_part('/lib/php/aq_resizer'); 
	include 'lib/php/main.php';
	include 'lib/php/localize-js.php';
    
    
    include 'lib/php/actions.register.php';
    include 'lib/php/menu.register.php';

    if( function_exists( 'add_theme_support' ) ){ 
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
    }

//    image::add_size();

  	add_theme_support( 'custom-background' ); /*requires WP v >= 3.4  */

	add_theme_support( 'post-formats' , array( 'image' , 'video' , 'audio','gallery', 'quote'  ) );
	add_editor_style('editor-style.css');
	
	

    /* Localization */
    load_theme_textdomain( 'cosmotheme' );
    load_theme_textdomain( 'cosmotheme' , get_template_directory() . '/languages' );
    
    if ( function_exists( 'load_child_theme_textdomain' ) ){
        load_child_theme_textdomain( 'cosmotheme' );
    }

	function remove_post_format_fields() {
		remove_meta_box( 'formatdiv' , 'post' , 'side' ); 
		remove_meta_box( 'formatdiv' , 'gallery' , 'side' ); 
	}
	add_action( 'admin_menu' , 'remove_post_format_fields' );
    
	if(is_admin() && ini_get('allow_url_fopen') == '1'){
		/*New version check*/	
		if( options::logic( 'cosmothemes' , 'show_new_version' ) ){
			function versionNotify(){
				echo api_call::compareVersions(); 
			}
		
			// Add hook for admin <head></head>
			add_action('admin_head', 'versionNotify');
		}

		/*Cosmo news*/
		if( options::logic( 'cosmothemes' , 'show_cosmo_news' ) && !isset($_GET['post_id'])  && !isset($_GET['post'])){
			function doCosmoNews(){
				echo api_call::getCosmoNews(); 
			}
		
			// Add hook for admin <head></head>
			add_action('admin_head', 'doCosmoNews');
		}	
	}

    /* Cosmothemes Backend link */
    function de_cosmotheme() {
        global $wp_admin_bar;    
        if ( !is_super_admin() || !is_admin_bar_showing() ){
            return;
        }
        $wp_admin_bar -> add_menu( array(
            'id' => 'cosmothemes',
            'parent' => '',
            'title' => _TN_,
            'href' => admin_url( 'admin.php?page=cosmothemes__general' )
            ) );   
    }

	add_filter('excerpt_length', 'cosmo_excerpt_length');
	function cosmo_excerpt_length($length) {
		return 70;  /* Or whatever you want the length to be. */
	}

    if( !options::logic( 'general' , 'show_admin_bar' ) ){
		add_filter( 'show_admin_bar', '__return_false' );
	}

	//delete_option('first-install-settings');
	if(!get_option( 'first-install-settings' ) ){
		include get_template_directory() . '/lib/templates/init_templates.php';
		add_option( 'first-install-settings', 'pages created' );
	}

    add_filter( 'pre_get_posts', 'cosmo_posts_per_archive' );
    function cosmo_posts_per_archive( $query ) {
        if( isset( $_GET[ 'fp_element' ] ) && isset( $_GET[ 'fp_row' ] ) && isset( $_GET[ 'fp_template' ] ) ){
            $templateID = $_GET['fp_template'];
            $rowID = $_GET['fp_row'];
            $elementID = $_GET['fp_element'];
            $template = LBTemplate::get_template_by_id($templateID);
            $row = $template -> rows[$rowID];
            $element = $row -> elements[$elementID];
            $query -> set( 'posts_per_page', $element -> numberposts );
        }
        return $query;
    }

	/*  check if woocommerce is installed*/    
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		/*Default pages creation*/
		//delete_option('first-install-pages-'.ZIP_NAME);
		if(!get_option( 'first-install-pages-'.ZIP_NAME ) ){


			//create wishlist page
			$default_custom_pages = array('Wishlist', 'New products', 'Products');
			
			/*create wishlist and new products pages only ones */
			foreach($default_custom_pages as $page){
				$default_page = get_page_by_title( $page );
				if( !($default_page && isset($default_page->ID)) ){
					$pages = array(
						'post_status' => 'publish',
						'post_type' => 'page',
						'post_author' => 1,
						'post_name' => $page,
						'post_title' => $page,
						'post_content' => '',
						'comment_status' => 'closed'
					);
					$def_page = wp_insert_post($pages);

					if( is_numeric($def_page) && $def_page > 0){
						//assign page template (for example for 'My bookmarks' page -> 'my_bookmarks.php' template will be assigned)
						update_post_meta($def_page, '_wp_page_template', str_replace(' ','-', strtolower($page)).'.php'); 
					}

					if('Products' == $page){  // for products page we want to assing a different template
						assign_template_to_post( $def_page, 'products_list123' );	
					}
					
				}
			}

			add_option( 'first-install-pages-'.ZIP_NAME, 'pages created' );
		}

		//delete_option('woocommerce_template_patch');
		if(!get_option( 'woocommerce_template_patch' ) ){
			
			include get_template_directory() . '/lib/templates/woocommerce_template_patch.php';

			add_option( 'woocommerce_template_patch', 'woocommerce templates created' );
		}
	}


	

//delete_option('gallery_template_patch');
	if(!get_option( 'gallery_template_patch' ) ){

		include get_template_directory() . '/lib/templates/gallery_template_patch.php';

		add_option( 'gallery_template_patch', 'gallery template created' );
	}

	add_editor_style('editor-style.css');
	
	get_template_part( '/video-audio-player/mediaelement-js-wp' );


	function load_css() {
		
		wp_register_style( 'default_stylesheet',get_stylesheet_directory_uri() . '/style.css' );
		wp_enqueue_style( 'default_stylesheet' );

		$files = scandir( get_template_directory()."/css/autoinclude" );
		foreach( $files as $file ){
			if( is_file( get_template_directory()."/css/autoinclude/$file" ) ){
				wp_register_style( $file.'-style',get_template_directory_uri() . '/css/autoinclude/'.$file );
				wp_enqueue_style( $file.'-style' );
			}
		}

		if(get_option( 'woocommerce_frontend_css' ) != 'yes' && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
			wp_register_style( 'woostyles',get_template_directory_uri() . '/css/wooshop.css' );
			wp_enqueue_style( 'woostyles' );
		}

		if(options::logic( 'blog_post' , 'enb_lightbox' )){
			wp_register_style( 'prettyPhoto',get_template_directory_uri() . '/css/prettyPhoto.css' );
			wp_enqueue_style( 'prettyPhoto' );
		}

		load_google_fonts();
		
		
		//wp_enqueue_script( 'foundation' , get_template_directory_uri() . '/js/foundation.js' , array( 'jquery' ),false,true );
		//wp_enqueue_script( 'lazyload' , get_template_directory_uri() . '/js/jquery.lazyload.js' , array( 'jquery' ),false,true );
		
		// add conditions to incluthis only if woocommerce is enabled
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			wp_enqueue_script( 'flexslider' , get_template_directory_uri() . '/js/jquery.flexslider-min.js' , array( 'jquery' ),false,true );
			wp_enqueue_script( 'chosen' , get_template_directory_uri() . '/js/chosen.jquery.min.js' , array( 'jquery' ),false,true );
			wp_enqueue_script( 'hoverIntent' , get_template_directory_uri() . '/js/jquery.hoverIntent.js' , array( 'jquery' ),false,true );
		}
		

		wp_enqueue_script( 'pageslide' , get_template_directory_uri() . '/js/jquery.pageslide.min.js' , array( 'jquery' ),false,true ); /*for showing/hide mobile menu*/
		wp_enqueue_script( 'superfish' , get_template_directory_uri() . '/js/jquery.superfish.js' , array( 'jquery' ),false,true );
		wp_enqueue_script( 'supersubs' , get_template_directory_uri() . '/js/jquery.supersubs.js' , array( 'jquery' ),false,true );
		wp_enqueue_script( 'galleria' , get_template_directory_uri() . '/js/galleria-1.2.9.min.js' , array( 'jquery' ),false,true );	
		wp_enqueue_script( 'tour' , get_template_directory_uri() . '/js/tour.js' , array( 'jquery' ),false,true );
		wp_enqueue_script( 'tabs' , get_template_directory_uri() . '/js/jquery.tabs.pack.js' , array( 'jquery' ),false,true );
		wp_enqueue_script( 'scrollto' , get_template_directory_uri() . '/js/jquery.scrollTo-1.4.2-min.js' , array( 'jquery' ),false,true );
		wp_enqueue_script( 'masonry' , get_template_directory_uri() . '/js/jquery.isotope.min.js' , array( 'jquery' ) );
		
		
		wp_enqueue_script( 'jquery-cookie' , get_template_directory_uri() . '/js/jquery.cookie_renamed.js' , array( 'jquery' ),false,true );


		
		$gallery_type = options::get_value( 'blog_post' , 'gallery_type' ); // default value

		global $post, $current_template, $the_gallery_id;

		$aaa = $current_template -> _rows;
		foreach ($current_template -> _rows as $key => $template_part) {
            foreach ($template_part['_elements'] as $key => $element) {
                //var_dump($element );
                if(isset($element['galleryID']) && $element['galleryID'] != ''){
                    $galleryID = $element['galleryID'];
                }
            }
		}
		
		if (is_singular( 'gallery' )) {
			$the_gallery_id = $post -> ID;
		}else{
			if(isset($galleryID)){
				$the_gallery_id = $galleryID;
			}else{
				$the_gallery_id = 0;
			}
			
		}
		
		if(is_single() && get_post_format( $post -> ID ) == 'gallery'){
			$gallery_type = 'sly'; 
			wp_enqueue_script( 'sly' , get_template_directory_uri() . '/js/sly.min.js' , array( 'jquery' ),false,true );
		}elseif( get_posts_gallery_type($the_gallery_id) == 'clasic'){
			$gallery_type = 'clasic';
			wp_enqueue_script( 'galleria-classic' , get_template_directory_uri() . '/js/galleria.classic.min.js' , array( 'jquery' ),false,true );
			wp_register_style( 'galleria-classic',get_template_directory_uri() . '/css/galleria.classic.css' );
			wp_enqueue_style( 'galleria-classic' );

		} elseif( get_posts_gallery_type($the_gallery_id) == 'folio') { 
			$gallery_type = 'folio'; 
			wp_enqueue_script( 'galleria-folio' , get_template_directory_uri() . '/js/galleria.folio.min.js' , array( 'jquery' ),false,true );
			wp_register_style( 'galleria-folio',get_template_directory_uri() . '/css/galleria.folio.css' );
			wp_enqueue_style( 'galleria-folio' );
		}elseif( get_posts_gallery_type($the_gallery_id) == 'image_flow') { 
			$gallery_type = 'image_flow'; 
			wp_enqueue_script( 'highslide' , get_template_directory_uri() . '/js/highslide.packed.js' , array( 'jquery' ),false,true );
			wp_enqueue_script( 'galleria-folio' , get_template_directory_uri() . '/js/imageflow.packed.js' , array( 'jquery' ),false,true );
			wp_register_style( 'galleria-imgeFlow',get_template_directory_uri() . '/css/imageflow.css' );
			wp_enqueue_style( 'galleria-imgeFlow' );
			wp_register_style( 'highslide',get_template_directory_uri() . '/css/highslide.css' );
			wp_enqueue_style( 'highslide' );
			
		}elseif( get_posts_gallery_type($the_gallery_id) == 'mosaic') { 
			$gallery_type = 'mosaic'; 
		}else{
			$gallery_type = 'sly'; 
			wp_enqueue_script( 'sly' , get_template_directory_uri() . '/js/sly.min.js' , array( 'jquery' ),false,true );
		}

		wp_enqueue_script( 'functions' , get_template_directory_uri() . '/js/functions.js' , array( 'jquery' , 'tabs' , 'scrollto' ),false,true );
		wp_localize_script( 'functions', 'galleria', array(

            'gallery_type'          => $gallery_type
            )
        );

		

		if(options::logic( 'blog_post' , 'enb_lightbox' )){
			$enb_lightbox = true;
			wp_enqueue_script( 'prettyPhoto' , get_template_directory_uri() . '/js/jquery.prettyPhoto.js' , array( 'jquery' ),false,true );
		} else { $enb_lightbox = false; }
        wp_localize_script( 'functions', 'prettyPhoto_enb', array(
            'enb_lightbox'          => $enb_lightbox
            )
        );	
        /*  check if woocommerce is installed*/    
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        	wp_localize_script( 'functions', 'cosmo_woocommerce_cripts', array(
	            'is_enabled'          => true
	            )
	        );	
        }else{
        	wp_localize_script( 'functions', 'cosmo_woocommerce_cripts', array(
	            'is_enabled'          => false
	            )
	        );	
        }

		if(options::logic( 'blog_post' , 'disable_hover_effect' )){
			$disable_hover_effect = true;
		} else { $disable_hover_effect = false; }
        wp_localize_script( 'functions', 'hoverEffect', array(
            'disable_hover_effect'          => $disable_hover_effect
            )
        );		
	
		$logo_font_family = explode('&',options::get_value('styling' , 'logo_font_family'));
        $logo_font_family = $logo_font_family[0];
        $logo_font_family = str_replace( '+',' ',$logo_font_family );

		wp_localize_script( 'functions', 'logo_font', $logo_font_family);

		// localize the gallery slider speed
		$gallery_speed = options::get_value( 'blog_post' , 'gallery_slider_speed' );

		wp_localize_script( 'functions', 'gallery_speed', $gallery_speed);
		
		/*call this only on front page*/
		wp_enqueue_script( 'easing' , get_template_directory_uri() . '/js/jquery.easing.js' , array( 'jquery' ),false,true );
		wp_enqueue_script( 'jscroll' , get_template_directory_uri() . '/js/jquery.jscroll.js' , array( 'jquery' ),false,true );
        wp_enqueue_script( 'waitforimages' , get_template_directory_uri() . '/js/jquery.waitforimages.js' , array( 'jquery' ),false,true );

		if ( is_singular() ){ 
			wp_enqueue_script( "comment-reply" );
		} 
        
        // embed the javascript file that makes the AJAX request
		wp_register_script( 'actions', get_template_directory_uri().'/lib/js/actions.js' , array('jquery') );

        wp_enqueue_script( 'actions' );

        if(is_page() ) {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox'); 
            
            wp_enqueue_style( 'ui-lightness');
            wp_enqueue_style('thickbox');
        }

        global $wp_query;
		wp_localize_script( 'actions', 'MyAjax', array(
		    // URL to wp-admin/admin-ajax.php to process the request
		    'ajaxurl'          => admin_url( 'admin-ajax.php' ),
            'wpargs' => array( 'wpargs' => $wp_query -> query ),
		 
		    // generate a nonce with a unique ID "myajax-post-comment-nonce"
		    // so that you can check it later when an AJAX request is sent
		    'getMoreNonce' => wp_create_nonce( 'myajax-getMore-nonce' ),
		    )
		);



		
		wp_localize_script( 'login', 'MyAjax', array(
		    // URL to wp-admin/admin-ajax.php to process the request
		    'ajaxurl'          => admin_url( 'admin-ajax.php' )
		    )
		);

		/*deregister chosen scripts from WC, we are using ours*/
		wp_deregister_style( 'woocommerce_chosen_styles' );
	}

	add_action('wp_enqueue_scripts', 'load_css');
	if (!is_admin()) {
		add_action('wp_head', 'get_custom_css');
	}


	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		/* demo auto login for users*/
		if( defined('IS_FOR_DEMO') && !is_user_logged_in() ){
			$creds = array();
			$creds['user_login'] = 'demo';
			$creds['user_password'] = 'demo';
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( is_wp_error($user) )
			   echo $user->get_error_message();
		}
	}

	/*autoset_featured function to set automatically first attached image as featured image on image format post*/
	add_action('draft_to_publish', 'autoset_featured');
	add_action('new_to_publish', 'autoset_featured');
	add_action('pending_to_publish', 'autoset_featured');
	add_action('future_to_publish', 'autoset_featured');

	/*filter to modify the output of wp_get_attachment_link*/
	add_filter( 'wp_get_attachment_link', 'modify_attachment_link', 10, 4 );

	/*enable shortcodes in excerpts*/
	add_filter( 'the_excerpt', 'shortcode_unautop');
	add_filter( 'the_excerpt', 'do_shortcode');

	/*make shortcodes work in text widgets*/
	add_filter( 'widget_text', 'shortcode_unautop');
	add_filter( 'widget_text', 'do_shortcode');

	
	/*Make Archives.php Include Custom Post Types, this function is used in archived.php*/
	function namespace_add_custom_types( $query ) {
		/*make sure we run this only in front end for post format archives.*/
	  	if(  empty( $query->query_vars['suppress_filters'] ) && !is_admin()  ) {
		    $query->set( 'post_type', array(
		     	'post', 'gallery'
			));
		  return $query;
		}
	}
	add_post_type_support( 'gallery', 'post-formats' );

	add_action('draft_to_publish_gallery', 'autoset_featured');
	add_action('new_to_publish_gallery', 'autoset_featured');
	add_action('pending_to_publish_gallery', 'autoset_featured');
	add_action('future_to_publish_gallery', 'autoset_featured');

	/* action for color picker*/
	add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
	function mw_enqueue_color_picker( $hook_suffix ) {
	    // first check that $hook_suffix is appropriate for your admin page
	    wp_enqueue_style( 'wp-color-picker' );
	    wp_enqueue_script( 'wp-color-picker' );
	}


	/**********************************************/
	/************ Plugin recommendations **********/
	/**********************************************/
	require_once ('lib/php/plugin-recommendations.php');
	

	/**********************************************/
	/************ to keep functions.php not too big, all the functions and hooks and actions **********/
	/************ related to woocommerce are relocated to /woocustomtemplates/woo-commerce-custom-functions.php **********/
	/**********************************************/
	get_template_part('/woocustomtemplates/woo-commerce-custom-functions');

	add_action('admin_notices', 'woo_styles_enabled_warning');




	