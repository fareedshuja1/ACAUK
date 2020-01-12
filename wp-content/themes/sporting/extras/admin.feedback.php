<?php



	function smt_show_option_contact_details( $param ) { ?>
		<label><?php echo $param[ 'title' ]; ?>:</label>
		<input type="hidden" name="<?php echo $param['name']; ?>" value="" />
		<ul class="smt_object_list">
			<?php $i = 0; ?>
			<?php if ( is_array( $param[ 'value' ] ) ) foreach( $param[ 'value' ] as $detail ) { $i++; ?>

				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title"><?php echo $detail[ 'content' ]; ?></span>
						<span class="smt_object_list_element_remove">Remove</span>
						<div class="clear"></div>
					</div>
					<div class="smt_object_list_element_content">
						<?php 
							foreach( $param[ 'datascheme' ] as $option ) { 
							
								$option[ 'value' ] = $detail[ $option[ 'name' ] ];
								$option[ 'name' ] = $param['name'].'['.$i.']['.$option[ 'name' ].']';
								smt_show_option( $option );
								
							} 
						?>
					</div>
				</li>
				
			<?php } ?>
			
			
			
		</ul>
		<div class="smt_object_list_new" data-name="<?php echo $param[ 'name' ]; ?>"><span class="smt_object_list_element_title">Add new detail</span><div class="clear"></div>
			<div class="smt_object_list_blank">
				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title">New Detail</span>
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
	
	
	
		function smt_show_option_contact_form( $param ) { ?>
		<label><?php echo $param[ 'title' ]; ?>:</label>
		<input type="hidden" name="<?php echo $param['name']; ?>" value="" />
		<ul class="smt_object_list">
			<?php $i = 0; ?>
			<?php if ( is_array( $param[ 'value' ] ) ) foreach( $param[ 'value' ] as $input ) { $i++; ?>

				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title"><?php echo $input[ 'title' ]; ?></span>
						<span class="smt_object_list_element_remove">Remove</span>
						<div class="clear"></div>
					</div>
					<div class="smt_object_list_element_content">
						<?php 
							foreach( $param[ 'datascheme' ] as $option ) { 
							
								$option[ 'value' ] = isset( $input[ $option[ 'name' ] ] ) ? $input[ $option[ 'name' ] ] : false;
								$option[ 'name' ] = $param['name'].'['.$i.']['.$option[ 'name' ].']';
								smt_show_option( $option );
								
							} 
						?>
					</div>
				</li>
				
			<?php } ?>
			
			
			
		</ul>
		<div class="smt_object_list_new" data-name="<?php echo $param[ 'name' ]; ?>"><span class="smt_object_list_element_title">Add new field</span><div class="clear"></div>
			<div class="smt_object_list_blank">
				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title">New Field</span>
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