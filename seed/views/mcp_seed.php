<div id="seed_container" class="mor">


<?php if( $type == 'error' ) : ?>

	<div class="tg">
		<h2>Error</h2>
		<div class="alert info">
			There were <?php if( count( $errors ) > 1 ) :?>were some errors<?php else : ?>was an error<?php endif; ?> :
			<ul>
			<?php foreach( $errors as $error ) : ?>
				<li><?=$error?></li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>

<?php elseif( $type == 'success' ) : ?>
	
	<div class="tg">
		<h2>Success</h2>
		<div class="alert success">
			<?php foreach( $success as $msg ) : ?>
				<p><?=$msg?></p>
			<?php endforeach; ?>
		</div>

	</div>

<?php elseif ($channels): ?>

<form method="post" action="<?=$base_url?>&amp;method=start_seed">

	<input type="hidden" name="XID" value="<?=XID_SECURE_HASH?>" />

	<div class="tg">
		<h2>Start a new seed</h2>
		<table class="data" id="seed-new-seed">
	        <tbody>
	        	<tr class="<?=seed_row()?>">
					<td scope="row" style="width:30%">
						<label for="seed_channel">Channel to Seed</label>
					</td>
					<td>
						<select style="width:30%" id="seed_channel" name="seed_channel">
							<option value="">-</option>
							<?php foreach( $channels as $channel_id => $channel ) : ?>
								<option value="<?=$channel_id?>"><?=$channel['title']?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr class="<?=seed_row()?>" style="width:30%">
					<td scope="row">
						<label for="seed_count">Create How Many Entries?</label>
					</td>
					<td>
						<input style="width:30%" type="number" id="seed_count" name="seed_count" value="10"/>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	


	<?php foreach( $channels as $channel_id => $channel ) : ?>	


	<div class="seed_fields_channel" id="seed_fields_channel_<?=$channel_id?>" style="display:none; padding : 0 20px;">

		<div class="tg">
			<h2>Fields</h2>
			<div class="alert">Select your population options for this channel's fields.</div>



		<?php foreach( $channel['fields'] as $field_id => $field ) : ?>

		<?php if( $field['field_label'] != 'title' ) : ?>
		<div class="tg">
		<?php endif; ?> 

			<div style="display:block">
				<h3 style="background:#fff; border-top:3px double #849099; margin-top:-1px"><?=$field['field_label']?> <code>[<?=$field['field_name']?>]</code> <span class="help_text"><?=$field['field_type']?><?php if( $field['field_required'] == 'y' ) : ?>, *Required Field*<?php endif; ?></span></h3>
			</div>

			<table class="data">
				<thead>
					<tr>
						<th colspan="2">
						<?php if( $field['field_required'] == 'y' ) : ?>
							<label for="seed_field_<?=$channel_id?>_<?=$field_id?>">
								Populate Options : 
								<select style="width:30%" class="optional_field_populate_option" id="seed_field_<?=$channel_id?>_<?=$field_id?>" rel="seed_field_<?=$channel_id?>_<?=$field_id?>_options" name="seed_field_<?=$channel_id?>_<?=$field_id?>">
									<option value="always">Always Populate</option>
								</select>
							</label>
						<?php else : ?>
							<label for="seed_field_<?=$channel_id?>_<?=$field_id?>">
								Populate Options : 
								<select style="width:30%" class="optional_field_populate_option" id="seed_field_<?=$channel_id?>_<?=$field_id?>" rel="seed_field_<?=$channel_id?>_<?=$field_id?>_options" name="seed_field_<?=$channel_id?>_<?=$field_id?>">
									<option value="empty">Don't Populate</option>
									<option value="sparse">Populate Sparsely</option>
									<option value="always">Always Populate</option>
								</select>
							</label>
						<?php endif;?>
						</th>
					</tr>
				</head>


				<tbody <?php if( $field['field_required'] == 'n' ) : ?>style="display:none"<?php endif; ?> id="seed_field_<?=$channel_id?>_<?=$field_id?>_options">

				<!-- Field type options -->

				<?php echo( $this->seed_channel_model->get_field_view( $field['field_type'], $channel_id, $field_id, $field ) ); ?>


				</tbody>

			</table>

		</div>
		<?php endforeach; ?>

	</div>
	<?php endforeach; ?>

	<p><input type="submit" class="submit" value="<?=lang('start_seed')?>" /></p>
</form>

<?php else : ?>

	<p><?=lang('seed_no_channels_to_populate')?></p>

<?php endif; ?>


</div>

