<?php if ($vessel->Sections != null && count($vessel->Sections) > 0) : ?>
	<div class="yatco-grabbed-yacht-details-full-spec">
		<h3 class="section-heading" style="border-bottom: 0px">
			FULL SPECIFICATIONS
		</h3>

		<div class="section-content">
			<?php foreach ($vessel->Sections as $sec) : ?>
				<h4 class="accordion">
					<?= $sec->SectionName ?>
				</h4>
				<div class="_panel">
					<?= $sec->SectionText ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div style="height: 15px;"></div>
<?php endif; ?>