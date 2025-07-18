<?php
/**
 * Import configuration wizard
 *
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */

class PMXI_Admin_Import extends PMXI_Controller_Admin {
	protected $isWizard = true; // indicates whether controller is in wizard mode (otherwize it called to be deligated an edit action)
	protected $isTemplateEdit = false; // indicates whether controlled is deligated by manage imports controller

	protected function init() {
		parent::init();

		if ('PMXI_Admin_Manage' == PMXI_Plugin::getInstance()->getAdminCurrentScreen()->base) { // prereqisites are not checked when flow control is deligated
			$id = $this->input->get('id');
			$this->data['import'] = $import = new PMXI_Import_Record();
			if ( ! $id or $import->getById($id)->isEmpty()) { // specified import is not found
				wp_redirect(esc_url_raw(add_query_arg('page', 'pmxi-admin-manage', admin_url('admin.php')))); die();
			}
			$this->isWizard = false;
		} else {
            $action = PMXI_Plugin::getInstance()->getAdminCurrentScreen()->action;
            $this->_step_ready($action);
			$this->isInline = 'process' == $action;
		}

		XmlImportConfig::getInstance()->setCacheDirectory(sys_get_temp_dir());

		// preserve id parameter as part of baseUrl
		$id = $this->input->get('id') and $this->baseUrl = add_query_arg('id', $id, $this->baseUrl);

		$this->baseUrl = apply_filters('pmxi_base_url', $this->baseUrl);
	}

	public function set($var, $val)
	{
		$this->{$var} = $val;
	}
	public function get($var)
	{
		return $this->{$var};
	}

	/**
	 * Checks whether corresponding step of wizard is complete
	 * @param string $action
	 */
	protected function _step_ready($action) {
		// step #1: xml selction - has no prerequisites
		if ('index' == $action) return true;

		// step #2: element selection
		$this->data['dom'] = $dom = new DOMDocument('1.0', (PMXI_Plugin::$session->encoding ?? ''));
		$this->data['update_previous'] = $update_previous = new PMXI_Import_Record();
		$old = libxml_use_internal_errors(true);

		$xml = $this->get_xml();

		if (empty($xml) and in_array($action, array('process')) ){
			! empty( PMXI_Plugin::$session->update_previous ) and $update_previous->getById(PMXI_Plugin::$session->update_previous);
			return true;
		}

		if ( ! PMXI_Plugin::$session->has_session()
			or ! empty( PMXI_Plugin::$session->update_previous ) and $update_previous->getById(PMXI_Plugin::$session->update_previous)->isEmpty()
		    or empty($xml)
			or ! @$dom->loadXML($xml)// FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
		) {
			if ( ! PMXI_Plugin::is_ajax() ){
				$this->errors->add('form-validation', __('WP All Import lost track of where you are.<br/><br/>Maybe you cleared your cookies or maybe it is just a temporary issue on your or your web host\'s end.', 'wp-all-import-pro'));
				wp_redirect_or_javascript(esc_url_raw($this->baseUrl)); die();
			}
		}

		libxml_use_internal_errors($old);
		if ('element' == $action) return true;
		if ('evaluate' == $action) return true;
		if ('evaluate_variations' == $action) return true;

		// step #3: template
		$xpath = new DOMXPath(($dom ?? ''));
		$this->data['elements'] = $elements = @$xpath->query(PMXI_Plugin::$session->xpath ?? '');

		if ('preview' == $action or 'tag' == $action or 'preview_images' == $action or 'preview_taxonomies' == $action or 'preview_images' == $action) return true;

		if ( ! PMXI_Plugin::$session->get('xpath', false) or empty($elements) or ! $elements->length) {
			$this->errors->add('form-validation', __('There are no elements to import based on your XPath.<br/><br/>If you are on the Create Filters screen, you probably specified filtering options that don’t match any elements present in your file.<br/>If you are seeing this error elsewhere, it means that while the XPath expression for your initial import matched some elements in your file previously, there are now zero elements in the file that match this expression.<br/>You can edit the XPath for your import by going to the Manage Imports -> Import Settings page.', 'wp-all-import-pro'));
			wp_redirect_or_javascript(esc_url_raw(add_query_arg('action', 'element', $this->baseUrl))); die();
		}

		if ('template' == $action or 'preview' == $action or 'tag' == $action) return true;

		// step #4: options
		if ( empty( PMXI_Plugin::$session->options ) ) {
			wp_redirect_or_javascript(esc_url_raw(add_query_arg('action', 'template', $this->baseUrl))); die();
		}
		if ('options' == $action) return true;

		if ( empty( PMXI_Plugin::$session->options ) ) {
			wp_redirect(esc_url_raw(add_query_arg('action', 'options', $this->baseUrl))); die();
		}
	}

	/**
	 * Step #1: Choose File
	 */
	public function index() {

		$action = $this->input->get('action');

		$this->data['reimported_import'] = $import = new PMXI_Import_Record();
		$this->data['id'] = $id = $this->input->get('id');
		$this->data['parent_import'] = $parent_import = $this->input->get('parent_import', 0);
		$parent_import_record = new PMXI_Import_Record();

		$DefaultOptions = array(
			'type' => '',
			'wizard_type' => 'new',
			'custom_type' => 'post',
			'show_hidden_cpt' => 0,
			'feed_type' => '',
			'url' => '',
			'ftp_host' => '',
			'ftp_path' => '',
			'ftp_root' => '/',
			'ftp_port' => '21',
			'ftp_username' => '',
			'ftp_password' => '',
			'ftp_private_key' => '',
			'file' => '',
			'reimport' => '',
			'is_update_previous' => $id ? 1 : 0,
			'update_previous' => $id,
			'xpath' => '/',
			'filepath' => '',
			'root_element' => '',
			'downloaded' => '',
			'auto_generate' => 0,
			'go_to_create_filters' => 0,
			'template' => false	,
            'taxonomy_type' => ''
		);

		$DefaultOptions = apply_filters('wp_all_import_default_options', $DefaultOptions);

		if ($parent_import and ! $parent_import_record->getById($parent_import)->isEmpty()){
			$DefaultOptions['custom_type'] = $parent_import_record->options['custom_type'];
		}

		if ( $id ) { // update requested but corresponding import is not found
			if ( $import->getById($id)->isEmpty() ) {
				if ( ! empty($_GET['deligate']) and $_GET['deligate'] == 'wpallexport' ) {
					wp_redirect(esc_url_raw(add_query_arg('pmxi_nt', array('error' => urlencode(__('The import associated with this export has been deleted.', 'wp-all-import-pro')), 'updated' => urlencode(__('Please re-run your export by clicking Run Export on the All Export -> Manage Exports page. Then try your import again.', 'wp-all-import-pro'))), remove_query_arg('id', $this->baseUrl)))); die();
				} else {
					wp_redirect(esc_url_raw(add_query_arg('pmxi_nt', array('error' => urlencode(__('This import has been deleted.', 'wp-all-import-pro'))), remove_query_arg('id', $this->baseUrl)))); die();
				}
			} else {
				$DefaultOptions['custom_type'] = $import->options['custom_type'];
			}
		}

		if ( ! in_array($action, array('index'))) {
			PMXI_Plugin::$session->clean_session();
		} else {
			$DefaultOptions = (PMXI_Plugin::$session->has_session() && !empty(PMXI_Plugin::$session->first_step) ? PMXI_Plugin::$session->first_step : array()) + $DefaultOptions;
		}

		$this->data['post'] = $post = $this->input->post( $DefaultOptions );

		if ( ! class_exists('DOMDocument') or ! class_exists('XMLReader') ) {
			$this->errors->add('form-validation', __('Required PHP components are missing.<br/><br/>WP All Import requires DOMDocument, XMLReader, and XMLWriter PHP modules to be installed.<br/>These are standard features of PHP, and are necessary for WP All Import to read the files you are trying to import.<br/>Please contact your web hosting provider and ask them to install and activate the DOMDocument, XMLReader, and XMLWriter PHP modules.', 'wp-all-import-pro'));
		}

		$this->data['upload_validation'] = false;

		if ($this->input->post('is_submitted') and ! $this->errors->get_error_codes()) {

			check_admin_referer('choose-file', '_wpnonce_choose-file');

			if ('upload' == $this->input->post('type')) {
				$uploader = new PMXI_Upload($post['filepath'], $this->errors, rtrim(str_replace(basename($post['filepath']), '', $post['filepath']), '/'));
				$upload_result = $uploader->upload();
				if ($upload_result instanceof WP_Error) {
					$this->errors = $upload_result;
				} else {
					$source    = $upload_result['source'];
					$filePath  = $upload_result['filePath'];
					$post['template'] = $upload_result['template'];
					PMXI_Plugin::$is_csv = $upload_result['is_csv'];
					if ( ! empty($upload_result['root_element']))
						$post['root_element'] = $upload_result['root_element'];
				}
			} elseif ('url' == $this->input->post('type')) {
                $post['url'] = trim($post['url']);
				if ( ! empty($post['downloaded']) ){
					$downloaded = json_decode($post['downloaded'], true);
					$source    = $downloaded['source'];
					$filePath  = $downloaded['filePath'];
					$post['template'] = $downloaded['template'];
					PMXI_Plugin::$csv_path = $downloaded['csv_path'];
					PMXI_Plugin::$is_csv = $downloaded['is_csv'];
					if ( ! empty($downloaded['root_element']))
						$post['root_element'] = $downloaded['root_element'];
					$post['feed_type'] = $downloaded['feed_type'];
				} else {
					$uploader = new PMXI_Upload($post['url'], $this->errors);
					$upload_result = $uploader->url($post['feed_type']);
					if ($upload_result instanceof WP_Error) {
						$this->errors = $upload_result;
					} else {
						$source    = $upload_result['source'];
						$filePath  = $upload_result['filePath'];
						$post['template'] = $upload_result['template'];
						PMXI_Plugin::$csv_path = $upload_result['csv_path'];
						PMXI_Plugin::$is_csv = $upload_result['is_csv'];
						if ( ! empty($upload_result['root_element']))
							$post['root_element'] = $upload_result['root_element'];
						$post['feed_type'] = $upload_result['feed_type'];
					}
				}
			} elseif ( 'ftp' == $this->input->post('type')) {
                if ( ! empty($post['downloaded']) ){
                    $downloaded = json_decode($post['downloaded'], true);
                    $source    = $downloaded['source'];

                    $filePath  = $downloaded['filePath'];
                    $post['template'] = $downloaded['template'];
                    PMXI_Plugin::$csv_path = $downloaded['csv_path'];
                    PMXI_Plugin::$is_csv = $downloaded['is_csv'];
                    if ( ! empty($downloaded['root_element']))
                        $post['root_element'] = $downloaded['root_element'];
                    $post['feed_type'] = $downloaded['feed_type'];
                } else {
                    try {
                        $files = PMXI_FTPFetcher::fetch($post);
                        $uploader = new PMXI_Upload($files[0], $this->errors, rtrim(str_replace(basename($files[0]), '', $files[0]), '/'));
                        $upload_result = $uploader->upload();
                        if (!$this->errors->get_error_codes()) {
                            $source    = $upload_result['source'];
                            $filePath  = $upload_result['filePath'];
                            $post['template'] = $upload_result['template'];
                            PMXI_Plugin::$csv_path = $upload_result['csv_path'];
                            PMXI_Plugin::$is_csv = $upload_result['is_csv'];
                            if ( ! empty($upload_result['root_element']))
                                $post['root_element'] = $upload_result['root_element'];
                            $post['feed_type'] = $upload_result['feed_type'] ?? '';
                        }
                    } catch (Exception $e) {
                        $this->errors->add('form-validation', $e->getMessage());
                    }
                }
                $source['type'] = 'ftp';
            } elseif ('file' == $this->input->post('type')) {

				$uploader = new PMXI_Upload($post['file'], $this->errors);
				$upload_result = $uploader->file();
				if ($upload_result instanceof WP_Error) {
					$this->errors = $upload_result;
				} else {
					$source    = $upload_result['source'];
					$filePath  = $upload_result['filePath'];
					$post['template'] = $upload_result['template'];
					PMXI_Plugin::$is_csv = $upload_result['is_csv'];
					if ( ! empty($upload_result['root_element']))
						$post['root_element'] = $upload_result['root_element'];
				}
			}

			if ($this->input->post('is_submitted') and '' == $this->input->post('custom_type')) {
				$this->errors->add('form-validation', __('Select an item type to import the data', 'wp-all-import-pro'));
			}
			if ($post['is_update_previous'] and empty($post['update_previous'])) {
				$this->errors->add('form-validation', __('Previous import for update must be selected to proceed with a new one', 'wp-all-import-pro'));
			}
			if ( 'taxonomies' == $this->input->post('custom_type') and '' == $this->input->post('taxonomy_type')){
                $this->errors->add('form-validation', __('Select a taxonomy to import the data', 'wp-all-import-pro'));
            }
			$this->data['detection_feed_extension'] = false;
			$elements_cloud = array();

			@set_time_limit(0);
			$deligate = $this->input->get('deligate', false);
			$redirect_to_template = empty($post['go_to_create_filters']);
			$importRecord = new PMXI_Import_Record();

			switch ( $deligate ) {
				case 'wpallexport':
					$import_id = $this->input->get('id', 0);
					$importRecord->clear();
					$importRecord->getById($import_id);
					if ( ! $importRecord->isEmpty() and ! empty($importRecord->options['unique_key'])) {
						$importRecord->set(array(
							'path' => wp_all_import_get_relative_path($filePath),
							'parent_import_id' => 0
						))->save();
						$post['is_update_previous'] = 1;
						$post['update_previous'] = $importRecord->id;
						$redirect_to_template = true;
					}

					if ( $importRecord->isEmpty() ){
						$this->errors->add('form-validation', __('File is no longer in the correct format', 'wp-all-import-pro'));
					} elseif (empty($importRecord->options['unique_key'])) {
						$this->errors->add('form-validation', __('Certain columns are required to be present in your file to enable it to be re-imported with WP All Import. These columns are missing. Re-export your file using WP All Export, and don\'t delete any of the columns when editing it. Then, re-import will work correctly.', 'wp-all-import-pro'));
					} elseif($importRecord->options['custom_type'] == 'import_users' && ! class_exists('PMUI_Plugin')){
						$this->errors->add('form-validation', __('<p>The import template you are using requires the User Add-On.</p><a href="https://www.wpallimport.com/import-wordpress-users/?utm_source=wordpress.org&utm_medium=wpai-import-template&utm_campaign=free+wp+all+export+plugin" target="_blank">Purchase the User Add-On</a>', 'wp-all-import-pro'));
					} elseif($importRecord->options['custom_type'] == 'shop_customer' && ! class_exists('PMUI_Plugin')){
                        $this->errors->add('form-validation', __('<p>The import template you are using requires the User Add-On.</p><a href="https://www.wpallimport.com/import-wordpress-users/?utm_source=wordpress.org&utm_medium=wpai-import-template&utm_campaign=free+wp+all+export+plugin" target="_blank">Purchase the User Add-On</a>', 'wp-all-import-pro'));
					}
					break;

				default:
					# code...
					break;
			}

			$local_paths = !empty($local_paths) ? $local_paths : array($filePath);

			foreach ($local_paths as $key => $path) {
				if ( @file_exists($path) ){
					$file = new PMXI_Chunk($path, array('element' => $post['root_element'], 'get_cloud' => true));
					if ( ! empty($file->options['element']) ) {
						$xpath = empty($upload_result['bundle_xpath']) ? "/" . $file->options['element'] : $upload_result['bundle_xpath'];
						$elements_cloud = $file->cloud;
						if ( ! empty($elements_cloud) and class_exists('PMXE_Plugin') and ! $importRecord->isEmpty() ) {
							$is_file_valid = apply_filters('wp_all_import_is_exported_file_valid', true, $importRecord->options['export_id'], $elements_cloud);
							if ( ! $is_file_valid ) {
								$this->errors->add('form-validation', __('Certain columns are required to be present in your file to enable it to be re-imported with WP All Import. These columns are missing. Re-export your file using WP All Export, and don\'t delete any of the columns when editing it. Then, re-import will work correctly.', 'wp-all-import-pro'));
							}
						}
						if ( ($redirect_to_template or $post['auto_generate']) and ! $this->errors->get_error_codes() ) {
							// loop through the file until all lines are read
						    while ($xml = $file->read()) {
						    	if ( ! empty($xml) ) {
						      		//PMXI_Import_Record::preprocessXml($xml);
						      		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . "\n" . $xml;
							      	$dom = new DOMDocument('1.0', 'UTF-8');
									$old = libxml_use_internal_errors(true);
									$dom->loadXML($xml);
									libxml_use_internal_errors($old);
									$dxpath = new DOMXPath($dom);

									if (($elements = @$dxpath->query($xpath)) and $elements->length) {
										if ( empty($chunks) ) {
											$chunks = 0;
										}
										$chunks += $elements->length;
										unset($dom, $dxpath, $elements);
									}
							    }
							}
							//unset($file);
						}
						break;
					}
				} else {
                    $this->errors->add('form-validation', __('Unable to download feed resource.', 'wp-all-import-pro'));
                }
			}

			if ( ! $this->errors->get_error_codes() ) {

				// xml is valid
				$source['root_element'] = $file->options['element'];
				$source['first_import'] = date("Y-m-d H:i:s");

				PMXI_Plugin::$session->clean_session();

				$session_data = array(
					'filePath' => $filePath,
					'parent_import_id' => $parent_import,
					'xpath' => (!empty($xpath)) ? $xpath : '',
					'feed_type' => $post['feed_type'],
					'wizard_type' => $post['wizard_type'],
					'custom_type' => $post['custom_type'],
                    'taxonomy_type' => $post['taxonomy_type'],
                    'ftp_host' => $post['ftp_host'],
                    'ftp_path' => $post['ftp_path'],
                    'ftp_root' => $post['ftp_root'],
                    'ftp_port' => $post['ftp_port'],
                    'ftp_username' => $post['ftp_username'],
                    'ftp_password' => $post['ftp_password'],
					'ftp_private_key' => $post['ftp_private_key'],
					'source' => $source,
					'encoding' => 'UTF-8',
					'is_csv' => PMXI_Plugin::$is_csv,
					'csv_path' => PMXI_Plugin::$csv_path,
					'chunk_number' => 1,
					'log' => '',
					'processing' => 0,
					'queue_chunk_number' => 0,
					'count' => (isset($chunks)) ? $chunks : 0,
					'warnings' => 0,
					'errors' => 0,
					'start_time' => 0,
					'local_paths' => (!empty($local_paths)) ? $local_paths : array(), // ftp import local copies of remote files
					'csv_paths' => array(PMXI_Plugin::$csv_path), // ftp import local copies of remote CSV files
					'action' => 'import',
					'elements_cloud' => (!empty($elements_cloud)) ? $elements_cloud : array(),
					'pointer' => 1,
					'deligate' => $deligate,
					'first_step' => $post
				);

				// apply options from WP All Export bundle
				if ( ! empty($post['template'])) {
					$templates = json_decode($post['template'], true);
					$template_options = \pmxi_maybe_unserialize($templates[0]['options']);
					$template_options['type'] 	     = ($post['custom_type'] == 'page') ? 'page' : 'post';
					$template_options['custom_type'] = $post['custom_type'];
					$template_options['wizard_type'] = $post['wizard_type'];
					if ($post['wizard_type'] == 'new') {
						$template_options['create_new_records'] = 1;
					}
					$this->data['post'] = $template_options;
					PMXI_Plugin::$session->set('options', $template_options);
				}

				foreach ($session_data as $key => $value) {
					PMXI_Plugin::$session->set( $key, $value );
				}

				$update_previous = new PMXI_Import_Record();
				if ($post['is_update_previous'] and ! $update_previous->getById($post['update_previous'])->isEmpty()) {
					PMXI_Plugin::$session->set('update_previous', $update_previous->id);
					PMXI_Plugin::$session->set('xpath', $update_previous->xpath);
					PMXI_Plugin::$session->set('options', $update_previous->options);
				} else {
					PMXI_Plugin::$session->set('update_previous', '');
				}

				PMXI_Plugin::$session->save_data();

				$xml = $this->get_xml();

				if ( empty($xml) ) {
					$this->errors->add('upload-validation', __('Please confirm you are importing a valid feed.<br/> Often, feed providers distribute feeds with invalid data, improperly wrapped HTML, line breaks where they should not be, faulty character encodings, syntax errors in the XML, and other issues.<br/><br/>WP All Import has checks in place to automatically fix some of the most common problems, but we can’t catch every single one.<br/><br/>It is also possible that there is a bug in WP All Import, and the problem is not with the feed.<br/><br/>If you need assistance, please contact support – <a href="mailto:support@wpallimport.com">support@wpallimport.com</a> – with your XML/CSV file. We will identify the problem and release a bug fix if necessary.', 'wp-all-import-pro'));
					$this->data['upload_validation'] = true;
				} elseif( $redirect_to_template ) {
					wp_redirect(esc_url_raw(add_query_arg('action', 'template', $this->baseUrl))); die();
				} elseif( $post['auto_generate'] ) {
					wp_redirect(esc_url_raw(add_query_arg('action', 'options', $this->baseUrl))); die();
				} else {
					wp_redirect(esc_url_raw(add_query_arg('action', 'element', $this->baseUrl))); die();
				}
			} else if ('url' == $this->input->post('type') and !empty($this->errors)) {
				$this->errors->add('form-validation', __('WP All Import unable to detect file type.<br/><br/>WP All Import not able to determine what type of file you are importing. Make sure your file extension is correct for the file type you are importing.<br/> Please choose the correct file type from the dropdown below, or try adding &type=xml or &type=csv to the end of the URL, for example http://example.com/export-products.php?&type=xml', 'wp-all-import-pro'));
				$this->data['detection_feed_extension'] = true;
			} else {
				$this->errors->add('upload-validation', __('Please confirm you are importing a valid feed.<br/> Often, feed providers distribute feeds with invalid data, improperly wrapped HTML, line breaks where they should not be, faulty character encodings, syntax errors in the XML, and other issues.<br/><br/>WP All Import has checks in place to automatically fix some of the most common problems, but we can’t catch every single one.<br/><br/>It is also possible that there is a bug in WP All Import, and the problem is not with the feed.<br/><br/>If you need assistance, please contact support – <a href="mailto:support@wpallimport.com">support@wpallimport.com</a> – with your XML/CSV file. We will identify the problem and release a bug fix if necessary.', 'wp-all-import-pro'));
				$this->data['upload_validation'] = true;
			}
			do_action("pmxi_get_file", $filePath);
		}
		if ($this->input->post('is_submitted') and $this->errors->get_error_codes()) {
            PMXI_Plugin::$session->clean_session();
        }
		$this->render();
	}

