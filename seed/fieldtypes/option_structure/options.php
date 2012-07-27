

<tr>
	<th scope="row">
		Add entries as children of 
	</th>
	<td>
		<label style="display:block">
			<input type="checkbox"/>One
		</label>
		<label style="display:block">
			<input type="checkbox"/>One
		</label>
		<label style="display:block">
			<input type="checkbox"/>One
		</label>
		<label style="display:block">
			<input type="checkbox"/>One
		</label>
		<label style="display:block">
			<input type="checkbox"/>One
		</label>
		<label style="display:block">
			<input type="checkbox"/>One
		</label>
		<label style="display:block">
			<input type="checkbox"/>One
		</label>
	</td>
</tr>

<tr class="odd seed_option_<?=$channel_id?>_structure_one">
    <th scope="row">
    	Populate Field Values with
    </th>
    <td>
    	<label for="seed_field_<?=$channel_id?>_<?=$field_id?>_values">
    		<select style="width:50%" name="seed_field_<?=$channel_id?>_<?=$field_id?>_values" id="seed_field_<?=$channel_id?>_<?=$field_id?>_values" rel="seed_field_<?=$channel_id?>_<?=$field_id?>" class="field_sub_option_select">
    			<option value="generated">Generated Dummy Text</option>
    			<option value="specific">Specific Text from a Set</option>
    			<option value="sequence">Sequential Text</option>
    		</select>
    	</label>

    </td>
</tr>


<tr class="even generated seed_field_<?=$channel_id?>_<?=$field_id?> field_sub_option" style="display:none">
	<th scope="row">
		Minimum word count
	</th>
	<td>
		<label>
			<input style="width : 30%" type="number" id="seed_field_<?=$channel_id?>_<?=$field_id?>_from" name="seed_field_<?=$channel_id?>_<?=$field_id?>_from" value="3"/>
			Words
		</label>
	</td>
</tr>


<tr class="odd seed_field_<?=$channel_id?>_<?=$field_id?> generated field_sub_option" style="display:none">
	<th scope="row">
		Maximum word count
	</th>
	<td>
		<label>
			<input style="width:30%" type="number" id="seed_field_<?=$channel_id?>_<?=$field_id?>_to" name="seed_field_<?=$channel_id?>_<?=$field_id?>_to" value="6"/>
			Words
		</label>
	</td>
</tr>
