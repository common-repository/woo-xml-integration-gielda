<?php $exclude_label = __( 'Wyklucz produkt z pliku XML', 'woocommerce_gielda' ); ?>
<?php $loop_add_name = ''; ?>
<?php if ( isset( $args['loop'] ) ) : ?>
    <?php $loop_add_name = '[' . $args['loop'] . ']'; ?>
	<?php $exclude_label = __( 'Wyklucz wariant z pliku XML', 'woocommerce_gielda' ); ?>
<?php endif; ?>
<div class="inspire-panel">
	<?php if ( isset( $args['loop'] ) ) : ?>
        <h3 class="gielda-variant"><?php _e( 'Twoja Giełda', 'woocommerce_gielda' ); ?></h3>
	<?php endif; ?>
	<div class="options-group">
		<p class="lonely">
	    	<label><input type="checkbox" class="checkbox" value="1" name="woocommerce_gielda<?php echo $loop_add_name; ?>[disabled]" <?php if (get_post_meta($args['post']->ID, "woocommerce_gielda_disabled", true) == 1): ?>checked="checked"<?php endif; ?>/> <?php echo $exclude_label; ?></label>
		</p>
	</div>
	
	<div class="options-group">
		<p class="form-field">
		    <label>
		        <?php echo _('Nazwa produktu dla Twoja Giełda'); ?>
		    </label>
		    <input type="text" name="woocommerce_gielda<?php echo $loop_add_name; ?>[alternative_name]" value="<?php echo _wp_specialchars(get_post_meta($args['post']->ID, "woocommerce_gielda_alternative_name", true),ENT_COMPAT); ?>"/>
		</p>
		
		<p class="form-field">
		    <label>
		        <?php echo _('Opis produktu dla Twoja Giełda'); ?>
		    </label>
		    <textarea name="woocommerce_gielda<?php echo $loop_add_name; ?>[alternative_desc]"><?php echo _wp_specialchars(get_post_meta($args['post']->ID, "woocommerce_gielda_alternative_desc", true),ENT_COMPAT); ?></textarea>
		</p>
	</div>
	
	<?php
	/*
	<div class="options-group">
		<p class="form-field">
		    <label><?php echo _('Grupa produktów Twoja Giełda'); ?></label>
		    <select name="woocommerce_gielda<?php echo $loop_add_name; ?>[gielda_group]" class="gielda_group_selector">
		        <?php $gielda_group = get_post_meta($args['post']->ID, 'woocommerce_gielda_gielda_group', true); ?>
		
		        <option value=""><?php echo _('Wybierz'); ?></option>
		        <?php foreach ($args['gieldaGroups'] as $key => $value): ?>
		            <option value="<?php echo $key; ?>" <?php if ($gielda_group == $key): ?>selected="selected"<?php endif; ?>><?php echo $value['name']; ?></option>
		        <?php endforeach; ?>
		    </select>
		</p>
		
		<div class="hard-candy">
		    <p class="gielda_groups_header"><strong><?php echo _('Dodatkowe pola produktów:'); ?></strong></p>
		    
		    <div class="gielda_dynamic_fields">
		        <?php foreach ($args['gieldaGroups'] as $group_key => $group): ?>
		            <div class="<?php echo $group_key ?>_group gielda_group_container" style="display: none">
						<?php foreach ($group['fields'] as $key => $field): ?>
						    <div class="form-field">
								<label for=""><?php echo $field['title']; ?></label>
								
								<fieldset><legend class="screen-reader-text"><span><?php echo $field['title']; ?></span></legend>
								    <input type="text" placeholder="" value="<?php echo get_post_meta($args['post']->ID, 'woocommerce_gielda_' . $key, true); ?>" style="" id="" name="woocommerce_gielda<?php echo $loop_add_name; ?>[<?php echo $key; ?>]" class="input-text regular-input ">
								    <?php if (!empty($field['description'])): ?> 
								        <p class="description"><?php echo $field['description']; ?></p>
								    <?php endif; ?>
								</fieldset>
						    </div>
						<?php endforeach; ?>
		            </div>
		        <?php endforeach; ?>
		    </div>
		</div>
	</div>
			*/
		?>

</div>