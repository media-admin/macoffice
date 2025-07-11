<?php if ( ! $is_license_active ): ?>
	<form name="settings" method="post" action="" class="settings">
		<h2 style="padding:0px;"></h2>

		<div class="wpallimport-header">
			<div class="wpallimport-logo"></div>
			<div class="wpallimport-title">
				<h3><?php _e('Settings', 'wp-all-import-pro'); ?></h3>
			</div>
		</div>

		<div class="wpallimport-setting-wrapper">
			<?php if ($this->errors->get_error_codes()): ?>
				<?php $this->error() ?>
			<?php endif ?>

			<h3><?php _e('Licenses', 'wp-all-import-pro') ?></h3>

			<table class="form-table">
				<tbody>

				<?php foreach ($addons as $class => $addon) : if ( ! $addon['active'] ) continue; ?>
					<tr>
						<th scope="row"><label><?php _e('WP All Import License Key', 'wp-all-import-pro'); ?></label></th>
						<td>
							<input type="password" class="regular-text" name="licenses[<?php echo $class; ?>]" value="<?php if (!empty($post['licenses'][$class])) esc_attr_e( PMXI_Plugin::decode($post['licenses'][$class]) ); ?>"/>
							<?php if( ! empty($post['licenses'][$class]) ) { ?>

								<?php if( ! empty($post['statuses'][$class]) && $post['statuses'][$class] == 'valid' ) { ?>
									<div class="license-status inline updated"><?php _e('Active', 'wp-all-import-pro'); ?></div>
								<?php } else { ?>
									<input type="submit" class="button-secondary" name="pmxi_license_activate[<?php echo $class; ?>]" value="<?php _e('Activate License', 'wp-all-import-pro'); ?>"/>
									<?php if ( !empty($_POST['licenses'][$class] ) ) { ?>
										<div class="license-status inline error"><?php echo $post['statuses'][$class]; ?></div>
									<?php } ?>
								<?php } ?>

							<?php } ?>
							<p class="description"><?php _e('A license key is required to access plugin updates. You can use your license key on an unlimited number of websites. Do not distribute your license key to 3rd parties. You can get your license key in the <a target="_blank" href="https://www.wpallimport.com/portal">customer portal</a>.', 'wp-all-import-pro'); ?></p>
							<p class="submit-buttons">
								<?php wp_nonce_field('edit-license', '_wpnonce_edit-license') ?>
								<input type="hidden" name="is_license_submitted" value="1" />
								<input type="submit" class="button-primary" value="<?php _e('Save License', 'wp-all-import-pro'); ?>" />
							</p>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</form>
	<form name="settings" method="post" action="" class="settings">

		<table class="form-table">
			<tbody>

			<tr>
				<th scope="row"><label><?php _e('Automatic Scheduling License Key', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="password" class="regular-text" name="scheduling_license"
						   value="<?php if (!empty($post['scheduling_license'])) esc_attr_e(PMXI_Plugin::decode($post['scheduling_license'])); ?>"/>
					<?php if (!empty($post['scheduling_license'])) { ?>

						<?php if (!empty($post['scheduling_license_status']) && $post['scheduling_license_status'] == 'valid') { ?>
							<div class="license-status inline updated"><?php _e('Active', 'wp-all-import-pro'); ?></div>
						<?php } else { ?>
							<input type="submit" class="button-secondary" name="pmxi_scheduling_license_activate"
								   value="<?php _e('Activate License', 'wp-all-import-pro'); ?>"/>
							<?php if ( !empty( $_POST['scheduling_license'] ) ) { ?>
								<div class="license-status inline error"><?php echo $post['scheduling_license_status']; ?></div>
                                <input type="hidden" name="scheduling_license_limit" value="<?php echo get_option('wpai_wpae_scheduling_license_site_limit', 0); ?>">
							<?php } ?>
						<?php } ?>

					<?php } ?>
					<?php
					$scheduling = \Wpai\Scheduling\Scheduling::create();
					if(!($scheduling->checkLicense()['success'] ?? false)){
						require_once(PMXI_Plugin::ROOT_DIR . '/views/admin/import/options/scheduling/_scheduling_active_sites_limit_ui.php');
						?>
						<p class="description"><?php _e('A license key is required to use Automatic Scheduling. If you have already subscribed, <a href="https://www.wpallimport.com/portal/automatic-scheduling/" target="_blank">click here to access your license key</a>.<br>If you don\'t have a license, <a class="scheduling-subscribe-link" href="#">click here to subscribe</a>.', 'wp-all-import-pro'); ?></p>
						<?php
					}
					?>
					<p class="submit-buttons">
						<?php wp_nonce_field('edit-license', '_wpnonce_edit-scheduling-license') ?>
						<input type="hidden" name="is_scheduling_license_submitted" value="1"/>
						<input type="submit" class="button-primary" value="<?php _e('Save License', 'wp-all-import-pro'); ?>"/>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
<?php endif; ?>

<form class="settings" method="post" action="" enctype="multipart/form-data">

	<?php if ( $is_license_active ): ?>

	<div class="wpallimport-header">
		<div class="wpallimport-logo"></div>
		<div class="wpallimport-title">
			<h3><?php _e('Settings', 'wp-all-import-pro'); ?></h3>
		</div>
	</div>

	<h2 style="padding:0px;"></h2>

	<div class="wpallimport-setting-wrapper">
		<?php if ($this->errors->get_error_codes()): ?>
			<?php $this->error() ?>
		<?php endif ?>

		<?php if (!empty($license_message)):?>
			<div class="updated"><p><?php echo $license_message; ?></p></div>
		<?php endif;?>

	<?php endif; ?>

	<h3><?php _e('Import/Export Templates', 'wp-all-import-pro') ?></h3>
	<?php $templates = new PMXI_Template_List(); $templates->getBy()->convertRecords() ?>
	<?php wp_nonce_field('delete-templates', '_wpnonce_delete-templates') ?>
	<?php if ($templates->total()): ?>
		<table>
			<?php foreach ($templates as $t): ?>
				<tr>
					<td>
						<label class="selectit" for="template-<?php echo $t->id ?>"><input id="template-<?php echo $t->id ?>" type="checkbox" name="templates[]" value="<?php echo $t->id ?>" /> <?php echo $t->name ?></label>
					</td>
				</tr>
			<?php endforeach ?>
		</table>
		<p class="submit-buttons">
			<input type="submit" class="button-primary" name="delete_templates" value="<?php _e('Delete Selected', 'wp-all-import-pro') ?>" />
			<input type="submit" class="button-primary" name="export_templates" value="<?php _e('Export Selected', 'wp-all-import-pro') ?>" />
		</p>
	<?php else: ?>
		<em><?php _e('There are no templates saved', 'wp-all-import-pro') ?></em>
	<?php endif ?>
	<p>
		<input type="hidden" name="is_templates_submitted" value="1" />
		<input type="file" name="template_file"/>
		<input type="submit" class="button-primary" name="import_templates" value="<?php _e('Import Templates', 'wp-all-import-pro') ?>" />
	</p>
	<?php if ( $is_license_active ): ?>
	</div>
	<?php endif ?>
</form>

<form name="settings" method="post" action="" class="settings">

	<h3><?php _e('Cron Imports', 'wp-all-import-pro') ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label><?php _e('Secret Key', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="cron_job_key" value="<?php echo esc_attr($post['cron_job_key']); ?>"/>
					<p class="description"><?php _e('Changing this will require you to re-create your existing cron jobs.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php _e('Cron Processing Time Limit', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="cron_processing_time_limit" value="<?php echo esc_attr($post['cron_processing_time_limit']); ?>"/>
					<p class="description"><?php _e('Maximum execution time for the cron processing script. If this is blank, the default value of 120 (2 minutes) will be used.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php _e('Cron Sleep', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="cron_sleep" value="<?php echo esc_attr($post['cron_sleep']); ?>"/>
					<p class="description"><?php _e('Sleep the specified number of seconds between each post created, updated, or deleted with cron. Leave blank to not sleep. Only necessary on servers  that are slowed down by the cron job because they have very minimal processing power and resources.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="clear"></div>

	<h3><?php _e('Files', 'wp-all-import-pro') ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label><?php _e('Secure Mode', 'wp-all-import-pro'); ?></label></th>
				<td>
					<fieldset style="padding:0;">
						<legend class="screen-reader-text"><span><?php _e('Secure Mode', 'wp-all-import-pro'); ?></span></legend>
						<input type="hidden" name="secure" value="0"/>
						<label for="secure"><input type="checkbox" value="1" id="secure" name="secure" <?php echo (($post['secure']) ? 'checked="checked"' : ''); ?>><?php _e('Randomize folder names', 'wp-all-import-pro'); ?></label>
					</fieldset>
					<p class="description">
						<?php
							$wp_uploads = wp_upload_dir();
						?>
						<?php printf(__('Imported files, chunks, logs and temporary files will be placed in a folder with a randomized name inside of %s.', 'wp-all-import-pro'), $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php _e('Log Storage', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="log_storage" value="<?php echo esc_attr($post['log_storage']); ?>"/>
					<p class="description"><?php _e('Number of logs to store for each import. Enter 0 to never store logs.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php _e('Clean Up Temp Files', 'wp-all-import-pro'); ?></label></th>
				<td>
					<a class="button-primary wpallimport-clean-up-tmp-files" href="<?php echo esc_url(add_query_arg(array('action' => 'cleanup', '_wpnonce' => wp_create_nonce( '_wpnonce-cleanup_logs' )), $this->baseUrl)); ?>"><?php _e('Clean Up', 'wp-all-import-pro'); ?></a>
					<p class="description"><?php _e('Attempt to remove temp files left over by imports that were improperly terminated.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="clear"></div>

	<h3><?php _e('Advanced Settings', 'wp-all-import-pro') ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label><?php _e('Chunk Size', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="large_feed_limit" value="<?php echo esc_attr($post['large_feed_limit']); ?>"/>
					<p class="description"><?php _e('Split file into chunks containing the specified number of records.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php _e('WP_IMPORTING', 'wp-all-import-pro'); ?></label></th>
				<td>
					<fieldset style="padding:0;">
						<input type="hidden" name="pingbacks" value="0"/>
						<label for="pingbacks"><input type="checkbox" value="1" id="pingbacks" name="pingbacks" <?php echo (($post['pingbacks']) ? 'checked="checked"' : ''); ?>><?php _e('Enable WP_IMPORTING', 'wp-all-import-pro'); ?></label>
					</fieldset>
					<p class="description"><?php _e('Setting this constant avoids triggering pingback.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
            <tr>
                <th scope="row"><label><?php _e('I HAVE BACKUPS', 'wp-all-import-pro'); ?></label></th>
                <td>
                    <fieldset style="padding:0;">
                        <input type="hidden" name="backups_prompt" value="0"/>
                        <label for="backups_prompt"><input type="checkbox" value="1" id="backups_prompt" name="backups_prompt" <?php echo (($post['backups_prompt']) ? 'checked="checked"' : ''); ?>><?php _e('Enable "I HAVE BACKUPS" prompt', 'wp-all-import-pro'); ?></label>
                    </fieldset>
                    <p class="description"><?php _e('Setting this will enable the "I HAVE BACKUPS" prompt at import settings screen.', 'wp-all-import-pro'); ?></p>
                </td>
            </tr>
			<tr>
				<th scope="row"><label><?php _e('Add Port To URL', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="port" value="<?php echo esc_attr($post['port']); ?>"/>
					<p class="description"><?php _e('Specify the port number to add if you\'re having problems continuing to Create Filters or Setup Import and are running things on a custom port. Default is blank.', 'wp-all-import-pro'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php _e('Auto-retry failed import', 'wp_all_import_plugin'); ?></label></th>
				<td>
					<fieldset style="padding:0;">
					<input type="hidden" name="pmxi_auto_retry_import" value="0">
					<label for="pmxi_auto_retry_import"><input type="checkbox" value="1" id="pmxi_auto_retry_import" name="pmxi_auto_retry_import" <?php echo ( ! empty( $post['pmxi_auto_retry_import'] ) ? 'checked="checked"' : ''); ?>><?php _e('Enable auto-retry', 'wp_all_import_plugin'); ?></label>
					<p class="description"><?php _e('If your server terminates the import, WP All Import will continue the import with lower records per iteration. If it fails more than 4 times or the records per iteration is set to 1, it will stop retrying.', 'wp_all_import_plugin'); ?></p>
				</td>
			</tr>
            <tr>
                <th scope="row"><label><?php _e('File Download Time Limit', 'wp_all_import_plugin'); ?></label></th>
                <td>
                    <fieldset style="padding:0;">
                        <input type="text" class="regular-text" name="pmxi_file_download_timeout" value="<?php echo esc_attr($post['pmxi_file_download_timeout'] ?? 5); ?>"/>
                        <p class="description"><?php _e('This is used for the option to download from URL and should be set lower than your server\'s PHP max execution time limit. Default is 5.', 'wp-all-import-pro'); ?></p>
                </td>
            </tr>
			<?php do_action('pmxi_settings_advanced', $post); ?>
		</tbody>
	</table>

	<h3><?php _e('Force Stream Reader', 'wp-all-import-pro') ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label><?php _e('Force WP All Import to use StreamReader instead of XMLReader to parse all import files', 'wp-all-import-pro'); ?></label></th>
				<td>
					<fieldset style="padding:0;">
						<input type="hidden" name="force_stream_reader" value="0"/>
						<label for="force_stream_reader"><input type="checkbox" value="1" id="force_stream_reader" name="force_stream_reader" <?php echo (($post['force_stream_reader']) ? 'checked="checked"' : ''); ?>><?php _e('Enable Stream Reader', 'wp-all-import-pro'); ?></label>
					</fieldset>
					<p class="description"><?php _e('XMLReader is much faster, but has a bug that sometimes prevents certain records from being imported with import files that contain special cases.', 'wp-all-import-pro'); ?></p>
					<p class="submit-buttons">
						<?php wp_nonce_field('edit-settings', '_wpnonce_edit-settings') ?>
						<input type="hidden" name="is_settings_submitted" value="1" />
						<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'wp-all-import-pro'); ?>" />
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>

<?php if ( $is_license_active ): ?>
	<form name="settings" method="post" action="" class="settings">

		<h3><?php _e('Licenses', 'wp-all-import-pro') ?></h3>

		<table class="form-table">
			<tbody>

			<?php foreach ($addons as $class => $addon) : if ( ! $addon['active'] ) continue; ?>
				<tr>
					<th scope="row"><label><?php _e('WP All Import License Key', 'wp-all-import-pro'); ?></label></th>
					<td>
						<input type="password" class="regular-text" name="licenses[<?php echo $class; ?>]" value="<?php if (!empty($post['licenses'][$class])) esc_attr_e( PMXI_Plugin::decode($post['licenses'][$class]) ); ?>"/>
						<?php if( ! empty($post['licenses'][$class]) ) { ?>

							<?php if( ! empty($post['statuses'][$class]) && $post['statuses'][$class] == 'valid' ) { ?>
								<div class="license-status inline updated"><?php _e('Active', 'wp-all-import-pro'); ?></div>
							<?php } else { ?>
								<input type="submit" class="button-secondary" name="pmxi_license_activate[<?php echo $class; ?>]" value="<?php _e('Activate License', 'wp-all-import-pro'); ?>"/>
								<?php if ( !empty($_POST['licenses'][$class] ) ) { ?>
									<div class="license-status inline error"><?php echo $post['statuses'][$class]; ?></div>
								<?php } ?>
							<?php } ?>

						<?php } ?>
						<p class="description"><?php _e('A license key is required to access plugin updates. You can use your license key on an unlimited number of websites. Do not distribute your license key to 3rd parties. You can get your license key in the <a target="_blank" href="https://www.wpallimport.com/portal">customer portal</a>.', 'wp-all-import-pro'); ?></p>
						<p class="submit-buttons">
							<?php wp_nonce_field('edit-license', '_wpnonce_edit-license') ?>
							<input type="hidden" name="is_license_submitted" value="1" />
							<input type="submit" class="button-primary" value="<?php _e('Save License', 'wp-all-import-pro'); ?>" />
						</p>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</form>

	<form name="settings" method="post" action="" class="settings">

		<table class="form-table">
			<tbody>

			<tr>
				<th scope="row"><label><?php _e('Scheduling License Key', 'wp-all-import-pro'); ?></label></th>
				<td>
					<input type="password" class="regular-text" name="scheduling_license"
						   value="<?php if (!empty($post['scheduling_license'])) esc_attr_e(PMXI_Plugin::decode($post['scheduling_license'])); ?>"/>
					<?php if (!empty($post['scheduling_license'])) { ?>

						<?php if (!empty($post['scheduling_license_status']) && $post['scheduling_license_status'] == 'valid') { ?>
							<div class="license-status inline updated"><?php _e('Active', 'wp-all-import-pro'); ?></div>
						<?php } else { ?>
							<?php if ( !empty( $_POST['scheduling_license'] ) ) { ?>
								<div class="license-status inline error"><?php echo $post['scheduling_license_status']; ?></div>
                                <input type="hidden" name="scheduling_license_limit" value="<?php echo get_option('wpai_wpae_scheduling_license_site_limit', 0); ?>">
							<?php } ?>
						<?php } ?>

					<?php } ?>
					<?php
					$scheduling = \Wpai\Scheduling\Scheduling::create();
					if(!($scheduling->checkLicense()['success'] ?? false)){
						require_once(PMXI_Plugin::ROOT_DIR . '/views/admin/import/options/scheduling/_scheduling_active_sites_limit_ui.php');
						?>
						<p class="description"><?php _e('A license key is required to use Automatic Scheduling. If you have already subscribed, <a href="https://www.wpallimport.com/portal/automatic-scheduling/" target="_blank">click here to access your license key</a>.<br>If you don\'t have a license, <a class="scheduling-subscribe-link" href="#">click here to subscribe</a>.', 'wp-all-import-pro'); ?></p>
						<?php
					}
					?>
					<p class="submit-buttons">
						<?php wp_nonce_field('edit-license', '_wpnonce_edit-scheduling-license') ?>
						<input type="hidden" name="is_scheduling_license_submitted" value="1"/>
						<input type="submit" class="button-primary" value="<?php _e('Save License', 'wp-all-import-pro'); ?>"/>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
<?php endif; ?>

<?php
$uploads = wp_upload_dir();
$functions = $uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
$functions = apply_filters( 'import_functions_file_path', $functions );
if (file_exists($functions) && PMXI_Plugin::$is_php_allowed):
    $functions_content = file_get_contents($functions); ?>
    <hr />
    <br>
    <h3><?php _e('Function Editor', 'wp-all-import-pro') ?></h3>
    <?php require_once(PMXI_Plugin::ROOT_DIR . '/views/admin/shared/function_editor.php');?>
<?php endif; ?>
<div class="wpallimport-overlay"></div>
<div class="wpallimport-super-overlay"></div>

<div class="wpallimport-loader" style="border-radius: 5px; z-index: 999999; display:none; position: fixed;top: 200px;    left: 50%; width: 100px;height: 100px;background-color: #fff; text-align: center;">
    <img style="margin-top: 45%;" src="<?php echo WP_ALL_IMPORT_ROOT_URL; ?>/static/img/preloader.gif" />
</div>
<a href="http://soflyy.com/" target="_blank" class="wpallimport-created-by"><?php _e('Created by', 'wp-all-import-pro'); ?> <span></span></a>
