<?php
  $onclick = esc_attr(sprintf(
    " openCharterLeadModal(\"%s\", \"%s\"); ",
    $charter->VesselID,
    $charter->CompanyID
  ));
?>
<div class="yt-col-12 yt-col-md-6 yt-col-lg-4 col-yacht">
  <div class="card forsale forsaleGallery">
    <a href="<?= $charter->a_url ?>">
      <div class="pic pic-wide">
        
        <picture>
          <source media="(min-width: 650px)" srcset="<?= $charter->LargeImageUrl ?>">
          <img src="<?= $charter->MediumImageUrl ?>" loading="lazy" >
        </picture>

        <div class="overlay"></div>
      </div>
    </a>

    <div class="below-picture">
      <a class="link-title" href="<?= $charter->a_url ?>">
        <div class="card-title" style="">

        	  <?= ($charter->VesselName != '')?$charter->VesselName.' | '.$charter->BuilderName.' '. $charter->ModelYear:'<br>' ?>


         <!--  <?= $charter->ModelYear ?> 
          <?= $charter->BuilderName ?>
          <?= $charter->LOAFeet."'" ?> -->
        </div>

      	<div class="card-title card-name greyfont">
          <?= ($charter->LOAMetric == 1)?$charter->LOA."'":$charter->LOA.'m' ?>	| <?= $charter->NumberOfGuests ?> Cr. Guests | <?= $charter->TotalStateRooms ?> Staterooms | <?= $charter->NumberOfCrew ?> Crew 
        </div>

        <div class="card-price ellipsis">
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


        </div>
      </a>

      <div class="btnRow" style="position: absolute; bottom: 5px; right: 10px;">
          <a onclick="<?= $onclick ?>">
            Inquire
          </a>
      </div>
    </div>
  </div>
</div>