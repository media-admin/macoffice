<!-- Preload Images -->

<img src="<?php echo PMXI_Plugin::ROOT_URL . '/static/img/soflyy-logo.svg'; ?>" class="wpallimport-preload-image"/>

<script type="text/javascript">
	var plugin_url = '<?php echo WP_ALL_IMPORT_ROOT_URL; ?>';
</script>

<table class="wpallimport-layout wpallimport-step-1">
	<tr>
		<td class="left">
			<div class="wpallimport-wrapper">
				<h2 class="wpallimport-wp-notices"></h2>
				<div class="wpallimport-header">
					<div class="wpallimport-logo"></div>
					<div class="wpallimport-title">
						<h2><?php _e('New Import', 'wp-all-import-pro'); ?></h2>
					</div>
					<div class="wpallimport-links">
						<a href="https://www.wpallimport.com/support/" target="_blank"><?php _e('Support', 'wp-all-import-pro'); ?></a> | <a href="https://www.wpallimport.com/documentation/" target="_blank"><?php _e('Documentation', 'wp-all-import-pro'); ?></a>
					</div>
				</div>

				<div class="clear"></div>

				<?php if ($this->errors->get_error_codes()): ?>
					<?php $this->error() ?>
				<?php endif ?>

				<?php //do_action('pmxi_choose_file_header'); ?>

		        <form method="post" class="wpallimport-choose-file" enctype="multipart/form-data" autocomplete="off">

		        	<div class="wpallimport-upload-resource-step-one">

						<input type="hidden" name="is_submitted" value="1" />

						<div class="clear"></div>

						<div class="wpallimport-import-types">
							<?php if (empty($_GET['deligate'])): ?>
							<h2><?php _e('First, specify how you want to import your data', 'wp-all-import-pro'); ?></h2>
							<?php else: ?>
							<h2 style="margin-bottom: 10px;"><?php _e('First, specify previously exported file', 'wp-all-import-pro'); ?></h2>
							<h2 class="wp_all_import_subheadline"><?php _e('The data in this import file can be modified, but the structure of the file (column/element names) should not change.', 'wp-all-import-pro'); ?></h2>
							<?php endif; ?>
							<a class="wpallimport-import-from wpallimport-upload-type <?php echo ('upload' == $post['type']) ? 'selected' : '' ?>" rel="upload_type" href="javascript:void(0);">
								<span class="wpallimport-icon"></span>
								<span class="wpallimport-icon-label"><?php _e('Upload a file', 'wp-all-import-pro'); ?></span>
							</a>
							<a class="wpallimport-import-from wpallimport-url-type <?php echo ('url' == $post['type'] || 'ftp' == $post['type']) ? 'selected' : '' ?>" rel="url_type" href="javascript:void(0);">
								<span class="wpallimport-icon"></span>
								<span class="wpallimport-icon-label"><?php _e('Download a file', 'wp-all-import-pro'); ?></span>
							</a>
							<a class="wpallimport-import-from wpallimport-file-type <?php echo 'file' == $post['type'] ? 'selected' : '' ?>" rel="file_type" href="javascript:void(0);">
								<span class="wpallimport-icon"></span>
								<span class="wpallimport-icon-label"><?php _e('Use existing file', 'wp-all-import-pro'); ?></span>
							</a>
						</div>

						<input type="hidden" value="<?php echo $post['type']; ?>" name="type"/>

						<div class="wpallimport-upload-type-container" rel="upload_type">
							<div id="plupload-ui" class="wpallimport-file-type-options">
					            <div>
					                <input type="hidden" name="filepath" value="<?php echo $post['filepath'] ?>" id="filepath"/>
					                <a id="select-files" href="javascript:void(0);" <?php if (empty($post['filepath'])):?>style="display:none;"<?php endif; ?> /><?php _e('Click here to select file from your computer...', 'wp-all-import-pro'); ?></a>
					                <div id="progressbar" class="wpallimport-progressbar">
					                	<?php if (!empty($post['filepath'])):?>
					                	<span><?php _e('Upload Complete', 'wp-all-import-pro');?></span> - <?php echo basename($post['filepath']); ?>
					                	<?php endif; ?>
					                </div>
					                <div id="progress" class="wpallimport-progress" <?php if (!empty($post['filepath'])):?>style="visibility: visible; display: block;"<?php endif; ?>>
					                	<?php if (!empty($post['filepath'])):?>
					                	<div class="wpallimport-upload-process ui-progressbar ui-widget ui-widget-content ui-corner-all" id="upload_process" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="100"><div class="ui-progressbar-value ui-widget-header ui-corner-left ui-corner-right" style="width: 100%;"></div></div>
					                	<?php else: ?>
					                	<div id="upload_process" class="wpallimport-upload-process"></div>
					                	<?php endif; ?>
					                </div>
					            </div>
					        </div>
					        <div class="wpallimport-note" style="margin: 0 auto; font-size: 13px;"><span></span></div>
						</div>
						<div class="wpallimport-upload-type-container" rel="url_type">
                            <div class="wpallimport-choose-data-type">
                                <a class="wpallimport-download-from rad4 wpallimport-download-file-from-url <?php if ($post['type'] == 'url') echo 'wpallimport-download-from-checked'; ?>" rel="url" href="javascript:void(0);">
                                    <span class="wpallimport-download-from-title"><?php _e('From URL', 'wp-all-import-pro'); ?></span>
                                    <span class="wpallimport-download-from-arrow"></span>
                                </a>
                                <a class="wpallimport-download-from rad4 wpallimport-download-file-from-ftp <?php if ($post['type'] == 'ftp') echo 'wpallimport-download-from-checked'; ?>" rel="ftp" href="javascript:void(0);">
                                    <span class="wpallimport-download-from-title"><?php _e('From FTP/SFTP', 'wp-all-import-pro'); ?></span>
                                    <span class="wpallimport-download-from-arrow"></span>
                                </a>
                            </div>
                        </div>
						<div class="wpallimport-upload-type-container" rel="file_type">
							<?php $upload_dir = wp_upload_dir(); ?>
							<div class="wpallimport-file-type-options">

								<?php
									$files_directory = DIRECTORY_SEPARATOR . PMXI_Plugin::FILES_DIRECTORY . DIRECTORY_SEPARATOR;

									$scan_recursively = apply_filters('wp_all_import_scan_files_recursively', TRUE);

									$glob_flags = $scan_recursively ? PMXI_Helper::GLOB_NODIR | PMXI_Helper::GLOB_RECURSE : PMXI_Helper::GLOB_NODIR;

									$local_files = array_merge(
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.xml', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.gz', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.zip', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.gzip', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.csv', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.dat', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.psv', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.json', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.txt', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.sql', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.xls', $glob_flags),
										PMXI_Helper::safe_glob($upload_dir['basedir'] . $files_directory . '*.xlsx', $glob_flags)
									);
									sort($local_files);
									$sizes = array();
									if ( ! empty($local_files)){
										foreach ($local_files as $file) {
											$sizes[] = filesize($upload_dir['basedir'] . $files_directory . $file);
										}
									}
								?>
								<script type="text/javascript">
									var existing_file_sizes = <?php echo json_encode($sizes) ?>;
								</script>

								<select id="file_selector">
									<option value=""><?php _e('Select a previously uploaded file', 'wp-all-import-pro'); ?></option>
									<?php foreach ($local_files as $file) :?>
										<option value="<?php echo $file; ?>" <?php if ( $file == esc_attr($post['file'])):?>selected="selected"<?php endif; ?>><?php echo basename($file); ?></option>
									<?php endforeach; ?>
								</select>

								<input type="hidden" name="file" value="<?php echo esc_attr($post['file']); ?>"/>

								<div class="wpallimport-note" style="margin: 0 auto; font-size: 13px;">
									<?php printf(__('Upload files to <strong>%s</strong> and they will appear in this list', 'wp-all-import-pro'), $upload_dir['basedir'] . $files_directory) ?>
									<span></span>
								</div>
							</div>
						</div>
						<div id="wpallimport-url-upload-status"></div>

						<?php if (empty($_GET['deligate'])): ?>

                        <div class="wpallimport-download-resource-step-two">
                            <div class="wpallimport-download-resource wpallimport-download-resource-step-two-url">
                                <div class="wpallimport-file-type-options">
                                    <span class="wpallimport-input-icon wpallimport-url-icon"></span>
                                    <input type="text" class="regular-text" name="url" value="<?php echo ( ! empty($post['url'])) ? esc_attr($post['url']) : ''; ?>" placeholder="<?php _e('Enter a web address to download the file from...', 'wp-all-import-pro'); ?>"/>
                                    <a class="wpallimport-download-from-url rad4" href="javascript:void(0);"><?php _e('Download', 'wp-all-import-pro'); ?></a>
                                    <span class="img_preloader" style="top:0; left: 5px; visibility: hidden; display: inline;"></span>
                                </div>
                                <div class="wpallimport-note" style="margin: 20px auto 0; font-size: 13px;">
                                    <?php _e('<strong>Hint:</strong> After you create this import, you can schedule it to run automatically, on a pre-defined schedule, with cron jobs.', 'wp-all-import-pro'); ?>
                                    <span></span>
                                </div>
                                <input type="hidden" name="downloaded" value="<?php echo esc_attr($post['downloaded']); ?>"/>
                                <input type="hidden" name="template" value="<?php echo esc_attr($post['template']); ?>"/>
                                <input type="hidden" name="bundle_xpath" value="<?php echo esc_attr($post['bundle_xpath'] ?? ''); ?>"/>
                            </div>
                            <div class="wpallimport-download-resource wpallimport-download-resource-step-two-ftp">

                                <div class="wpallimport-file-type-options">
                                    <span class="wpallimport-input-icon wpallimport-ftp-host-icon"></span>
                                    <input type="text" class="regular-text" name="ftp_host" value="<?php echo ( ! empty($post['ftp_host'])) ? esc_attr($post['ftp_host']) : ''; ?>" placeholder="<?php _e('FTP server address', 'wp-all-import-pro'); ?>"/>
                                    <a class="wpallimport-help" href="#help" style="position: relative; top: -2px;" title="<?php _e('The server address of your FTP/SFTP server. This can be an IP address or domain name. You do not need to include the connection protocol. For example, files.example.com or 127.0.0.1', 'wp-all-import-pro'); ?>">?</a>
                                </div>
                                <div class="wpallimport-file-type-options">
                                    <span class="wpallimport-input-icon wpallimport-ftp-port-icon"></span>
                                    <input type="text" class="regular-text" name="ftp_port" value="<?php echo ( ! empty($post['ftp_port'])) ? esc_attr($post['ftp_port']) : ''; ?>" placeholder="<?php _e('FTP port', 'wp-all-import-pro'); ?>"/>
                                    <a class="wpallimport-help" href="#help" style="position: relative; top: -2px;" title="<?php _e('The port that your server uses. FTP usually uses port 21, SFTP usually uses port 22', 'wp-all-import-pro'); ?>">?</a>
                                </div>
                                <div class="wpallimport-file-type-options">
                                    <span class="wpallimport-input-icon wpallimport-ftp-username-icon"></span>
                                    <input type="text" class="regular-text" name="ftp_username" value="<?php echo ( ! empty($post['ftp_username'])) ? esc_attr($post['ftp_username']) : ''; ?>" placeholder="<?php _e('FTP username', 'wp-all-import-pro'); ?>"/>
                                    <a class="wpallimport-help" href="#help" style="position: relative; top: -2px;" title="<?php _e('If you don\'t know your FTP/SFTP username, contact the host of the server.', 'wp-all-import-pro'); ?>">?</a>
                                </div>
                                <div class="wpallimport-file-type-options">
                                    <span class="wpallimport-input-icon wpallimport-ftp-password-icon"></span>
                                    <input type="text" class="regular-text" name="ftp_password" value="<?php echo ( ! empty($post['ftp_password'])) ? esc_attr($post['ftp_password']) : ''; ?>" placeholder="<?php _e('FTP password', 'wp-all-import-pro'); ?>"/>
                                    <a class="wpallimport-help" href="#help" style="position: relative; top: -2px;" title="<?php _e('These passwords are stored in plaintext in your WordPress database. Ideally, the user account should only have read access to the files that you are importing.