	/**
	 * Step #2: Choose elements
	 */
	public function element() {
		$xpath = new DOMXPath($this->data['dom']);
		$post = $this->input->post(array('xpath' => '', 'filters_output' => PMXI_Plugin::$session->options['filters_output'] ?? ''));
		$this->data['post'] =& $post;
		$this->data['elements_cloud'] = PMXI_Plugin::$session->elements_cloud;
		$this->data['is_csv'] = PMXI_Plugin::$session->is_csv;

		if ($this->input->post('is_submitted')) {
			check_admin_referer('choose-elements', '_wpnonce_choose-elements');
			if ('' == $post['xpath']) {
				$this->errors->add('form-validation', __('No elements selected', 'wp-all-import-pro'));
			} else {
				$node_list = @ $xpath->query($post['xpath']); // make sure only element selection is allowed; prevent parsing warning to be displayed
				if (FALSE === $node_list) {
					$this->errors->add('form-validation', __('Your XPath is not valid.', 'wp-all-import-pro'));
				} else {
					foreach ($node_list as $el) {
						if ( ! $el instanceof DOMElement) {
							$this->errors->add('form-validation', __('XPath must match only elements', 'wp-all-import-pro'));
							break;
						};
					}
				}
			}
			if ( ! $this->errors->get_error_codes()) {
                if (isset(PMXI_Plugin::$session->options)) {
                    $post = array_replace_recursive(PMXI_Plugin::$session->options, $post);
                }
                PMXI_Plugin::$session->set('options', $post);
                PMXI_Plugin::$session->save_data();
				wp_redirect(apply_filters('pmxi_element_redirect' , esc_url_raw(add_query_arg('action', 'template', $this->baseUrl)))); die();
			}
		} else {
			if ( PMXI_Plugin::$session->xpath )  {
				$post['xpath'] = PMXI_Plugin::$session->xpath;
				$this->data['elements'] = $elements = $xpath->query($post['xpath']);
				$this->data['is_show_warning'] = true;
				foreach ($elements as $element) {
					if ($element instanceof DOMElement) {
						foreach ($element->childNodes as $child) {
							if ($child instanceof DOMElement) {
								$this->data['is_show_warning'] = false;
							}
						}
						break;
					}
				}
				if ( ! $elements->length and ! empty( PMXI_Plugin::$session->update_previous ) ) {
					$_GET['pmxi_nt'] = __('Warning: No matching elements found for XPath expression from the import being updated. It probably means that new XML file has different format. Though you can update XPath, procceed only if you sure about update operation being valid.', 'wp-all-import-pro');
				}
			} else {
				// suggest 1st repeating element as default selection
				$post['xpath'] = PMXI_Render::xml_find_repeating($this->data['dom']->documentElement);
				if ( ! empty($post['xpath'])){
					$this->data['elements'] = $elements = $xpath->query($post['xpath']);
				}
			}
		}
		// workaround to prevent rendered XML representation to eat memory since it has to be stored in memory when output is bufferred
		$this->render();
	}

	/**
	 * Helper to evaluate xpath and return matching elements as direct paths for javascript side to highlight them
	 */
	public function evaluate() {

		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			wp_redirect(esc_url_raw(add_query_arg('action', 'element', $this->baseUrl))); die();
		}

		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$xpath = new DOMXPath($this->data['dom']);
		$post = $this->input->post(array('xpath' => '', 'show_element' => 1, 'root_element' => PMXI_Plugin::$session->source['root_element'], 'delimiter' => '', 'is_csv' => 0));
		$wp_uploads = wp_upload_dir();

		if ( ! check_ajax_referer( 'wp_all_import_secure', 'security', false )) {
			$this->errors->add('form-validation', __('Security check', 'wp-all-import-pro'));
		} elseif ('' == $post['xpath']) {
			$this->errors->add('form-validation', __('Your XPath is empty.<br/><br/>Please enter an XPath expression.', 'wp-all-import-pro'));
		} else {

			$source = PMXI_Plugin::$session->get('source');

			// counting selected elements
			if ('' != $post['delimiter'] and $post['delimiter'] != PMXI_Plugin::$session->is_csv ) {

				include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');

				PMXI_Plugin::$session->set('is_csv', $post['delimiter']);

				wp_all_import_remove_source(PMXI_Plugin::$session->filePath, false);

				$csv = new PMXI_CsvParser( array(
					'filename' => PMXI_Plugin::$session->get('csv_path'),
					'xpath' => '',
					'delimiter' => $post['delimiter'],
					'targetDir' => rtrim(str_replace(basename(PMXI_Plugin::$session->filePath), '', PMXI_Plugin::$session->filePath), '/')
				));

				$filePath = $csv->xml_path;
				PMXI_Plugin::$session->set('filePath', $filePath);
				PMXI_Plugin::$session->set('local_paths', array($filePath));

			}

			// counting selected elements
			PMXI_Plugin::$session->set('xpath', $post['xpath']);

			$current_xpath = '';

			if ($post['show_element'] == 1) {
				PMXI_Plugin::$session->set('count', $this->data['node_list_count'] = 0);
			} else {
				$this->data['node_list_count'] = PMXI_Plugin::$session->count;
			}

			$xpath_elements = explode('[', $post['xpath']);
			$xpath_parts    = explode('/', $xpath_elements[0]);

			$source['root_element'] = $xpath_parts[1];

			PMXI_Plugin::$session->set('source', $source);

			PMXI_Plugin::$session->save_data();

			$loop = 0;

			foreach (PMXI_Plugin::$session->local_paths as $key => $path) {

				$file = new PMXI_Chunk($path, array('element' => $source['root_element'], 'encoding' => PMXI_Plugin::$session->encoding));

			    // loop through the file until all lines are read
			    while ($xml = $file->read()) {

			    	if ( ! empty($xml) )
			      	{
			      		//PMXI_Import_Record::preprocessXml($xml);
			      		$xml = "<?xml version=\"1.0\" encoding=\"". PMXI_Plugin::$session->encoding ."\"?>" . "\n" . $xml;

				      	$dom = new DOMDocument('1.0', PMXI_Plugin::$session->encoding);
						$old = libxml_use_internal_errors(true);
						$dom->loadXML($xml);
						libxml_use_internal_errors($old);
						$xpath = new DOMXPath($dom);

						if (($elements = @$xpath->query($post['xpath'])) and $elements->length){

							if ( $post['show_element'] == 1 ){
								$this->data['node_list_count'] += $elements->length;
								if (!$loop) $this->data['dom'] = $dom;
							}

							$loop += $elements->length;

							if ( $post['show_element'] > 1 and $loop == $post['show_element']) {
								$this->data['dom'] = $dom;
								break(2);
							}

							unset($dom, $xpath, $elements);
						}
				    }
				}
				unset($file);

				PMXI_Plugin::$session->set('count', $this->data['node_list_count']);
			}
			if ( ! $this->data['node_list_count']) {
				$this->errors->add('form-validation', __('There are no elements to import based on your XPath.<br/><br/>If you are on the Create Filters screen, you probably specified filtering options that don’t match any elements present in your file.<br/>If you are seeing this error elsewhere, it means that while the XPath expression for your initial import matched some elements in your file previously, there are now zero elements in the file that match this expression.<br/>You can edit the XPath for your import by going to the Manage Imports -> Import Settings page.', 'wp-all-import-pro'));
			}
		}

		$this->data['show_element'] = $post['show_element'];

		PMXI_Plugin::$session->save_data();

		$this->data['is_csv'] = $post['is_csv'];

		ob_start();
		if ( ! $this->errors->get_error_codes()) {
			$xpath = new DOMXPath($this->data['dom']);
			$this->data['elements'] = $elements = @ $xpath->query($post['xpath']); // prevent parsing warning to be displayed
			$paths = array(); $this->data['paths'] =& $paths;
			if (PMXI_Plugin::getInstance()->getOption('highlight_limit') and $elements->length <= PMXI_Plugin::getInstance()->getOption('highlight_limit')) {
				foreach ($elements as $el) {
					if ( ! $el instanceof DOMElement) continue;
					$p = PMXI_Render::get_xml_path($el, $xpath) and $paths[] = $p;
				}
			}
			$this->render();
		} else {
			$this->error();
		}

		$html = ob_get_clean();

		ob_start();

		if ( ! empty($elements->length) ) PMXI_Render::render_xml_elements_for_filtring($elements->item(0));

		$render_element = ob_get_clean();

