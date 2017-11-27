<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Carousel_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/carousel-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/carousel-admin.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, false );

	}

	/**
	 * Register the admin page.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {

		add_menu_page( 'Caorusel', 'Caorusel', '', 'carousel', array( $this, 'create_carousel' ), 'dashicons-slides' );

		add_submenu_page( 'carousel', '슬라이더 목록', '슬라이더 목록', 'manage_options', 'carousels', array( $this, 'admin_page_router' ) );
		add_submenu_page( 'carousel', '슬라이더 추가', '슬라이더 추가', 'manage_options', 'create_carousel', array( $this, 'admin_page_router' ) );
		add_submenu_page( '', '슬라이더 수정', '슬라이더 수정', 'manage_options', 'update_carousel', array( $this, 'admin_page_router' ) );

	}

	/**
	 * Admin page router
	 *
	 * @since 1.0.0
	 */
	public function admin_page_router() {

		$page = $_GET[ 'page' ];

		if ( method_exists( $this, 'page_' . $page ) ) {
			$this->{'page_' . $page}( str_replace( '_', '-', $page ) );
		}

	}

	/**
	 * Back-End of the Carousels page
	 */
	private function page_carousels( $page ) {

		require_once CAROUSEL_ADMIN_PATH . 'class-carousels-list-table.php';

		global $carousels_list;
		$carousels_list = new Carousels_List_Table();

		include_once CAROUSEL_ADMIN_PATH . 'pages/' . $page . '.php';

	}

	/**
	 * Back-End of the Create carousel page
	 */
	private function page_create_carousel( $page ) {

		wp_enqueue_media();

		include_once CAROUSEL_ADMIN_PATH . 'pages/' . $page . '.php';

	}

	/**
	 * Back-end of the Update carousel page
	 */
	private function page_update_carousel( $page ) {

		global $wpdb;

		wp_enqueue_media();

		$id = $_GET[ 'id' ];
		$slider_name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}carousels WHERE ID = {$id}" );
		$slides = $wpdb->get_results( "SELECT ID, image_url, title, link, slide_order FROM {$wpdb->prefix}carousel_slides WHERE carousel_id = {$id} ORDER BY slide_order ASC" );

		include_once CAROUSEL_ADMIN_PATH . 'pages/' . $page . '.php';

	}

	/**
	 * Create carousel slider
	 */
	public function create_slider() {

		if ( empty( $_POST[ 'nonce' ] ) || ! wp_verify_nonce( $_POST[ 'nonce' ], 'create-carousel' ) ) return;

		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix.'carousels',
			array(
				'name' => $_POST[ 'name' ],
				'created' => current_time( 'mysql' )
			)
		);

		if ( empty( $wpdb->insert_id ) ) {
			die( wp_send_json( array( 'error' => '저장 중 문제가 발생했습니다.\n다시 시도해주세요.' ) ) );
		}

		$carousel_id = $wpdb->insert_id;
		$slides = json_decode( stripcslashes( $_POST[ 'slides' ] ) );

		foreach ( $slides as $slide ) {
			$wpdb->insert(
				$wpdb->prefix.'carousel_slides',
				array(
					'carousel_id' => $carousel_id,
					'image_url' => $slide->image_url,
					'title' => $slide->title,
					'link' => $slide->link,
					'slide_order' => $slide->slide_order
				)
			);
		}

		die( wp_send_json( array( 'id' => $carousel_id ) ) );

	}

	/**
	 * Update carousel slider
	 */
	public function update_slider() {

		if ( empty( $_POST[ 'nonce' ] ) || ! wp_verify_nonce( $_POST[ 'nonce' ], 'update-carousel' ) ) return;

		global $wpdb;

		$update = $wpdb->update(
			$wpdb->prefix.'carousels',
			array(
				'name' => $_POST[ 'name' ],
				'modified' => current_time( 'mysql' )
			),
			array(
				'ID' => $_POST[ 'id' ]
			)
		);

		if ( ! $update ) {
			die( wp_send_json( array( 'error' => '저장 중 문제가 발생했습니다.\n다시 시도해주세요.' ) ) );
		}

		$slides = json_decode( stripcslashes( $_POST[ 'slides' ] ) );

		foreach ( $slides as $slide ) {
			if ( empty( $slide->slide_id ) ) {
				$wpdb->insert(
					$wpdb->prefix.'carousel_slides',
					array(
						'carousel_id' => $_POST[ 'id' ],
						'image_url' => $slide->image_url,
						'title' => $slide->title,
						'link' => $slide->link,
						'slide_order' => $slide->slide_order
					)
				);
			}
			else {
				$wpdb->update(
					$wpdb->prefix.'carousel_slides',
					array(
						'image_url' => $slide->image_url,
						'title' => $slide->title,
						'link' => $slide->link,
						'slide_order' => $slide->slide_order
					),
					array(
						'ID' => $slide->slide_id
					)
				);
			}
		}

		$deleted_slides = json_decode( stripcslashes( $_POST[ 'deleted_slides' ] ) );

		if ( $deleted_slides ) {
			foreach ( $deleted_slides as $deleted_slide ) {
				$wpdb->delete(
					$wpdb->prefix.'carousel_slides',
					array(
						'ID' => $deleted_slide
					)
				);
			}
		}

		die( wp_send_json( array( 'id' => $_POST[ 'id' ] ) ) );

	}

}
