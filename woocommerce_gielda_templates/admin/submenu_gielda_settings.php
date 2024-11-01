<form action="" method="post">
	<?php settings_fields( 'woocommerce_gielda_settings' ); ?>

 	<?php if (!empty($_POST['option_page']) && $_POST['option_page'] === 'woocommerce_gielda_settings'): ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Ustawienia zostały zapisane.', 'woocommerce_gielda' ); ?></strong></p></div>
	<?php endif; ?>

	<h3><?php _e( 'Ustawienia', 'woocommerce_gielda' ); ?></h3>
	
	<p>Plik XML generowany jest cyklicznie co godzinę i zapisywany do pamięci podręcznej. Dzięki temu rozwiązaniu gdy Twoja Giełda pobiera plik jest on już wygenerowany i cały proces odbywa się szybciej.</p>
	
	<table class="form-table">
		<tbody>
          
            
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="woocommerce_gielda_xml_link"><?php _e( '<u>Krok 1.</u> Ręczne generowanie w cron', 'woocommerce_gielda' ); ?></label>
				</th>
				
				<td class="forminp forminp-text">
                
          	<input style="width:95%;" class="regular-text" value="<?php echo $args['ceno_xml_url']; ?>" id="woocommerce_gielda_xml_link" name="" type="text" readonly="readonly" />
              
                    
                    
                    
					<p class="description"><?php _e( 'Plik jest generowany co godzinę. Możesz też dodać go do cron jeśli plik nie generuje się samoistnie lub przejść w powyższy adres url.', 'woocommerce_gielda' ); ?></p>
				</td>
			</tr>
            
            
                      <?php
						$upload_dir   = wp_upload_dir();
    $file = $upload_dir['baseurl'] . '/gielda.xml';                 
     			/*		<input style="width:95%;" class="regular-text" value="<?php echo $args['ceno_xml_url']; ?>" id="woocommerce_gielda_xml_link" name="" type="text" readonly="readonly" />*/
           ?>
            
            			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="woocommerce_gielda_xml_link"><?php _e( '<u>Krok 2.</u> Link wygerowanego pliku XML', 'woocommerce_gielda' ); ?></label>
				</th>
				
				<td class="forminp forminp-text">
                
          	<input style="width:95%;" class="regular-text" value="<?php echo $file; ?>" id="wygenerowany_xml" name="wygenerowany_xml" type="text" readonly="readonly" />
              
                    
                    
                    
					<p class="description"><?php _e( 'Przed wykonaj krok 1 Aby wygenerować plik. Generowanie może zająć sporo czasu zwłaszcza w dużych sklepach. W przypadku niepowodzenia sprawdź wartości upload_max_filesize i max_execution_time w php.ini', 'woocommerce_gielda' ); ?></p>
				</td>
			</tr>
      
            
            
            
            
            
		</tbody>
	</table>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="woocommerce_gielda_catmap"><?php _e( 'Mapowanie kategorii', 'woocommerce_gielda' ); ?></label>
				</th>
				
				<td class="forminp forminp-text">
					<label for="woocommerce_gielda_catmap"><input class="checkbox" <?php if ($this->getSettingValue('catmap') != ''): ?>checked="checked"<?php endif; ?> id="woocommerce_gielda_catmap" name="woocommerce_gielda[catmap]" type="checkbox" /> Mapuj kategorie WooCommerce na kategorie Twojej Giełdy</label>
				</td>
			</tr>
           <?php
			/*   <tr valign="top">
          
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Warianty produktów', 'woocommerce_gielda' ); ?></label>
                </th>
				

                <td class="forminp forminp-text">
                    <label for="woocommerce_gielda_variants"><input class="checkbox" <?php if ($this->getSettingValue('variants') != ''): ?>checked="checked"<?php endif; ?> id="woocommerce_gielda_variants" name="woocommerce_gielda[variants]" type="checkbox" /> Włącz wystawianie wariantów jako oddzielnych produktów na Twojej Giełdy</label>
                </td>
            </tr>
*/
				?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Producent', 'woocommerce_gielda' ); ?></label>
                </th>

                <td class="forminp forminp-text">
            					<input style="max-width:200px;" class="regular-text" value="<?php if ($this->getSettingValue('brandxml') != ''): echo $this->getSettingValue('brandxml'); endif;?>" id="woocommerce_gielda_xml_link" name="woocommerce_gielda[brandxml]" type="text" />
					<p class="description"><?php _e( 'Dodaj slug atrybutu przypisanego do marki producenta', 'woocommerce_gielda' ); ?></p>


                </td>
            </tr>

            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_kupteraz"><?php _e( 'Kup teraz', 'woocommerce_gielda' ); ?></label>
                </th>

                <td class="forminp forminp-text">
                    <label for="woocommerce_gielda_kupteraz"><input class="checkbox" <?php if ($this->getSettingValue('kupteraz') != ''): ?>checked="checked"<?php endif; ?> id="woocommerce_gielda_kupteraz" name="woocommerce_gielda[kupteraz]" type="checkbox" /> Włącz "kup teraz" na stronie Giełdy</label>
                </td>
            </tr>



			<tr valign="top">
				<th class="titledesc" scope="row">
					<label for="woocommerce_gielda_disable_the_content_filter"><?php _e( 'Filtr the_content', 'woocommerce_gielda' ); ?></label>
				</th>
				
				<td class="forminp forminp-text">
					<label for="woocommerce_gielda_disable_the_content_filter"><input class="checkbox" <?php if ($this->getSettingValue('disable_the_content_filter') != ''): ?>checked="checked"<?php endif; ?> id="woocommerce_gielda_disable_the_content_filter" name="woocommerce_gielda[disable_the_content_filter]" type="checkbox" /> <?php _e( 'Wyłącz filtr the_content dla opisów produktów', 'woocommerce_gielda' ); ?></label>
					<p class="description"><?php _e( 'Wyłącz filtr <b>the_content</b> jeżeli w opisie produtów nie są używane shortcody i nie są włączone wtyczki modyfikujące opisy. Wyłączenie przyspiesza generowanie pliku XML.', 'woocommerce_gielda' ); ?></p>
				</td>
			</tr>
            
    
    
                            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Dodatkowe atrybuty', 'woocommerce_gielda' ); ?></label>
                </th>
