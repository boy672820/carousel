<div class="wrap">

	<h1 class="wp-heading-inline">슬라이더 목록</h1>

	<a href="<?php echo add_query_arg( array( 'page' => 'create_carousel' ), site_url( 'wp-admin/admin.php' ) ); ?>" class="page-title-action">새 슬라이더 추가</a>

	<hr class="wp-header-end">

	<form method="post">

		<?php
			global $carousels_list;
			$carousels_list->prepare_items();
			$carousels_list->display();
		?>

	</form>

</div><!-- //.wrap -->
