<div class="wrap">

	<h1>슬라이더 추가</h1>

	<form action="" class="carousel form slider">

		<input type="hidden" name="action" value="carousel_create_slider">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'create-carousel' ) ?>">

		<input type="hidden" id="slides_action" value="carousel_create_slides">

		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="name">이름</label></th>
					<td><input type="text" name="name" id="name" value="" placeholder="이름을 입력해주세요."></td>
				</tr>
			</tbody>
		</table><!-- //.form-table -->

		<p>
			<button type="button" class="button carousel modal popover new image">이미지 추가</button>
		</p>

		<div class="media-frame wp-core-ui mode-grid mode-edit hide-menu">

			<div class="media-frame-content" data-columns="10">

				<ul tabindex="-1" class="attachments carousel slides list">
				</ul><!-- //.attachments.ui-sortable.ui-sortable-disabled -->

			</div><!-- //.media-frame-content -->

		</div><!-- //.media-frame.wp-core-ui.mode-grid.mode-edit.hide-menu -->

		<?php submit_button(); ?>

	</form>

</div><!-- //.wrap -->


<div class="carousel modal wrap">

	<div class="carousel modal popup">

		<h2 class="carousel modal title">이미지 추가</h2>

		<form action="" class="carousel form create slide" data-action="create">

			<input type="hidden" name="slide_id" id="slide_id" class="carousel form control" value="">
			<input type="hidden" name="slide_order" id="slide_order" class="carousel form control" value="">

			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="image">이미지 업로드</label>
						</th>
						<td>
							<button type="button" class="button carousel wp media uploader">미디어 추가</button>
						</td>
					</tr>
					<tr>
						<th>
							<label for="image_url">또는 직접 주소 입력</label>
						</th>
						<td class="carousel form group">
							<input type="text" name="image_url" id="image_url" class="carousel form control required" value="" placeholder="이미지 주소를 입력하세요.">
						</td>
					</tr>
					<tr>
						<th>
							<label for="title">이미지 제목</label>
						</th>
						<td class="carousel form group">
							<input type="text" name="title" id="title" class="carousel form control required" value="" placeholder="이미지 제목을 입력하세요.">
						</td>
					</tr>
					<tr>
						<th>
							<label for="link">링크</label>
						</th>
						<td class="carousel form group">
							<input type="text" name="link" id="link" class="carousel form control required" value="" placeholder="링크를 입력하세요.">
						</td>
					</tr>
				</tbody>
			</table>

			<button type="submit" name="submit" id="submit" class="button button-primary">저장</button>
			<button type="button" class="button button-delete carousel delete slide">삭제</button>

		</form>

		<button type="button" class="carousel modal close">
			<span class="dashicons dashicons-no"></span> 닫기
		</button>

	</div><!-- //.carousel.modal.popup -->


</div><!-- //.carousel.modal.wrap -->
