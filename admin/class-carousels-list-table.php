<?php

class Carousels_List_Table extends WP_List_Table {

	const CAROUSELS = 'carousels';
	const CAROUSEL_SLIDES = 'carousel_slides';

	public function __construct() {
		parent::__construct( array(
			'singular'  => 'carousel',
			'plural'    => 'carousels',
			'ajax'      => false
		) );
	}

	public static function get_carousels( $per_page = 10, $page_number = 1 ) {
		global $wpdb;

		$sql = "SELECT
					ID,
					name,
					created,
					modified
				FROM " . $wpdb->prefix . self::CAROUSELS . "
				LIMIT {$per_page}
				OFFSET " . ( $page_number - 1 ) * $per_page;

		return $wpdb->get_results( $sql, 'ARRAY_A' );
	}

	public static function delete_carousel( $id ) {
		global $wpdb;

		$delete = $wpdb->delete(
			$wpdb->prefix . self::CAROUSELS,
			array(
				'ID' => $id
			)
		);

		if ( $delete ) {
			$wpdb->delete(
				$wpdb->prefix . self::CAROUSEL_SLIDES,
				array( 'carousel_id' => $id )
			);
		}
	}

	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT( ID )
				FROM " . $wpdb->prefix . self::CAROUSELS;

		return $wpdb->get_var( $sql );
	}

	public function no_items() {
		echo '등록된 슬라이더가 없습니다.';
	}

	public function column_title( $item ) {
		$nonce = wp_create_nonce( 'carousel-delete' );
		$title = '<a href="' . add_query_arg( array( 'page' => 'update_carousel', 'id' => $item[ 'ID' ] ), site_url( 'wp-admin/admin.php' ) ) . '"><strong>' . $item[ 'name' ] . '</strong></a>';
		$actions = array(
			'delete' => sprintf(
				'<a href="?page=%s&action=%s&id=%s&nonce=%s">삭제</a>',
				self::CAROUSELS,
				'delete',
				$item[ 'ID' ],
				$nonce
			)
		);
		return $title . $this->row_actions( $actions );
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%s[]" value="%s" />',
			$this->_args[ 'singular' ],
			$item[ 'ID' ]
		);
	}

	public function get_columns() {
		return array(
			'cb' => '<input type="checkbox" />',
			'title' => '이름',
			'shortcode' => '숏코드',
			'created' => '생성일',
			'modified' => '수정일'
		);
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'shortcode':
				return '[carousel id=' . $item[ 'ID' ] . ']';
			break;

			default:
				return $item[ $column_name ];
			break;
		}
	}

	public function get_sortable_columns() {
		return array(
			'name' => array( 'name', false )
		);
	}

	public function get_bulk_actions() {
		return array(
			'bulk_delete' => '삭제'
		);
	}

	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$per_page = $this->get_items_per_page( 'carousels_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page' => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );

		$this->items = self::get_carousels( $per_page, $current_page );
	}

	public function process_bulk_action() {
		switch ( $this->current_action() ) {
			case 'delete':
				if ( ! wp_verify_nonce( esc_attr( $_GET[ 'nonce' ] ), 'carousel-delete' ) ) die( '잘못된 접근입니다.' );

				self::delete_carousel( $_GET[ 'id' ] );

				?>
				<script>
					location.replace( '<?php echo add_query_arg( array( 'page' => self::CAROUSELS ), site_url( 'wp-admin/admin.php' ) ) ?>' );
				</script>
				<?php
				exit;
			break;

			case 'bulk_delete':
				$ids = esc_sql( $_POST[ $this->_args[ 'singular' ] ] );
				foreach ( $ids as $id ) {
					self::delete_carousel( $id );
				}

				?>
				<script>
					location.replace( '<?php echo add_query_arg( array( 'page' => self::CAROUSELS ), site_url( 'wp-admin/admin.php' ) ) ?>' );
				</script>
				<?php
				exit;
			break;
		}
	}
}
