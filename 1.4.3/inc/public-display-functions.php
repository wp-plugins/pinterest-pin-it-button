<?php

//Add Public CSS/JS

function pib_add_public_css_js() {
    global $pib_options;
	
    //Add CSS to header
	wp_enqueue_style( 'pinterest-pin-it-button', PIB_CSS_URL . 'pinterest-pin-it-button.css' );
    
    //Image pre-selected
    if  ( $pib_options['button_style'] == 'image_selected' ) {
        
        //Image pre-selected AND use Custom image button
        if ( (bool)$pib_options['use_custom_img_btn'] ) {
			//Popup small sized window like original Pinterest create pin popup
			wp_enqueue_script( 'pin-it-button-image-selected', PIB_JS_URL . 'pin-it-button-image-selected.js', array( 'jquery' ), '', true );
        }
        
        //Image pre-selected AND use Stock button (embed code from Pinterest)
        else {
			//No longer users iframe
			wp_enqueue_script( 'pinterest-assets', 'http' . ( is_ssl() ? 's' : '' ) . '://assets.pinterest.com/js/pinit.js', null, '', true );
        }    
    }
    
    //User selects image (default)
    //$pib_options['button_style'] == 'user_selects_image' (or blank)
    else {
        //Fire off Pinterest's pinmarklet.js
        wp_enqueue_script( 'pin-it-button-user-selects-image', PIB_JS_URL . 'pin-it-button-user-selects-image.js', array( 'jquery' ), '', true );
        
        //User selects image AND use Custom image button
        if ( (bool)$pib_options['use_custom_img_btn'] ) {
            //Do nothing
        }
        
        //User selects image AND use Stock button
        else {
            //Do nothing
        }
    }
    
    //Custom count bubble JS for non-iframe buttons (if count layout specified)
    if (
        ( ( $pib_options['button_style'] != 'image_selected' ) || (bool)$pib_options['use_custom_img_btn'] ) &&
        ( isset( $pib_options['count_layout'] ) && ( $pib_options['count_layout'] != 'none' ) )
       ) {
        wp_enqueue_script( 'pin-it-button-custom-btn-img', PIB_JS_URL . 'pin-it-button-custom-btn-img.js', array( 'jquery' ), '', true );
    }
    
    //Load other sharing button JS in head (for compatibility with themes not implementing wp_footer() hook)
    if ( PIB_IS_PRO && (bool)$pib_options['use_other_sharing_buttons'] ) {
        wp_enqueue_script( 'twitter-embed', PIB_JS_URL . 'twitter-embed.js', null, '', true );
        wp_enqueue_script( 'gplus-embed', PIB_JS_URL . 'gplus-embed.js', null, '', true );
    }
}

add_action( 'wp_enqueue_scripts', 'pib_add_public_css_js' );

//Add Custom CSS

function pib_add_custom_css() {
    global $pib_options;
    
    $custom_css = trim( $pib_options['custom_css'] );
    
    //Add custom sharebar button width
    $sharebar_btn_width = ( !empty( $pib_options['sharebar_btn_width'] ) ? $pib_options['sharebar_btn_width'] : '100' );
    //Mark as !important
    $sharebar_btn_width_css = '.pib-sharebar li { width: ' . $sharebar_btn_width . 'px; }';
    
    echo "\n" .
        '<style type="text/css">' . "\n" .
        $sharebar_btn_width_css . "\n" .
        $custom_css . "\n" . //Put custom css last
        '</style>' . "\n";
}

add_action( 'wp_head', 'pib_add_custom_css' );

//Function for rendering "Pin It" button base html

