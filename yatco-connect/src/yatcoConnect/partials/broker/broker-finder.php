<div class="yatco-shortcode-broker-results" data-shortcode-attributes="<?= esc_attr(json_encode($atts)); ?>">

    <div class="card-container">
        <div class="yt-row display-flex">
        	<?php 
                if (count($results->Results) == 0) : 
                    include 'results-not-found.php';
                endif; 

        		foreach ($results->Results as $broker) :
        			//var_dump($broker); 

                    $contact_onclick = esc_attr(sprintf(
                        " openBrokerContactModal(\"%s\", \"%s\", \"%s\"); ",
                        $broker->BrokerID,
                        addslashes($broker->Company->CompanyName),
                        addslashes($broker->FullName)
                    ));

    		        include __DIR__.'/broker-item.php';

    		    endforeach;
    		        
        	?>
        </div>
    </div>

    <!-- Modal: Email Broker -->
    <div id="single-company-modal-email-broker" class="yt-modal single-yacht-modal single-yacht-modal-email-broker">
        <div class="headings">
            <h4>Contact Broker</h4>

            <div class="yt-input-block">
                <h5 class="broker-name"></h5>
                <h5 class="brokerage"></h5>
            </div>
        </div>

        <?php include 'form.php'; ?>
    </div>
</div>