<br/><br/>Even if the password is correct, sometimes your host will require SFTP connections to use an SSH key and will deny connection attempts using passwords. If you\'re unable to login and you are sure the password is correct, contact the host of the server.', 'wp-all-import-pro'); ?>">?</a>
                                </div>
                                <div class="wpallimport-file-type-options">
                                    <span class="wpallimport-input-icon wpallimport-ftp-private-key-icon"></span>
                                    <textarea class="wpai-ftp-text-area" name="ftp_private_key" value="<?php echo ( ! empty($post['ftp_private_key'])) ? esc_attr($post['ftp_private_key']) : ''; ?>" placeholder="<?php _e('SFTP Private Key', 'wp-all-import-pro'); ?>"></textarea>
                                    <a class="wpallimport-help" id="wpai-ftp-text-area-help" href="#help" style="position: relative; top: -2px;" title="<?php _e('If you don\'t know if you need an SFTP Private Key, contact the host of the server.', 'wp-all-import-pro'); ?>">?</a>
                                </div>
                                <div style="display:none;">
                                    <input type="hidden" name="ftp_root"
                                           value="<?php echo ( ! empty( $post['ftp_root'] ) ) ? esc_attr( $post['ftp_root'] ) : ''; ?>"/>
                                </div>
                                <div class="wpallimport-file-type-options ftp_path">

                                    <input type="text" class="regular-text" name="ftp_path"
                                           value="<?php echo ( ! empty( $post['ftp_path'] ) ) ? esc_attr( $post['ftp_path'] ) : ''; ?>"
                                           placeholder="<?php _e('FTP file path', 'wp-all-import-pro'); ?>"/>

                                    <a class="wpallimport-ftp-builder rad4 button button-primary button-hero wpallimport-large-button wpai-ftp-select-file-button"
                                       href="javascript:void(0);">
                                        <div class="easing-spinner"
                                             style="display: none; left: 36px !important; top: 2px;">
                                            <div class="double-bounce1"></div>
                                            <div class="double-bounce2"></div>
                                        </div>
			                            <?php _e( 'Select File', 'wp-all-import-pro' ); ?>
                                    </a>

                                </div>
                                <div style="display:block;position:relative;width:75%;margin:auto;">
                                    <span class="wpallimport-input-icon wpallimport-ftp-path-icon"></span>
                                    <a class="wpallimport-help" href="#help"
                                       style="position: absolute;top: -32px;right: -30px;"
                                       title="<?php _e( 'The path to the file you want to import. In case multiple files are found, only the first will be downloaded. Examples: /home/ftpuser/import.csv or import-files/{newest.csv}', 'wp-all-import-pro' ); ?>">?</a>
                                </div>

                                <span class="wpallimport-ftp-builder-wrap">
                                <div class="wpallimport-ftp-connection-builder" id="wpallimport-ftp-connection-builder">
                                </div>
                                <input type="hidden" id="wpai-ftp-browser-nonce"
                                       value="<?php echo wp_create_nonce( 'wpai-ftp-browser' ); ?>"/>

                                </span>

                                <div class="rad4 first-step-errors wpai-ftp-connection-error">
                                     <div class="wpallimport-notify-wrapper">
                                        <div class="error-headers exclamation">
                                            <h3><?php _e('Unable to Connect', 'wp-all-import-pro');?></h3>
                                            <br/>
                                            <span id="wpai-ftp-connection-error-message"></span>
                                        </div>
                                    </div>
                                </div>


                                <br/>

                                <div class="input" style="display:none;">
                                    <a class="wpallimport-download-from-url rad4" href="javascript:void(0);"><?php _e('Download', 'wp-all-import-pro'); ?></a>
                                    <span class="img_preloader" style="top:0; left: 5px; visibility: hidden; display: inline;"></span>
                                </div>
                            </div>
                        </div>

						<div class="wpallimport-upload-resource-step-two">

							<div class="wpallimport-choose-post-type">

								<input type="hidden" name="wizard_type" value="<?php echo $post['wizard_type']; ?>"/>

								<h2 style="margin-top:0;"><?php _e('Import data from this import file into...', 'wp-all-import-pro'); ?></h2>

								<div class="wpallimport-choose-data-type">
									<a class="wpallimport-import-to rad4 wpallimport-to-new-items <?php if ($post['wizard_type'] == 'new') echo 'wpallimport-import-to-checked'; ?>" rel="new" href="javascript:void(0);">
										<span class="wpallimport-import-to-title"><?php _e('New Items', 'wp-all-import-pro'); ?></span>
										<span class="wpallimport-import-to-arrow"></span>
									</a>
									<a class="wpallimport-import-to rad4 wpallimport-to-existing-items <?php if ($post['wizard_type'] == 'matching') echo 'wpallimport-import-to-checked'; ?>" rel="matching" href="javascript:void(0);">
										<span class="wpallimport-import-to-title"><?php _e('Existing Items', 'wp-all-import-pro'); ?></span>
										<span class="wpallimport-import-to-arrow"></span>
									</a>
								</div>

								<?php

                                    $all_types = array();
                                    $sort_order = array();

                                    $hiddenPosts = array(
                                        'attachment',
                                        'revision',
                                        'nav_menu_item',
                                        'shop_webhook',
                                        'import_users',
                                        'wp-types-group',
                                        'wp-types-user-group',
                                        'wp-types-term-group',
                                        'acf-field',
                                        'acf-field-group',
                                        'custom_css',
                                        'customize_changeset',
                                        'oembed_cache',
                                        'wp_block',
                                        'user_request',
                                        'scheduled-action',
                                        'wp_navigation'
                                    );

									$custom_types = get_post_types(array('_builtin' => true), 'objects') + get_post_types(array('_builtin' => false, 'show_ui' => true), 'objects');
									foreach ($custom_types as $key => $ct) {
										if (in_array($key, $hiddenPosts)) unset($custom_types[$key]);
                                    }

                                    $custom_types = apply_filters( 'pmxi_custom_types', $custom_types, 'custom_types' );

									$sorted_cpt = array();
									foreach ($custom_types as $key => $cpt){

										$sorted_cpt[$key] = $cpt;

										// Put users & comments & taxonomies after Pages
										if ( ! empty($custom_types['page']) && $key == 'page' || empty($custom_types['page']) && $key == 'post' ){
											$sorted_cpt['taxonomies'] = new stdClass();
											$sorted_cpt['taxonomies']->labels = new stdClass();
											$sorted_cpt['taxonomies']->labels->name = __('Taxonomies','wp-all-import-pro');

											$sorted_cpt['import_users'] = new stdClass();
											$sorted_cpt['import_users']->labels = new stdClass();
											$sorted_cpt['import_users']->labels->name = __('Users','wp-all-import-pro');

                                            $sorted_cpt['comments'] = new stdClass();
                                            $sorted_cpt['comments']->labels = new stdClass();
                                            $sorted_cpt['comments']->labels->name = __('Comments','wp-all-import-pro');

											break;
										}
									}
									$order = array('shop_order', 'shop_coupon', 'shop_customer', 'product');
									foreach ($order as $cpt){
										if (!empty($custom_types[$cpt])) $sorted_cpt[$cpt] = $custom_types[$cpt];
									}

									uasort($custom_types, "wp_all_import_cmp_custom_types");

									foreach ($custom_types as $key => $cpt) {
										if (empty($sorted_cpt[$key])){
											$sorted_cpt[$key] = $cpt;
										}
									}

									$hidden_post_types = get_post_types(array('_builtin' => false, 'show_ui' => false), 'objects');
									foreach ($hidden_post_types as $key => $ct) {
										if (in_array($key, $hiddenPosts)) unset($hidden_post_types[$key]);
									}
                                    $hidden_post_types = apply_filters( 'pmxi_custom_types', $hidden_post_types, 'hidden_post_types' );

								?>
								<div class="wpallimport-choose-import-direction">
									<div class="wpallimport-extra-text-left">
										<div class="wpallimport-new-records"><?php _e('Create new', 'wp-all-import-pro'); ?></div>
										<div class="wpallimport-existing-records"><?php _e('Import to existing', 'wp-all-import-pro'); ?></div>
									</div>
									<div class="wpallimport-extra-text-right">
										<div class="wpallimport-new-records"><?php _e('for each record in my data file.', 'wp-all-import-pro'); ?>
											<a class="wpallimport-help" href="#help" style="position: relative; top: -2px;" title="<?php _e('The New Items option is commonly used to import new posts or products to your site without touching the existing records.<br/><br/>If the import is later run again with modified data, WP All Import will only update/remove posts created by this import.', 'wp-all-import-pro'); ?>">?</a>
										</div>
										<div class="wpallimport-existing-records"><?php _e('and update some or all of their data.', 'wp-all-import-pro'); ?>
											<a class="wpallimport-help" href="#help" style="position: relative; top: -2px;" title="<?php _e('The Existing Items option is commonly used to update existing products with new stock quantities while leaving all their other data alone, update properties on your site with new pricing, etc. <br/><br/> On the Import Settings screen, you will map the records in your file to the existing items on your site and specify which data points will be updated and which will be left alone.', 'wp-all-import-pro'); ?>">?</a>
										</div>
									</div>
                                    <select name="custom_type_selector" id="custom_type_selector" class="wpallimport-post-types">

                                    <?php

                                    // *****************************************************
                                    // **************** START CPT LOOP *********************
                                    // *****************************************************
                                    ?>


                                    <?php
                                    $known_imgs     = array( 'post', 'page', 'product', 'import_users', 'shop_order', 'shop_coupon', 'shop_customer', 'users', 'comments', 'taxonomies', 'woo_reviews', 'gf_entries' );
                                    $all_posts      = array_merge( $sorted_cpt, $hidden_post_types );
                                    $all_posts      = apply_filters( 'pmxi_custom_types', $all_posts, 'all_types' );
                                    $ordered_posts  = array( 'post', 'page', 'taxonomies', 'comments', 'import_users', 'shop_order', 'shop_coupon', 'product', 'woo_reviews', 'shop_customer');

                                    foreach ( $all_posts as $key => $post_obj ) {
                                        if ( ! in_array( $key, $ordered_posts ) ) {
                                            array_push( $ordered_posts, $key );
                                        }
                                    }

                                    $order_arr          = apply_filters( 'pmxi_post_list_order', $ordered_posts );
                                    $image_data         = apply_filters( 'wp_all_import_post_type_image', array() );

                                    foreach ( $order_arr as $key => $post_name ) {
                                        if ( array_key_exists( $post_name, $all_posts ) ) {
                                            $post_obj = $all_posts[ $post_name ];

                                            if ( in_array( $post_name, $known_imgs ) ) {
                                                $image_src = 'dashicon-' . $post_name;
                                            } else {
                                                $image_src = 'dashicon-cpt';
                                            }
                                            if ( ! empty( $image_data ) && array_key_exists( $post_name, $image_data ) ) {
                                                $custom_img_defined = true;
                                            } else {
                                                $custom_img_defined = false;
                                            }

                                            $original_image_src = $image_src;
                                            $cpt = $post_name;
                                            $cpt_label = $post_obj->labels->name;

                                            $custom_selected_post = apply_filters( 'wpai_custom_selected_post', false, $post, $cpt, 'step1' );

                                            $img_to_echo = 'dashicon ';

                                            if ( $custom_img_defined === true ) {
                                                $img_to_echo .= $image_data[ $cpt ]['image'];
                                            } else {
                                                $img_to_echo .= $image_src;
                                            }

                                            ?>

                                            <option value="<?php echo $cpt; ?>" data-imagesrc="<?php echo $img_to_echo; ?>" <?php if ( $custom_selected_post === true ):?>selected="selected"<?php else: if ( $cpt == $post['custom_type'] ):?>selected="selected"<?php endif; endif; ?>><?php echo $cpt_label; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
									<div class="taxonomy_to_import_wrapper">
										<input type="hidden" name="taxonomy_type" value="<?php echo $post['taxonomy_type'];?>">
										<h2 style="margin: 30px 0 -10px 0;"><?php _e('Select taxonomy to import into...', 'wp-all-import-pro');?> <a class="wpallimport-help" href="#help" style="position: relative; top: -2px;" title="Hover over each entry to view the taxonomy slug.">?</a></h2>
										<select id="taxonomy_to_import">
											<option value=""><?php _e('Select Taxonomy', 'wp-all-import-pro'); ?></option>
											<?php $options = wp_all_import_get_taxonomies(); ?>
                                            <?php //$options = apply_filters( 'pmxi_custom_types', $options, 'taxonomies' ); ?>
											<?php foreach ($options as $slug => $name):?>
												<option value="<?php echo $slug;?>" <?php if ($post['taxonomy_type'] == $slug):?>selected="selected"<?php endif;?>><?php echo $name;?></option>
											<?php endforeach;?>
										</select>
									</div>
									<?php if ( ! class_exists('PMUI_Plugin') ): ?>
									<div class="wpallimport-upgrade-notice" rel="import_users">
										<p><?php _e('The User Add-On is Required to Import Users', 'wp-all-import-pro'); ?></p>
										<a href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839963&edd_options%5Bprice_id%5D=1" target="_blank" class="upgrade_link"><?php _e('Purchase the User Add-On', 'wp-all-import-pro');?></a>
									</div>
									<?php endif; ?>
									<?php if ( class_exists('WooCommerce') && ! class_exists('PMWI_Plugin') ): ?>
									<div class="wpallimport-upgrade-notice" rel="product">
										<p><?php _e('The WooCommerce Add-On is Required to Import Products', 'wp-all-import-pro'); ?></p>
										<a href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839961&edd_options%5Bprice_id%5D=1" target="_blank" class="upgrade_link"><?php _e('Purchase the WooCommerce Add-On Pro', 'wp-all-import-pro');?></a>
									</div>
									<?php endif; ?>
                                    <?php if ( class_exists('GFForms') && ! class_exists('PMGI_Plugin') ): ?>
                                        <div class="wpallimport-upgrade-notice" rel="gf_entries">
                                            <p><?php _e('The Gravity Forms Add-On is Required to Import Gravity Forms Entries', 'wp-all-import-pro'); ?></p>
                                            <a href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839927&edd_options%5Bprice_id%5D=1" target="_blank" class="upgrade_link"><?php _e('Purchase the Gravity Forms Add-On Pro', 'wp-all-import-pro');?></a>
                                        </div>
                                    <?php endif; ?>
									<?php if ( class_exists('WooCommerce') &&  ( ! class_exists('PMWI_Plugin') || class_exists('PMWI_Plugin') && PMWI_EDITION == 'free') ): ?>
										<div class="wpallimport-upgrade-notice" rel="shop_order">
											<?php if (class_exists('PMWI_Plugin') && PMWI_EDITION == 'free'): ?>
												<p><?php _e('The Pro version of the WooCommerce Add-On is required to Import Orders, but you have the free version installed', 'wp-all-import-pro'); ?></p>
											<?php else: ?>
												<p><?php _e('The WooCommerce Add-On Pro is Required to Import Orders', 'wp-all-import-pro'); ?></p>
											<?php endif; ?>
											<a href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839961&edd_options%5Bprice_id%5D=1" target="_blank" class="upgrade_link"><?php _e('Purchase the WooCommerce Add-On Pro', 'wp-all-import-pro');?></a>
										</div>
										<div class="wpallimport-upgrade-notice" rel="shop_coupon">
											<?php if (class_exists('PMWI_Plugin') && PMWI_EDITION == 'free'): ?>
												<p><?php _e('The Pro version of the WooCommerce Add-On is required to Import Coupons, but you have the free version installed', 'wp-all-import-pro'); ?></p>
											<?php else: ?>
												<p><?php _e('The WooCommerce Add-On Pro is Required to Import Coupons', 'wp-all-import-pro'); ?></p>
											<?php endif; ?>
											<a href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839961&edd_options%5Bprice_id%5D=1" target="_blank" class="upgrade_link"><?php _e('Purchase the WooCommerce Add-On Pro', 'wp-all-import-pro');?></a>
										</div>
									<?php endif; ?>


									<?php if ( class_exists('WooCommerce') && ! class_exists('PMUI_Plugin') ): ?>
										<div class="wpallimport-upgrade-notice" rel="shop_customer">
											<p><?php _e('The User Add-On is Required to Import Customers', 'wp-all-import-pro'); ?></p>
											<a href="https://www.wpallimport.com/checkout/?edd_action=add_to_cart&download_id=5839963&edd_options%5Bprice_id%5D=1" target="_blank" class="upgrade_link"><?php _e('Purchase the User Add-On', 'wp-all-import-pro');?></a>
										</div>
									<?php endif; ?>

                                    <?php do_action('wp_all_import_entity_type_bundle', $post, false); ?>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</div>

					<div class="rad4 first-step-errors error-upload-rejected">
						<div class="wpallimport-notify-wrapper">
							<div class="error-headers exclamation">
								<h3><?php _e('File upload rejected by server', 'wp-all-import-pro');?></h3>
								<h4><?php _e("Contact your host and have them check your server's error log.", "wp-all-import-pro"); ?></h4>
							</div>
						</div>
						<a class="button button-primary button-hero wpallimport-large-button wpallimport-notify-read-more" href="https://www.wpallimport.com/documentation/problems-with-import-files/" target="_blank"><?php _e('Read More', 'wp-all-import-pro');?></a>
					</div>

					<div class="rad4 first-step-errors error-file-validation" <?php if ( ! empty($upload_validation) ): ?> style="display:block;" <?php endif; ?>>
						<div class="wpallimport-notify-wrapper">
							<div class="error-headers exclamation">
								<h3><?php _e('There\'s a problem with your import file', 'wp-all-import-pro');?></h3>
								<h4>
									<?php
									if ( ! empty($upload_validation) ):
										$file_type = strtoupper(pmxi_getExtension($post['file']));
										printf(__('Please verify that the file you using is a valid %s file.', 'wp-all-import-pro'), $file_type);
									endif;
									?>
								</h4>
							</div>
						</div>
						<a class="button button-primary button-hero wpallimport-large-button wpallimport-notify-read-more" href="https://www.wpallimport.com/documentation/problems-with-import-files/" target="_blank"><?php _e('Read More', 'wp-all-import-pro');?></a>
					</div>

					<div class="wpallimport-submit-buttons">
                        <div class="wpallimport-submit-button-container" style="display:flex; justify-content: center; padding:20px; gap:10px;">
                            <input type="hidden" name="custom_type" value="<?php echo $post['custom_type'];?>">
                            <input type="hidden" name="is_submitted" value="1" />
                            <input type="hidden" name="auto_generate" value="0" />
                            <input type="hidden" name="go_to_create_filters" value="0" />
    
                            <?php wp_nonce_field('choose-file', '_wpnonce_choose-file'); ?>
                            <a href="javascript:void(0);" class="back rad3 auto-generate-template" style="float:none; background: #e4e6e6; padding: 0 50px;"><?php _e('Skip to Import Settings', 'wp-all-import-pro'); ?></a>
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <a href="javascript:void(0);" class="back rad3 create-filters-step" style="float:none; background: #e4e6e6; padding: 0 50px;"><?php _e('Create Filters', 'wp-all-import-pro'); ?></a>
                                <span style="display: block; text-align: center; margin-top: 5px; font-size: 10px; font-weight:500; color:#777;"><?php _e('Limit What\'s Imported', 'wp-all-import-pro'); ?></span>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <input type="submit" class="button button-primary button-hero wpallimport-large-button" value="<?php _e('Set Up Import', 'wp-all-import-pro') ?>" id="advanced_upload"/>
                                <span style="display: block; text-align: center; margin-top: 5px; font-size: 10px; font-weight:500; color:#777;"><?php _e('Import Everything', 'wp-all-import-pro'); ?></span>
                            </div>
                        </div>
					</div>

					<table><tr><td class="wpallimport-note"></td></tr></table>
				</form>
				<a href="http://soflyy.com/" target="_blank" class="wpallimport-created-by"><?php _e('Created by', 'wp-all-import-pro'); ?> <span></span></a>
			</div>
		</td>
	</tr>
</table>
