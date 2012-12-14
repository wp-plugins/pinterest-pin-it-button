<?php

//*** Admin Display Functions - Lite & Pro Shared ***

function pib_settings_page() {
	global $pib_options;
    
    $custom_css = trim( $pib_options['custom_css'] );
	?>
    
    <div class="wrap">        
        <?php screen_icon( 'pib-icon32' ); ?>        
        <h2><?php _e( 'Pinterest "Pin It" Button ' . pib_pro_or_lite() . ' Settings', 'pib' ); ?></h2>
        
        <?php ( PIB_IS_PRO ? '' : pib_upgrade_banner_top() ); ?>
        
        <div id="poststuff" class="metabox-holder has-right-sidebar">

			<!-- Fixed right sidebar like WP post edit screen -->
            
			<div id="side-info-column" class="inner-sidebar">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <?php ( PIB_IS_PRO ? pib_settings_sidebar_pro() : pib_settings_sidebar_lite() ); ?>
				</div>
                
                <?php //pib_debug_options(); ?>
            </div>
			
			<div id="post-body">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<?php settings_errors(); //Display status messages after action ("settings saved", errors) ?>
					
						<form method="post" action="options.php">
							<?php settings_fields( 'pib_settings_group' ); ?>
                            
                            <?php if ( PIB_IS_PRO ) { pib_license_key_input(); } ?>
							
							<div class="postbox pib-postbox">
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle"><?php _e( 'Button Style & Pin Count', 'pib' ); ?></h3>

								<table class="form-table inside">
									<tr valign="top">
										<td>
											<input type="radio" id="user_selects_image" value="user_selects_image" name="pib_options[button_style]"
												<?php checked( ( $pib_options['button_style'] == 'user_selects_image' ) || empty( $pib_options['button_style'] ) ); ?> />
											<label for="user_selects_image"><?php _e( '<strong>User selects image</strong> from popup', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input type="radio" id="image_selected" value="image_selected" name="pib_options[button_style]" 
												<?php checked( $pib_options['button_style'], 'image_selected' ); ?> />
											<label for="image_selected"><?php _e( 'Image is <strong>pre-selected</strong> (defaults to first image in post)', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td class="pib-pad-cell-top">
											<label for="count_layout" class="pib-plain-label"><?php _e( 'Pin Count:' ); ?></label>
											<select id="count_layout" name="pib_options[count_layout]">
												<option value="none" <?php selected( ( $pib_options['count_layout'] == 'none' ) || empty( $pib_options['count_layout'] ) ); ?>><?php _e( 'No Count' ); ?></option>
												<option value="horizontal" <?php selected( $pib_options['count_layout'], 'horizontal' ); ?>><?php _e( 'Horizontal' ); ?></option>
												<option value="vertical" <?php selected( $pib_options['count_layout'], 'vertical' ); ?>><?php _e( 'Vertical' ); ?></option>
											</select>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input type="checkbox" id="use_featured_image" name="pib_options[use_featured_image]" value="1"                                            
                                                <?php checked( (bool)$pib_options['use_featured_image'] ); ?>                                                
                                                <?php pib_lite_disabled_attr(); ?> />
											<label for="use_featured_image" class="<?php pib_lite_disabled_class(); ?>">
                                                <?php _e( 'For pre-selected image, use <strong>featured image</strong> if available', 'pib' ); ?></label>
                                            <?php pib_pro_label(); ?>
										</td>
									</tr>								
									<tr valign="top">
										<td class="pib-pad-cell-top">
                                            <?php _e( 'You may individually override what website address (URL), image and description will be pinned for each post. ' .
                                                'Go to the post (or page) <strong>edit screen</strong> and scroll to the bottom.', 'pib' ); ?>
										</td>
									</tr>
									<tr valign="top">
										<td>
                                            <?php _e( 'Button style settings apply to <strong>all</strong> "Pin It" buttons including widgets and shortcodes. ', 'pib' ); ?>											
										</td>
									</tr>                                
								</table>
							</div>
							
							<div class="postbox pib-postbox">
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle">
                                    <?php _e( 'Custom Button Image', 'pib' ); ?>
                                    <?php pib_pro_label(); ?>
                                </h3>

								<table class="form-table inside">
                                    
                                    <?php if ( PIB_IS_PRO ): ?>
                                    
									<tr valign="top">
										<td>
											<input type="checkbox" id="use_custom_img_btn" name="pib_options[use_custom_img_btn]" value="1" 
                                                <?php checked( (bool)$pib_options['use_custom_img_btn'] ); ?> />
											<label for="use_custom_img_btn">
                                                <?php _e( 'Enable custom button image', 'pib' ); ?>
                                            </label>
										</td>
									</tr>                                    
									<tr id="custom_img_btn_select_row" valign="top">
										<td>                                            
                                            <table>
                                                <tr>
                                                    <td>Current image:</td>
                                                    <td>
                                                        <img id="custom_btn_img" src="<?php echo ( !empty( $pib_options['custom_btn_img_url'] ) ? $pib_options['custom_btn_img_url'] : PIB_DEFAULT_CUSTOM_BUTTON_IMAGE_URL ); ?>" />
                                                    </td>
                                                    <td>
                                                        <a id="custom_btn_img_select_link" href="#TB_inline?width=600&amp;height=400&amp;inlineId=custom_img_btn_selector" class="thickbox"
                                                            title="Select a Custom Button"><?php _e( 'Select New Button Image', 'pib' ); ?></a>
                                                    </td>
                                                    
                                                    <input type="hidden" id="custom_btn_img_url" name="pib_options[custom_btn_img_url]" 
                                                        value="<?php echo ( !empty( $pib_options['custom_btn_img_url'] ) ? $pib_options['custom_btn_img_url'] : PIB_DEFAULT_CUSTOM_BUTTON_IMAGE_URL ); ?>" />
                                                    
                                                    <!-- Thickbox popup image selector div in separate function (Pro) -->                                                    
                                                </tr>
                                            </table>
										</td>
									</tr>

                                    <?php else: ?>                                    
                                    
									<tr valign="top">
										<td>                                            
                                            <table id="custom_img_btn_lite_row" class="pib-no-margin">
                                                <tr>
                                                    <td><?php _e( 'Custom button image examples', 'pib' ); ?>:</td>
                                                    <td>
                                                        <img src="<?php echo PIB_IMAGES_URL . 'pin-it-button-pro-custom-image-examples-small.png'; ?>" alt="Custom button image preview" />
                                                    </td>
                                                    <td>
                                                        <strong><a href="#TB_inline?width=600&amp;height=400&amp;inlineId=custom_img_btn_examples" class="thickbox"
                                                            title="Custom Button Image Examples"><?php _e( 'See More Button Images', 'pib' ); ?></a></strong>
                                                    </td>
                                                </tr>
                                            </table>
										</td>
									</tr>
									<tr valign="top">
										<td>
                                            <div class="pib-upgrade-to-pro">
                                                <?php _e( 'Available in "Pin It" Button Pro. ', 'pib' ); ?>
                                                <a href="<?php echo PIB_UPGRADE_URL_BASE . pib_campaign_url( 'upgrade_link_custom_button', 'pro_upgrade' ); ?>" target="_blank" class="external"><?php _e( 'Upgrade Now', 'pib' ); ?></a>
                                            </div>
										</td>
									</tr>
                                    
                                    <?php endif; ?>
								</table>
							</div>
                            
							<div class="postbox pib-postbox">
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle">
                                    <?php _e( 'Share Bar (Facebook, Twitter & Google +1 Buttons)', 'pib' ); ?>
                                    <?php pib_pro_label(); ?>
                                </h3>

								<table class="form-table inside">
                                
                                    <?php if ( PIB_IS_PRO ): ?>
                                
									<tr valign="top">
										<td colspan="2">
											<input type="checkbox" id="use_other_sharing_buttons" name="pib_options[use_other_sharing_buttons]" value="1" 
                                                <?php checked( (bool)$pib_options['use_other_sharing_buttons'] ); ?> />
											<label for="use_other_sharing_buttons">
                                                <?php _e( 'Enable other social sharing buttons (Facebook, Twitter and/or Google +1)', 'pib' ); ?>
                                            </label>
										</td>
									</tr>
                                    
                                    <?php endif; ?>
                                    
									<tr valign="top">
                                        <th scope="row">
                                            <label id="share_btn_label" class="pib-plain-label"><?php _e( 'Button order (left to right):', 'pib' ); ?></label>
                                        </th>
                                        <td class="pib-pad-cell-top">
                                            <select id="share_btn_1" name="pib_options[share_btn_1]">
                                                <option value="none" <?php selected( ( $pib_options['share_btn_1'] == 'none' ) || empty( $pib_options['share_btn_1'] ) ); ?>>-- <?php _e( 'None', 'pib' ); ?> --</option>
                                                <option value="pinterest" <?php selected( $pib_options['share_btn_1'], 'pinterest' ); ?>>Pinterest</option>
                                                <option value="facebook" <?php selected( $pib_options['share_btn_1'], 'facebook' ); ?>>Facebook</option>
                                                <option value="twitter" <?php selected( $pib_options['share_btn_1'], 'twitter' ); ?>>Twitter</option>
                                                <option value="gplus" <?php selected( $pib_options['share_btn_1'], 'gplus' ); ?>>Google +1</option>
                                            </select>
                                            
                                            <select id="share_btn_2" name="pib_options[share_btn_2]">
                                                <option value="none" <?php selected( ( $pib_options['share_btn_2'] == 'none' ) || empty( $pib_options['share_btn_2'] ) ); ?>>-- <?php _e( 'None', 'pib' ); ?> --</option>
                                                <option value="pinterest" <?php selected( $pib_options['share_btn_2'], 'pinterest' ); ?>>Pinterest</option>
                                                <option value="facebook" <?php selected( $pib_options['share_btn_2'], 'facebook' ); ?>>Facebook</option>
                                                <option value="twitter" <?php selected( $pib_options['share_btn_2'], 'twitter' ); ?>>Twitter</option>
                                                <option value="gplus" <?php selected( $pib_options['share_btn_2'], 'gplus' ); ?>>Google +1</option>
                                            </select>

                                            <select id="share_btn_3" name="pib_options[share_btn_3]">
                                                <option value="none" <?php selected( ( $pib_options['share_btn_3'] == 'none' ) || empty( $pib_options['share_btn_3'] ) ); ?>>-- <?php _e( 'None', 'pib' ); ?> --</option>
                                                <option value="pinterest" <?php selected( $pib_options['share_btn_3'], 'pinterest' ); ?>>Pinterest</option>
                                                <option value="facebook" <?php selected( $pib_options['share_btn_3'], 'facebook' ); ?>>Facebook</option>
                                                <option value="twitter" <?php selected( $pib_options['share_btn_3'], 'twitter' ); ?>>Twitter</option>
                                                <option value="gplus" <?php selected( $pib_options['share_btn_3'], 'gplus' ); ?>>Google +1</option>
                                            </select>

                                            <select id="share_btn_4" name="pib_options[share_btn_4]">
                                                <option value="none" <?php selected( ( $pib_options['share_btn_4'] == 'none' ) || empty( $pib_options['share_btn_4'] ) ); ?>>-- <?php _e( 'None', 'pib' ); ?> --</option>
                                                <option value="pinterest" <?php selected( $pib_options['share_btn_4'], 'pinterest' ); ?>>Pinterest</option>
                                                <option value="facebook" <?php selected( $pib_options['share_btn_4'], 'facebook' ); ?>>Facebook</option>
                                                <option value="twitter" <?php selected( $pib_options['share_btn_4'], 'twitter' ); ?>>Twitter</option>
                                                <option value="gplus" <?php selected( $pib_options['share_btn_4'], 'gplus' ); ?>>Google +1</option>
                                            </select>
										</td>
									</tr>
                                    
                                    <?php if ( PIB_IS_PRO ) { pib_sharebar_more_options(); } ?>
                                    
                                    <?php if ( !PIB_IS_PRO ): ?>
                                    
									<tr valign="top">
										<td class="pib-pad-cell-top" colspan="2">
                                            <div class="pib-upgrade-to-pro">
                                                <?php _e( 'Available in "Pin It" Button Pro.', 'pib' ); ?>
                                                <a href="<?php echo PIB_UPGRADE_URL_BASE . pib_campaign_url( 'upgrade_link_social_buttons', 'pro_upgrade' ); ?>" target="_blank" class="external"><?php _e( 'Upgrade Now', 'pib' ); ?></a>
                                            </div>
										</td>
									</tr>
                                    
                                    <?php endif; ?>
								</table>
							</div>
                            
							<div class="pib-submit-settings">
								<input name="submit" type="submit" value="<?php _e( 'Save Changes', 'pib' ); ?>" class="button-primary" />
							</div>                            

							<div class="postbox pib-postbox">
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle"><?php _e( 'Page/Post Visibility', 'pib' ); ?></h3>
								
								<table class="form-table inside">
									<tr valign="top">
										<td>
											<?php _e( 'What types of pages should the button appear on?', 'pib' ); ?>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_home_page" name="pib_options[display_home_page]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_home_page'] ); ?> />
											<label for="display_home_page"><?php _e( 'Blog Home Page (or Latest Posts Page)', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_front_page" name="pib_options[display_front_page]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_front_page'] ); ?> />
											<label for="display_front_page"><?php _e( 'Front Page (different from Home Page only if set in Settings > Reading)', 'pib' ); ?></label>
										</td>
									</tr>					
									<tr valign="top">
										<td>
											<input id="display_posts" name="pib_options[display_posts]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_posts'] ); ?> />
											<label for="display_posts"><?php _e( 'Individual Posts', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_pages" name="pib_options[display_pages]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_pages'] ); ?> />
											<label for="display_pages"><?php _e( 'WordPress Static "Pages"', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_archives" name="pib_options[display_archives]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_archives'] ); ?> />
											<label for="display_archives"><?php _e( 'Archives (includes Category, Tag, Author and date-based pages)', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td class="pib-pad-cell-top">
											<?php _e( 'To hide the "Pin It" button for a specific post, page or category, go to the edit screen for that post, page or category,
											scroll down to the bottom, and uncheck the "Show" checkbox.', 'pib' ); ?>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<?php _e( 'Go to Appearance', 'pib' ); ?> &rarr; <a href="<?php echo admin_url( 'widgets.php' ); ?>"><?php _e( 'Widgets', 'pib' ); ?></a>
                                                <?php _e( 'to add a "Pin It" button to your sidebar.', 'pib' ); ?>
										</td>
									</tr>
								</table>
							</div>
								
							<div class="postbox pib-postbox">
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle"><?php _e( 'Page/Post Placement', 'pib' ); ?></h3>
								
								<table class="form-table inside">
									<tr valign="top">
										<td>
											<?php _e( 'Where on each page should the button appear?', 'pib' ); ?>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_above_content" name="pib_options[display_above_content]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_above_content'] ); ?> />
											<label for="display_above_content"><?php _e( 'Above Content', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_below_content" name="pib_options[display_below_content]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_below_content'] ); ?> />
											<label for="display_below_content"><?php _e( 'Below Content', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_on_post_excerpts" name="pib_options[display_on_post_excerpts]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['display_on_post_excerpts'] ); ?> />
											<label for="display_on_post_excerpts"><?php _e( 'On Post Excerpts', 'pib' ); ?></label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<?php _e( 'Currently, only the button style <strong>"image is pre-selected"</strong> will use the individual post URL when a visitor
											pins on a post excerpt.', 'pib' ); ?>
										</td>
									</tr>								
								</table>
							</div>
                            
							<div class="postbox pib-postbox">                         
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle"><?php _e( 'Admin Settings', 'pib' ); ?></h3>
								
								<table class="form-table inside">
									<tr valign="top">
										<td>
											<input id="uninstall_save_settings" name="pib_options[uninstall_save_settings]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['uninstall_save_settings'] ); ?> />
											<label for="uninstall_save_settings">
                                                <?php _e( '<strong>Save settings</strong> when uninstalling this plugin? Useful when upgrading to Pro or re-installing later.', 'pib' ); ?>
                                            </label>
										</td>
									</tr>
								</table>
							</div>                            
							
							<div class="postbox pib-postbox">                         
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle"><?php _e( 'Custom CSS & Styling', 'pib' ); ?></h3>
								
								<table class="form-table inside">
									<tr valign="top">
										<td>
											<label for="custom_css"><?php _e( 'Additional CSS to add', 'pib' ); ?>:</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<textarea id="custom_css" name="pib_options[custom_css]" rows="6"><?php echo $custom_css; ?></textarea>
									   </td>
									</tr>
									<tr valign="top">
										<td>
											<input id="remove_div" name="pib_options[remove_div]" type="checkbox" value="1"
												<?php checked( (bool)$pib_options['remove_div'] ); ?> />
											<label for="remove_div">
                                                <?php _e( 'Remove div tag surrounding regular button', 'pib' ); ?> (<code><?php echo htmlentities('<div class="pin-it-btn-wrapper"></div>'); ?></code>).
                                                <?php _e( 'Already removed if other social sharing buttons enabled.', 'pib' ); ?>
                                            </label>
										</td>
									</tr>
								</table>
							</div>
							
							<div class="postbox pib-postbox">
								<?php pib_handlediv(); ?>
								<h3 class="hndle pib-hndle"><?php _e( 'Shortcode Instructions', 'pib' ); ?></h3>
								
								<div class="inside">
									<p>
										<?php _e( 'Use the shortcode', 'pib' ); ?> <code>[pinit]</code> <?php _e( 'to display the button within your content.', 'pib' ); ?>
									</p>
									<p>
										<?php _e( 'Use the function', 'pib' ); ?> <code><?php echo htmlentities( '<?php echo do_shortcode(\'[pinit]\'); ?>' ); ?></code>
										<?php _e( 'to display within template or theme files.', 'pib' ); ?>
									</p>
									<p><strong>Shortcode parameters</strong></p>
									<p>
										- <?php _e( 'count: none (default), horizontal, vertical', 'pib' ); ?><br/>
										- <?php _e( 'url: URL of the web page to be pinned (defaults to current post/page URL, but <em>must specify</em> if on home or index page)', 'pib' ); ?><br/>
										- <?php _e( 'image_url: URL of the image to be pinned (defaults to first image in post)', 'pib' ); ?><br/>
										- <?php _e( 'description: description of the pin (defaults to post title)', 'pib' ); ?><br/>
										- <?php _e( 'float: none (default), left, right', 'pib' ); ?><br/>
										- <?php _e( 'remove_div: false (default), true -- if true removes surrounding div tag ', 'pib' ); ?>
											(<code><?php echo htmlentities( '<div class="pin-it-btn-wrapper-shortcode"></div>' ); ?></code>),
                                            <?php _e( 'which also removes float setting', 'pib' ); ?><br/>
                                        - <?php _e( 'social_buttons: false (default), true -- if true and enabled above, will show Facebook, Twitter & Google +1 buttons', 'pib' ); ?>
                                            <?php pib_pro_label(); ?>
									</p>
									<p><strong><?php _e( 'Examples', 'pib' ); ?></strong></p>
									<p>
										<code>[pinit count="horizontal"]</code><br/>
										<code>[pinit count="vertical" url="http://www.mysite.com" image_url="http://www.mysite.com/myimage.jpg" 
											description="My favorite image!" float="right"]</code><br/>
									</p>
								</div>
							</div>
							
							<div class="pib-submit-settings">
								<input name="submit" type="submit" value="<?php _e( 'Save Changes', 'pib' ); ?>" class="button-primary" />
							</div>
							
						</form>
					</div>
				</div>
			</div>
        </div>
        
        <?php echo ( PIB_IS_PRO ? pib_custom_btn_img_selector_popup() : pib_custom_btn_img_examples_popup() ); ?>
        
    </div>

    <?php
}

//Handle div tag html

function pib_handlediv() {
    ?>
    <div class="handlediv pib-handlediv" title="Click to toggle"><br /></div>
    <?php
}

//Subscribe by email admin section

function pib_newsletter_subscribe() {
    ?>
    
    <div class="postbox">
        <?php pib_handlediv(); ?>
        <h3 class="hndle pib-hndle"><?php _e( 'Subscribe by Email', 'tpp' ); ?></h3>
        
        <div class="inside">
            <p><?php _e( 'Subscribe to get notified of important updates and news for our Pinterest plugins.', 'pib' ); ?></p>
            &raquo; <a href="http://pinterestplugin.com/newsletter<?php echo pib_campaign_url( 'sidebar_link', 'newsletter' ); ?>" target="_blank" class="external">
                <?php _e( 'Get Updates', 'pib' ); ?></a>
        </div>
    </div>

    <?php
}

//Plugin news feed

function pib_plugin_news() {
    ?>

    <div class="postbox">
        <?php pib_handlediv(); ?>
        <h3 class="hndle pib-hndle"><?php _e( 'Pinterest Plugin News', 'pib' ); ?></h3>
        
        <div class="inside">
            <?php pib_rss_news(); ?>
        </div>
    </div>
    
    <?php
}

//Render rss items from pinterestplugin.com
//http://codex.wordpress.org/Function_Reference/fetch_feed

function pib_rss_news() {
	// Get RSS Feed(s)
	include_once( ABSPATH . WPINC . '/feed.php' );

	// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed( 'http://pinterestplugin.com/feed/' );
	
	if ( !is_wp_error( $rss ) ) {
		// Checks that the object is created correctly 
		// Figure out how many total items there are, but limit it to 5. 
		$maxitems = $rss->get_item_quantity( 3 ); 

		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items( 0, $maxitems ); 
	}
	
	?>

	<ul>
		<?php if ($maxitems == 0): ?>
			<li><?php _e( 'No items.', 'pib' ); ?></li>
		<?php else: ?>
			<?php
			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $rss_items as $item ): ?>
				<li>
					&raquo; <a href="<?php echo esc_url( $item->get_permalink() ) . pib_campaign_url( 'sidebar_link', 'blog_post_link' ); ?>" target="_blank" class="external">
						<?php echo esc_html( $item->get_title() ); ?></a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
	
	<?php
}

//*** Functions depending on Pro vs Lite versions ***

//Return "Pro" or blank string (no need to show "Lite")

function pib_pro_or_lite() {
	return ( PIB_IS_PRO ? 'Pro' : '' );
}

//Lite version: Add disabled attribute

function pib_lite_disabled_attr() {
	if ( !PIB_IS_PRO ) { echo 'disabled="disabled"'; }
}

//Lite version: Add disabled class name

function pib_lite_disabled_class() {
	if ( !PIB_IS_PRO ) { echo 'disabled'; }
}

function pib_pro_label() {
    if ( !PIB_IS_PRO ) { 
        echo '<span class="pib-pro-label">' . __( 'Pro Feature', 'pib' ) . '</span>'; 
    }
}

//Debug options

function pib_debug_options() {
    global $pib_options;    
    ?>
    
    <h4>Debug Options</h4>
    <p>
        <?php foreach ( $pib_options as $option => $value ): ?>
            <?php echo $option . ': ' . $value; ?><br/>
        <?php endforeach; ?>
    </p>
    
    <?php
}
