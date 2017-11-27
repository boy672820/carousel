<div class="carousel slider wrap">

	<div class="carousel slider container">

		<ul class="carousel slider slides">

		<?php $i = 0; foreach ( $slides as $slide ) : ?>

			<li class="carousel slider slide" data-index="<?php echo $i ?>">

				<a href="<?php echo $slide->link ?>" class="carousel slider anchor">

					<img src="<?php echo $slide->image_url ?>" alt="<?php echo $slide->title ?>" title="<?php echo $slide->title ?>" class="carousel slider thumbnail">

				</a><!-- //.carousel.slider.slide -->

			</li><!-- //.carousel.slider.slide -->

		<?php $i += 1; endforeach; ?>

		</ul><!-- //.carousel.slider.slides -->

	</div><!-- //.carousel.slider.container -->

</div><!-- //.carousel.slider.wrap -->


<button class="carousel slider handle previous" type="button">왼쪽</button>
<button class="carousel slider handle next" type="button">오른쪽</button>