function pib_button_base( $post_url, $image_url, $description, $count_layout ) {
    global $pib_options;
	
	$btn_class = '';
    $btn_img_url = '';

    //Specify no-iframe class for all but Stock button
    if ( ( $pib_options['button_style'] != 'image_selected' ) || (bool)$pib_options['use_custom_img_btn'] ) {
        $btn_class .= 'pin-it-button-no-iframe';
    }
    
    //Set button image URL    
    if ( PIB_IS_PRO && (bool)$pib_options['use_custom_img_btn'] ) {
        $btn_img_url = $pib_options['custom_btn_img_url'];
    }
    else {
        //Default non-sprite button image url from Pinterest
        $btn_img_url = '//assets.pinterest.com/images/PinExt.png';
        //New image added on 11/15/2012 if needed:
        //$btn_img_url = '//assets.pinterest.com/images/pidgets/pin_it_button.png';
    }
    
    //Image pre-selected
    if ( $pib_options['button_style'] == 'image_selected' ) {
    
        //Image pre-selected AND use Custom image button
        if ( (bool)$pib_options['use_custom_img_btn'] ) {
            $btn_class .= ' pin-it-button-image-selected';
        }
        
        //Image pre-selected AND use Stock button (embed code from Pinterest)
        else {
            //Official iframe + image pre-selected (original embed code from Pinterest, use their class name)
            $btn_class .= ' pin-it-button';
        }
    }
    
    //User selects image (default)
    //$pib_options['button_style'] == 'user_selects_image' (or blank)
    else {    
        $btn_class .= ' pin-it-button-user-selects-image';
    }
	
    //HTML from Pinterest Goodies 3/19/2012
    //<a href="http://pinterest.com/pin/create/button/?url=[PAGE]&media=[IMG]&description=[DESC]" class="pin-it-button" count-layout="horizontal">
    //<img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
    //rel="nobox" is to prevent lightbox popup
    
	$inner_btn_html = '<img border="0" class="pib-count-img" src="' . $btn_img_url . '" title="Pin It" />';
	$full_btn_html = '';
    
    //Link href always needs all the parameters in it for the count bubble to work
    //Note: leave "http:" here - will break some setups otherwise
    $link_href = 'http://pinterest.com/pin/create/button/?url=' . rawurlencode( $post_url ) . '&media=' . rawurlencode( $image_url ) . 
        '&description='. rawurlencode( $description );
	
	//Full link html with attributes
    $link_html = '<a href="' . $link_href . '" ' .
        'count-layout="' . $count_layout . '" class="' . $btn_class . '" rel="nobox">' .
        $inner_btn_html . '</a>';

	//Count bubble HTML for non-iframe buttons (if count layout specified)
    if (
        ( ( $pib_options['button_style'] == 'user_selects_image' ) || (bool)$pib_options['use_custom_img_btn'] ) &&
        ( $count_layout != 'none' )
       ) {
       
		if ( $count_layout == 'horizontal' ) {
		
			$full_btn_html = '<table class="pib-count-table pib-count-table-horizontal"><tbody><tr>' . "\n" .
				'<td>' . $link_html . '</td>' . "\n" .
				'<td class="pib-count-cell"><div class="pib-count-bubble"></div></td>' . "\n" .
				'</tr></tbody></table>' . "\n";
		}
		elseif ( $count_layout == 'vertical' ) {
		
			$full_btn_html = '<table class="pib-count-table pib-count-table-vertical"><tbody><tr>' . "\n" .
				'<td class="pib-count-cell"><div class="pib-count-bubble"></div></td>' . "\n" .
				'</tr><tr>' . "\n" .
				'<td>' . $link_html . '</td>' . "\n" .
				'</tr></tbody></table>' . "\n";
		}
	}
	else {
		$full_btn_html = $link_html;
	}
	
    return $full_btn_html;
}

//Button HTML to render

function pib_button_html() {
    global $pib_options;
	global $post;
    $postID = $post->ID;
    
    //Return nothing if sharing disabled on current post
	if ( get_post_meta( $postID, 'pib_sharing_disabled', 1 ) ) {			
		return '';
	}

    //Set post url, image url and description from current post meta
	$post_url = get_post_meta( $postID, 'pib_url_of_webpage', true );
	$image_url = get_post_meta( $postID, 'pib_url_of_img', true );
	$description = get_post_meta( $postID, 'pib_description', true );
    
    //Use featured image if specified (also not blank and Pro only)
    $featured_img_array = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'full' );
    $featured_img_url = $featured_img_array[0];
    if ( PIB_IS_PRO && (bool)$pib_options['use_featured_image'] && !empty( $featured_img_url ) ) {
        $image_url = $featured_img_url;
    }
    
    //Set post url to current post if still blank
    if ( empty( $post_url ) ) { $post_url = get_permalink( $postID ); }
    
    //Set image url to first image if still blank
    if ( empty( $image_url ) ) {
        //Get url of img and compare width and height
        $output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
        $first_img = $matches [1] [0];
        $image_url = $first_img;
    }
    
    //Set description to post title if still blank
    if ( empty( $description ) ) { $description = get_the_title( $postID ); }
    
	$count_layout = $pib_options['count_layout'];

    $base_btn = pib_button_base( $post_url, $image_url, $description, $count_layout );
    
    //Don't wrap with div if using other sharing buttons or "remove div" is checked
    if ( (bool)$pib_options['use_other_sharing_buttons'] || (bool)$pib_options['remove_div'] ) {
        return $base_btn;
    }
    else {
        //Surround with div tag
        return '<div class="pin-it-btn-wrapper">' . $base_btn . '</div>';
    }
}

