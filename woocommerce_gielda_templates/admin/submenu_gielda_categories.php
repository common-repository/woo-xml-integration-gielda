<form action="" method="post">
	<?php settings_fields( 'woocommerce_gielda_categories' ); ?>

 	<?php if (!empty($_POST['option_page']) && $_POST['option_page'] === 'woocommerce_gielda_categories'): ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Ustawienia zostały zapisane.', 'woocommerce_gielda' ); ?></strong></p></div>
	<?php endif; ?>

	<h3><?php _e( 'Mapowanie kategorii', 'woocommerce_gielda' ); ?></h3>
	
	<p><?php _e( 'Przypisz kategorie WooCommerce do odpowiednich kategorii Twojej Giełdy.', 'woocommerce_gielda' ); ?></p>
	
	<table class="form-table">
		<tbody>
			<?php 
			foreach ($args['product_categories'] as $category): ?>
				<?php
				 $value = $this->getSettingValue('term_' . $category->term_id); ?>
				<tr valign="top">
					<th class="titledesc" scope="row"><a target="_blank" href="<?php echo get_term_link( $category->term_id, 'product_cat' ); ?>"><?php echo $category->name; ?></a></th>
                    <th><?php 
					
					$urllink= get_term_link( $category->term_id, 'product_cat' ); 
                    $linknames=explode('/', $urllink);
                    $linknameslicz=count(explode('/', $urllink))-2;
					$resname='';
					for ($i = 2; $i < $linknameslicz; $i++) {						
						$term22= get_term_by('slug', $linknames[$i], 'product_cat');
						
						if(!empty($term22)){						
					$resname.=$term22->name.'>';
						}
					}
					echo substr($resname, 0, -1);
					
					
					
					
                    ?>
                    
                    
                    </th>
					<td class="forminp forminp-text">
                        <?php if ( version_compare( WC_VERSION, '2.7', '<' ) ) : ?>
                            <input type="hidden" name="woocommerce_gielda[term_<?php echo $category->term_id; ?>]" id="woocommerce_category_<?php echo $category->slug; ?>" class="gielda_category_autocomplete_select2" data-placeholder="<?php _e( 'Wybierz kategorię', 'woocommerce_gielda' ); ?>" value="<?php echo $value; ?>" data-label="<?php if ( !empty( $value ) ) echo $args['gieldaCategoriesOptions'][$value]; ?>">
                        <?php else : ?>
                            <select name="woocommerce_gielda[term_<?php echo $category->term_id; ?>]" id="woocommerce_category_<?php echo $category->slug; ?>" class="gielda_category_autocomplete_select2" data-placeholder="<?php _e( 'Wybierz kategorię', 'woocommerce_gielda' ); ?>">
                                <?php if (!empty($value)): ?>
                                    <option selected="selected" value="<?php echo $value; ?>"><?php echo $args['gieldaCategoriesOptions'][$value]; ?></option>
                                <?php endif; ?>
                                <option value="">Wybierz kategorię...</option>
                            </select>
                        <?php endif; ?>
					</td>
				</tr>
			<?php 
				endforeach; ?>
		</tbody>
	</table>

	<p class="submit"><input type="submit" value="<?php _e( 'Zapisz zmiany', 'woocommerce_gielda' ); ?>" class="button button-primary" id="submit" name="submitForm"></p>
</form>
