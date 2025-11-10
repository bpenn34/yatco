<div class="yt-col-12 yt-col-md-6 yt-col-lg-4 col-yacht">
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
      <div class="pic pic-wide">
        <?php 
          if ($image_size == 'large') :
            $image_url = str_replace('small', 'large', htmlentities($vessel->MainPhotoUrl));
            
          elseif ($image_size == 'medium') : 
            $image_url = str_replace('small', 'medium', htmlentities($vessel->MainPhotoUrl));
          
          else :
            $image_url = $vessel->MainPhotoUrl;

          endif;
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
          <?= ($vessel->VesselName != '')?$vessel->VesselName:'<br>' ?>
        </div>

        <div class="card-price ellipsis">
          <?php if (isset($_params['modificationtypelist']) && in_array(2, $_params['modificationtypelist'])) : ?>
            <span style="font-size: 10px;"><?= $vessel->HistoryText ?></span>

          <?php else : ?>
          	<?= $vessel->AskingPriceFormattedNoCurrency ?>

          <?php endif; ?>
        </div>
      </a>

      <div class="btnRow">
        <?php if ($vessel->VesselStatus != 99) : ?>
          <span onclick="<?= $onclick ?>">
          	Contact
          </span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>