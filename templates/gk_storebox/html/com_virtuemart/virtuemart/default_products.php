<?php defined('_JEXEC') or die('Restricted access'); 


// Separator
$verticalseparator = " vertical-separator";

foreach ($this->products as $type => $productList ) {
// Calculating Products Per Row
$products_per_row = VmConfig::get ( 'homepage_products_per_row', 3 ) ;
$cellwidth = ' width'.floor ( 100 / $products_per_row );

// Category and Columns Counter
$col = 1;
$nb = 1;

$productTitle = JText::_('COM_VIRTUEMART_'.$type.'_PRODUCT');

?>

<div class="<?php echo $type ?>-view box bigtitle">
 <h3 class="header"><span><?php echo $productTitle ?></span></h3>
	<div class="row">
	<?php // Start the Output
foreach ( $productList as $product ) {

	$show_vertical_separator = $verticalseparator;

		// Show Products ?>
		<div class="product floatleft<?php echo $cellwidth . $show_vertical_separator ?>">
			<div class="spacer">
				<?php // Product Image
				if ($product->images) {
					echo '<div>';
					echo JHTML::_ ( 'link', JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id ), $product->images[0]->displayMediaThumb( 'class="featuredProductImage" border="0"', false,'class="modal"' ) );
					echo '</div>';
				}
				?>
									
				<div>
					<h3 class="catProductTitle"><?php echo JHTML::link ( JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id, FALSE ), $product->product_name, array ('title' => $product->product_name ) ); ?></h3>
				</div>
			</div>
		</div>
		<?php

}

?> </div>
</div>
<?php } 



