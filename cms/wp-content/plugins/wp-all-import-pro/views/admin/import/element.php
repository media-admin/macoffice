
<h2 class="wpallimport-wp-notices"></h2>

<form class="wpallimport-choose-elements no-enter-submit wpallimport-step-2 wpallimport-wrapper" method="post">
	<div class="wpallimport-header">
		<div class="wpallimport-logo"></div>
		<div class="wpallimport-title">
			<h2><?php _e('Review Import File', 'wp-all-import-pro'); ?></h2>
		</div>
		<div class="wpallimport-links">
			<a href="https://www.wpallimport.com/support/" target="_blank"><?php _e('Support', 'wp-all-import-pro'); ?></a> | <a href="https://www.wpallimport.com/documentation/" target="_blank"><?php _e('Documentation', 'wp-all-import-pro'); ?></a>
		</div>
	</div>
	<div class="clear"></div>
	<?php
	switch (PMXI_Plugin::$session->custom_type){
		case 'taxonomies':
			$custom_type = new stdClass();
			$custom_type->labels = new stdClass();
			$custom_type->labels->singular_name = empty($tx->labels->singular_name) ? __('Taxonomy Term', 'wp-all-import-pro') : $tx->labels->singular_name;
			$custom_type->labels->name = empty($tx->labels->name) ? __('Taxonomy Terms', 'wp-all-import-pro') : $tx->labels->name;
			break;
        case 'comments':
            $custom_type = new stdClass();
            $custom_type->labels = new stdClass();
            $custom_type->labels->singular_name = __('Comments', 'wp-all-import-pro');
            $custom_type->labels->name = __('Comment', 'wp-all-import-pro');
            break;
		default:
			$custom_type = wp_all_import_custom_type( PMXI_Plugin::$session->custom_type );
			break;
	}
    if (empty($custom_type) && PMXI_Plugin::$session->custom_type) {
        $custom_type = new stdClass();
        $custom_type->labels = new stdClass();
        $custom_type->labels->singular_name = ucwords(preg_replace("%[_-]%", " ", PMXI_Plugin::$session->custom_type));
    }
	?>
	<div class="wpallimport-content-section wpallimport-console">
		<div class="ajax-console">
			<?php if ($this->errors->get_error_codes()): ?>
				<?php $this->error() ?>
			<?php endif ?>
		</div>
		<input type="submit" class="button button-primary button-hero wpallimport-large-button" value="<?php _e('Continue to Setup Import', 'wp-all-import-pro'); ?>" style="position:absolute; top:45px; right:10px;"/>
	</div>

	<div class="wpallimport-content-section wpallimport-elements-preloader">
		<div class="preload" style="height: 80px; margin-top: 25px;"></div>
	</div>

	<div class="wpallimport-content-section" style="padding-bottom:0; max-height: 600px; overflow:scroll; width: 100%;">

		<table class="wpallimport-layout" style="width:100%;">
			<tr>
				<?php if ( ! $is_csv): ?>
				<td class="left" style="width: 25%; min-width: unset; border-right: 1px solid #ddd;">
					<h3 class="txt_center"><?php _e('What element are you looking for?', 'wp-all-import-pro'); ?></h3>
					<?php
					if ( ! empty($elements_cloud) and ! $is_csv ){
						foreach ($elements_cloud as $tag => $count){
							?>
							<a href="javascript:void(0);" rel="<?php echo $tag;?>" class="wpallimport-change-root-element <?php if (PMXI_Plugin::$session->source['root_element'] == $tag) echo 'selected';?>">
								<span class="tag_name"><?php echo strtolower($tag); ?></span>
								<span class="tag_count"><?php echo $count; ?></span>
							</a>
							<?php
						}
					}
					?>
				</td>
				<?php endif; ?>
				<td class="right" <?php if ( ! $is_csv){?>style="width:75%; padding:0;"<?php } else {?>style="width:100%; padding:0;"<?php }?>>
					<div class="action_buttons">
						<table style="width:100%;">
							<tr>
								<td>
									<a href="javascript:void(0);" id="prev_element" class="wpallimport-go-to">&nbsp;</a>
								</td>
								<td class="txt_center">

									<p class="wpallimport-root-element">
										<?php echo PMXI_Plugin::$session->source['root_element'];?>
									</p>
									<input type="text" id="goto_element" value="1"/>
									<span class="wpallimport-elements-information">
										<?php printf(__('of <span class="wpallimport-elements-count-info">%s</span>','wp-all-import-pro'), PMXI_Plugin::$session->count);?>
									</span>

								</td>
								<td>
									<a href="javascript:void(0);" id="next_element" class="wpallimport-go-to">&nbsp;</a>
								</td>
							</tr>
						</table>
					</div>
					<fieldset class="widefat" style="background:fafafa;">

						<div class="input">

							<?php if ($is_csv !== false): ?>

								<div class="wpallimport-set-csv-delimiter">
									<label>
										<?php _e("Set delimiter for CSV fields:", 'wp-all-import-pro'); ?>
									</label>
									<input type="text" name="delimiter" value="<?php echo $is_csv;?>"/>
									<input type="button" name="apply_delimiter" class="rad4" value="<?php _e('Apply', 'wp-all-import-pro'); ?>"/>
								</div>

							<?php else: ?>

								<input type="hidden" value="" name="delimiter"/>

							<?php endif; ?>

						</div>

						<div class="wpallimport-xml">
							<?php //$this->render_xml_element($dom->documentElement) ?>
						</div>
					</fieldset>
					<div class="import_information">
						<?php if (PMXI_Plugin::$session->wizard_type == 'new') :?>
						<h3>
							<?php printf(__('Each <span>&lt;<span class="root_element">%s</span>&gt;</span> element will be imported into a <span>New %s</span>', 'wp-all-import-pro'), PMXI_Plugin::$session->source['root_element'], $custom_type->labels->singular_name); ?>
						</h3>
						<?php else: ?>
						<h3>
							<?php printf(__('Data in <span>&lt;<span class="root_element">%s</span>&gt;</span> elements will be imported to <span>%s</span>', 'wp-all-import-pro'), PMXI_Plugin::$session->source['root_element'], $custom_type->labels->name); ?>
						</h3>
						<?php endif; ?>

						<h3 class="wp_all_import_warning">
							<?php _e('This doesn\'t look right, try manually selecting a different root element on the left.', 'wp-all-import-pro'); ?>
						</h3>

					</div>
				</td>
			</tr>
		</table>
	</div>

	<?php require_once 'filters.php'; ?>

	<hr>

	<p class="wpallimport-submit-buttons" style="text-align:center;">
		<a href="<?php echo esc_url(add_query_arg('action', 'index', $this->baseUrl)); ?>" class="back rad3"><?php _e('Back to Data Source','wp-all-import-pro');?></a>
		&nbsp;
		<input type="hidden" name="is_submitted" value="1" />
		<?php wp_nonce_field('choose-elements', '_wpnonce_choose-elements') ?>
		<input type="submit" class="button button-primary button-hero wpallimport-large-button" value="<?php _e('Continue to Setup Import', 'wp-all-import-pro'); ?>" />
	</p>
	<a href="http://soflyy.com/" target="_blank" class="wpallimport-created-by"><?php _e('Created by', 'wp-all-import-pro'); ?> <span></span></a>

</form>
