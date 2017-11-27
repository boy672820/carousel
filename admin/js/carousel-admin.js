( function( $ ) {

	'use strict';

	/**
	 * Wordpress media uploader panel
	 */
	var mediaUploader;

	/**
	 * Slide data keys
	 */
	var controlDataKeys = [
		'slide_id',
		'slide_order',
		'image_url',
		'title',
		'link'
	];

	/**
	 * Slide to be deleted
	 */
	var deletedSlides = [];

	/**
	 * Display modal popup
	 *
	 * @param string display
	 */
	var modalPopover = function ( display ) {
		if ( display === 'show' || display === 'hide' || display === 'toggle' ) {
			$( '.carousel.modal.wrap' )[ display ]();
		}
	};

	/**
	 * Changing the Modal title
	 *
	 * @param string title
	 */
	var changeModalTitle = function ( title ) {
		$( '.carousel.modal.title' ).text( title );
	};

	/**
	 * Set action of the slide form
	 *
	 * @param string action
	 */
	var setActionOfslideForm = function ( action, slide_id ) {
		slide_id = typeof slide_id === 'undefined' ? 0 : slide_id;

		$( '.carousel.form.slide' ).data( {
			'action': action,
			'slide-id': slide_id
		} );
	};

	/**
	 * Initialize input field values
	 *
	 * @param object $control
	 */
	var initControls = function ( $control ) {
		$control.each( function () {
			$( this ).val( '' );
		} );
	};

	/**
	 * Get the controls of create slide form
	 */
	var getControlsOfSlideForm = function () {
		return $( '.carousel.form.slide' ).find( '.carousel.form.control' );
	};

	/**
	 * Create a blank slide item
	 */
	var createSlideItem = function () {
		return $( '<li tabindex="0" role="button" class="attachment carousel slides item">' +
					'<div class="attachment-preview js--select-attachment type-image subtype-png landscape">' +
						'<div class="thumbnail">' +
							'<div class="centered">' +
								'<img src="" alt="">' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</li>' );
	};

	/**
	 * Append slide to list
	 *
	 * @param object data
	 */
	var appendSlideItem = function ( data ) {
		var $slideItem = createSlideItem();

		controlDataKeys.forEach( function ( v, i ) {
			$slideItem.attr( 'data-' + v.replace( /_/gi, '-' ), data[ v ] );
		} );

		$slideItem.attr( 'id', 'carousel-slide-' + data.slide_order );
		$slideItem.attr( 'aria-label', data.title );
		$slideItem.find( 'img' ).attr( 'src', data.image_url );

		$( '.carousel.slides.list' ).append( $slideItem );
	};

	/**
	 * Update slide item
	 *
	 * @param object data
	 */
	var updateSlideItem = function ( data ) {
		var $carouselSlide = $( '#carousel-slide-' + data.slide_order );
			dummy = {};

		controlDataKeys.forEach( function ( v ) {
			dummy[ v.replace( /_/gi, '-' ) ] = data[ v ];
		} );
		$carouselSlide.data( dummy );
		$carouselSlide.find( 'img' ).attr( 'src', data.image_url );
	};

	/**
	 * Input field validation
	 *
	 * @param object $control
	 */
	var validateControls = function ( $control ) {
		var error_cnt = 0;

		$control.filter( '.required' ).each( function () {
			var $this = $( this ),
				has_empty = $this.val() == '';

			error_cnt = has_empty ? error_cnt + 1 : error_cnt;

			if ( has_empty && ! $this.hasClass( 'error' ) ) {
				$this
					.addClass( 'error' )
					.after( '<p class="carousel form label error">' + $this.attr( 'placeholder' ) + '</p>' )
				;
			}
			else if ( ! has_empty ) {
				$this
					.removeClass( 'error' )
					.parent().children( '.carousel.form.label.error' ).remove()
				;
			}
		} );

		return error_cnt <= 0 ? true : false;
	};

	/**
	 * Set data of the slide
	 *
	 * @param object $slide
	 */
	var setDataOfSlide = function ( $slide ) {
		controlDataKeys.forEach( function ( v, i ) {
			$( '#' + v ).val( $slide.data( v.replace( /_/gi, '-' ) ) );
		} );
	};

	/**
	 * Aligning slides
	 */
	var aligningSlides = function ( e, ui ) {
		var $items = $( this ).children();

		$items.each( function ( i, item ) {
			var $item = $( item ),
				order = i + 1;

			$item.attr( 'id', 'carousel-slide-' + order );
			$item.data( 'slide-order', order );
		} );
	};

	/**
	 * Add deleted slide
	 *
	 * @param int id
	 */
	var addDeletedSlide = function ( id ) {
		if ( id != '' ) {
			deletedSlides.push( id );
		}
	};


	$( document ).ready( function () {

		/**
		 * Sortable slides
		 */
		$( '.carousel.slides.list' ).sortable( {
			items: '> .carousel.slides.item',
			stop: aligningSlides
		} );

		/**
		 * Modal hide
		 */
		$( '.carousel.modal.wrap' ).hide();
		$( '.carousel.modal.wrap' ).appendTo( 'body' );

		/**
		 * Modal popup position
		 */
		$( '.carousel.modal.popup' ).css( {
			left: function () {
				var $this = $( this );
				return ( $this.parent().width() / 2 ) - ( $this.width() / 2 ) + 80;
			},
			top: function () {
				var $this = $( this );
				return ( $this.parent().height() / 2 ) - ( $this.height() / 2 ) - 25;
			}
		} );

		/**
		 * Display modal popup
		 */
		$( '.carousel.modal.popover.new.image' ).click( function () {
			modalPopover( 'show' );
			changeModalTitle( '이미지 추가' );
			setActionOfslideForm( 'create' );
			initControls( getControlsOfSlideForm() );

			$( '.carousel.delete.slide' ).hide();
			$( '#slide_order' ).val( $( '.carousel.slides.item' ).size() + 1 );
		} );

		/**
		 * Edit carousel slide
		 *
		 * Activate the modal popup and
		 * Set data of the slide.
		 */
		$( document ).on( 'click', '.carousel.slides.item', function () {
			modalPopover( 'show' );
			changeModalTitle( '이미지 수정' );
			setActionOfslideForm( 'update' );
			setDataOfSlide( $( this ) );

			$( '.carousel.delete.slide' ).show();
		} );

		// Close modal popup
		$( '.carousel.modal.close' ).click( function () {
			modalPopover( 'hide' );
		} );

		/**
		 * Submit create slide form
		 */
		$( '.carousel.form.slide' ).submit( function ( e ) {
			var $control = getControlsOfSlideForm(),
				slideData = {};

			e.preventDefault();

			controlDataKeys.forEach( function ( v, i ) {
				slideData[ v ] = $control.filter( '#' + v ).val();
			} );

			if ( ! validateControls( $control ) ) return;

			modalPopover( 'hide' );
			initControls( $control );

			switch ( $( this ).data( 'action' ) ) {
				case 'create':
					appendSlideItem( slideData );
				break;

				case 'update':
					updateSlideItem( slideData );
				break;
			}
		} );

		/**
		 * Media upload handle
		 *
		 * Using the wordpress media javascript api.
		 */
		$( '.carousel.wp.media.uploader' ).click( function( e ) {

			// If the uploader object has already been created, reopen the dialog
			if ( mediaUploader ) {
				mediaUploader.open();
				return;
			}

			mediaUploader = wp.media.frames.file_frame = wp.media( {
				title: '이미지를 선택하세요.',
				button: { text: '이미지 선택' },
				multiple: false
			} );

			// When a file is selected, grab the URL and set it as the text field's value
			mediaUploader.on( 'select', function () {
				var attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
				$( '#image_url' ).val( attachment.url );
			} );

			// Open the uploader dialog
			mediaUploader.open();
		} );

		/**
		 * Submit create slider form
		 */
		$( '.carousel.form.slider' ).submit( function ( e ) {
			e.preventDefault();

			if ( $( '#name' ).val() == '' ) {
				alert( '이름을 입력해주세요.' );
				return;
			}

			$( '#submit' ).prop( 'disabled', true );

			// Object slide information
			var slidesData = [],
				slides_action = $( '#slides_action' ).val();

			$( '.carousel.slides.item' ).each( function ( i ) {
				var $this = $( this ),
					data = {};

				controlDataKeys.forEach( function ( key ) {
					var in_key = slides_action === 'carousel_create_slides' ? key === 'slide_id' : false;
					if ( in_key ) return;

					data[ key ] = $this.data( key.replace( /_/gi, '-' ) );
				} );

				slidesData.push( data );
			} );

			var serializeArray = $( this ).serializeArray();

			// Add slides to parameters
			serializeArray.push( {
				name: 'slides',
				value: JSON.stringify( slidesData )
			} );

			// Add deleted slides to parameters
			serializeArray.push( {
				name: 'deleted_slides',
				value: JSON.stringify( deletedSlides )
			} );

			// Request for ajax
			$.post( '/wp-admin/admin-post.php', serializeArray )

				.done( function ( res ) {
					if ( res.error ) {
						alert( res.error );
						return;
					}

					alert( '저장되었습니다.' );
					location.replace( '/wp-admin/admin.php?page=update_carousel&id=' + res.id );
				} )

				.fail( function () {
					alert( '저장 중 문제가 발생했습니다.\n재시도 해주세요.' );

					$( '#submit' ).prop( 'disabled', false );
				} )

			;

		} );

		/**
		 * Delete slide
		 */
		$( '.carousel.delete.slide' ).click( function () {
			var $selectedSlide = $( '#carousel-slide-' + $( '#slide_order' ).val() ),
				slide_id = $selectedSlide.data( 'slide-id' );

			addDeletedSlide( slide_id );
			modalPopover( 'hide' );

			$selectedSlide.remove();
		} );

	} );

} )( jQuery );
