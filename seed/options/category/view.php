

<tr class="odd seed_option_<?=$channel_id?>_category">
    <th scope="row">
    	Possible Categories
    </th>
    <td>

    	<?php foreach( $option['values'][ 'groups' ] as $group ) : 

            $show_header = TRUE;

                
                foreach( $group as $cat ) :
                    if( $show_header ) : ?>
                        <h3><?=$cat['3']?></h3>
                    <?php $show_header = FALSE; endif; ?>

                 
                <?php endforeach; 

            endforeach; ?>


        <?php echo('<pre> - '.$channel_id.' - '.print_R($option,1).'</pre>') ?>
		
		<!--
-->

        Populate categories in the [ one ] group<br/>
        Populate categories in the [ two ] group

    	
    </td>
</tr>
