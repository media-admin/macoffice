<?php
/**
 * Basic email settings class
 *
 * @since             1.9.2
 * @package           TInvWishlist\Public
 * @subpackage          Email
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
if ( class_exists( 'WC_Settings_API' ) ) {
	/**
	 * Basic email class
	 */
	class TInvWL_Public_Settings extends WC_Settings_API {

		/**
		 * The plugin name.
		 *
		 * @var string
		 */
		public $_name;

		/**
		 * The plugin version.
		 *
		 * @var string
		 */
		public $_version;

		/**
		 * The instance of the settings class.
		 *
		 * @var mixed
		 */
		protected $settings_class;

		/**
		 * The email title.
		 *
		 * @var string
		 */
		public $title;

		/**
		 * The email description.
		 *
		 * @var string
		 */
		public $description;

		/**
		 * The email heading.
		 *
		 * @var string
		 */
		public $heading;

		/**
		 * The email subject.
		 *
		 * @var string
		 */
		public $subject;

		/**
		 * The template name used for the email.
		 *
		 * @var string
		 */
		public $template_name;

		/**
		 * This class
		 *
		 * @var \TInvWL_Public_TInvWL
		 */
		protected static $_instance = null;

		/**
		 * Get this class object
		 *
		 * @param $class
		 * @param string $version Plugin version.
		 *
		 * @param string $plugin_name Plugin name.
		 *
		 * @return \TInvWL_Public_TInvWL
		 */
		public static function instance( $class, $version = TINVWL_VERSION, $plugin_name = TINVWL_PREFIX ) {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self( $class, $version, $plugin_name );
			}

			return self::$_instance;
		}

		/**
		 * Constructor
		 *
		 * @param $class
		 * @param string $version Plugin version.
		 * @param string $plugin_name Plugin name.
		 */
		function __construct( $class, $version, $plugin_name ) {
			$this->_name          = $plugin_name;
			$this->_version       = $version;
			$this->settings_class = new $class;

			$this->id          = $this->settings_class->id;
			$this->title       = $this->settings_class->title;
			$this->description = $this->settings_class->description;

			$this->heading = $this->settings_class->heading;
			$this->subject = $this->settings_class->subject;

			$this->template_name = $this->settings_class->template_name;
			$this->form_fields   = $this->settings_class->form_fields;
		}
	}
}
