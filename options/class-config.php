<?php
/**
 * Config Class
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 2.5.6
 */

namespace MoreConvert\McCompare\MCTOptions;

/**
 * Config class.
 */
class Config {
	/**
	 * Singleton instance
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Framework configuration
	 *
	 * @var array
	 */
	private $config = array();

	/**
	 * Private constructor to prevent direct creation
	 */
	private function __construct() {}

	/**
	 * Get singleton instance
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Set framework configuration
	 *
	 * @param array $config configs.
	 */
	public function set_config( $config ) {
		$this->config = $config;
	}

	/**
	 * Get framework configuration
	 *
	 * @return array
	 */
	public function get_config() {
		return $this->config;
	}

	/**
	 * Get specific configuration value
	 *
	 * @param string $key key.
	 * @param mixed  $default_value default value.
	 * @return mixed
	 */
	public function get( $key, $default_value = null ) {
		return isset( $this->config[ $key ] ) ? $this->config[ $key ] : $default_value;
	}


	/**
	 * Set specific configuration value
	 *
	 * @param string $key key.
	 * @param mixed  $value value.
	 */
	public function set( $key, $value ) {
		$this->config[ $key ] = $value;
	}

	/**
	 * Get template path
	 *
	 * @return string
	 */
	public function get_template_path() {
		return $this->get( 'template_path', '' );
	}

	/**
	 * Get assets URL
	 *
	 * @return string
	 */
	public function get_assets_url() {
		return $this->get( 'assets_url', '' );
	}

	/**
	 * Get plugin ID
	 *
	 * @return string
	 */
	public function get_plugin_id() {
		return $this->get( 'plugin_id', '' );
	}
}
