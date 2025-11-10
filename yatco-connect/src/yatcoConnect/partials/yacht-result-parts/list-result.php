
<div class="yt-col-12 col-yacht">
    <div class="card forsale forsaleList">
        <div class="yt-row">
            <div class="yt-col-12 yt-col-md-4">
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
            </div>
            <div class="yt-col-12 yt-col-md-8">
                <div class="specs-padding" style="">
            	 	<div class="btnRow" style="width: auto; top: 5px;">
                        <?php if ($vessel->VesselStatus != 99) : ?>
                	       <span onclick="<?= $onclick ?>">
                            	Contact
                            </span>
                        <?php endif; ?>
                    </div>

                    <a href="<?= $vessel->a_url ?>">
                        <div class="card-title" style="padding-top: 10px; padding-bottom:25px;">
                        	<?= ($vessel->LengthUnit == 1)?$vessel->LOAFeet."'":$vessel->LOAMeters.'m'; ?>
                        	<?= $vessel->BuilderName ?>, 
                        	<?= $vessel->VesselName ?>
                        </div>
                    </a>
                    
                    <div class="card-description greyfont" style="padding-bottom: 5px;">
                    	<span>
                    		<b>Length:</b> 
                    		<?= $vessel->LoaFormat ?>
                    	</span>

                    	<span><b>Year:</b> <?= $vessel->Year ?></span>

                    	<span><b>Staterooms:</b> <?= $vessel->StateRooms ?></span>
                    </div>

                    <div class="card-description greyfont ellipsis" style="padding-bottom:5px;">
                    	<b>Location:</b> 

                    	<?= $vessel->City ?>

                    	<?php
                    		if (isset($vessel->State) && ! empty($vessel->State)) {
                    			echo $vessel->State.', ';
                    		}
                    	?>

                    	<?= $vessel->Country ?>
                    </div>

                    <?php if (! $options->is_brokerage_site) : ?>
                        <div class="card-broker card-description greyfont" style="width:100%;">
                        	<b>Brokage:</b> <?= $vessel->BrokerList[0]->CompanyName ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($vessel->BrokerTeaser) && $vessel->BrokerTeaser != '') : ?>

                      <div class="card-broker card-description greyfont" style="width:100%; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                          <i><?= $vessel->BrokerTeaser ?></i>
                      </div>

                    <?php endif; ?>

                    <div class="card-price yt-text-right" style="width:100%;">
                    	<b>                       
                        <?php if (isset($_params['modificationtypelist']) && in_array(2, $_params['modificationtypelist'])) : ?>
                          <?= $vessel->HistoryText ?>

                        <?php else : ?>
                          <?= $vessel->AskingPriceFormattedNoCurrency ?>

                        <?php endif; ?> 
                      </b>
                    </div> 
                </div>
           	</div>
        </div>

        <div class="yt-clearfix"></div>
    </div>
</div>