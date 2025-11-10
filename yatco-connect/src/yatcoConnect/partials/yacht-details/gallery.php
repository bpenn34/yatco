<?php if (count($vessel->PhotoGallery) != 0) : ?>
	<div class="yatco-grabbed-yacht-details-gallery gallery-panel">
		<!-- <h3 class="section-heading">
			GALLERY
		</h3> -->

		<div class="section-content">
			<div id="lightgallery" class="yt-row">
				<?php foreach ($vessel->PhotoGallery as $pic) : ?>
					
					<a href="<?= $pic->largeImageURL ?>">
						<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
							<div class="pic-wrapper">
								<div class="pic-overlay">VIEW GALLERY</div>

								<img src="<?= $pic->smallImageURL ?>" alt="<?= $pic->Caption ?>" class="pic" loading="lazy" />

								<?php /* <picture>
							        <source media="(min-width: 650px)" srcset="<?= $vessel_medium_main_img ?>">
							        <img src="<?= $vessel->MainPhotoUrl ?>" style="width:auto;">
							      </picture> */ ?>
							</div>
						</div>
					</a>

				<?php endforeach; ?>

				<?php foreach ($vessel->Videos as $vid) : 
					preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $vid->VideoUrl, $matches);
					$y_id=$matches[1];?>

					<a href="<?= $vid->VideoUrl ?>">
						<div class="yt-col-lg-3 yt-col-md-4 yt-col-6 col-pic">
							<div class="pic-wrapper">
								<div class="pic-overlay">VIEW GALLERY</div>
								
								<picture>
							        <source media="(min-width: 650px)" srcset="https://img.youtube.com/vi/<?= $y_id ?>/hqdefault.jpg">
						        	<img src="https://img.youtube.com/vi/<?= $y_id ?>/mqdefault.jpg" alt="<?= $pic->Caption ?>" loading="lazy" class="pic" />
						      	</picture>
							</div>
						</div>
					</a>

				<?php endforeach; ?>
			</div>

		</div>
		
	</div>
<?php endif; ?>