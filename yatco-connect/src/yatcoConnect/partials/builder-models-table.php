<div class="yt-builder-models-table">

	<table class="yt-table">
		<?php if (count($models) > 0) : ?>
			<thead>
				<th>Model Name</th>
				<th>Production Year</th>
				<th>LOA</th>
				<th>Beam</th>
				<th>Draft</th>
			</thead>
		<?php endif;?>

		<tbody>
			<?php foreach ($models as $model) : ?>
				<tr>
					<td style="white-space: nowrap;"><?= $model->ModelName ?></td>
					<td><?= $model->Year ?></td>
					<td><?= $model->LengthFormatted ?></td>
					<td><?= $model->BeamFormatted ?></td>
					<td><?= $model->DraftFormatted ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if (count($models) == 0) : ?>
				<tr>
					<td colspan="5" style="text-align:  center; white-space: nowrap; font-weight: bold;">
						<noindex>
							<!--googleoff: all-->
							<div class="robots-nocontent noindex no-results-found">
								More Info is Coming.
							</div>
							<!--googleon: all-->
						</noindex>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>

	</table>

</div>