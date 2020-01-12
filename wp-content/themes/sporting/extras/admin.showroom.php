<?php



	function smt_show_option_custom_items( $param ) { ?>
		
		<input type="hidden" name="<?php echo $param['name']; ?>" value="" />
		<ul class="smt_object_list">
			<?php $i = 0; ?>
			<?php if ( is_array( $param[ 'value' ] ) ) foreach( $param[ 'value' ] as $item ) { $i++; ?>

				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title"><?php echo $item[ 'title' ]; ?></span>
						<span class="smt_object_list_element_remove">Remove</span>
						<div class="clear"></div>
					</div>
					<div class="smt_object_list_element_content">
						<?php 
							foreach( $param[ 'datascheme' ] as $option ) { 
								$option[ 'value' ] = $item[ $option[ 'name' ] ];
								$option[ 'name' ] = 'custom_items['.$i.']['.$option[ 'name' ].']';
								
								smt_show_option( $option );
							} 
						?>
					</div>
				</li>
				
			<?php } ?>
			
			
			
		</ul>
		<div class="smt_object_list_new" data-name="<?php echo $param[ 'name' ]; ?>"><span class="smt_object_list_element_title">Add new Item</span><div class="clear"></div>
			<div class="smt_object_list_blank">
				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title">New Showrrom Item</span>
						<span class="smt_object_list_element_remove">Remove</span>
						<div class="clear"></div>
					</div>
					<div class="smt_object_list_element_content">
						<?php 
							foreach( $param[ 'datascheme' ] as $option ) { 
								smt_show_option( $option );
							} 
						?>
					</div>
				</li>
			</div>
		</div>
	<?php }