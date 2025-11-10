
<div class="yt-col-12 col-yacht">
    <div class="card forsale forsaleList">
        <div class="yt-row">
            <div class="yt-col-12 yt-col-md-4">

                <a href="#">
                    <div class="pic pic-wide">
                        
                        <img src="<?= $image_url ?>" alt="<?= $product_details->title ?>" loading="lazy" >
                                               
                        <div class="overlay"></div>
                    </div>
                </a>
            </div>
            <div class="yt-col-12 yt-col-md-8">
                <div class="specs-padding" style="">

                    <a href="#">
                        <div class="card-title" style="padding-top: 10px; padding-bottom:25px;">
                            <?php
                            if( isset( $product_details->length_type ) ){
                                ( $product_details->length_type == 'Meters' )?$product_details->length_in_metre . "'":$product_details->length_in_feet .' m';
                            }
                            ?>
                        	<?= $product_features_index->Builder ?>, 
                        	<?php if( isset( $product_details->title ) ){ echo $product_details->title; } ?>
                        </div>
                    </a>
                    
                    <div class="card-description greyfont" style="padding-bottom: 5px;">
                    	<span>
                    		<b>Length:</b> 
                    		<?= $product_details->length ?>
                    	</span>

                    	<span><b>Year:</b> <?= $product_details->year ?></span>
                        <?php
                        if( isset( $product_features_index->{'Number Of Engines'} ) ){
                        ?>
                    	<span><b>Engines:</b> <?= $product_features_index->{'Number Of Engines'} ?></span>
                        <?php } ?>
                    </div>

                    <div class="card-description greyfont ellipsis" style="padding-bottom:5px;">
                    	<b>Location:</b> 

                    	<?php
                    		if ( isset( $product_details->city_name ) && ! empty( $product_details->city_name ) ) {
                    			echo $product_details->city_name.', ';
                    		}
                    	?>

                    	<?php
                    		if ( isset( $product_details->state_name ) && ! empty( $product_details->state_name ) ) {
                    			echo $product_details->state_name.', ';
                    		}
                    	?>

                        <?php
                    		if ( isset( $product_details->country_name ) && ! empty( $product_details->country_name ) ) {
                    			echo $product_details->country_name.', ';
                    		}
                    	?>

                    </div>

                    <div class="card-price yt-text-right" style="width:100%;">
                    	<b>                       
                        <?= $product_details->price_text ?>
                      </b>
                    </div> 
                </div>
           	</div>
        </div>

        <div class="yt-clearfix"></div>
    </div>
</div>