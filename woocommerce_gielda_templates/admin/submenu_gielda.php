<div class="wrap woocommerce woocommerce-gielda">
	<div id="icon-options-general" class="icon32"><br /></div>
	
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab <?php if ($args['current_tab'] === 'settings'): ?>nav-tab-active<?php endif; ?>" href="<?php echo admin_url( 'admin.php?page=woocommerce_gielda&tab=settings' ); ?>"><?php echo __( 'Ustawienia', 'woocommerce_gielda' ); ?></a>
		<a class="nav-tab <?php if ($args['current_tab'] === 'categories'): ?>nav-tab-active<?php endif; ?>" href="<?php echo admin_url( 'admin.php?page=woocommerce_gielda&tab=categories' ); ?>"><?php echo __( 'Mapowane kategorie', 'woocommerce_gielda' ); ?></a>
	</h2>
	
	<?php echo $this->loadTemplate('submenu_gielda_' . $args['current_tab'], 'admin', $args); ?>
</div>