<section class="search-ui">
	<div class="results-col">
	
		<?php 
			if (count($results->Results) == 0) : 
				echo '<div class="yt-text-center">0 members were found that match your criteria.</div>';
			endif; 

			//var_dump($results->Results);
			
			foreach ($results->Results as $member) :

				//var_dump($member);
				?>

				<article>
				    <div class="img-container">
				        <a href="/Membership/Detail/<?= $member->CRMCompanyID ?>"><img src="<?= $member->ImageMediumUrl; ?>"></a>
				    </div>
				    <div class="info-container">
				        <h4>
				        	<a href="/Membership/Detail/<?= $member->CRMCompanyID ?>">
				        		<?= $member->CompanyName ?>
				        	</a>
				        </h4>
				        
				        <ul class="post-info">
				            <li><?= $member->ContactName ?></li>
				            
				            <li>
				            	<?php if (isset($member->ContactNumber)) : ?>
					            	<a href="tel:<?= $member->ContactNumber ?>">
					            		<?= $member->ContactNumber ?>
					            	</a>
					           	<?php elseif (isset($member->ContactCell)) : ?>
					           		<a href="tel:<?= $member->ContactCell ?>">
					            		<?= $member->ContactCell ?>
					            	</a>
					            <?php elseif (isset($member->Phone)): ?>
					            	<a href="tel:<?= $member->Phone ?>">
					            		<?= $member->Phone ?>
					            	</a>
					           	<?php endif; ?>
				            </li>
				            
				        </ul>

				        <p><?= apply_filters('yatco_full_address', $member, true, true) ?></p>
				    </div>
				</article>

				<?php 
				
			endforeach; 
		?>
	</div>

	<div class="membership-pagination yt-text-center">
		<?php 
							
			echo paginate_links(array(
	            'base' => get_the_permalink().'%_%',
	            'format' => '?page_index=%#%',
	            'current' => $page_index,
	            'total' => $page_count,
	            'mid_size' => 3,
	            'end_size' => 2,
	            'prev_text' => "<",
	            'next_text' => ">"
	        ));

		?>
	</div>
</div>

<style type="text/css">
section.search-ui .results-col article {
    display: flex;
    align-items: center;
    padding: 7px 0 7px;
    line-height: 1.785714;
    font-size: 14px;
    letter-spacing: -.1px;
    border-bottom: 2px solid #e9e9e9;
}

section.search-ui .results-col article:first-child {
    border-top: 2px solid #e9e9e9;
}

section.search-ui .results-col article .img-container {
    width: 25%;
}

section.search-ui .results-col article .info-container {
    width: 75%;
}

section.search-ui .results-col article .img-container img {
    max-width: 100%;
    width: 100%;
    padding: 0 1rem 0 0;
}

section.search-ui .results-col article h4 {
    font-size: 18px;
    line-height: 1.4;
    letter-spacing: -.2px;
    margin-bottom: 5px;
    color: #002a54;
    font-weight: 500;
    text-transform: uppercase;
}

section.search-ui .results-col article h4 a {
    color: inherit !important;
    text-decoration: none !important;
}

 section.search-ui .results-col article ul.post-info {
    text-transform: none;
    margin: 0 -21px 3px;
    font-size: 0;
    font-weight: 500;
    color: #0091d0;
    padding: 0;
    list-style: none;
}

section.search-ui .results-col article ul.post-info li {
    font-size: 16px;
    padding: 0 21px;
    display: inline-block;
    vertical-align: middle;
    border-right: 2px solid #ddd;
}

section.search-ui .results-col article ul.post-info li:last-child {
    border-right: none;
}

section.search-ui .results-col article ul.post-info a {
    text-decoration: none;
}

section.search-ui .results-col article p {
    margin-bottom: 0px;
}

</style>