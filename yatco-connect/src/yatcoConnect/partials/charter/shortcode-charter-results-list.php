<?php
  $onclick = esc_attr(sprintf(
    " openCharterLeadModal(\"%s\", \"%s\"); ",
    $charter->VesselID,
    $charter->CompanyID
  ));
?>
<div class="yt-col-12 col-yacht">
    <div class="card forsale forsaleList">
        <div class="yt-row">
            <div class="yt-col-12 yt-col-md-4">

                <a href="<?= $charter->a_url ?>">
                    <div class="pic pic-wide">
                        <picture>
                            <source media="(min-width: 650px)" srcset="<?= $charter->LargeImageUrl ?>">
                            <img src="<?= $charter->MediumImageUrl ?>" loading="lazy" >
                        </picture>
                                               
                        <div class="overlay"></div>
                    </div>
                </a>
            </div>
            <div class="yt-col-12 yt-col-md-8">
                <div class="specs-padding" style="">
            	 	<div class="btnRow" style="width: auto; position: absolute; top: 5px; right: 15px;">
                	 	<a onclick="<?= $onclick ?>">
                        	Inquire
                        </a>
                    </div>

                    <a href="<?= $charter->a_url ?>">
                        <div class="card-title" style="padding-top: 10px; padding-bottom:25px;">
                        	<?= $charter->VesselName ?>
                        </div>
                    </a>
                    
                    <div class="card-description greyfont" style="padding-bottom: 5px;">
                        <?php if ($charter->BuilderName) : ?>
                            <span><b>Builder:</b> <?= $charter->BuilderName ?></span>
                        <?php endif; ?>

                        <?php if (isset($charter->RefitYear)) : ?>
                            <span><b>Year:</b> <?= $charter->ModelYear ?>/<?= $charter->RefitYear ?></span>

                        <?php else : ?>
                             <span><b>Year:</b> <?= $charter->ModelYear ?></span> 

                        <?php endif; ?>

                        <?php if ( $charter->NumberOfGuests ) : ?>
                        	<span><b>Cr. Guests:</b> <?= $charter->NumberOfGuests ?></span>
                        <?php endif; ?>

                        <?php if ( $charter->TotalStateRooms ) : ?>
                        	<span><b>Staterooms:</b> <?= $charter->TotalStateRooms ?></span>
                        <?php endif; ?>

                        <?php if ($charter->TotalHeads) : ?>
                        	<span><b>Heads:</b> <?= $charter->TotalHeads ?></span>
                        <?php endif; ?>

                        <?php if ($charter->NumberOfCrew) : ?>
                            <span><b>Crew:</b> <?= $charter->NumberOfCrew ?></span>
                        <?php endif; ?>                                
                    </div>

                    <div class="card-description greyfont ellipsis" style="padding-bottom:5px;">
                    	<b>Locations:</b> 

                    	<?php 
                            $locations = array_map(function($l) {return $l->DestinationName;}, $charter->Locations);

                            echo join(', ', $locations);
                        ?>
                    </div>

                    <div class="card-description greyfont ellipsis" style="padding-bottom:5px;">
                        <b>Season:</b> <?= $charter->Rate->SeasonName ?>
                    </div>

                    <?php // if (! $options->is_brokerage_site) : ?>
                        <!-- <div class="card-broker card-description greyfont" style="width:100%;">
                        	<b>Brokage: <?= $charter->CompanyName ?></b> 
                        </div> -->
                    <?php // endif; ?>

                    <div class="card-price yt-text-right" style="width:100%;">
                    	<b>
                            <?php if (isset($charter->Rate->LowRate_USD) && $charter->Rate->LowRate_USD > 0) : ?>
                                <span class="greyfont" style="font-weight: normal;">From</span> 
                                
                                <?php if ($options->is_euro_site) : ?>
                                  <?= $charter->Rate->fLowRateEuro ?>
                                <?php else: ?>
                                 <?= $charter->Rate->fLowRate ?>
                                <?php endif; ?>
                            <?php else : ?>
                                Call for pricing
                            <?php endif; ?>
                        </b>
                    </div> 
                </div>
           	</div>
        </div>

        <div class="yt-clearfix"></div>
    </div>
</div>