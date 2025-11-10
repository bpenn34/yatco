<div class="yt-col-12 yt-col-md-6 yt-col-lg-4 col-yacht">
  <div class="card forsale forsaleGallery">

    <a href="#">
      <div class="pic pic-wide">

        <img src="<?= $image_url ?>" alt="<?= $product_details->title ?>" loading="lazy" >
        
        <div class="overlay"></div>
      </div>
    </a>
    
    <div class="below-picture">
      <a class="link-title" href="#">
        <div class="card-title" style="">
          <?= $product_details->year ?> 
          <?= $product_features_index->Builder ?>
          <?= $product_details->length ?>
        </div>

      	<div class="card-title card-name greyfont">
          <?php
          if( isset( $product_details->title ) ){
            echo $product_details->title;
          }
          ?>
        </div>

        <div class="card-price ellipsis">
          <?php if ( isset( $product_details->price_text ) ) : ?>
          	<?= $product_details->price_text ?>
          <?php endif; ?>
        </div>
      </a>
    </div>
  </div>
</div>