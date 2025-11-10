<?php 
	if (isset($vessel->tags[0]) ) {
		switch ($vessel->tags[0]) {
		  case 4:
		      echo '<div class="VesselTag Featured">
		          Featured
		      </div>';

		      break;

		}
	}

	if ($vessel->VesselStatus == 99) {
		echo '<div class="VesselTag Sold">
		    Sold
		</div>';
	}

	if( isset($vessel->DaysOnMarket) && $vessel->DaysOnMarket <= 31 ) {
		echo '<div class="VesselTag New">
		    New Listing
		</div>'; 
	}

	if (
		isset($vessel->HistoryText) 
		&& 
		strpos($vessel->HistoryText, 'Price was ') !== false
		&&
		strpos($vessel->HistoryText, 'Price on Application') == false
	) {
		echo '<div class="VesselTag PC" title="'. $vessel->HistoryText .'">
		    Price Reduced
		</div>'; 
	}
	
	if( isset($vessel->AcceptsCrypto) && $vessel->AcceptsCrypto ) {
		echo '<div class="VesselTag Crypto">
		    Crypto Accepted
		</div>'; 
	}
?>