		exit( json_encode( array('result' => true, 'html' => $html, 'root_element' =>  $source['root_element'], 'count' => $this->data['node_list_count'], 'render_element' => $render_element )));
	}

	/**
	 * Helper to evaluate xpath and return matching elements as direct paths for javascript side to highlight them
	 */
	public function evaluate_variations() {
		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			wp_redirect(esc_url_raw(add_query_arg('action', 'element', $this->baseUrl))); die();
		}

		$post = $this->input->post(array('xpath' => '', 'show_element' => 1, 'root_element' => (!empty(PMXI_Plugin::$session->source['root_element'])) ? PMXI_Plugin::$session->source['root_element'] : '', 'tagno' => 0, 'parent_tagno' => 1));
		$wp_uploads = wp_upload_dir();

		$this->get_xml( $post['parent_tagno'], true );

		$xpath = new DOMXPath($this->data['dom']);

		$this->data['tagno'] = max(intval($this->input->getpost('tagno', 1)), 0);

		if ('' == $post['xpath']) {
			$this->errors->add('form-validation', __('Your XPath is empty.<br/><br/>Please enter an XPath expression.', 'wp-all-import-pro'));
		} else {
			$post['xpath'] = '/' . ((!empty($this->data['update_previous']->root_element)) ? $this->data['update_previous']->root_element : PMXI_Plugin::$session->source['root_element']) .'/'.  ltrim(trim(str_replace("[*]","",$post['xpath']),'{}'), '/');
			// in default mode
			$this->data['variation_elements'] = $elements = @ $xpath->query($post['xpath']); // prevent parsing warning to be displayed
			$this->data['variation_list_count'] = $elements->length;
			if (FALSE === $elements) {
				$this->errors->add('form-validation', __('Your XPath is not valid.', 'wp-all-import-pro'));
			} elseif ( ! $elements->length) {
				$this->errors->add('form-validation', __('No matching variations found for XPath specified', 'wp-all-import-pro'));
			} else {
				foreach ($elements as $el) {
					if ( ! $el instanceof DOMElement) {
						$this->errors->add('form-validation', __('XPath must match only elements', 'wp-all-import-pro'));
						break;
					};
				}
			}
		}

		ob_start();

		if ( ! $this->errors->get_error_codes()) {

			$paths = array(); $this->data['paths'] =& $paths;
			if (PMXI_Plugin::getInstance()->getOption('highlight_limit') and $elements->length <= PMXI_Plugin::getInstance()->getOption('highlight_limit')) {
				foreach ($elements as $el) {
					if ( ! $el instanceof DOMElement) continue;

					$p = PMXI_Render::get_xml_path($el, $xpath) and $paths[] = $p;
				}
			}

			$this->render();
		} else {
			$this->error();
		}

		exit( json_encode(array('html' => ob_get_clean())) );
	}

	/**
	 * Preview selected xml tag (called with ajax from `template` step)
	 */
	public function tag( $is_ajax = true ) {

		if ($is_ajax) check_ajax_referer( 'wp_all_import_secure', 'security' );

        $this->data['is_ajax'] = $is_ajax;

		if (empty($this->data['elements']->length)) {
			$update_previous = new PMXI_Import_Record();
			$id = $this->input->get('id');
			if ($id and $update_previous->getById($id)) {
				PMXI_Plugin::$session->set('update_previous', $update_previous->id);
				PMXI_Plugin::$session->set('xpath', $update_previous->xpath);
				PMXI_Plugin::$session->set('options', $update_previous->options);
				$history = new PMXI_File_List();
				$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $update_previous->id), 'id DESC');

				if ($history->count()){
					$history_file = new PMXI_File_Record();
					$history_file->getBy('id', $history[0]['id']);

					if ( PMXI_Plugin::$session->has_session() ){
						PMXI_Plugin::$session->set('filePath', wp_all_import_get_absolute_path($history_file->path));
						PMXI_Plugin::$session->set('count', $update_previous->count);
						PMXI_Plugin::$session->set('encoding', ( ! empty($update_previous->options['encoding'])) ? $update_previous->options['encoding'] : 'UTF-8');
						PMXI_Plugin::$session->save_data();
					}
				}
			} else {
				unset(PMXI_Plugin::$session->update_previous);
			}
		}

		$this->data['tagno'] = max(intval($this->input->getpost('tagno', 1)), 1);

		$this->data['import_action'] = $this->input->getpost('import_action', false);

		if ($this->data['tagno']) {
            if (empty(PMXI_Plugin::$session->local_paths) && !empty(PMXI_Plugin::$session->filePath) && !file_exists(PMXI_Plugin::$session->filePath) && !empty(PMXI_Plugin::$session->update_previous)) {
                $history_file = new PMXI_File_Record();
                $history_file->getBy( array('import_id' => PMXI_Plugin::$session->update_previous), 'id DESC' );
                $local_paths = ( ! $history_file->isEmpty() ) ? array(wp_all_import_get_absolute_path($history_file->path)) : array();
            } else {
                $local_paths = ( ! empty(PMXI_Plugin::$session->local_paths) && isset(PMXI_Plugin::$session->local_paths[0]) && file_exists(PMXI_Plugin::$session->local_paths[0])) ? PMXI_Plugin::$session->local_paths : array(PMXI_Plugin::$session->filePath);
            }
            $local_paths = array_filter($local_paths);
			PMXI_Plugin::$session->set('local_paths', $local_paths);
			$loop = 0;
            $xpath_value = $this->input->getpost('xpath', PMXI_Plugin::$session->xpath);
            if ($xpath_value !== PMXI_Plugin::$session->xpath) {
                $this->data['tagno'] = 1;
                PMXI_Plugin::$session->set('xpath', $xpath_value);
            }
            $this->data['node_list_count'] = $this->data['tagno'] == 1 ? 0 : PMXI_Plugin::$session->count;
            if (!empty($local_paths)) {
                $this->data['elements'] = FALSE;
                foreach ($local_paths as $key => $path) {
                    if (@file_exists($path)){
                        $file = new PMXI_Chunk($path, array(
                            'element' => PMXI_Plugin::$session->source['root_element'],
                            'encoding' => PMXI_Plugin::$session->encoding
                        ));
                        // loop through the file until all lines are read
                        while ($xml = $file->read()) {
                            if ( ! empty($xml) ) {
                                //PMXI_Import_Record::preprocessXml($xml);
                                $xml = "<?xml version=\"1.0\" encoding=\"". PMXI_Plugin::$session->encoding ."\"?>" . "\n" . $xml;
                                $dom = new DOMDocument('1.0', PMXI_Plugin::$session->encoding);
                                $old = libxml_use_internal_errors(true);
                                $dom->loadXML($xml);
                                libxml_use_internal_errors($old);
                                $xpath = new DOMXPath($dom);
                                $elements = @$xpath->query($xpath_value);
                                if ($elements and $elements->length){

                                    if ( $this->data['tagno'] == 1 ){
                                        $this->data['node_list_count'] += $elements->length;
                                        PMXI_Plugin::$session->set('count', $this->data['node_list_count']);
                                        if (!$loop) $this->data['dom'] = $dom;
                                    }

                                    $loop += $elements->length;

                                    if ($loop == $this->data['tagno']) {
                                        $this->data['elements'] = $elements;
                                    }

                                    if ( $this->data['tagno'] > 1 and ($loop == $this->data['tagno'] or $loop == PMXI_Plugin::$session->count)) {
                                        unset($dom, $xpath, $elements);
                                        break(2);
                                    }

                                    unset($dom, $xpath, $elements);
                                }
                            }
                        }
                        unset($file);
                    }
                }
            }
            PMXI_Plugin::$session->save_data();
		}

		if ( $is_ajax ) {
			ob_start();
			$this->render();
			exit( json_encode(array('html' => ob_get_clean())) );
		}
		else $this->render();
	}

	/**
	 * Preview future post based on current template and tag (called with ajax from `template` step)
	 */
	public function preview() {

		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			exit('Nice try!');
		}

		if ( ! check_ajax_referer( 'wp_all_import_preview', 'security', false )){
			$this->errors->add('form-validation', __('Security check', 'wp-all-import-pro'));
		}

		if ( ! $this->errors->get_error_codes()) {

			$post = $this->input->post(array(
				'title' => '',
				'content' => '',
				'is_keep_linebreaks' => 0,
				'is_leave_html' => 0,
				'fix_characters' => 0,
				'import_encoding' => 'UTF-8',
				'tagno' => 0
			));
			$wp_uploads = wp_upload_dir();

			$this->data['tagno'] = $tagno = min(max(intval($this->input->getpost('tagno', 1)), 1), PMXI_Plugin::$session->count);

			$xml = '';

			$local_paths = ( ! empty(PMXI_Plugin::$session->local_paths) ) ? PMXI_Plugin::$session->local_paths : array(PMXI_Plugin::$session->filePath);

			$loop = 1;
			foreach ($local_paths as $key => $path) {

				if (PMXI_Plugin::$session->encoding != $post['import_encoding'] and ! empty(PMXI_Plugin::$session->csv_paths[$key])){
					// conver CSV to XML with selected encoding
					include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');
					$csv = new PMXI_CsvParser(array(
						'filename' => PMXI_Plugin::$session->csv_paths[$key],
						'xpath' => '',
						'delimiter' => PMXI_Plugin::$is_csv,
						'encoding' => $post['import_encoding'],
						'xml_path' => $path
					));
				}

				$file = new PMXI_Chunk($path, array(
					'element' => PMXI_Plugin::$session->source['root_element'],
					'encoding' => $post['import_encoding']
				));

			    // loop through the file until all lines are read
			    while ($xml = $file->read()) {

			    	if ( ! empty($xml) ) {
			      		//PMXI_Import_Record::preprocessXml($xml);
			      		$xml = "<?xml version=\"1.0\" encoding=\"". $post['import_encoding'] ."\"?>" . "\n" . $xml;

				      	$dom = new DOMDocument('1.0', $post['import_encoding']);
						$old = libxml_use_internal_errors(true);
						$dom->loadXML($xml); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
						libxml_use_internal_errors($old);
						$xpath = new DOMXPath($dom);
						if (($this->data['elements'] = $elements = @$xpath->query(PMXI_Plugin::$session->xpath)) and $elements->length){

						    if ( $loop == $tagno ){
                                /* Merge nested XML/CSV files */
                                /*$nested_files = json_decode(PMXI_Plugin::$session->options['nested_files'], true);
                                if ( ! empty($nested_files) ){
                                    $merger = new PMXI_Nested($dom, $nested_files, $xml, PMXI_Plugin::$session->xpath);
                                    $merger->merge();
                                    $xml = $merger->get_xml();
                                    unset($merger);
                                }*/
                                unset($dom, $xpath, $elements);
                                break(2);
                            }
                            unset($dom, $xpath, $elements);
                            $loop++;
						}
				    }
				}
				unset($file);
			}

			$xpath = "(" . PMXI_Plugin::$session->xpath . ")[1]";

            // validate root XPath
            try{
                list($this->data['title']) = XmlImportParser::factory($xml, $xpath, $post['title'], $file)->parse(); unlink($file);
            }
            catch(XmlImportException $e){
                $xpath = PMXI_Plugin::$session->xpath;
            }

			PMXI_Plugin::$session->set('encoding', $post['import_encoding']);
			PMXI_Plugin::$session->save_data();

			$functions = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

			// validate
			try {
				if (empty($xml)){
					$this->errors->add('form-validation', __('WP All Import lost track of where you are.<br/><br/>Maybe you cleared your cookies or maybe it is just a temporary issue on your web host\'s end.', 'wp-all-import-pro'));
				} elseif (empty($post['title']) && wp_all_import_is_title_required(PMXI_Plugin::$session->options['custom_type'])) {
					$this->errors->add('form-validation', __('<strong>Warning</strong>: your title is blank.', 'wp-all-import-pro'));
					$this->data['title'] = "";
				} else {
					list($this->data['title']) = XmlImportParser::factory($xml, $xpath, $post['title'], $file)->parse(); unlink($file);
					if ( ! isset($this->data['title']) || '' == strval(trim(strip_tags($this->data['title'], '<img><input><textarea><iframe><object><embed>')))) {
						$this->errors->add('xml-parsing', __('<strong>Warning</strong>: resulting post title is empty', 'wp-all-import-pro'));
					}
					else $this->data['title'] = ($post['is_leave_html']) ? html_entity_decode($this->data['title']) : $this->data['title'];
				}
			} catch (XmlImportException $e) {
				$this->errors->add('form-validation', sprintf(__('Error parsing title: %s', 'wp-all-import-pro'), $e->getMessage()));
			}
			try {
				if (empty($xml)){
					$this->errors->add('form-validation', __('WP All Import lost track of where you are.<br/><br/>Maybe you cleared your cookies or maybe it is just a temporary issue on your web host\'s end.', 'wp-all-import-pro'));
				} elseif (empty($post['content']) && wp_all_import_is_title_required(PMXI_Plugin::$session->options['custom_type'])) {
					$this->errors->add('form-validation', __('<strong>Warning</strong>: your content is blank.', 'wp-all-import-pro'));
					$this->data['content'] = "";
				} else {
					list($this->data['content']) = XmlImportParser::factory($post['is_keep_linebreaks'] ? $xml : preg_replace('%\r\n?|\n%', ' ', $xml), $xpath, $post['content'], $file)->parse(); unlink($file);
					if ( ! isset($this->data['content']) || '' == strval(trim(strip_tags($this->data['content'], '<img><input><textarea><iframe><object><embed>')))) {
						$this->errors->add('xml-parsing', __('<strong>Warning</strong>: resulting post content is empty', 'wp-all-import-pro'));
					}
					else $this->data['content'] = ($post['is_leave_html']) ? html_entity_decode($this->data['content']) : $this->data['content'];
				}
			} catch (XmlImportException $e) {
				$this->errors->add('form-validation', sprintf(__('Error parsing content: %s', 'wp-all-import-pro'), $e->getMessage()));
			}
		}

		ob_start();
		$this->render();
		exit( json_encode(array('html' => ob_get_clean())) );
	}

	/**
	 * Preview future post images based on current template and tag (called with ajax from `template` step)
	 */
	public function preview_images() {
		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			exit('Nice try!');
		}

		if ( ! check_ajax_referer( 'wp_all_import_preview', 'security', false )){
			$this->errors->add('form-validation', __('Security check', 'wp-all-import-pro'));
		}

		if ( ! $this->errors->get_error_codes()) {

			$get = $this->data['get'] = $this->input->get(array(
				'slug' => ''
			));

			$post = $this->data['post'] = $this->input->post(array(
				$get['slug'] . 'download_images' => 'no',
				$get['slug'] . 'featured_delim' => '',
				$get['slug'] . 'featured_image' => '',
				$get['slug'] . 'download_featured_delim' => '',
				$get['slug'] . 'download_featured_image' => '',
				$get['slug'] . 'gallery_featured_delim' => '',
				$get['slug'] . 'gallery_featured_image' => '',
				'import_encoding' => 'UTF-8',
				'tagno' => 0
			));

			$wp_uploads = wp_upload_dir();

			$this->data['tagno'] = $tagno = min(max(intval($this->input->getpost('tagno', 1)), 1), PMXI_Plugin::$session->count);

			$xml = '';

			$local_paths = (!empty(PMXI_Plugin::$session->local_paths)) ? PMXI_Plugin::$session->local_paths : array(PMXI_Plugin::$session->filePath);

			$loop = 1;
			foreach ($local_paths as $key => $path) {

				if (PMXI_Plugin::$session->encoding != $post['import_encoding'] and ! empty(PMXI_Plugin::$session->csv_paths[$key])){
					// convert CSV to XML with selected encoding
					include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');
					$csv = new PMXI_CsvParser(array(
						'filename' => PMXI_Plugin::$session->csv_paths[$key],
						'xpath' => '',
						'delimiter' => PMXI_Plugin::$is_csv,
						'encoding' => $post['import_encoding'],
						'xml_path' => $path
					));
				}

				$file = new PMXI_Chunk($path, array('element' => (!empty($this->data['update_previous']->root_element)) ? $this->data['update_previous']->root_element : PMXI_Plugin::$session->source['root_element'], 'encoding' => $post['import_encoding']));

			    // loop through the file until all lines are read
			    while ($xml = $file->read()) {
			    	if (!empty($xml)) {
			      		//PMXI_Import_Record::preprocessXml($xml);
			      		$xml = "<?xml version=\"1.0\" encoding=\"". $post['import_encoding'] ."\"?>" . "\n" . $xml;

				      	$dom = new DOMDocument('1.0', $post['import_encoding']);
						$old = libxml_use_internal_errors(true);
						$dom->loadXML($xml); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
						libxml_use_internal_errors($old);
						$xpath = new DOMXPath($dom);
						if (($this->data['elements'] = $elements = @$xpath->query(PMXI_Plugin::$session->xpath)) and $elements->length){

							if ( $loop == $tagno ){
								/* Merge nested XML/CSV files */
								/*$nested_files = json_decode(PMXI_Plugin::$session->options['nested_files'], true);
								if ( ! empty($nested_files) ){
									$merger = new PMXI_Nested($dom, $nested_files, $xml, PMXI_Plugin::$session->xpath);
									$merger->merge();
									$xml = $merger->get_xml();
									unset($merger);
								}	*/
								unset($dom, $xpath, $elements);
								break(2);
							}
							unset($dom, $xpath, $elements);
							$loop++;
						}
				    }
				}
				unset($file);
			}
			//$this->data['tagno'] = $tagno = 1;

			$xpath = "(" . PMXI_Plugin::$session->xpath . ")[1]";

			PMXI_Plugin::$session->set('encoding', $post['import_encoding']);
			PMXI_Plugin::$session->save_data();

			$functions = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

			// validate
			try {
				$this->data['featured_images'] = false;
				if (empty($xml)){
					$this->errors->add('form-validation', __('WP All Import lost track of where you are.<br/><br/>Maybe you cleared your cookies or maybe it is just a temporary issue on your web host\'s end.', 'wp-all-import-pro'));
				} else {
					switch ($post[$get['slug'] . 'download_images']) {
						case 'no':
							$featured_image = $post[$get['slug'] . 'featured_image'];
							break;
						case 'gallery':
							$featured_image = $post[$get['slug'] . 'gallery_featured_image'];
							break;
						default: // yes
							$featured_image = $post[$get['slug'] . 'download_featured_image'];
							break;
					}

					if (empty($featured_image)){
                        $this->data['featured_images'] = '';
                    } else {
                        list($this->data['featured_images']) = XmlImportParser::factory($xml, $xpath, $featured_image, $file)->parse(); unlink($file);
                    }
				}
			} catch (XmlImportException $e) {
				$this->errors->add('form-validation', sprintf(__('Error parsing: %s', 'wp-all-import-pro'), $e->getMessage()));
			}
		}

		ob_start();
		$this->render();
		exit( json_encode(array('html' => ob_get_clean())) );
	}

	/**
	 * Preview taxonomies hierarchy based on current template and tag (called with ajax from `template` step)
	 */
	public function preview_taxonomies() {

		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			exit('Nice try!');
		}

		if ( ! check_ajax_referer( 'wp_all_import_preview', 'security', false )){
			$this->errors->add('form-validation', __('Security check', 'wp-all-import-pro'));
		}

		if ( ! $this->errors->get_error_codes()) {

			$post = $this->data['post'] = $this->input->post(array(
				'tax_logic' => '',
				'tax_hierarchical_logic_entire' => '',
				'tax_hierarchical_xpath' => '',
				'tax_hierarchical_delim' => '>',
				'is_tax_hierarchical_group_delim' => 0,
				'tax_hierarchical_group_delim'=> '|',
				'tax_enable_mapping' => '',
				'tax_mapping' => '',
				'tax_logic_mapping' => '',
				'import_encoding' => 'UTF-8',
				'tagno' => 0
			));

			$this->data['tagno'] = $tagno = min(max(intval($this->input->getpost('tagno', 1)), 1), PMXI_Plugin::$session->count);

			$xml = '';

			$local_paths = (!empty(PMXI_Plugin::$session->local_paths)) ? PMXI_Plugin::$session->local_paths : array(PMXI_Plugin::$session->filePath);

			$loop = 1;
			foreach ($local_paths as $key => $path) {
				if (PMXI_Plugin::$session->encoding != $post['import_encoding'] and ! empty(PMXI_Plugin::$session->csv_paths[$key])){
					// conver CSV to XML with selected encoding
					include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');
					$csv = new PMXI_CsvParser(array(
						'filename' => PMXI_Plugin::$session->csv_paths[$key],
						'xpath' => '',
						'delimiter' => PMXI_Plugin::$is_csv,
						'encoding' => $post['import_encoding'],
						'xml_path' => $path
					));
				}
				$file = new PMXI_Chunk($path, array('element' => (!empty($this->data['update_previous']->root_element)) ? $this->data['update_previous']->root_element : PMXI_Plugin::$session->source['root_element'], 'encoding' => $post['import_encoding']));
			    // loop through the file until all lines are read
			    while ($xml = $file->read()) {
			    	if (!empty($xml)) {
			      		//PMXI_Import_Record::preprocessXml($xml);
			      		$xml = "<?xml version=\"1.0\" encoding=\"". $post['import_encoding'] ."\"?>" . "\n" . $xml;
				      	$dom = new DOMDocument('1.0', $post['import_encoding']);
						$old = libxml_use_internal_errors(true);
						$dom->loadXML($xml); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
						libxml_use_internal_errors($old);
						$xpath = new DOMXPath($dom);
						if (($this->data['elements'] = $elements = @$xpath->query(PMXI_Plugin::$session->xpath)) and $elements->length){

							if ( $loop == $tagno ){
								/* Merge nested XML/CSV files */
								/*$nested_files = json_decode(PMXI_Plugin::$session->options['nested_files'], true);
								if ( ! empty($nested_files) ){
									$merger = new PMXI_Nested($dom, $nested_files, $xml, PMXI_Plugin::$session->xpath);
									$merger->merge();
									$xml = $merger->get_xml();
									unset($merger);
								}	*/
								unset($dom, $xpath, $elements);
								break(2);
							}
							unset($dom, $xpath, $elements);
							$loop++;
						}
				    }
				}
				unset($file);
			}
			//$this->data['tagno'] = $tagno = 1;

			$xpath = "(" . PMXI_Plugin::$session->xpath . ")[1]";

			PMXI_Plugin::$session->set('encoding', $post['import_encoding']);
			PMXI_Plugin::$session->save_data();

			$wp_uploads = wp_upload_dir();
			$functions  = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

			// validate
			try {
				if (empty($xml)){
					$this->errors->add('form-validation', __('Error parsing: String could not be parsed as XML', 'wp-all-import-pro'));
				} else{
					$data_to_preview = false;
					$this->data['tax_hierarchical'] = array();
					foreach ($post['tax_logic'] as $ctx => $logic) {
						if ( $logic == 'hierarchical' and ! empty($post['tax_hierarchical_logic_entire'][$ctx]) and is_array($post['tax_hierarchical_xpath'][$ctx])){
							foreach ($post['tax_hierarchical_xpath'][$ctx] as $ctx_path) { if (empty($ctx_path)) continue;
								list($d) = XmlImportParser::factory($xml, $xpath, $ctx_path, $file)->parse(); unlink($file);
								if ($post['is_tax_hierarchical_group_delim'][$ctx] and !empty($post['tax_hierarchical_group_delim'][$ctx])){
									// apply mapping rules before splitting via separator symbol
									if ( ! empty($post['tax_enable_mapping'][$ctx]) and ! empty($post['tax_logic_mapping'][$ctx]) ){
										if ( ! empty($post['tax_mapping'][$ctx])){
											$mapping_rules = json_decode($post['tax_mapping'][$ctx], true);
											if ( ! empty( $mapping_rules) ){
												foreach ($mapping_rules as $rule) {
													if ( ! empty($rule[trim($d)])){
														$d = trim($rule[trim($d)]);
														break;
													}
												}
											}
										}
									}
									$hierarchy_groups = explode($post['tax_hierarchical_group_delim'][$ctx], $d);
									if (!empty($hierarchy_groups) and is_array($hierarchy_groups)){
										foreach ($hierarchy_groups as $key => $group) {
											$this->data['tax_hierarchical'][$ctx][] = $group;
										}
									}
								}
								else{
									$this->data['tax_hierarchical'][$ctx][] = $d;
								}
							}
							$data_to_preview = true;
						}
					}
					if ( ! $data_to_preview )
						$this->errors->add('form-validation', __('There is no data to preview', 'wp-all-import-pro'));
				}
			} catch (XmlImportException $e) {
				$this->errors->add('form-validation', sprintf(__('Error parsing: %s', 'wp-all-import-pro'), $e->getMessage()));
			}
		}

		ob_start();
		$this->render();
		exit( json_encode(array('html' => ob_get_clean())) );
	}

	/**
	 * Preview prices based on current template and tag (called with ajax from `template` step)
	 */
	public function preview_prices() {

		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			exit('Nice try!');
		}

		if ( ! check_ajax_referer( 'wp_all_import_preview', 'security', false )){
			$this->errors->add('form-validation', __('Security check', 'wp-all-import-pro'));
		}

		if ( ! $this->errors->get_error_codes()) {

			$post = $this->data['post'] = $this->input->post(array(
				'single_product_regular_price' => '',
				'single_product_sale_price' => '',
				'disable_prepare_price' => 0,
				'prepare_price_to_woo_format' => 0,
				'convert_decimal_separator' => 1,
				'single_product_regular_price_adjust' => '',
				'single_product_regular_price_adjust_type' => '%',
				'single_product_sale_price_adjust' => '',
				'single_product_sale_price_adjust_type' => '%',
				'import_encoding' => 'UTF-8',
				'tagno' => 0
			));

			$this->data['tagno'] = $tagno = min(max(intval($this->input->getpost('tagno', 1)), 1), PMXI_Plugin::$session->count);

			$xml = '';

			$local_paths = (!empty(PMXI_Plugin::$session->local_paths)) ? PMXI_Plugin::$session->local_paths : array(PMXI_Plugin::$session->filePath);

			$loop = 1;
			foreach ($local_paths as $key => $path) {

				if (PMXI_Plugin::$session->encoding != $post['import_encoding'] and ! empty(PMXI_Plugin::$session->csv_paths[$key])){
					// conver CSV to XML with selected encoding
					include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');

					$csv = new PMXI_CsvParser(array(
						'filename' => PMXI_Plugin::$session->csv_paths[$key],
						'xpath' => '',
						'delimiter' => PMXI_Plugin::$is_csv,
						'encoding' => $post['import_encoding'],
						'xml_path' => $path
					));
				}

				$file = new PMXI_Chunk($path, array('element' => (!empty($this->data['update_previous']->root_element)) ? $this->data['update_previous']->root_element : PMXI_Plugin::$session->source['root_element'], 'encoding' => $post['import_encoding']));

			    // loop through the file until all lines are read
			    while ($xml = $file->read()) {
			    	if (!empty($xml))
			      	{
			      		//PMXI_Import_Record::preprocessXml($xml);
			      		$xml = "<?xml version=\"1.0\" encoding=\"". $post['import_encoding'] ."\"?>" . "\n" . $xml;

				      	$dom = new DOMDocument('1.0', $post['import_encoding']);
						$old = libxml_use_internal_errors(true);
						$dom->loadXML($xml); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
						libxml_use_internal_errors($old);
						$xpath = new DOMXPath($dom);
						if (($this->data['elements'] = $elements = @$xpath->query(PMXI_Plugin::$session->xpath)) and $elements->length){

							if ( $loop == $tagno ){
								/* Merge nested XML/CSV files */
								/*$nested_files = json_decode(PMXI_Plugin::$session->options['nested_files'], true);
								if ( ! empty($nested_files) ){
									$merger = new PMXI_Nested($dom, $nested_files, $xml, PMXI_Plugin::$session->xpath);
									$merger->merge();
									$xml = $merger->get_xml();
									unset($merger);
								}	*/
								unset($dom, $xpath, $elements);
								break(2);
							}
							unset($dom, $xpath, $elements);
							$loop++;
						}
				    }
				}
				unset($file);
			}
			//$this->data['tagno'] = $tagno = 1;

			$xpath = "(" . PMXI_Plugin::$session->xpath . ")[1]";

			PMXI_Plugin::$session->set('encoding', $post['import_encoding']);
			PMXI_Plugin::$session->save_data();

			$wp_uploads = wp_upload_dir();
			$functions = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

			// validate
			try {
				if (empty($xml)){
					$this->errors->add('form-validation', __('Error parsing: String could not be parsed as XML', 'wp-all-import-pro'));
				} else{
					$data_to_preview = false;

					if ("" != $post['single_product_regular_price']){
						list($this->data['product_regular_price']) = XmlImportParser::factory($xml, $xpath, $post['single_product_regular_price'], $file)->parse(); unlink($file);
						$this->data['product_regular_price'] = pmwi_adjust_price(pmwi_prepare_price($this->data['product_regular_price'], $post['disable_prepare_price'], $post['prepare_price_to_woo_format'], $post['convert_decimal_separator']), 'regular_price', $post);
						$data_to_preview = true;

					}
					if ("" != $post['single_product_sale_price']){
						list($this->data['product_sale_price']) = XmlImportParser::factory($xml, $xpath, $post['single_product_sale_price'], $file)->parse(); unlink($file);
						$this->data['product_sale_price'] = pmwi_adjust_price(pmwi_prepare_price($this->data['product_sale_price'], $post['disable_prepare_price'], $post['prepare_price_to_woo_format'], $post['convert_decimal_separator']), 'sale_price', $post);
						$data_to_preview = true;

					}

					if ( ! $data_to_preview )
						$this->errors->add('form-validation', __('There is no data to preview', 'wp-all-import-pro'));
				}
			} catch (XmlImportException $e) {
				$this->errors->add('form-validation', sprintf(__('Error parsing: %s', 'wp-all-import-pro'), $e->getMessage()));
			}
		}

		ob_start();
		$this->render();
		exit( json_encode(array('html' => ob_get_clean())) );
	}

	/**
	 * Step #3: Choose template
	 */
	public function template() {

		$template = new PMXI_Template_Record();

		$default = PMXI_Plugin::get_default_import_options();

		if ($this->isWizard) {
			$this->data['source_type'] = PMXI_Plugin::$session->source['type'];

			foreach (PMXI_Admin_Addons::get_active_addons() as $class) {
				if (class_exists($class)) $default += call_user_func(array($class, "get_default_import_options"));
			}
			$default['wizard_type'] = PMXI_Plugin::$session->wizard_type;
			if (empty($default['custom_type'])) $default['custom_type'] = PMXI_Plugin::$session->custom_type;
            if (empty($default['taxonomy_type'])) $default['taxonomy_type'] = PMXI_Plugin::$session->taxonomy_type;
			if (empty($default['delimiter'])) $default['delimiter'] = PMXI_Plugin::$session->is_csv;
			if (empty($default['ftp_host'])) $default['ftp_host'] = PMXI_Plugin::$session->ftp_host;
			if (empty($default['ftp_path'])) $default['ftp_path'] = PMXI_Plugin::$session->ftp_path;
			$default['ftp_root'] = PMXI_Plugin::$session->ftp_root;
			if (empty($default['ftp_username'])) $default['ftp_username'] = PMXI_Plugin::$session->ftp_username;
			if (empty($default['ftp_password'])) $default['ftp_password'] = PMXI_Plugin::$session->ftp_password;
			if (empty($default['ftp_private_key'])) $default['ftp_private_key'] = PMXI_Plugin::$session->ftp_private_key;
            $default['ftp_port'] = PMXI_Plugin::$session->ftp_port;

			$DefaultOptions = (isset(PMXI_Plugin::$session->options)) ? array_replace_recursive($default, PMXI_Plugin::$session->options) : $default;

            $DefaultOptions['xpath'] = '';

			$post = $this->input->post( apply_filters('pmxi_options_options', $DefaultOptions, $this->isWizard) );

		} else {
            $this->data['dom'] = new DOMDocument('1.0', 'UTF-8');
//            $this->data['update_previous'] = new PMXI_Import_Record();
//            $old = libxml_use_internal_errors(true);
//
            $this->data['is_csv'] = PMXI_Plugin::$session->is_csv;

            $this->data['source_type'] = $this->data['import']->type;
            foreach (PMXI_Admin_Addons::get_active_addons() as $class) {
                if (class_exists($class)) $default += call_user_func(array($class, "get_default_import_options"));
            }
            $DefaultOptions = (is_array($this->data['import']->options)) ? array_replace_recursive($default, $this->data['import']->options) : $default;
            $source = array(
                'name' => $this->data['import']->name,
                'type' => $this->data['import']->type,
                'path' => wp_all_import_get_relative_path($this->data['import']->path),
                'root_element' => $this->data['import']->root_element,
            );
            PMXI_Plugin::$session->set('source', $source);
            $post = $this->input->post( apply_filters('pmxi_options_options', $DefaultOptions, $this->isWizard) );
            $post['xpath'] = $this->data['import']->xpath;

            $xml = $this->get_xml();
            if ( ! empty($xml) ) {
                @$this->data['dom']->loadXML($xml);
                $xpath = new DOMXPath($this->data['dom']);
                $this->data['elements'] = $elements = $xpath->query($post['xpath']);
            }
		}

		$max_input_vars = @ini_get('max_input_vars');

		if(ctype_digit($max_input_vars) && count($_POST, COUNT_RECURSIVE) >= $max_input_vars) {
			$this->errors->add('form-validation', sprintf(__('You\'ve reached your max_input_vars limit of %d. Please increase this.', 'wp-all-import-pro'), $max_input_vars));
		}

		$this->data['post'] =& $post;

		PMXI_Plugin::$session->set('options', $post);
		PMXI_Plugin::$session->set('is_loaded_template', '');

		if (($load_template = $this->input->post('load_template'))) { // init form with template selected
			if ( ! $template->getById($load_template)->isEmpty()) {

				$template_options = $template->options;
				$template_options['type'] = $post['type'];
				$template_options['custom_type'] = $post['custom_type'];
                $template_options['taxonomy_type'] = $post['taxonomy_type'];
				$template_options['wizard_type'] = $post['wizard_type'];
				$template_options['delimiter'] = $post['delimiter'];
				$template_options['ftp_host'] = $post['ftp_host'];
				$template_options['ftp_path'] = $post['ftp_path'];
				$template_options['ftp_root'] = $post['ftp_root'];
				$template_options['ftp_port'] = $post['ftp_port'];
				$template_options['ftp_username'] = $post['ftp_username'];
				$template_options['ftp_password'] = $post['ftp_password'];
				$template_options['ftp_private_key'] = $post['ftp_private_key'];

				if ($this->isWizard and $post['wizard_type'] == 'new') {
					$template_options['create_new_records'] = 1;
				}
                if ($this->isWizard) {
                    $template_options['delimiter'] = PMXI_Plugin::$session->is_csv;
                }

				$this->data['post'] = $template_options;
				PMXI_Plugin::$session->set('is_loaded_template', $load_template);
				PMXI_Plugin::$session->set('options', $template_options);
			}

		} elseif ($this->input->post('is_submitted')) { // save template submission

			check_admin_referer('template', '_wpnonce_template');

			$wp_uploads = wp_upload_dir();
			$functions  = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

			if (!empty($post['title'])) {
				$this->_validate_template($post['title'], 'Post title');
			}
			elseif ( wp_all_import_is_title_required($post['custom_type']) ) {
				$this->warnings->add('1', __('<strong>Warning:</strong> your title is blank.', 'wp-all-import-pro'));
			}

			if (!empty($post['content'])) {
				$this->_validate_template($post['content'], 'Post content');
			}
			elseif ( wp_all_import_is_title_required($post['custom_type']) ){
				$this->warnings->add('2', __('<strong>Warning:</strong> your content is blank.', 'wp-all-import-pro'));
			}

			if ( ! $this->errors->get_error_codes()) {

				// Attributes fields logic
				$post = apply_filters('pmxi_save_options', $post);

				// validate post excerpt
				if ( ! empty($post['post_excerpt'])) $this->_validate_template($post['post_excerpt'], __('Excerpt', 'wp-all-import-pro'));
				// validate images
				if ( $post['download_images'] == 'yes') {
					if ( ! empty($post['download_featured_image'])) $this->_validate_template($post['download_featured_image'], __('Images', 'wp-all-import-pro'));
				} else {
					if ( ! empty($post['featured_image'])) $this->_validate_template($post['featured_image'], __('Images', 'wp-all-import-pro'));
				}
				// validate images meta data
				foreach (array('title', 'caption', 'alt', 'decription') as $section) {
					if ( !empty($post['set_image_meta_' . $section]) )
					{
						if ( ! empty($post['image_meta_' . $section])) $this->_validate_template($post['image_meta_' . $section], __('Images meta ' . $section, 'wp-all-import-pro'));
					}
				}

				// remove entires where both custom_name and custom_value are empty
				$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($post['custom_name'], 'strlen')), array_keys(array_filter($post['custom_value'], 'strlen')))));

				$post['custom_name'] = array_intersect_key($post['custom_name'], $not_empty);
				$post['custom_value'] = array_intersect_key($post['custom_value'], $not_empty);

				// validate
                foreach ($post['custom_name'] as $custom_name) {
                    $this->_validate_template($custom_name, __('Custom Field Name', 'wp-all-import-pro'));
                }
                foreach ($post['custom_value'] as $key => $custom_value) {
                    if ( empty($post['custom_format'][$key]) ) {
                        $this->_validate_template($custom_value, __('Custom Field Value', 'wp-all-import-pro'));
                    }
                }

				if ( $post['type'] == "post" and $post['custom_type'] == "product" and class_exists('PMWI_Plugin')){
					// remove entires where both custom_name and custom_value are empty
					$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($post['attribute_name'], 'strlen')), array_keys(array_filter($post['attribute_value'], 'strlen')))));
					$post['attribute_name'] = array_intersect_key($post['attribute_name'], $not_empty);
					$post['attribute_value'] = array_intersect_key($post['attribute_value'], $not_empty);
					// validate
					if (array_keys(array_filter($post['attribute_name'], 'strlen')) != array_keys(array_filter($post['attribute_value'], 'strlen'))) {
						$this->errors->add('form-validation', __('Both name and value must be set for all woocommerce attributes', 'wp-all-import-pro'));
					} else {
						foreach ($post['attribute_name'] as $attribute_name) {
							$this->_validate_template($attribute_name, __('Attribute Field Name', 'wp-all-import-pro'));
						}
						foreach ($post['attribute_value'] as $custom_value) {
							$this->_validate_template($custom_value, __('Attribute Field Value', 'wp-all-import-pro'));
						}
					}
				}

				if ('post' == $post['type'] && isset($post['tags'])) {
					/*'' == $post['categories'] or $this->_validate_template($post['categories'], __('Categories', 'wp-all-import-pro'));*/
					'' == $post['tags'] or $this->_validate_template($post['tags'], __('Tags', 'wp-all-import-pro'));
				}
				if ('specific' == $post['date_type']) {
					'' == $post['date'] or $this->_validate_template($post['date'], __('Date', 'wp-all-import-pro'));
				} else {
					'' == $post['date_start'] or $this->_validate_template($post['date_start'], __('Start Date', 'wp-all-import-pro'));
					'' == $post['date_end'] or $this->_validate_template($post['date_end'], __('Start Date', 'wp-all-import-pro'));
				}

				$this->errors = apply_filters('pmxi_options_validation', $this->errors, $post, isset($this->data['import']) ? $this->data['import'] : false);

				if ( ! $this->errors->get_error_codes()) { // no validation errors found
					// assign some defaults
					'' !== $post['date'] or $post['date'] = 'now';
					'' !== $post['date_start'] or $post['date_start'] = 'now';
					'' !== $post['date_end'] or $post['date_end'] = 'now';

					if ( ! empty($post['name']) and !empty($post['save_template_as']) ) { // save template in database
						$template->getByName($post['name'])->set(array(
							'name' => $post['name'],
							'is_keep_linebreaks' => $post['is_keep_linebreaks'],
							'is_leave_html' => $post['is_leave_html'],
							'fix_characters' => $post['fix_characters'],
							'options' => $post
						))->save();
						PMXI_Plugin::$session->set('saved_template', $template->id);
					}

					if ($this->isWizard) {
                        PMXI_Plugin::$session->set('options', $post);
                        PMXI_Plugin::$session->save_data();

                        $DefaultOptions = array();
                        $DefaultOptions['tmp_unique_key'] = $this->findUniqueKey();


                        if(!PMXI_Plugin::$session->get('update_previous')) {
                            $import = new PMXI_Import_Record();
                            $import->set(
                                (empty(PMXI_Plugin::$session->source) ? array() : PMXI_Plugin::$session->source)
                                + array(
                                    'xpath' => PMXI_Plugin::$session->xpath,
                                    'options' => $DefaultOptions + PMXI_Plugin::$session->options,
                                    'count' => PMXI_Plugin::$session->count,
                                    'friendly_name' => wp_all_import_clear_xss(PMXI_Plugin::$session->options['friendly_name']),
                                    'feed_type' => PMXI_Plugin::$session->feed_type,
                                    'parent_import_id' => ($this->data['update_previous']->isEmpty()) ? PMXI_Plugin::$session->parent_import_id : $this->data['update_previous']->parent_import_id,
                                    'queue_chunk_number' => 0,
                                    'triggered' => 0,
                                    'processing' => 0,
                                    'executing' => 0,
                                    'iteration' => (!empty($import->iteration)) ? $import->iteration : 0
                                )
                            )->save();

                            $history_file = new PMXI_File_Record();
                            $history_file->set(array(
                                'name' => $import->name,
                                'import_id' => $import->id,
                                'path' => wp_all_import_get_relative_path(PMXI_Plugin::$session->filePath),
                                'registered_on' => date('Y-m-d H:i:s'),
                            ))->save();


                            $this->data['update_previous'] = $import;
                            PMXI_Plugin::$session->set('update_previous', $import->id);
                            PMXI_Plugin::$session->set('import_id', $import->id);
                            PMXI_Plugin::$session->set('import', $import);
                            PMXI_Plugin::$session->save_data();
                        }

						wp_redirect(esc_url_raw(add_query_arg('action', 'options', $this->baseUrl))); die();

					} else {
                        $xpath = $this->input->post('xpath');
                        if (!empty($xpath)) {
                            $this->data['import']->set(['xpath' => $xpath]);
                        }
						$this->data['import']->set(array( 'options' => $post, 'settings_update_on' => date('Y-m-d H:i:s')))->update();
						$args = array(
							'page' => 'pmxi-admin-manage',
							'pmxi_nt' => urlencode(__('Template updated', 'wp-all-import-pro'))
						);

						if ($this->warnings->get_error_codes())
							$args['warnings'] = implode(',', $this->warnings->get_error_codes());

						wp_redirect(esc_url_raw(add_query_arg( $args + array_intersect_key($_GET, array_flip($this->baseUrlParamNames)) ,admin_url('admin.php'))));
						die();
					}
				}

			}
		}

		PMXI_Plugin::$session->save_data();

        global $wpdb;

        switch ($post['custom_type']){
			case 'import_users':
			case 'shop_customer':
                // Get All meta keys in the system
                $this->data['meta_keys'] = array();
                $meta_keys = new PMXI_Model_List();
                $meta_keys->setTable($wpdb->usermeta);
                $meta_keys->setColumns('umeta_id', 'meta_key')->getBy(NULL, "umeta_id", NULL, NULL, "meta_key");
                $hide_fields = array('first_name', 'last_name', 'nickname', 'description', PMXI_Plugin::getInstance()->getWPPrefix() . 'capabilities');
                if ( ! empty($meta_keys) and $meta_keys->count() ){
                    foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields) or strpos($meta_key['meta_key'], '_wp') === 0) continue;
                        $this->data['meta_keys'][] = $meta_key['meta_key'];
                    }
                }
                break;
            case 'taxonomies':
                // Get All meta keys in the system
                $this->data['meta_keys'] = array();
                $meta_keys = new PMXI_Model_List();
                $meta_keys->setTable(PMXI_Plugin::getInstance()->getWPPrefix() . 'termmeta');
                $meta_keys->setColumns('meta_id', 'meta_key')->getBy(NULL, "meta_id", NULL, NULL, "meta_key");
                $hide_fields = array();
                if ( ! empty($meta_keys) and $meta_keys->count() ){
                    foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields)) continue;
                        $this->data['meta_keys'][] = $meta_key['meta_key'];
                    }
                }
                break;
            case 'woo_reviews':
            case 'comments':
                // Get All meta keys in the system
                $this->data['meta_keys'] = array();
                $meta_keys = new PMXI_Model_List();
                $meta_keys->setTable(PMXI_Plugin::getInstance()->getWPPrefix() . 'commentmeta');
                $meta_keys->setColumns('meta_id', 'meta_key')->getBy(NULL, "meta_id", NULL, NULL, "meta_key");
                $hide_fields = array();
                if ( ! empty($meta_keys) and $meta_keys->count() ){
                    foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields)) continue;
                        $this->data['meta_keys'][] = $meta_key['meta_key'];
                    }
                }
                break;
            default:

                // Get all meta keys for requested post type
                $this->data['meta_keys'] = array();
                $hide_fields = array('_edit_lock', '_edit_last', '_wp_trash_meta_status', '_wp_trash_meta_time');

                $records = get_posts( array('post_type' => $post['custom_type'], 'post_status' => 'any') );
                if ( ! empty($records)){
                    foreach ($records as $record) {
                        $record_meta = get_post_meta($record->ID, '');
                        if ( ! empty($record_meta)){
                            foreach ($record_meta as $record_meta_key => $record_meta_value) {
                                if ( ! in_array($record_meta_key, $this->data['meta_keys']) and ! in_array($record_meta_key, $hide_fields)) $this->data['meta_keys'][] = $record_meta_key;
                            }
                        }
                    }
                }

                if ($post['custom_type'] == 'product') {
                    $records = get_posts( array('post_type' => 'product_variation', 'post_status' => 'any') );
                    if ( ! empty($records)){
                        foreach ($records as $record) {
                            $record_meta = get_post_meta($record->ID, '');
                            if ( ! empty($record_meta)){
                                foreach ($record_meta as $record_meta_key => $record_meta_value) {
                                    if ( ! in_array($record_meta_key, $this->data['meta_keys']) and ! in_array($record_meta_key, $hide_fields)) $this->data['meta_keys'][] = $record_meta_key;
                                }
                            }
                        }
                    }
                }

                // Get existing product attributes
                $existing_attributes = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_product_attributes' LIMIT 0 , 50" );
                $this->data['existing_attributes'] = array();
                if ( ! empty($existing_attributes)){
                    foreach ($existing_attributes as $key => $existing_attribute) {
                        $existing_attribute = \pmxi_maybe_unserialize($existing_attribute->meta_value);
                        if (!empty($existing_attribute) and is_array($existing_attribute)):
                            foreach ($existing_attribute as $attr_key => $value) {
                                if (strpos($attr_key, "pa_") === false and ! in_array($attr_key, $this->data['existing_attributes'])) $this->data['existing_attributes'][] = $attr_key;
                            }
                        endif;
                    }
                }

                break;
        }

		if (user_can_richedit()) {
			wp_enqueue_script('editor');
		}
		wp_enqueue_script('word-count');
		add_thickbox();
		wp_enqueue_script('media-upload');
		wp_enqueue_script('quicktags');

		$this->render();
	}

	protected function _validate_template($text, $field_title) {
		try {
            if ($text != ''){
                $scanner = new XmlImportTemplateScanner();
                $tokens = $scanner->scan(new XmlImportStringReader($text));
                $parser = new XmlImportTemplateParser($tokens);
                $parser->parse();
            }
		} catch (XmlImportException $e) {
			$this->errors->add('form-validation', sprintf(__('%s template is invalid: %s', 'wp-all-import-pro'), $field_title, $e->getMessage()));
		}
	}

	/**
	 * Step #4: Options
	 */
	public function options() {

		$default = PMXI_Plugin::get_default_import_options();

		if ($this->isWizard) {
            if (!PMXI_Plugin::$session->get('update_previous')) {

				$import = new PMXI_Import_Record();

				$friendly_name = ( ! empty(PMXI_Plugin::$session->options['friendly_name']) ) ? PMXI_Plugin::$session->options['friendly_name'] : '';

                $import->set(
                    (empty(PMXI_Plugin::$session->source) ? array() : PMXI_Plugin::$session->source)
                    + array(
                        'xpath' => PMXI_Plugin::$session->xpath,
                        'options' => PMXI_Plugin::$session->options,
                        'count' => PMXI_Plugin::$session->count,
                        'friendly_name' => wp_all_import_clear_xss($friendly_name),
                        'feed_type' => PMXI_Plugin::$session->feed_type,
                        'parent_import_id' => ($this->data['update_previous']->isEmpty()) ? PMXI_Plugin::$session->parent_import_id : $this->data['update_previous']->parent_import_id,
                        'queue_chunk_number' => 0,
                        'triggered' => 0,
                        'processing' => 0,
                        'executing' => 0,
                        'iteration' => (!empty($import->iteration)) ? $import->iteration : 0
                    )
                )->save();

                $history_file = new PMXI_File_Record();
                $history_file->set(array(
                    'name' => $import->name,
                    'import_id' => $import->id,
                    'path' => wp_all_import_get_relative_path(PMXI_Plugin::$session->filePath),
                    'registered_on' => date('Y-m-d H:i:s'),
                ))->save();


                $this->data['update_previous'] = $import;

                PMXI_Plugin::$session->set('update_previous', $import->id);
                PMXI_Plugin::$session->set('import_id', $import->id);
                PMXI_Plugin::$session->set('import', $import);
                PMXI_Plugin::$session->save_data();
            }
			$this->data['source_type'] = PMXI_Plugin::$session->source['type'];

			foreach (PMXI_Admin_Addons::get_active_addons() as $class) {
				if (class_exists($class)) $default += call_user_func(array($class, "get_default_import_options"));
			}

			$DefaultOptions = array_replace_recursive($default, (isset(PMXI_Plugin::$session->options) ? PMXI_Plugin::$session->options : array()));

			if ( wp_all_import_is_title_required(PMXI_Plugin::$session->options['custom_type']) ){
				if (empty(PMXI_Plugin::$session->options['title']))
					$this->warnings->add('form-validation', __('<strong>Warning:</strong> your title is blank.', 'wp-all-import-pro'));
			}

            $DefaultOptions['tmp_unique_key'] = $this->findUniqueKey();

			if ($DefaultOptions['custom_type'] == "product" and class_exists('PMWI_Plugin') and $DefaultOptions['wizard_type'] != 'new'){
				$DefaultOptions['duplicate_indicator'] = empty($DefaultOptions['duplicate_indicator']) ? 'custom field' : $DefaultOptions['duplicate_indicator'];
				$DefaultOptions['custom_duplicate_name'] = empty($DefaultOptions['custom_duplicate_name']) ? '_sku' : $DefaultOptions['custom_duplicate_name'];
			}

			$DefaultOptions['wizard_type'] = PMXI_Plugin::$session->wizard_type;
			if (empty($DefaultOptions['custom_type'])) $DefaultOptions['custom_type'] = PMXI_Plugin::$session->custom_type;
            if (empty($DefaultOptions['taxonomy_type'])) $DefaultOptions['taxonomy_type'] = PMXI_Plugin::$session->taxonomy_type;
			if (empty($DefaultOptions['delimiter'])) $DefaultOptions['delimiter'] = PMXI_Plugin::$session->is_csv;
			if (empty($DefaultOptions['ftp_host'])) $DefaultOptions['ftp_host'] = PMXI_Plugin::$session->ftp_host;
			if (empty($DefaultOptions['ftp_path'])) $DefaultOptions['ftp_path'] = PMXI_Plugin::$session->ftp_path;
			if (empty($DefaultOptions['ftp_root'])) $DefaultOptions['ftp_root'] = PMXI_Plugin::$session->ftp_root;
			if (empty($DefaultOptions['ftp_port'])) $DefaultOptions['ftp_port'] = PMXI_Plugin::$session->ftp_port;
			if (empty($DefaultOptions['ftp_username'])) $DefaultOptions['ftp_username'] = PMXI_Plugin::$session->ftp_username;
			if (empty($DefaultOptions['ftp_password'])) $DefaultOptions['ftp_password'] = PMXI_Plugin::$session->ftp_password;
			if (empty($DefaultOptions['ftp_private_key'])) $DefaultOptions['ftp_private_key'] = PMXI_Plugin::$session->ftp_private_key;

			$post = $this->input->post( $DefaultOptions );

		} else {

			$this->data['source_type'] = $this->data['import']->type;
			foreach (PMXI_Admin_Addons::get_active_addons() as $class) {
				if (class_exists($class)) $default += call_user_func(array($class, "get_default_import_options"));
			}

			$DefaultOptions = (is_array($this->data['import']->options)) ? array_replace_recursive($default, $this->data['import']->options) : $default;

			$source = array(
				'name' => $this->data['import']->name,
				'type' => $this->data['import']->type,
				'path' => wp_all_import_get_relative_path($this->data['import']->path),
				'root_element' => $this->data['import']->root_element
			);

			PMXI_Plugin::$session->set('source', $source);

			$post = $this->input->post( $DefaultOptions );

		}

		$this->data['post'] =& $post;

		PMXI_Plugin::$session->set('options', $post);

		if ($this->input->post('is_submitted')) {

			check_admin_referer('options', '_wpnonce_options');

			$wp_uploads = wp_upload_dir();
			$functions  = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

            if ($post['is_delete_missing'] && !empty($post['delete_missing_action']) && $post['delete_missing_action'] == 'keep') {
                if (empty($post['is_send_removed_to_trash']) && empty($post['is_change_post_status_of_removed']) && empty($post['is_update_missing_cf']) && empty($post['missing_records_stock_status'])) {
                    $this->errors->add('delete-missing-validation', __('At least one option must be selected.', 'wp-all-import-pro'));
                }
            }

			if ( empty( $post['records_per_request'] ) || !is_numeric($post['records_per_request']) ) {
				$post['records_per_request'] = 20;
			}

			if ($post['is_import_specified']) {
				if (empty($post['import_specified'])) {
					$this->errors->add('form-validation', __('Records to import must be specified or uncheck `Import only specified records` option to process all records', 'wp-all-import-pro'));
				} else {
					$chanks = preg_split('% *, *%', $post['import_specified']);
					foreach ($chanks as $chank) {
						if ( ! preg_match('%^([1-9]\d*)( *- *([1-9]\d*))?$%', $chank, $mtch)) {
							$this->errors->add('form-validation', __('Wrong format of `Import only specified records` value', 'wp-all-import-pro'));
							break;
						} elseif (isset($mtch[3]) and intval($mtch[3]) > PMXI_Plugin::$session->count) {
							$this->errors->add('form-validation', __('One of the numbers in `Import only specified records` value exceeds record quantity in XML file', 'wp-all-import-pro'));
							break;
						} elseif (preg_match('%^(\d+)-(\d+)$%', $chank, $mtch) && intval($mtch[1]) > intval($mtch[2])) {
                            $this->errors->add('form-validation', __('Wrong format of `Import only specified records` value', 'wp-all-import-pro'));
                        }
					}
				}
			}
			if ('manual' != $post['duplicate_matching'] and '' == $post['unique_key']) {
				$this->errors->add('form-validation', __('Unique ID is currently empty and must be set. If you are not sure what to use as a Unique ID, click Auto-detect.', 'wp-all-import-pro'));
			} elseif ('manual' != $post['duplicate_matching']) {
				$this->_validate_template($post['unique_key'], __('Post Unique Key', 'wp-all-import-pro'));
			}
			if ( 'manual' == $post['duplicate_matching'] and 'custom field' == $post['duplicate_indicator']){
				if ('' == $post['custom_duplicate_name'])
					$this->errors->add('form-validation', __('Custom field name must be specified.', 'wp-all-import-pro'));
				if ('' == $post['custom_duplicate_value'])
					$this->errors->add('form-validation', __('Custom field value must be specified.', 'wp-all-import-pro'));
			}
			if ( 'manual' == $post['duplicate_matching'] ){
				if ( 'pid' == $post['duplicate_indicator'] && '' == $post['pid_xpath'] ){
					if ($post['custom_type'] == 'gf_entries') {
						$this->errors->add('form-validation', __('Entry ID must be specified.', 'wp-all-import-pro'));
					} else {
						$this->errors->add('form-validation', __('Post ID must be specified.', 'wp-all-import-pro'));
					}
                }
                if ( 'taxonomies' == $post['custom_type'] ){
                    if ( 'title' == $post['duplicate_indicator'] && '' == $post['title_xpath'] ){
                        $this->errors->add('form-validation', __('Term name must be specified.', 'wp-all-import-pro'));
                    }
                    if ( 'slug' == $post['duplicate_indicator'] && '' == $post['slug_xpath'] ){
                        $this->errors->add('form-validation', __('Term slug must be specified.', 'wp-all-import-pro'));
                    }
                }
			}

			// Categories/taxonomies logic
			if ($post['update_categories_logic'] == 'only' and ! empty($post['taxonomies_only_list'])){
				$post['taxonomies_list'] = explode(",", $post['taxonomies_only_list']);
			}
			elseif ($post['update_categories_logic'] == 'all_except' and ! empty($post['taxonomies_except_list'])){
				$post['taxonomies_list'] = explode(",", $post['taxonomies_except_list']);
			}

			// Custom fields logic
			if ($post['update_custom_fields_logic'] == 'only' and ! empty($post['custom_fields_only_list'])){
				$post['custom_fields_list'] = explode(",", $post['custom_fields_only_list']);
			}
			elseif ($post['update_custom_fields_logic'] == 'all_except' and ! empty($post['custom_fields_except_list']) ){
				$post['custom_fields_list'] = explode(",", $post['custom_fields_except_list']);
			}


			$upload_result = false;

			if ( ! $this->isWizard) {

                // updating csv delimiter
                if ( $post['delimiter'] != $this->data['import']->options['delimiter'] ){
                    $import_options = $this->data['import']->options;
                    $import_options['delimiter'] = $post['delimiter'];
                    $this->data['import']->set('options', $import_options)->save();
                }

				// File Path validation
				switch ($this->input->post('new_type')){
					case 'upload':
						$filePath = $this->input->post('filepath');
						if ($this->data['import']['path'] != $filePath){
							$uploader = new PMXI_Upload($filePath, $this->errors);
							$upload_result = $uploader->upload();
						}
						break;
					case 'url':
						$filePath = $this->input->post('url');

						if ($this->data['import']['path'] != $filePath){

							$filesXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<data><node></node></data>";

                            $filePath = apply_filters('wp_all_import_feed_url', wp_all_import_sanitize_url($filePath));

							$filePaths = XmlImportParser::factory($filesXML, '/data/node', $filePath, $file)->parse(); $tmp_files[] = $file;

							foreach ($tmp_files as $tmp_file) { // remove all temporary files created
								@unlink($tmp_file);
							}

							$file_to_import = $filePath;

							if ( ! empty($filePaths) and is_array($filePaths) ) {
								$file_to_import = array_shift($filePaths);
							}

							$uploader = new PMXI_Upload($file_to_import, $this->errors);
							$upload_result = $uploader->url($this->data['import']->feed_type, $filePath);
						}

						break;
					case 'file':
						$filePath = $this->input->post('file');
						if ($this->data['import']['path'] != $filePath){
							$uploader = new PMXI_Upload($filePath, $this->errors);
							$upload_result = $uploader->file();
						}
						break;
                    case 'ftp':
                        $filePath = $this->data['import']['path'];
                        $ftp_host = $this->input->post('ftp_host');
                        $ftp_port = $this->input->post('ftp_port');
                        $ftp_path = $this->input->post('ftp_path');
                        $ftp_root = $this->input->post('ftp_root');
                        $ftp_username = $this->input->post('ftp_username');
                        $ftp_password = $this->input->post('ftp_password');
                        $ftp_private_key = $this->input->post('ftp_private_key');
                        if ($ftp_host !== $this->data['import']['options']['ftp_host'] ||
                            $ftp_path !== $this->data['import']['options']['ftp_path'] ||
                            $ftp_root !== $this->data['import']['options']['ftp_root'] ||
                            $ftp_port !== $this->data['import']['options']['ftp_port'] ||
                            $ftp_username !== $this->data['import']['options']['ftp_username'] ||
                            $ftp_password !== $this->data['import']['options']['ftp_password'] || $ftp_private_key !== $this->data['import']['options']['ftp_private_key']) {
                            try {
                                $files = PMXI_FTPFetcher::fetch([
                                    'ftp_host' => $ftp_host,
                                    'ftp_path' => $ftp_path,
                                    'ftp_root' => $ftp_root,
                                    'ftp_port' => $ftp_port,
                                    'ftp_username' => $ftp_username,
                                    'ftp_password' => $ftp_password,
	                                'ftp_private_key' => $ftp_private_key,
                                ]);
                                $uploader = new PMXI_Upload($files[0], $this->errors, rtrim(str_replace(basename($files[0]), '', $files[0]), '/'));
                                $upload_result = $uploader->upload();
                            } catch (Exception $e) {
                                $this->errors->add('form-validation', $e->getMessage());
                            }
                        }
                        break;
					default:
						$this->errors->add('form-validation', __('WP All Import doesn\'t support this import type.', 'wp-all-import-pro'));
						break;
				}

				$is_validate_file = apply_filters('wp_all_import_is_validate_file_options_update', true, $this->data['import']->id);

				if (!$this->errors->get_error_codes() && $upload_result !== false and $this->data['import']['path'] != $filePath and $is_validate_file) {

					$file = new PMXI_Chunk($upload_result['filePath'], array('element' => ( ! empty($this->data['import']->root_element)) ? $this->data['import']->root_element : ''));

					$this->data['is_404'] = $file->is_404;

					$root_element = '';
					if ( ! empty($file->options['element']) ) {

						$root_element = $file->options['element'];

						$baseXpath = $this->data['import']->xpath;

						$loop = 0;

						// loop through the file until all lines are read
					    while ($xml = $file->read()) {

					    	if ( ! empty($xml) ) {
					      		//PMXI_Import_Record::preprocessXml($xml);
					      		$xml = "<?xml version=\"1.0\" encoding=\"". $this->data['import']['options']['encoding'] ."\"?>" . "\n" . $xml;

						      	$dom = new DOMDocument('1.0', $this->data['import']['options']['encoding']);
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($xml);
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);

								if (($elements = @$xpath->query($baseXpath)) and $elements->length) $loop += $elements->length;
								unset($dom, $xpath, $elements);

						    }
						}
						unset($file);

						if ( (int) $loop === 0 ){

							$this->warnings->add('root-element-validation', __('<strong>Warning:</strong> this import file does not have the same structure as the last file associated with this import. WP All Import won\'t be able to import this file with your current settings. You\'ll probably need to adjust your XPath in the "Configure Advanced Settings" box below, and reconfigure your import by clicking "Edit" on the Manage Imports page.', 'wp-all-import-pro'));

							$file = new PMXI_Chunk($upload_result['filePath'], array('element' => ( ! empty($upload_result['root_element'])) ? $upload_result['root_element'] : ''));

							if ( ! empty($file->options['element']) ) {

								$root_element = $file->options['element'];

								$baseXpath = '/' . $upload_result['root_element'];

								$loop = 0;

								// loop through the file until all lines are read
							    while ($xml = $file->read()) {
							    	if ( ! empty($xml) ) {
							      		//PMXI_Import_Record::preprocessXml($xml);
							      		$xml = "<?xml version=\"1.0\" encoding=\"". $this->data['import']['options']['encoding'] ."\"?>" . "\n" . $xml;
								      	$dom = new DOMDocument('1.0', $this->data['import']['options']['encoding']);
										$old = libxml_use_internal_errors(true);
										$dom->loadXML($xml);
										libxml_use_internal_errors($old);
										$xpath = new DOMXPath($dom);

										if (($elements = @$xpath->query($baseXpath)) and $elements->length) $loop += $elements->length;
										unset($dom, $xpath, $elements);
								    }
								}
								unset($file);
								if ($loop) $this->data['import']->set(array('count' => $loop))->save();
							}
						}
						$upload_result['root_element'] = $root_element;
					} else {
						$this->warnings->add('root-element-validation', __('Root element not found for uploaded feed.', 'wp-all-import-pro'));
					}
				}
			}

			$this->errors = apply_filters('pmxi_options_validation', $this->errors, $post, isset($this->data['import']) ? $this->data['import'] : false);

			if ( ! $this->errors->get_error_codes()) { // no validation errors found

				// Attributes fields logic
				$post = apply_filters('pmxi_save_options', $post, $this->isWizard);

				if ($this->isWizard) {

					PMXI_Plugin::$session->set('options', $post);

					PMXI_Plugin::$session->save_data();

					if($this->data['update_previous']) {
                        $this->data['update_previous']->set('options', $post)->save();
                    }

                    // update import template with final settings
					if ( PMXI_Plugin::$session->saved_template ){
						$template = new PMXI_Template_Record();
						$template->getById(PMXI_Plugin::$session->saved_template)->set(array(
							'options' => $post
						))->save();
					}

					if ( ! $this->input->post('save_only')) {
						wp_redirect(esc_url_raw(add_query_arg('action', 'confirm', $this->baseUrl))); die();
					} else {
						$import = $this->data['update_previous'];
						$is_update = ! $import->isEmpty();
						$import->set(
							PMXI_Plugin::$session->source
							+ array(
								'xpath' => PMXI_Plugin::$session->xpath,
								'options' => PMXI_Plugin::$session->options,
								'count' => PMXI_Plugin::$session->count,
								'friendly_name' => wp_all_import_clear_xss($this->data['post']['friendly_name']),
							)
						)->save();

						wp_redirect(esc_url_raw(add_query_arg(array('page' => 'pmxi-admin-manage', 'pmxi_nt' => urlencode($is_update ? __('Import updated', 'wp-all-import-pro') : __('Import created', 'wp-all-import-pro'))), admin_url('admin.php')))); die();
					}

				} else {

					$xpath = $this->input->post('xpath');

					$toUpdate = array(
						'friendly_name' => wp_all_import_clear_xss($this->data['post']['friendly_name']),
						'xpath' => $this->input->post('xpath'),
						'settings_update_on' => date('Y-m-d H:i:s')
					);

					// detecting root element
					if ( $xpath != $this->data['import']->xpath ){
						$xpath_elements = explode('[', $xpath);
						$xpath_parts    = explode('/', $xpath_elements[0]);
						$toUpdate['root_element'] = $xpath_parts[1];
					}

					$this->data['import']->set('options', $post)->set( $toUpdate )->save();

					// set new import file

					switch ($this->input->post('new_type')){
						case 'upload':
							$filePath = $this->input->post('filepath');
							$source = array(
								'name' => basename($filePath),
								'type' => 'upload',
								'path' => $filePath,
							);
							break;
						case 'url':
							$filePath = $this->input->post('url');

                            $filePath = apply_filters('wp_all_import_feed_url', wp_all_import_sanitize_url($filePath));

							$source = array(
								'name' => basename(parse_url($filePath, PHP_URL_PATH)),
								'type' => 'url',
								'path' => $filePath,
							);
							break;
						case 'file':
							$wp_uploads = wp_upload_dir();
							$filePath = $this->input->post('file');
							$source = array(
								'name' => basename($filePath),
								'type' => 'file',
								'path' => $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . PMXI_Plugin::FILES_DIRECTORY . DIRECTORY_SEPARATOR . $filePath,
							);
							break;
						case 'ftp':
							$filePath = empty($upload_result) ? $filePath : $upload_result['filePath'];
							$source = array(
								'name' => basename($filePath),
								'type' => 'ftp',
								'path' => $filePath,
							);
							break;
					}

					$source['path'] = wp_all_import_get_relative_path($source['path']);

					// if new file is successfully uploaded
					if (!empty($upload_result['filePath'])){
						// unlink previous files
						$history = new PMXI_File_List();
						$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $this->data['import']->id), 'id DESC');
						if ($history->count()){
							foreach ($history as $file){
								$history_file_path = wp_all_import_get_absolute_path($file['path']);
								if ( @file_exists($history_file_path) and $history_file_path != $upload_result['filePath'] ){
									if (in_array($this->data['import']->type, array('upload')))
										wp_all_import_remove_source($history_file_path, false);
									else
										wp_all_import_remove_source($history_file_path);
								}
								$history_file = new PMXI_File_Record();
								$history_file->getBy('id', $file['id']);
								if ( ! $history_file->isEmpty()) $history_file->delete( $history_file_path != $upload_result['filePath'] );
							}
						}

						$history_file = new PMXI_File_Record();
						$history_file->set(array(
							'name' => $this->data['import']->name,
							'import_id' => $this->data['import']->id,
							'path' => wp_all_import_get_relative_path($upload_result['filePath']),
							'registered_on' => date('Y-m-d H:i:s')
						))->save();
					}

					if ( ! $this->warnings->get_error_codes()) {
						$this->data['import']->set($source)->save();
						wp_redirect(esc_url_raw(add_query_arg(array('page' => 'pmxi-admin-manage', 'pmxi_nt' => urlencode(__('Configuration updated', 'wp-all-import-pro'))) + array_intersect_key($_GET, array_flip($this->baseUrlParamNames)), admin_url('admin.php')))); die();
					} else {
						$source['root_element'] = $upload_result['root_element'];
						PMXI_Plugin::$session->set('source', $source);
						$this->data['import']->set( array_merge($source, array('xpath' => '/' . $upload_result['root_element'])) )->save();
					}
				}
			}
		}

        global $wpdb;

		$this->data['existing_meta_keys'] = array();

        switch ($post['custom_type']){
			case 'import_users':
			case 'shop_customer':
                // Get All meta keys in the system
                $this->data['meta_keys'] = array();
                $meta_keys = new PMXI_Model_List();
                $meta_keys->setTable($wpdb->usermeta);
                $meta_keys->setColumns('umeta_id', 'meta_key')->getBy(NULL, "umeta_id", NULL, NULL, "meta_key");
                $hide_fields = array('first_name', 'last_name', 'nickname', 'description', PMXI_Plugin::getInstance()->getWPPrefix() . 'capabilities');
                if ( ! empty($meta_keys) and $meta_keys->count() ){
                    foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields) or strpos($meta_key['meta_key'], '_wp') === 0) continue;
                        $this->data['existing_meta_keys'][] = $meta_key['meta_key'];
                    }
                }
                break;
            case 'taxonomies':
                // Get All meta keys in the system
                $this->data['meta_keys'] = array();
                $meta_keys = new PMXI_Model_List();
                $meta_keys->setTable(PMXI_Plugin::getInstance()->getWPPrefix() . 'termmeta');
                $meta_keys->setColumns('meta_id', 'meta_key')->getBy(NULL, "meta_id", NULL, NULL, "meta_key");
                $hide_fields = array();
                if ( ! empty($meta_keys) and $meta_keys->count() ){
                    foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields)) continue;
                        $this->data['existing_meta_keys'][] = $meta_key['meta_key'];
                    }
                }
                break;
            case 'comments':
            case 'woo_reviews':
                // Get All meta keys in the system
                $this->data['meta_keys'] = array();
                $meta_keys = new PMXI_Model_List();
                $meta_keys->setTable(PMXI_Plugin::getInstance()->getWPPrefix() . 'commentmeta');
                $meta_keys->setColumns('meta_id', 'meta_key')->getBy(NULL, "meta_id", NULL, NULL, "meta_key");
                $hide_fields = array();
                if ( ! empty($meta_keys) and $meta_keys->count() ){
                    foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields)) continue;
                        $this->data['existing_meta_keys'][] = $meta_key['meta_key'];
                    }
                }
                break;
            default:

                // Get all meta keys for requested post type
                $hide_fields = array('_edit_lock', '_edit_last', '_wp_trash_meta_status', '_wp_trash_meta_time');

				if ( $post['custom_type'] == 'product' ) {
					$records = get_posts( array('post_type' => array('product', 'product_variation')) );
				} else {
					$records = get_posts( array('post_type' => $post['custom_type']) );
				}

                if ( ! empty($records)){
                    foreach ($records as $record) {
                        $record_meta = get_post_meta($record->ID, '');
                        if ( ! empty($record_meta)){
                            foreach ($record_meta as $record_meta_key => $record_meta_value) {
                                if ( ! in_array($record_meta_key, $this->data['existing_meta_keys']) and ! in_array($record_meta_key, $hide_fields)) $this->data['existing_meta_keys'][] = $record_meta_key;
                            }
                        }
                    }
                }

                $this->data['existing_meta_keys'] = apply_filters('wp_all_import_existing_meta_keys', $this->data['existing_meta_keys'], $post['custom_type']);

                // Get existing product attributes
                $existing_attributes = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_product_attributes' LIMIT 0 , 50" );
                $this->data['existing_attributes'] = array();
                if ( ! empty($existing_attributes)){
                    foreach ($existing_attributes as $key => $existing_attribute) {
                        $existing_attribute = \pmxi_maybe_unserialize($existing_attribute->meta_value);
                        if (!empty($existing_attribute) and is_array($existing_attribute)):
                            foreach ($existing_attribute as $attr_key => $value) {
                                if (strpos($attr_key, "pa_") === false and ! in_array($attr_key, $this->data['existing_attributes'])) $this->data['existing_attributes'][] = $attr_key;
                            }
                        endif;
                    }
                }
                break;
        }

		$this->render();
	}

	/**
	 * Step #5: Confirm & Run Import
	 */
	public function confirm(){

		$default = PMXI_Plugin::get_default_import_options();

		$this->data['source'] = PMXI_Plugin::$session->source;
        $this->data['locfilePath'] = PMXI_Plugin::$session->filePath;
		$this->data['count'] = PMXI_Plugin::$session->count;
		$this->data['xpath'] = PMXI_Plugin::$session->xpath;
        if (empty($this->data['import'])){
            $this->data['import'] = $this->data['update_previous'];
        }
		$this->data['import_id_val'] = PMXI_Plugin::$session->update_previous;
		$this->data['isWizard'] = true;
		$DefaultOptions = (isset(PMXI_Plugin::$session->options) ? PMXI_Plugin::$session->options : array()) + $default;
		foreach (PMXI_Admin_Addons::get_active_addons() as $class) {
			if (class_exists($class)) $DefaultOptions += call_user_func(array($class, "get_default_import_options"));
		}

		if ($this->isWizard and wp_all_import_is_title_required(PMXI_Plugin::$session->options['custom_type'])){
			if (empty(PMXI_Plugin::$session->options['title']))
				$this->warnings->add('form-validation', __('<strong>Warning:</strong> your title is blank.', 'wp-all-import-pro'));
		}

		$this->data['post'] =& $DefaultOptions;

		if ($this->input->post('is_confirmed')) {

			check_admin_referer('confirm', '_wpnonce_confirm');

			$continue = $this->input->post('is_continue', 'no');

			if ($continue == 'yes'){
				PMXI_Plugin::$session->set('action', 'continue');
				PMXI_Plugin::$session->save_data();
			}

			if ( ! $this->errors->get_error_codes()) { // no validation errors found
				wp_redirect(esc_url_raw(add_query_arg('action', 'process', $this->baseUrl))); die();
			}

		}

		$this->render();
	}

	/**
	 * Import processing step (status console)
	 */
	public function process($save_history = true) {

		$wp_uploads = wp_upload_dir();
		$import = $this->data['update_previous'];
		$history_log = new PMXI_History_Record();
		$input  = new PMXI_Input();

		if ( ! empty(PMXI_Plugin::$session->history_id) ) {
            $history_log->getById(PMXI_Plugin::$session->history_id);
        }

		$log_storage = (int) PMXI_Plugin::getInstance()->getOption('log_storage');

		if ( ! PMXI_Plugin::is_ajax() ) {

			$import->set(
				(empty(PMXI_Plugin::$session->source) ? array() : PMXI_Plugin::$session->source)
				+ array(
					'xpath' => PMXI_Plugin::$session->xpath,
					'options' => ($this->data['update_previous']->isEmpty()) ? PMXI_Plugin::$session->options : $import->options + PMXI_Plugin::$session->options,
					'count' => PMXI_Plugin::$session->count,
					'friendly_name' => wp_all_import_clear_xss(PMXI_Plugin::$session->options['friendly_name']),
					'feed_type' => PMXI_Plugin::$session->feed_type,
					'parent_import_id' => ($this->data['update_previous']->isEmpty()) ? PMXI_Plugin::$session->parent_import_id : $this->data['update_previous']->parent_import_id,
					'queue_chunk_number' => 0,
					'triggered' => 0,
					'processing' => 0,
					'executing' => 1,
					'iteration' => ( ! empty($import->iteration) ) ? $import->iteration : 0
				)
			)->save();

			if ( PMXI_Plugin::$session->action != 'continue' ){
				// store import info in database
				$import->set(array(
					'imported' => 0,
					'created' => 0,
					'updated' => 0,
					'skipped' => 0,
					'deleted' => 0,
					'changed_missing' => 0
				))->update();
			}

			// Add history log.
            $custom_type = wp_all_import_custom_type_labels($import->options['custom_type'], $import->options['taxonomy_type']);

			// Unlink previous logs.
			$by = array();
			$by[] = array(array('import_id' => $import->id), 'AND');
			$historyLogs = new PMXI_History_List();
			$historyLogs->setColumns('id', 'import_id', 'type', 'date')->getBy($by, 'id ASC');
			if ($historyLogs->count() and $historyLogs->count() >= $log_storage ){
				$logsToRemove = $historyLogs->count() - $log_storage;
				foreach ($historyLogs as $i => $file){
					$historyRecord = new PMXI_History_Record();
					$historyRecord->getBy('id', $file['id']);
					if ( ! $historyRecord->isEmpty()) $historyRecord->delete(); // unlink history file only
					if ($i == $logsToRemove)
						break;
				}
			}

            $log_msg = sprintf(__("%d %s created %d updated %d skipped", "wp-all-import-pro"), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->skipped);
            if ($import->options['is_delete_missing']) {
                if (empty($import->options['delete_missing_action']) || $import->options['delete_missing_action'] != 'remove') {
                    $log_msg = sprintf(__("%d %s created %d updated %d changed missing %d skipped", "wp-all-import-pro"), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->changed_missing, $import->skipped);
                } else {
                    $log_msg = sprintf(__("%d %s created %d updated %d deleted %d skipped", "wp-all-import-pro"), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->deleted, $import->skipped);
                }
            }
			$history_log->set(array(
				'import_id' => $import->id,
				'date' => date('Y-m-d H:i:s'),
				'type' => ( PMXI_Plugin::$session->action != 'continue' ) ? 'manual' : 'continue',
				'summary' => $log_msg
			))->save();

			PMXI_Plugin::$session->set('history_id', $history_log->id);

			foreach ( get_taxonomies() as $tax ) {
                delete_transient("pmxi_{$tax}_terms");
            }

			$functions  = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

			PMXI_Plugin::$session->set('update_previous', $import->id);

			if (empty($import->options['encoding'])){
				$currentOptions = $import->options;
				$currentOptions['encoding'] = 'UTF-8';
				$import->set(array(
					'options' => $currentOptions
				))->update();
			}

			// unlink previous files
			$history = new PMXI_File_List();
			$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $import->id), 'id DESC');
			if ($history->count()){
				foreach ($history as $file){
					$history_file_path = wp_all_import_get_absolute_path($file['path']);
					if ( @file_exists($history_file_path) and $history_file_path != PMXI_Plugin::$session->filePath ){
						if (in_array($import->type, array('upload')))
							wp_all_import_remove_source($history_file_path, false);
						else
							wp_all_import_remove_source($history_file_path);
					}
					$history_file = new PMXI_File_Record();
					$history_file->getBy('id', $file['id']);
					if ( ! $history_file->isEmpty()) $history_file->delete( $history_file_path != PMXI_Plugin::$session->filePath );
				}
			}

			if ($save_history){
				$history_file = new PMXI_File_Record();
				$history_file->set(array(
					'name' => $import->name,
					'import_id' => $import->id,
					'path' => wp_all_import_get_relative_path(PMXI_Plugin::$session->filePath),
					'registered_on' => date('Y-m-d H:i:s')
				))->save();
			}

            do_action( 'pmxi_before_xml_import', $import->id );

			/*
				Split file up into 1000 record chunks.
				This option will decrease the amount of slowdown experienced at the end of large imports.
				The slowdown is partially caused by the need for WP All Import to read deeper and deeper into the file on each successive iteration.
				Splitting the file into pieces means that, for example, instead of having to read 19000 records into a 20000 record file when importing the last 1000 records,
				WP All Import will just split it into 20 chunks, and then read the last chunk from the beginning.
			*/
			if ( $import->count > PMXI_Plugin::getInstance()->getOption('large_feed_limit') and $import->options['chuncking'] ){

				$chunk_files = array();

				if ( ! empty(PMXI_Plugin::$session->local_paths)) {

					$records_count = 0;
					$chunk_records_count = 0;

					$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";

					foreach (PMXI_Plugin::$session->local_paths as $key => $path) {

						$file = new PMXI_Chunk($path, array('element' => $import->root_element, 'encoding' => $import->options['encoding']));
					    // Loop through the file until all lines are read.
					    while ($xml = $file->read()) {

					    	if ( ! empty($xml) )  {
					      		//PMXI_Import_Record::preprocessXml($xml);
					      		$chunk = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . $xml;

						      	$dom = new DOMDocument('1.0', $import->options['encoding']);
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($chunk); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);

								if ($elements = @$xpath->query($import->xpath) and $elements->length){
									$records_count += $elements->length;
									$chunk_records_count += $elements->length;
									$feed .= $xml;
								}
							}

							if ( $chunk_records_count == PMXI_Plugin::getInstance()->getOption('large_feed_limit') or $records_count == $import->count ){
								$feed .= "</pmxi_records>";
								$chunk_file_path = wp_all_import_secure_file($wp_uploads['basedir'] . DIRECTORY_SEPARATOR . PMXI_Plugin::TEMP_DIRECTORY, $import->id) . DIRECTORY_SEPARATOR . "pmxi_chunk_" . count($chunk_files) . "_" . basename($path);
								file_put_contents($chunk_file_path, $feed);
								$chunk_files[] = $chunk_file_path;
								$chunk_records_count = 0;
								$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";
							}
						}
					}
					PMXI_Plugin::$session->set('local_paths', $chunk_files);
				}
			}

			PMXI_Plugin::$session->save_data();

			if ( $log_storage ){
				$log_file = wp_all_import_secure_file( $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . PMXI_Plugin::LOGS_DIRECTORY, $history_log->id ) . DIRECTORY_SEPARATOR . $history_log->id . '.html';
				if ( PMXI_Plugin::$session->action != 'continue'){
					if (file_exists($log_file)) {
                        wp_all_import_remove_source($log_file, false);
                    }
				}
			}

			$this->data['ajax_processing'] = true;

			$this->render();
			wp_ob_end_flush_all(); flush();
			@set_time_limit(0);

			$import_id = $input->get('id', 0);

			if ( ! $import_id ) {
				PMXI_Plugin::$session->convertData($import->id);
			}
		}
		elseif (empty($import->id)) {
			$import = new PMXI_Import_Record();
			$import_id = $input->get('id', PMXI_Plugin::$session->update_previous);
			$import->getById($import_id);
		}

		$ajax_processing = true;

		if ( PMXI_Plugin::is_ajax() and $ajax_processing and ! check_ajax_referer( 'wp_all_import_secure', 'security', false )){
			exit( __('Security check', 'wp-all-import-pro') );
		}

		if ($ajax_processing) {
			$logger = function($m) {echo "<div class='progress-msg'>[". date("H:i:s") ."] ".wp_all_import_filter_html_kses($m)."</div>\n";flush();};
		} else {
            $logger = function($m) {echo "<div class='progress-msg'>".wp_all_import_filter_html_kses($m)."</div>\n"; if ( "" != strip_tags(wp_all_import_strip_tags_content(wp_all_import_filter_html_kses($m)))) { PMXI_Plugin::$session->log .= "<p>".strip_tags(wp_all_import_strip_tags_content(wp_all_import_filter_html_kses($m)))."</p>"; flush(); }};
		}

		$logger = apply_filters('wp_all_import_logger', $logger);

		PMXI_Plugin::$session->set('start_time', (empty(PMXI_Plugin::$session->start_time)) ? time() : PMXI_Plugin::$session->start_time);

        $is_reset_cache = apply_filters('wp_all_import_reset_cache_before_import', false, $import->id);

		if ($is_reset_cache) {
            wp_cache_flush();
        }

		wp_defer_term_counting(true);
		wp_defer_comment_counting(true);

		if ( PMXI_Plugin::is_ajax() or ! $ajax_processing ) {

			$functions  = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . WP_ALL_IMPORT_UPLOADS_BASE_DIRECTORY . DIRECTORY_SEPARATOR . 'functions.php';
			$functions = apply_filters( 'import_functions_file_path', $functions );
			if ( @file_exists($functions) && PMXI_Plugin::$is_php_allowed)
				\Wpai\Integrations\CodeBox::requireFunctionsFile();

			$iteration_start_time = time();

			if ( $log_storage ) {
                $log_file = wp_all_import_secure_file( $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . PMXI_Plugin::LOGS_DIRECTORY, $history_log->id ) . DIRECTORY_SEPARATOR . $history_log->id . '.html';
            }

			if ( $ajax_processing ) {
				// HTTP headers for no cache etc
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
			}

			$loop = 0;
			$pointer = 0;
			$records = array();

			if ($import->options['is_import_specified']) {
                $import_specified_option = apply_filters('wp_all_import_specified_records', $import->options['import_specified'], $import->id, false);
				foreach (preg_split('% *, *%', $import_specified_option, -1, PREG_SPLIT_NO_EMPTY) as $chank) {
					if (preg_match('%^(\d+)-(\d+)$%', $chank, $mtch)) {
						$records = array_merge($records, range(intval($mtch[1]), intval($mtch[2])));
					} else {
						$records = array_merge($records, array(intval($chank)));
					}
				}
			}

			$records_to_import = (empty($records) || $import->options['is_delete_missing']) ? $import->count : $records[count($records) -1];

			$failures = $input->get('failures', 0);

			// Auto decrease records per iteration option.
            if ($failures) {
                $options = $import->options;
                $options['records_per_request'] = (ceil($options['records_per_request']/2)) ? ceil($options['records_per_request']/2) : 1;
                $import->set(array('options' => $options))->update();
            }

            $records_per_request = ( ! $ajax_processing and $import->options['records_per_request'] < 50 ) ? 50 : $import->options['records_per_request'];

			if (!empty(PMXI_Plugin::$session->local_paths)) {

                if (!empty($records) && $import->queue_chunk_number < $records[0] && strpos($import->xpath, "[") === false && ! $import->options['is_delete_missing']) {
                    $pointer = $records[0];
                }
                $chunk_records_count = PMXI_Plugin::getInstance()->getOption('large_feed_limit');
				$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";

				foreach (PMXI_Plugin::$session->local_paths as $key => $path) {
					$import_done = ($import->imported + $import->skipped == $records_to_import ) ? true : false;
			    	if ($import_done) {
                        foreach (PMXI_Plugin::$session->local_paths as $chunk_file) {
                            if (strpos($chunk_file, "pmxi_chunk_") !== false and @file_exists($chunk_file)) wp_all_import_remove_source($chunk_file, false);
                        }
                        PMXI_Plugin::$session->set('local_paths', array());
				    	PMXI_Plugin::$session->save_data();
				    	break;
				    }
				    // Set XMLReader pointer to first value of specified records option.
                    if ( ! empty($records) && $import->queue_chunk_number < $records[0] && strpos($import->xpath, "[") === false && ! $import->options['is_delete_missing']) {
                        if ($import->options['chuncking'] && $pointer > $chunk_records_count) {
                            $pointer -= $chunk_records_count;
                            if (strpos($path, "pmxi_chunk_") !== false and @file_exists($path)) {
                                @unlink($path);
                            }
                            PMXI_Plugin::$session->set('chunk_number', $import->queue_chunk_number + $chunk_records_count);
                            $lp = PMXI_Plugin::$session->local_paths;
                            array_shift($lp);
                            PMXI_Plugin::$session->set('local_paths', $lp);
                            PMXI_Plugin::$session->save_data();

                            $import->set(array(
                                'skipped' => $import->skipped + $chunk_records_count,
                                'queue_chunk_number' => $import->queue_chunk_number + $chunk_records_count
                            ))->save();
                            continue;
                        }
                        PMXI_Plugin::$session->set('chunk_number', $import->queue_chunk_number + $pointer);
                        PMXI_Plugin::$session->set('pointer', $pointer);
                        PMXI_Plugin::$session->save_data();
                        $import->set(array(
                            'skipped' => $import->skipped + $pointer - 1,
                            'queue_chunk_number' => $import->queue_chunk_number + $pointer
                        ))->save();
                        $pointer = 0;
                    }
					$file = new PMXI_Chunk($path, array(
						'element' => $import->root_element,
						'encoding' => $import->options['encoding'],
						'pointer' => PMXI_Plugin::$session->pointer,
						'filter' => true
					));
				    // Loop through the file until all lines are read.
				    while ($xml = $file->read() and empty($import->canceled) ) {
				    	if (!empty($xml)) {
				      		$chunk = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . $xml;
					      	$dom = new DOMDocument('1.0', $import->options['encoding']);
							$old = libxml_use_internal_errors(true);
							$dom->loadXML($chunk); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
							libxml_use_internal_errors($old);
							$xpath = new DOMXPath($dom);
							$pointer++;
							if (($this->data['elements'] = $elements = @$xpath->query($import->xpath)) and $elements->length) {
								// Continue action.
								if ( $import->imported + $import->skipped >= PMXI_Plugin::$session->chunk_number + $elements->length - 1 ){
									PMXI_Plugin::$session->set('chunk_number', PMXI_Plugin::$session->chunk_number + $elements->length);
									PMXI_Plugin::$session->save_data();
									continue;
								}
                                if ( ! $loop and $ajax_processing ) {
                                    ob_start();
                                }
                                $feed .= $xml; $loop += $elements->length;
								$processed_records = $import->imported + $import->skipped;
								if ( $loop == $records_per_request or $processed_records + $loop == $records_to_import or $processed_records == $records_to_import) {
									$feed .= "</pmxi_records>";
									$import->process($feed, $logger, PMXI_Plugin::$session->chunk_number, false, '/pmxi_records', $loop);
									unset($dom, $xpath);
									if ( ! $ajax_processing ){
										$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";
										$loop = 0;
									} else {
										if ( ! $history_log->isEmpty()) {
                                            $custom_type = wp_all_import_custom_type_labels($import->options['custom_type'], $import->options['taxonomy_type']);
                                            $log_msg = sprintf(__("%d %s created %d updated %d skipped", "wp-all-import-pro"), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->skipped);
                                            if ($import->options['is_delete_missing']) {
                                                if (empty($import->options['delete_missing_action']) || $import->options['delete_missing_action'] != 'remove') {
                                                    $log_msg = sprintf(__("%d %s created %d updated %d changed missing %d skipped", "wp-all-import-pro"), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->changed_missing, $import->skipped);
                                                } else {
                                                    $log_msg = sprintf(__("%d %s created %d updated %d deleted %d skipped", "wp-all-import-pro"), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->deleted, $import->skipped);
                                                }
                                            }

											$history_log->set(array(
												'time_run' => time() - strtotime($history_log->date),
												'summary' => $log_msg
											))->update();
										}
										unset($file);
										PMXI_Plugin::$session->set('pointer', PMXI_Plugin::$session->pointer + $pointer);
										PMXI_Plugin::$session->save_data();

										$log_data = ob_get_clean();
										if ($log_storage) {
											$log = @fopen($log_file, 'a+');
											if ( is_resource( $log ) ) {
												@fwrite($log, $log_data);
												@fclose($log);
											}
										}
										$iteration_execution_time = time() - $iteration_start_time;
										wp_send_json(array(
											'imported' => $import->imported,
											'created' => $import->created,
											'updated' => $import->updated,
											'skipped' => $import->skipped,
											'skipped_by_hash' => PMXI_Plugin::$session->skipped,
                                            'deleted' => $import->deleted,
                                            'changed_missing' => $import->changed_missing,
                                            'percentage' => ceil(($processed_records/$import->count) * 100),
											'warnings' => PMXI_Plugin::$session->warnings,
											'errors' => PMXI_Plugin::$session->errors,
											'log' => $log_data,
											'done' => false,
											'records_per_request' => $records_per_request,
											'iteration_execution_time' => $iteration_execution_time
										));
									}
								}
							}
					    }
					}
					// Move to the next file, set pointer to first element.
					if ( $ajax_processing ) {
						if (strpos($path, "pmxi_chunk_") !== false and @file_exists($path)) {
                            @unlink($path);
                        }
						PMXI_Plugin::$session->set('pointer', 1);
						$pointer = 0;
						$lp = PMXI_Plugin::$session->local_paths;
				    	array_shift($lp);
				    	PMXI_Plugin::$session->set('local_paths', $lp);
				    	PMXI_Plugin::$session->save_data();
				    }
				    else break;
				}
			}
		}

		// Delete missing records.
		if ( ( PMXI_Plugin::is_ajax() and empty(PMXI_Plugin::$session->local_paths) ) or ! $ajax_processing ) {
			ob_start();
			$is_all_records_deleted = $import->delete_missing_records($logger, $import->iteration);
			$log_data = ob_get_clean();
			if ($log_storage) {
				$log = @fopen($log_file, 'a+');
				if ( is_resource( $log ) ) {
					@fwrite($log, $log_data);
					@fclose($log);
				}
			}
			$iteration_execution_time = time() - $iteration_start_time;
			if ( $ajax_processing and ! $is_all_records_deleted ) {
				wp_send_json(array(
					'imported' => $import->imported,
					'created' => $import->created,
					'updated' => $import->updated,
					'skipped' => $import->skipped,
                    'skipped_by_hash' => PMXI_Plugin::$session->skipped,
					'deleted' => $import->deleted,
					'changed_missing' => $import->changed_missing,
					'percentage' => 99,
					'warnings' => PMXI_Plugin::$session->warnings,
					'errors' => PMXI_Plugin::$session->errors,
					'log' => $log_data,
					'done' => false,
					'records_per_request' => $records_per_request,
					'iteration_execution_time' => $iteration_execution_time
				));
			}
		}

		if ( ( PMXI_Plugin::is_ajax() and empty(PMXI_Plugin::$session->local_paths) ) or ! $ajax_processing or ! empty($import->canceled) ) {
			$import->set(array(
				'processing' => 0, // unlock cron requests
				'triggered' => 0,
				'queue_chunk_number' => 0,
				'registered_on' => date('Y-m-d H:i:s'),
				'iteration' => ++$import->iteration
			))->update();

			foreach ( get_taxonomies() as $tax ) {
				delete_option( "{$tax}_children" );
				_get_term_hierarchy( $tax );
			}

			$import->set(array(
				'registered_on' => date('Y-m-d H:i:s'),
				'executing' => 0
			))->update();

			wp_defer_term_counting(false);
			wp_defer_comment_counting(false);

			// add history log
            $custom_type = wp_all_import_custom_type_labels($import->options['custom_type'], $import->options['taxonomy_type']);
            $log_msg = sprintf(__("%d %s created %d updated %d skipped", 'wp-all-import-pro'), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->skipped);
            if ($import->options['is_delete_missing']) {
                if (empty($import->options['delete_missing_action']) || $import->options['delete_missing_action'] != 'remove') {
                    $log_msg = sprintf(__("%d %s created %d updated %d changed missing %d skipped", 'wp-all-import-pro'), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->changed_missing, $import->skipped);
                } else {
                    $log_msg = sprintf(__("%d %s created %d updated %d deleted %d skipped", 'wp-all-import-pro'), $import->created, ( ($import->created == 1) ? $custom_type->labels->singular_name : $custom_type->labels->name ), $import->updated, $import->deleted, $import->skipped);
                }
            }

			$history_log->set(array(
				'time_run' => time() - strtotime($history_log->date),
				'summary' => $log_msg
			))->update();

			// clear import session
			PMXI_Plugin::$session->clean_session($import->id); // clear session data (prevent from reimporting the same data on page refresh)

			// [indicate in header process is complete]
			$msg = ( ! empty($import->canceled) ) ? addcslashes(__('Canceled', 'wp-all-import-pro'), "\n\r") : addcslashes(__('Complete', 'wp-all-import-pro'), "\n\r");

			if ( $ajax_processing ) ob_start();

			do_action( 'pmxi_after_xml_import', $import->id, $import );

			$import->delete_source( $logger );
			$import->options['is_import_specified'] and $logger and call_user_func($logger, 'Done');

echo <<<COMPLETE
<script type="text/javascript">
//<![CDATA[
(function($){
	$('#status').html('$msg');
	window.onbeforeunload = false;
})(jQuery);
//]]>
</script>
COMPLETE;
// [/indicate in header process is complete]

			if ( $ajax_processing ) {

				wp_send_json(array(
					'imported' => $import->imported,
					'created' => $import->created,
					'updated' => $import->updated,
					'skipped' => $import->skipped,
                    'skipped_by_hash' => PMXI_Plugin::$session->skipped,
                    'deleted' => $import->deleted,
                    'changed_missing' => $import->changed_missing,
                    'percentage' => 100,
					'warnings' => PMXI_Plugin::$session->warnings,
					'errors' => PMXI_Plugin::$session->errors,
					'log' => ob_get_clean(),
					'done' => true,
					'records_per_request' => $import->options['records_per_request']
				));

			}
		}
	}

	protected $_unique_key = array();
	protected function find_unique_key($el){
		if ($el->hasChildNodes()) {
			if ($el->childNodes->length) {
				foreach ($el->childNodes as $child) {
					if ($child instanceof DOMElement) {
						if (!in_array($child->nodeName, $this->_unique_key)) $this->_unique_key[] = $child->nodeName;
						$this->find_unique_key($child);
					}
				}
			}
		}
	}

	protected function get_xml( $tagno = 0, $debug = false ){
		$xml = '';
		$update_previous = new PMXI_Import_Record();

        if ($this->input->get('id')) {
            $update_previous->getById($this->input->get('id'));
        }

		if ( ! empty(PMXI_Plugin::$session->update_previous) ) $update_previous->getById(PMXI_Plugin::$session->update_previous);

		$local_paths = (empty(PMXI_Plugin::$session->local_paths)) ? array() : PMXI_Plugin::$session->local_paths;

		if ( empty($local_paths) and ! $update_previous->isEmpty() ){
			$history_file = new PMXI_File_Record();
			$history_file->getBy( array('import_id' => $update_previous->id), 'id DESC' );
			$local_paths = ( ! $history_file->isEmpty() ) ? array(wp_all_import_get_absolute_path($history_file->path)) : array();
		}

		if ( ! empty($local_paths)) {

			$loop = 0;

			foreach ( $local_paths as $key => $path ) {

				if ( @file_exists($path) ){

					$root_element = ( ! $update_previous->isEmpty() ) ? $update_previous->root_element : PMXI_Plugin::$session->source['root_element'];

                    $import_xpath = ( ! $update_previous->isEmpty() ) ? $update_previous->xpath : PMXI_Plugin::$session->xpath;

                    $encoding = PMXI_Plugin::$session->encoding ?? 'UTF-8';

					$file = new PMXI_Chunk($path, array('element' => $root_element, 'encoding' => $encoding) );

				    while ($xml = $file->read()) {

				    	if ( ! empty($xml) ) {

				      		//PMXI_Import_Record::preprocessXml($xml);
				      		$xml = "<?xml version=\"1.0\" encoding=\"". $encoding ."\"?>" . "\n" . $xml;

					      	if ( $import_xpath ){
						      	$dom = new DOMDocument( '1.0', $encoding );
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($xml);
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);
								if (($elements = $xpath->query($import_xpath)) and $elements->length){
									$this->data['dom'] = $dom;
									$loop++;
									if ( ! $tagno or $loop == $tagno ) break;
								}
							}
							else break;
					    }
					}
					unset($file);
				}
			}
		}
		return $xml;
	}

    /**
     * @return string
     */
    private function findUniqueKey()
    {
        $uniqueKey = '';

        if (empty(PMXI_Plugin::$session->options['unique_key'])) {

            $keys_black_list = array('programurl');

			if ( empty(PMXI_Plugin::$session->deligate) ) {
				if (PMXI_Plugin::$session->options['custom_type'] == 'import_users') {
					$uniqueKey = PMXI_Plugin::$session->options['pmui']['login'];
				} elseif (PMXI_Plugin::$session->options['custom_type'] == 'shop_customer') {
					$uniqueKey = PMXI_Plugin::$session->options['pmsci_customer']['login'];
				} else {
					$uniqueKey = PMXI_Plugin::$session->options['title'];
				}
			}

            // auto searching ID element
            if (!empty($this->data['dom']) and empty(PMXI_Plugin::$session->deligate)) {
                $dom = empty($this->data['dom']->documentElement) ? $this->data['dom'] : $this->data['dom']->documentElement;
                $this->find_unique_key($dom);
                if (!empty($this->_unique_key)) {
                    foreach ($keys_black_list as $key => $value) {
                        $uniqueKey = str_replace('{' . $value . '[1]}', "", $uniqueKey);
                    }
                    foreach ($this->_unique_key as $key) {
                        if (stripos($key, 'id') !== false) {
                            $uniqueKey .= ' - {' . $key . '[1]}';
                            break;
                        }
                    }
                    foreach ($this->_unique_key as $key) {
                        if (stripos($key, 'url') !== false or stripos($key, 'sku') !== false or stripos($key, 'ref') !== false) {
                            if (!in_array($key, $keys_black_list)) {
                                $uniqueKey .= ' - {' . $key . '[1]}';
                                break;
                            }
                        }
                    }
                }
                $uniqueKey = apply_filters('pmxi_unique_key', $uniqueKey, PMXI_Plugin::$session->options);
            }
        } else {
            $uniqueKey = PMXI_Plugin::$session->options['unique_key'];
        }

        return $uniqueKey;
    }
}
