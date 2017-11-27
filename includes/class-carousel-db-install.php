<?php

/**
 * 플러그인에 필요한 데이터베이스 설치
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Carousel
 * @subpackage carousel/includes
 * @author     Seonzoo <boy672820@gmail.com>
 */
class Carousel_DB_Install {

	const DB_VERSION = '1.1 beta';

	const CAROUSEL_SLIDES = 'carousel_slides';
	const CAROUSELS = 'carousels';

	private $charset_collate;
	private $prefix;


	/**
	 * 데이터베이스 설치 초기화
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( $this->check_db_version() ) {
			global $wpdb;

			$this->charset_collate = $wpdb->get_charset_collate();
			$this->prefix = $wpdb->prefix;

			$this->create_tables();
		}
	}

	/**
	 * 데이터베이스 버전 확인
	 * 이전 버전일 경우, 사이트 플러그인 버전 업데이트
	 *
	 * @since    1.0.0
	 */
	private function check_db_version() {
		$carousel_db_version = get_option( 'carousel_db_version' );

		if ( Carousel_DB_Install::DB_VERSION != $carousel_db_version ) {
			update_option( 'carousel_db_version', Carousel_DB_Install::DB_VERSION );
			return true;
		}

		return false;
	}

	/**
	 * 플러그인 테이블 생성
	 *
	 * @since    1.0.0
	 */
	private function create_tables() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( "
		CREATE TABLE {$this->prefix}" . Carousel_DB_Install::CAROUSEL_SLIDES . " (
			ID int NOT NULL AUTO_INCREMENT,
			carousel_id int NOT NULL,
			image_url text NOT NULL,
			title varchar(100) NOT NULL,
			link text,
			slide_order int DEFAULT 0 NOT NULL,
			PRIMARY KEY (ID)
		) {$this->charset_collate}
		" );

		dbDelta( "
		CREATE TABLE {$this->prefix}" . Carousel_DB_Install::CAROUSELS . " (
			ID int NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL,
			created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (ID)
		) {$this->charset_collate}
		" );
	}

}
