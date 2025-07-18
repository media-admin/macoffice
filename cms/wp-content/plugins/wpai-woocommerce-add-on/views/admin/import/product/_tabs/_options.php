<div class="panel woocommerce_options_panel" id="add_on_options" style="display:none;">
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
    <p class="form-field"><?php _e('Import options','wpai_woocommerce_addon_plugin');?></p>

    <?php if ( "new" == $post['wizard_type'] && version_compare(PMXI_VERSION, '4.7.1-beta-1.2') < 0): ?>
    <div class="options_group hide_if_external">
        <p class="form-field wpallimport-radio-field">
            <input type="hidden" name="missing_records_stock_status" value="0" />
            <input type="checkbox" id="missing_records_stock_status" name="missing_records_stock_status" value="1" <?php echo $post['missing_records_stock_status'] ? 'checked="checked"' : '' ?> />
            <label for="missing_records_stock_status"><?php _e('Set out of stock status for missing records', 'wpai_woocommerce_addon_plugin') ?></label>
            <a href="#help" class="wpallimport-help" title="<?php _e('Option to set the stock status to out of stock instead of deleting the product entirely.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:-2px;">?</a>
        </p>
    </div>
    <?php endif; ?>
    <div class="options_group">
        <p class="form-field wpallimport-radio-field">
            <input type="hidden" name="disable_auto_sku_generation" value="0" />
            <input type="checkbox" id="disable_auto_sku_generation" name="disable_auto_sku_generation" value="1" <?php echo $post['disable_auto_sku_generation'] ? 'checked="checked"' : '' ?> />
            <label for="disable_auto_sku_generation"><?php _e('Disable auto SKU generation', 'wpai_woocommerce_addon_plugin') ?></label>
            <a href="#help" class="wpallimport-help" title="<?php _e('Plugin will NOT automaticaly generate the SKU for each product based on md5 algorithm, if SKU option is empty.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:-2px;">?</a>
        </p>
        <p class="form-field wpallimport-radio-field">
            <input type="hidden" name="disable_sku_matching" value="0" />
            <input type="checkbox" id="disable_sku_matching" name="disable_sku_matching" value="1" <?php echo $post['disable_sku_matching'] ? 'checked="checked"' : '' ?> />
            <label for="disable_sku_matching"><?php _e('Don\'t check for duplicate SKUs', 'wpai_woocommerce_addon_plugin') ?></label>
            <a href="#help" class="wpallimport-help" title="<?php _e('Each product should have a unique SKU. If this box is checked, WP All Import won\'t check for duplicate SKUs, which speeds up the import process. Make sure the SKU for each of your products is unique. If this box is unchecked, WP All Import will import products with duplicate SKUs with a blank SKU.', 'wpai_woocommerce_addon_plugin') ?>" style="position:relative; top:-2px;">?</a>
        </p>

    </div>
</div>