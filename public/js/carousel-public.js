( function( $ ) {

	'use strict';

	$( document ).ready( function () {

		var next_width = 0;

		$( '.carousel.slider.slide' ).each( function () {

			$( this ).css( 'left', next_width );

			next_width += $( this ).width();

		} );

	} );


	$( document ).ready( function () {

		var timer,
			$slide = $( '.carousel.slider.slide' ),
			slide_count = $slide.size() - 1,
			slide_index = 0,
			container_width = $( '.carousel.slider.container' ).width();

		var slideAnimate = function ( direction ) {

			timer = setInterval( function () {

				$slide.each( function () {
					$( this ).css( 'left', parseInt( $( this ).css( 'left' ) ) - ( 10 * direction ) );
				} );

				var slide_next_index = slide_index + 1;
				if ( slide_next_index > slide_count ) slide_next_index = 0;

				var slide_prev_index = slide_index - 1;
				if ( slide_prev_index < 0 ) slide_prev_index = slide_count;

				var $currentSlide = $( '.carousel.slider.slide[data-index="' + slide_index + '"]' ),
					$nextSlide = $( '.carousel.slider.slide[data-index="' + slide_next_index + '"]' ),
					$prevSlide = $( '.carousel.slider.slide[data-index="' + slide_prev_index + '"]' );

				var currentslide_left = parseInt( $currentSlide.css( 'left' ) ),
					currentslide_width = $currentSlide.width();

				// 슬라이드 왼쪽 방향

				if ( direction > 0 ) {

					currentslide_left = currentslide_left * -1;

					// 슬라이드가 화면에 완전히 넘어 갔는가
					if ( currentslide_left >= currentslide_width ) {
						var prevslide_full_left = parseInt( $prevSlide.css( 'left' ) ) + $prevSlide.width();

						// 이전 슬라이드가 화면에 완전히 넘어갔는가
						if ( prevslide_full_left < container_width )
							$currentSlide.css( 'left', container_width );
						else
							$currentSlide.css( 'left', prevslide_full_left );

						// 다음 슬라이드 index
						slide_index += 1;
						if ( slide_index > slide_count ) slide_index = 0;
					}

				}

				// 슬라이드 오른쪽 방향

				else {

					// 슬라이드가 화면에 완전히 넘어 갔는가
					if ( currentslide_left >= container_width ) {
						var nextslide_left = parseInt( $nextSlide.css( 'left' ) );

						// 다음 슬라이드 다음으로 위치
						$currentSlide.css( 'left', nextslide_left + currentslide_width * -1 );

						// 다음 슬라이드 index
						slide_index -= 1;
						if ( slide_index < 0 ) slide_index = slide_count;
					}

				}

				// $( '.carousel.slider.slide' ).css( 'border', '0 none' );

			}, 100 );

		};

		var direction = 1;

		slideAnimate( direction );

		$( '.carousel.slider.slides' ).hover(
			function () {
				clearInterval( timer );
			},
			function () {
				slideAnimate( direction );
			}
		);

		// 슬라이드 왼쪽이동 버튼
		$( '.carousel.slider.handle.previous' ).click( function () {
			if ( direction > 0 ) return;

			direction = 1;

			slide_index += 1;
			if ( slide_index > slide_count ) slide_index = 0;

			clearInterval( timer );
			slideAnimate( direction );
		} );

		// 슬라이드 오른쪽이동 버튼
		$( '.carousel.slider.handle.next' ).click( function () {
			if ( direction < 0 ) return;

			direction = -1;

			slide_index -= 1;
			if ( slide_index < 0 ) slide_index = slide_count;

			clearInterval( timer );
			slideAnimate( direction );
		} );

	} );

} )( jQuery );
