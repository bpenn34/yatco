<div class="wrap">
	<h1>YATCO SHORTCODE EXAMPLES</h1>

	<h2>Yachts</h2>
	<table class="widefat striped">
		<thead>
			<tr class="">
				<td>Shortcode</td>
				<td>Parameters</td>
				<td>Example</td>
			</tr>
		</thead>
		<tbody>
			<!--  YACHT QUICK FORM -->
			<tr>
				<td>
					<strong>YACHT QUICK SEARCH FORM</strong>
					<p>
						<code>[yatco-yachts-quick-search]</code>
					</p>

					<p class="description">
						Display quick search form on pages that the call to action is go to the search page, for example a homepage.
					</p>
				</td>

				<td>
					<p>
						<code>lengthunit</code>
						<em>(1) Feet or (2) Meters</em>
					</p>

					<hr>

					<p>
						<code>currencytype</code>
						<em>
							3508 - AED | 2555 - AUD | 2556 - CAD | 2561 - CHF | 2557 - EUR | 2562 - GBP | 2558 - HKD | 2559 - NZD | 2560 - SEK | 2563 - USD
						</em>
					</p>

					<hr>

				</td>

				<td>
					<code>[yatco-yachts-quick-search lengthunit="2" currencytype="2557"]</code>
				</td>
			</tr>

			<!--  YACHT QUICK FORM -->
			<tr>
				<td>
					<strong>YACHT QUICK SEARCH FORM NEW DESIGN</strong>
					<p>
						<code>[yt-yachts-quick-search]</code>
					</p>

					<p class="description">
						Display quick search form on pages that the call to action is go to the search page, for example a homepage.
					</p>
				</td>

				<td>
					<p>
						<code>lengthunit</code>
						<em>(1) Feet or (2) Meters</em>
					</p>

					<hr>

					<p>
						<code>currencytype</code>
						<em>
							3508 - AED | 2555 - AUD | 2556 - CAD | 2561 - CHF | 2557 - EUR | 2562 - GBP | 2558 - HKD | 2559 - NZD | 2560 - SEK | 2563 - USD
						</em>
					</p>

					<hr>

				</td>

				<td>
					<code>[yt-yachts-quick-search lengthunit="2" currencytype="2557"]</code>
				</td>
			</tr>
			<!--  YACHT FORM -->
			<tr>
				<td>
					<strong>YACHT SEARCH FORM</strong>
					<p>
						<code>[yatco-yachts-search-page-form]</code>
					</p>

					<p class="description">
						Display search form
					</p>
				</td>

				<td>
					<p>
						<!-- <code></code> -->
						<em>Same as above.</em>
					</p>

					<hr>

				</td>

				<td>
					<code>[yatco-yachts-search-page-form lengthunit="2" currencytype="2557"]</code>
				</td>
			</tr>

			<!--  YACHT FORM NEW DESIGN -->
			<tr>
				<td>
					<strong>YACHT SEARCH FORM NEW DESIGN</strong>
					<p>
						<code>[yt-search-form]</code>
					</p>

					<p class="description">
						Display search form with new design
					</p>
				</td>

				<td>
					<p>
						<!-- <code></code> -->
						<em>Same as above.</em>
					</p>

					<hr>

				</td>

				<td>
					<code>[yt-search-form lengthunit="2" currencytype="2557"]</code>
				</td>
			</tr>

			<!--  YACHT RESULT -->
			<tr>
				<td>
					<strong>YACHT RESULTS</strong>
					<p>
						<code>[yatco-yachts-results]</code>
					</p>

					<p class="description">
						Display results
					</p>
				</td>

				<td>
					<p>
						<code>BOSS API DOC</code>
						<em>See Parameters <a href="https://api.yatco.com/" target="_blank">HERE</a></em>
					</p>

					<hr><p>
						<code>Parameters Class</code>
						<em>/src/yatcoConnect/ApiToBossYachtParams.php</em>
					</p>

					<hr>

				</td>

				<td>
					<code>[yatco-yachts-results CurrencyType="2557"]</code>
				</td>
			</tr>
			
			<tr>
				<td>
					<p class="description">
						Display vessels by LOA range
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results loa_from='60' loa_to='70']</code>
						<br><em>By default value of LOA are in meters</em>
					</p>

					<hr><p>
						<code>[yatco-yachts-results lengthunit="1" loa_from='60' loa_to='70']</code>
						<br><em>Additional parameter for LOA is lengthunit; 1 - Feet or 2 - Meters</em>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display vessels by year range
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results year_from='2023' year_to='2025']</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display vessels by Weight range
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results GrossTonnage_from='1' GrossTonnage_to='2']</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display vessels by condition
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results condition='1']</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display vessels by type
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results vesseltype='1']</code>
						<br><em>1 for Sail, 2 for Motor</em>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display vessels by hullmaterial
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results hullmaterial="2437"]</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display only certain vessels by vessel ids
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results VesselIDs='416380, 416423']</code>
					</p>
					<hr>
				</td>
			</tr>
			
			<tr>
				<td>
					<p class="description">
					To exclude vessels by vessel ids
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results ExcludeVesselIDs='416380, 416423']</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To exclude vessels by company ids
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results ExcludeCompanyIDs='416380, 416423']</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display only featured vessels
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results TagsList='4']</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display vessels by builder ids
					</p>
				</td>

				<td colspan="2">
					<p>
						<code>[yatco-yachts-results BuilderIDList='2492, 5138']</code>
					</p>
					<hr>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display featured results in a grid slider with a template id
					</p>
				</td>

				<td>
					<p>
						<code>[yatco-yachts-grid-slider TagsList='4' template_id='11']</code>
					</p>
					<hr>
				</td>

				<td>
					<p>card_count='4' // by default card count is 3 in desktop view</p>
					<hr>
				</td>
			</tr>
		</tbody>
	</table>

	<h2>Charters</h2>

	<table class="widefat striped">
		<thead>
			<tr class="">
				<td>Shortcode</td>
				<td>Parameters</td>
				<td>Example</td>
			</tr>
		</thead>
		<tbody>
			<!--  YACHT QUICK FORM -->
			<tr>
				<td>
					<strong>CHARTER QUICK SEARCH FORM</strong>
					<p>
						<code>[yatco-charter-quick-search]</code>
					</p>

					<p class="description">
						Display quick search form on pages that the call to action is go to the search page, for example a homepage.
					</p>
				</td>

				<td>
					
				</td>

				<td>
					<code>[yatco-charter-quick-search LOAMetric="2"]</code><br><p>1 for Feet and 2 for Meter.</p>
				</td>
			</tr>

			<!--  CHARTER FORM -->
			<tr>
				<td>
					<strong>CHARTER SEARCH FORM</strong>
					<p>
						<code>[yatco-charter-search-page-form]</code>
					</p>

					<p class="description">
						Display search form
					</p>
				</td>

				<td>
					<p>
						<!-- <code></code> -->
						<em>Same as above.</em>
					</p>

					<hr>

				</td>

				<td>
					<code>[yatco-charter-search-page-form]</code>
				</td>
			</tr>

			<!--  CHARTER RESULT -->
			<tr>
				<td>
					<strong>CHARTER RESULTS</strong>
					<p>
						<code>[yatco-charter-results]</code>
					</p>

					<p class="description">
						Display results
					</p>
				</td>

				<td>
					<p>
						<code>BOSS API DOC</code>
						<em>See Parameters <a href="https://api.yatco.com/" target="_blank">HERE</a></em>
					</p>

					<hr><p>
						<code>Parameters Class</code>
						<em>/src/yatcoConnect/ApiToBossCharterParams.php</em>
					</p>

					<hr>

				</td>

				<td>
					<code>[yatco-charter-results CurrencyType="2557"]</code>
				</td>
			</tr>

			<tr>
				<td>
					<p class="description">
					To display featured results in a grid slider with a template id
					</p>
				</td>

				<td>
					<p>
						<code>[yatco-charter-grid-slider TagsList='4' template_id='11']</code>
					</p>
					<hr>
				</td>

				<td>
					<p>card_count='4' // by default card count is 3 in desktop view</p>
					<hr>
				</td>
			</tr>
		</tbody>
	</table>

	<h2>Newsletter</h2>
	<table class="widefat striped">
		<thead>
			<tr class="">
				<td>Shortcode</td>
				<td>Parameters</td>
				<td>Example</td>
			</tr>
		</thead>
		<tbody>
			<!--  NEWSLETTER FORM SIGNUP -->
			<tr>
				<td>
					<strong>NEWSLETTER SIGNUP FORM</strong>
					<p>
						<code>[yatco-user-newsletter-signup]</code>
					</p>

					<p class="description">
						Newsletter signup form for boss mailing lists.
					</p>
				</td>

				<td>
				
				</td>

				<td>
					<code>[yatco-user-newsletter-signup]</code>
				</td>
			</tr>
			<tr>
				<td>
					<strong>NEWSLETTER SUBSCRIPTIONS LIST FOR USER</strong>
					<p>
						<code>[yatco-user-subscriptions]</code>
					</p>

					<p class="description">
						Current user can see what list they subscripted too.
					</p>
				</td>

				<td>
				
				</td>

				<td>
					<code>[yatco-user-subscriptions]</code>
				</td>
			</tr>
		</tbody>
	</table>

</div>

<style type="text/css">
	.description{ 
		margin: 2px 0 5px;
    	color: #646970;
    }
</style>