</tr>
    
            
                        <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Kolor', 'woocommerce_gielda' ); ?></label>
                </th>
                <td class="forminp forminp-text">
            					<input style="max-width:200px;" class="regular-text" value="<?php if ($this->getSettingValue('colorsxml') != ''): echo $this->getSettingValue('colorsxml'); endif;?>" id="woocommerce_gielda_xml_link" name="woocommerce_gielda[colorsxml]" type="text" />
					<p class="description"><?php _e( 'Dodaj slug atrybutu przypisanego do koloru produktu', 'woocommerce_gielda' ); ?></p>
                </td>
            </tr>


                        <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Kolekcja', 'woocommerce_gielda' ); ?></label>
                </th>
                <td class="forminp forminp-text">
            					<input style="max-width:200px;" class="regular-text" value="<?php if ($this->getSettingValue('collectsxml') != ''): echo $this->getSettingValue('collectsxml'); endif;?>" id="woocommerce_gielda_xml_link" name="woocommerce_gielda[collectsxml]" type="text" />
					<p class="description"><?php _e( 'Dodaj slug atrybutu przypisanego do danej wartośći', 'woocommerce_gielda' ); ?></p>
                </td>
            </tr>


                        <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Pamięć RAM', 'woocommerce_gielda' ); ?></label>
                </th>
                <td class="forminp forminp-text">
            					<input style="max-width:200px;" class="regular-text" value="<?php if ($this->getSettingValue('ramxml') != ''): echo $this->getSettingValue('ramxml'); endif;?>" id="woocommerce_gielda_xml_link" name="woocommerce_gielda[ramxml]" type="text" />
					<p class="description"><?php _e( 'Dodaj slug atrybutu przypisanego do danej wartośći', 'woocommerce_gielda' ); ?></p>
                </td>
            </tr>



                        <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Procesor', 'woocommerce_gielda' ); ?></label>
                </th>
                <td class="forminp forminp-text">
            					<input style="max-width:200px;" class="regular-text" value="<?php if ($this->getSettingValue('procesorxml') != ''): echo $this->getSettingValue('procesorxml'); endif;?>" id="woocommerce_gielda_xml_link" name="woocommerce_gielda[procesorxml]" type="text" />
					<p class="description"><?php _e( 'Dodaj slug atrybutu przypisanego do danej wartośći', 'woocommerce_gielda' ); ?></p>
                </td>
            </tr>


                        <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="woocommerce_gielda_variants"><?php _e( 'Dysk', 'woocommerce_gielda' ); ?></label>
                </th>
                <td class="forminp forminp-text">
            					<input style="max-width:200px;" class="regular-text" value="<?php if ($this->getSettingValue('dyskxml') != ''): echo $this->getSettingValue('dyskxml'); endif;?>" id="woocommerce_gielda_xml_link" name="woocommerce_gielda[dyskxml]" type="text" />
					<p class="description"><?php _e( 'Dodaj slug atrybutu przypisanego do danej wartośći', 'woocommerce_gielda' ); ?></p>
                </td>
            </tr>



            
            
            
		</tbody>
	</table>
	
	<p class="submit"><input type="submit" value="<?php _e( 'Zapisz zmiany', 'woocommerce_gielda' ); ?>" class="button button-primary" id="submit" name="submitForm"></p>
</form>