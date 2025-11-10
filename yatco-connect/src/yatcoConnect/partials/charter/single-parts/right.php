
<?php include 'form.php'; ?>

<?php if (! $options->is_brokerage_site) : ?>
	<?php if (count($data->BrokerList) > 0) : ?>
		<?php foreach ($data->BrokerList as $b_index => $broker) : ?>
			<div class="ycd-broker-detail">
				
				<!-- <a href="<?= $broker->a_url ?>"> -->
					<div class="ycd-broker-name">
						<?= $broker->BrokerName ?>
					</div>
				<!-- </a>
 -->
				<a href="<?= $data->Company->a_url ?>">	
					<div class="ycd-brokerage"><?= $broker->BrokerCompanyName ?></div>
				</a>

				<div class="broker-btns">
					
					<a data-modal="#single-charter-modal-call-<?= $b_index ?>" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius broker-btn ycd-broker-call">Call</a>
				
					<a data-modal="#single-charter-modal-email-<?= $b_index ?>" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius broker-btn ycd-broker-email">Email</a>
					
				</div>
			</div>
		<?php endforeach; ?>
		<!-- Company If No Broker -->
	<?php else : ?>

		<div class="ycd-broker-detail">
			<a href="<?= $data->Company->a_url ?>">	
				<div class="ycd-broker-name">
					<?= $data->CompanyName ?>
				</div>

				<div class="ycd-brokerage"><?= $data->CompanyCity ?></div>
			</a>

			<div class="broker-btns">
				<a data-modal="#single-charter-modal-call-<?= $b_index ?>" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius broker-btn ycd-broker-call">Call</a>
				
					
				<a data-modal="#single-charter-modal-email-<?= $b_index ?>" class="yt-btn yt-btn-alt-dark-blue yt-btn-radius broker-btn ycd-broker-email">Email</a>
			</div>
		</div>
		
	<?php endif; ?>
<?php endif; ?>