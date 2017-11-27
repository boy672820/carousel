<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Carousel
 * @subpackage carousel/includes
 * @author     Seonzoo <boy672820@gmail.com>
 */
class Carousel_Activator {

	/**
	 * 플러그인 활성화
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once CAROUSEL_INCLUDES_PATH . 'class-carousel-db-install.php';
		new Carousel_DB_Install();
	}

}
