<?php if ($channels): ?>

<form method="post" action="<?=$base_url?>&amp;method=start_seed">

	<input type="hidden" name="XID" value="<?=XID_SECURE_HASH?>" />

	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

	<?php if( !empty( $errors ) ) : ?>

	<h3>Sorry</h3>
	<p>There <?php if( count( $errors ) > 1 ) :?>were some errors<?php else : ?>was an error<?php endif; ?> : </p>
	<ul>
		<?php foreach( $errors as $error ) : ?>
			<li><?=$error?></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<table class="mainTable" id="seed-new-seed" cellspacing="0" cellpadding="0">
		<colgroup>
			<col style="width:30%;" />
			<col style="width:70%" />
		</colgroup>
		<thead>
			<tr>
				<th scope="col"><?=lang('preference')?></th>
				<th scope="col"><?=lang('setting')?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="<?=seed_row()?>">
				<td style="text-align:right">
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
			<tr class="<?=seed_row()?>">
				<td style="text-align:right">
					<label for="seed_count">Create How Many Entries?</label>
				</td>
				<td>
					<input style="width:30%" type="number" id="seed_count" name="seed_count" value="10"/>
				</td>
			</tr>
		</tbody>
	</table>


	<?php foreach( $channels as $channel_id => $channel ) : ?>			

	<table class="mainTable seed_fields_channel" id="seed_fields_channel_<?=$channel_id?>" cellspacing="0" cellpadding="0" style="display:none">
		<colgroup>
			<col style="width:30%;" />
			<col style="width:20%" />
			<col style="width:50%" />
		</colgroup>
		<thead>
			<tr>
				<th scope="col"><?=lang('seed_field')?></th>
				<th scope="col"><?=lang('seed_populate')?></th>
				<th scope="col"><?=lang('seed_field_values')?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $channel['fields'] as $field_id => $field ) : ?>
			<tr class="<?=seed_row()?>">
				<td style="text-align:right">
					<label for="seed_field_<?=$channel_id?>_<?=$field_id?>">
						<?=$field['field_label']?> - {<?=$field['field_name']?>}
					</label>
					<?php if( isset( $field['is_title'] ) ) : ?>
						<p>Stanard Title Field.</p>
					<?php else : ?>
						<p><?=$field['field_type']?>, Formatting : <?=$field['field_fmt']?>, Content : <?=$field['field_content_type']?></p>
					<?php endif; ?>

				</td>
				<td>					
					<?php if( $field['field_required'] == 'y' ) : ?>
						<label for="seed_optional_field_<?=$channel_id?>_<?=$field_id?>">
							<select style="width:100%" id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>" disabled="disabled">
								<option value="always">Always Populate *Required Field*</option>
							</select>
						</label> 
					<?php else : ?>
						<label for="seed_optional_field_<?=$channel_id?>_<?=$field_id?>">
							<select style="width:100%" class="optional_field_populate_option" id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>" rel="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_options" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>">
								<option value="empty">Don't Populate</option>
								<option value="sparse">Populate Sparsely</option>
								<option value="always">Always Populate</option>
							</select>
						</label> 
					<?php endif;?>
				</td>	
				<td>
					<?php if( $field['field_required'] != 'y' ) : ?>
					<div id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_options" style="display:none">
					<?php endif; ?>

						<?php if( $field['field_type'] == 'text' ) : ?>

							<p>Populate with between
								<input style="width : 5%" type="number" id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_from" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_from" value="1"/> 
								and

								<input style="width : 5%" type="number" id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_to" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_to" value="10"/> 
								Words
							</p>
							<p>Up to a max length of <strong>
								<input style="width:10%" type="number" id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_max" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_max" value="<?=$field['field_maxl']?>"/>
								</strong> characters</p>

						<?php endif; ?>

						<?php if( $field['field_type'] == 'textarea' ) : ?>

							<p>Populate with between
								<input style="width : 5%" type="number" id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_from" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_from" value="3"/> 
								and

								<input style="width : 5%" type="number" id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_to" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_to" value="6"/> 
								Paragraphs
							</p>

							<p>Include
							<select id="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_markup" name="seed_optional_field_<?=$channel_id?>_<?=$field_id?>_markup">
								<option value="none">No Formatting</option>
								<option value="html">HTML</option>
								<option value="textile">Textile</option>
								<option value="markdown">Markdown</option>
							</select>
						</p>

						<?php endif; ?>



					<?php if( $field['field_required'] != 'y' ) : ?>
					</div>
					<?php endif; ?>

				</td>			
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php endforeach; ?>

	<p><input type="submit" class="submit" value="<?=lang('start_seed')?>" /></p>
</form>







<?php else : ?>

	<p><?=lang('no_searchable_channels_found')?></p>

<?php endif; ?>