//Share Bar HTML to render (pass through button unless Pro)

function pib_sharebar_html() {
    global $pib_options;
	global $post;
    $postID = $post->ID;
    
    if ( PIB_IS_PRO && (bool)$pib_options['use_other_sharing_buttons'] ) {
        return pib_sharebar_html_pro();
    } else {
        return pib_button_html();
    }
}

//Render share bar on pages with regular content

function pib_render_content( $content ) {
    global $pib_options;
 	global $post;    
	$postID = $post->ID;

    //Determine if button displayed on current page from main admin settings
    if (
        ( is_home() && ( (bool)$pib_options['display_home_page'] ) ) ||
        ( is_front_page() && ( (bool)$pib_options['display_front_page'] ) ) ||
		( is_single() && ( (bool)$pib_options['display_posts'] ) ) ||
        ( is_page() && ( (bool)$pib_options['display_pages'] ) && !is_front_page() ) ||
        
        //archive pages besides categories (tag, author, date, search)
        //http://codex.wordpress.org/Conditional_Tags
        ( is_archive() && ( (bool)$pib_options['display_archives'] ) && 
            ( is_tag() || is_author() || is_date() || is_search() ) 
        )
       ) {
        if ( (bool)$pib_options['display_above_content'] ) {
            $content = pib_sharebar_html( $postID ) . $content;
        }
        if ( (bool)$pib_options['display_below_content'] ) {
            $content .= pib_sharebar_html( $postID );
        }
    }	
	 
	//Determine if displayed on Category on the base of category edit Screen Option
	if ( is_archive() && ( (bool)$pib_options['display_archives'] ) ) {
		$tag_extra_fields = get_option( PIB_CATEGORY_FIELDS );
		$category_ids = get_all_category_ids();
		foreach( $category_ids as $term_id ) {					 
			if( !$tag_extra_fields[$term_id]['checkbox'] ) {
				
				if( is_category($term_id) ) {
					if ( (bool)$pib_options['display_above_content'] ) {
						$content = pib_sharebar_html( $postID ) . $content;
					}
					if ( (bool)$pib_options['display_below_content'] ) {
						$content .= pib_sharebar_html( $postID );
					}
				}
			}				
		}
	}
    
	return $content;
}

add_filter( 'the_content', 'pib_render_content' );

//Render share bar on pages with excerpts if option checked

function pib_render_content_excerpt( $content ) {
    global $pib_options;
    global $post;
	$postID = $post->ID;
	
    if ( $pib_options['display_on_post_excerpts'] ) {
        if (
            ( is_home() && ( $pib_options['display_home_page'] ) ) ||
            ( is_front_page() && ( $pib_options['display_front_page'] ) )           
           ) {
            if ( $pib_options['display_above_content'] ) {
                $content = pib_sharebar_html( $postID ) . $content;
            }
            if ( $pib_options['display_below_content'] ) {
                $content .= pib_sharebar_html( $postID );
            }
        }   
	
		//Determine if displayed on Category on the base of category edit Screen Option
		if( is_archive() && ( $pib_options['display_archives'] ) ) {
				
            $tag_extra_fields = get_option( PIB_CATEGORY_FIELDS );
            $category_ids = get_all_category_ids();
            foreach( $category_ids as $term_id ) {                     
                if( !$tag_extra_fields[$term_id]['checkbox'] ) {                            
                    
                    if(is_category($term_id)) {	
                        if ( $pib_options['display_above_content'] ) {
                            $content = pib_sharebar_html( $postID ) . $content;
                        }
                        if ( $pib_options['display_below_content'] ) {
                            $content .= pib_sharebar_html( $postID );
                        }
                    }
                }
            }
		}
	}
    
	return $content;
}

add_filter( 'the_excerpt', 'pib_render_content_excerpt' );

//Add thumbnail support to theme if needed

function pib_add_thumbnail_support() {
	if ( !current_theme_supports( 'post-thumbnails' ) ) {
		add_theme_support( 'post-thumbnails' );
	}
}

add_action('init', 'pib_add_thumbnail_support');
