<div class="panel woocommerce_options_panel" id="advanced_product_data" style="display:none;">
	<?php if (class_exists('PMWI_Plugin') && PMWI_EDITION == 'free'): ?>
        <div class="woo-add-on-free-edition-notice upgrade_template">
			<?php if(class_exists('PMXI_Plugin') && PMXI_EDITION == 'paid'):?>
                <a href="https://www.wpallimport.com/portal/discounts/?utm_source=import-plugin-pro&utm_medium=upgrade-notice&utm_campaign=import-woo" target="_blank" class="upgrade_woo_link"><?php _e('Upgrade to the Pro edition of the WooCommerce Add-On to Import to Variable, Affiliate, and Grouped Products', 'wpai_woocommerce_addon_plugin');?></a>
			<?php else: ?>
                <a href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839961&edd_options%5Bprice_id%5D=1" target="_blank" class="upgrade_woo_link"><?php _e('Upgrade to the WooCommerce Import Package to Import to Variable, Affiliate, and Grouped Products', 'wpai_woocommerce_addon_plugin');?></a>
			<?php endif; ?>
            <p><?php _e('If you already own it, remove the free edition and install the Pro edition.', 'wp_all_import_plugin'); ?></p>
        </div>
	<?php endif; ?>
	<div class="options_group hide_if_external">
		<p class="form-field">
			<label><?php _e("Purchase Note", 'wpai_woocommerce_addon_plugin'); ?></label>
			<input type="text" class="short" placeholder="" name="single_product_purchase_note" style="" value="<?php echo esc_attr($post['single_product_purchase_note']) ?>"/>
		</p>
	</div>
	<div class="options_group">
		<p class="form-field">
			<label><?php _e("Menu order", 'wpai_woocommerce_addon_plugin'); ?></label>
			<input type="text" class="short" placeholder="" name="single_product_menu_order" value="<?php echo esc_attr($post['single_product_menu_order']) ?>"/>
		</p>
	</div>

    <div class="options_group show_if_subscription show_if_variable_subscription">
        <div class="form-field wpallimport-radio-field">
            <input type="radio" id="multiple_product_subscription_limit_yes" class="switcher" name="is_multiple_product_subscription_limit" value="yes" <?php echo 'no' != $post['is_multiple_product_subscription_limit'] ? 'checked="checked"': '' ?>/>
            <label for="multiple_product_subscription_limit_yes"><?php _e("Subscription Limit", 'wpai_woocommerce_addon_plugin'); ?></label>
            <span class="wpallimport-clear"></span>
            <div class="switcher-target-multiple_product_subscription_limit_yes set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<select class="select short" name="multiple_product_subscription_limit">
						<option value="no" <?php echo 'no' == $post['multiple_product_subscription_limit'] ? 'selected="selected"': '' ?>><?php _e('No', 'wpai_woocommerce_addon_plugin');?></option>
						<option value="active" <?php echo 'active' == $post['multiple_product_subscription_limit'] ? 'selected="selected"': '' ?>><?php _e('Active', 'wpai_woocommerce_addon_plugin');?></option>
						<option value="any" <?php echo 'any' == $post['multiple_product_subscription_limit'] ? 'selected="selected"': '' ?>><?php _e('Any', 'wpai_woocommerce_addon_plugin');?></option>
					</select>
				</span>
            </div>
        </div>

        <div class="form-field wpallimport-radio-field">
            <input type="radio" id="multiple_product_subscription_limit_no" class="switcher" name="is_multiple_product_subscription_limit" value="no" <?php echo 'no' == $post['is_multiple_product_subscription_limit'] ? 'checked="checked"': '' ?>/>
            <label for="multiple_product_subscription_limit_no"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' ); ?></label>
            <span class="wpallimport-clear"></span>
            <div class="switcher-target-multiple_product_subscription_limit_no set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_subscription_limit" style="width:300px;" value="<?php echo esc_attr($post['single_product_subscription_limit']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('Value should be the slug for the tax status - \'no\', \'active\', and \'any\' are the default slugs.', 'wpai_woocommerce_addon_plugin') ?>">?</a>
				</span>
            </div>
        </div>
    </div>

	<div class="options_group reviews">

		<p class="form-field"><?php _e('Enable reviews','wpai_woocommerce_addon_plugin');?></p>
		
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="product_enable_reviews_yes" class="switcher" name="is_product_enable_reviews" value="yes" <?php echo 'yes' == $post['is_product_enable_reviews'] ? 'checked="checked"': '' ?>/>
			<label for="product_enable_reviews_yes"><?php _e("Yes"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="product_enable_reviews_no" class="switcher" name="is_product_enable_reviews" value="no" <?php echo 'no' == $post['is_product_enable_reviews'] ? 'checked="checked"': '' ?>/>
			<label for="product_enable_reviews_no"><?php _e("No"); ?></label>
		</p>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="product_enable_reviews_xpath" class="switcher" name="is_product_enable_reviews" value="xpath" <?php echo 'xpath' == $post['is_product_enable_reviews'] ? 'checked="checked"': '' ?>/>
			<label for="product_enable_reviews_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-product_enable_reviews_xpath set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0px;">
					<input type="text" class="smaller-text" name="single_product_enable_reviews" style="width:300px;" value="<?php echo esc_attr($post['single_product_enable_reviews']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
				</span>
			</div>
		</div>
		
	</div> <!-- End options group -->

	<div class="options_group">
		
		<p class="form-field"><?php _e('Featured','wpai_woocommerce_addon_plugin');?></p>
		
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="product_featured_yes" class="switcher" name="is_product_featured" value="yes" <?php echo 'yes' == $post['is_product_featured'] ? 'checked="checked"': '' ?>/>
			<label for="product_featured_yes"><?php _e("Yes"); ?></label>
		</p>
		<p class="form-field wpallimport-radio-field">
			<input type="radio" id="product_featured_no" class="switcher" name="is_product_featured" value="no" <?php echo 'no' == $post['is_product_featured'] ? 'checked="checked"': '' ?>/>
			<label for="product_featured_no"><?php _e("No"); ?></label>
		</p>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="product_featured_xpath" class="switcher" name="is_product_featured" value="xpath" <?php echo 'xpath' == $post['is_product_featured'] ? 'checked="checked"': '' ?>/>
			<label for="product_featured_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-product_featured_xpath set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_featured" style="width:300px;" value="<?php echo esc_attr($post['single_product_featured']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'yes\', \'no\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
				</span>
			</div>
		</div>
		
	</div> <!-- End options group -->

	<div class="options_group">
		
		<p class="form-field"><?php _e('Catalog visibility','wpai_woocommerce_addon_plugin');?></p>

		<?php if (function_exists('wc_get_product_visibility_options')): ?>
			<?php $visibility_options = wc_get_product_visibility_options();?>
			<?php foreach ($visibility_options as $visibility_option_key => $visibility_option_name):?>
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="product_visibility_<?php echo $visibility_option_key;?>" class="switcher" name="is_product_visibility" value="<?php echo $visibility_option_key; ?>" <?php echo $post['is_product_visibility'] == $visibility_option_key ? 'checked="checked"': '' ?>/>
				<label for="product_visibility_<?php echo $visibility_option_key;?>"><?php echo $visibility_option_name; ?></label>
			</p>
			<?php endforeach; ?>
		<?php else: ?>
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="product_visibility_visible" class="switcher" name="is_product_visibility" value="visible" <?php echo 'visible' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
				<label for="product_visibility_visible"><?php _e("Catalog/search", 'wpai_woocommerce_addon_plugin'); ?></label>
			</p>
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="product_visibility_catalog" class="switcher" name="is_product_visibility" value="catalog" <?php echo 'catalog' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
				<label for="product_visibility_catalog"><?php _e("Catalog", 'wpai_woocommerce_addon_plugin'); ?></label>
			</p>
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="product_visibility_search" class="switcher" name="is_product_visibility" value="search" <?php echo 'search' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
				<label for="product_visibility_search"><?php _e("Search", 'wpai_woocommerce_addon_plugin'); ?></label>
			</p>
			<p class="form-field wpallimport-radio-field">
				<input type="radio" id="product_visibility_hidden" class="switcher" name="is_product_visibility" value="hidden" <?php echo 'hidden' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
				<label for="product_visibility_hidden"><?php _e("Hidden", 'wpai_woocommerce_addon_plugin'); ?></label>
			</p>
		<?php endif; ?>
		<div class="form-field wpallimport-radio-field">
			<input type="radio" id="product_visibility_xpath" class="switcher" name="is_product_visibility" value="xpath" <?php echo 'xpath' == $post['is_product_visibility'] ? 'checked="checked"': '' ?>/>
			<label for="product_visibility_xpath"><?php _e('Set with XPath', 'wpai_woocommerce_addon_plugin' )?></label>
			<span class="wpallimport-clear"></span>
			<div class="switcher-target-product_visibility_xpath set_with_xpath">
				<span class="wpallimport-slide-content" style="padding-left:0;">
					<input type="text" class="smaller-text" name="single_product_visibility" style="width:300px;" value="<?php echo esc_attr($post['single_product_visibility']) ?>"/>
					<a href="#help" class="wpallimport-help" title="<?php _e('The value of presented XPath should be one of the following: (\'visible\', \'catalog\', \'search\', \'hidden\').', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:2px;">?</a>
				</span>
			</div>
		</div>
		
	</div> <!-- End options group -->
</div><!-- End Product Panel -->
