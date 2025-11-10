<div class="yt-col-12 yt-col-md-6 yt-col-lg-6 col-yacht">
  <div class="card forsale forsaleGallery">
     	<div class="VesselTagContainer">
       	<?php 
       		include 'vessel-tag.php';
          
          $onclick = esc_attr(sprintf(
            ' openContactModal("%s", "%s", "%s", "%s"); ',
            $vessel->VesselID,
            $vessel->BrokerList[0]->CompanyID,
            addslashes($vessel->BrokerList[0]->CompanyName),
            addslashes($vessel->BrokerList[0]->BrokerName)
          ));
       	?>
    </div>

    <a href="<?= $vessel->a_url ?>">
      <div class="pic pic-wide ">
        <?php 
          $image_url = str_replace('small', 'medium', htmlentities($vessel->MainPhotoUrl));
        ?>

        <img src="<?= $image_url ?>" alt="<?= $vessel->VesselName ?> <?= $vessel->MLSID ?>" loading="lazy" >
        
        <div class="overlay"></div>
      </div>
    </a>                      

    <div class="below-picture">
      <a class="link-title" href="<?= $vessel->a_url ?>">
        <div class="card-title" style="">
          <?= $vessel->Year ?> 
          <?= $vessel->BuilderName ?>
          <?= $vessel->LoaFormat ?>
        </div>

      	<div class="card-title card-name greyfont">
          <?= $vessel->VesselName ?>
        </div>

        <div class="card-price ellipsis">
        	<?= $vessel->AskingPriceFormattedNoCurrency ?>
        </div>
      </a>

      <div class="btnRow">
          <span onclick="<?= $onclick ?>">
          	Contact
          </span>
      </div>
    </div>
  </div>
</div>