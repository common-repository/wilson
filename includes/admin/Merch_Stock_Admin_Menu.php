<?php
/**
 * The menu-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/menu
 */
/**
 * The menu-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/menu
 * @author     <info@merchandise.nl>
 */
use Dompdf\Dompdf;  
class Merch_Stock_Admin_Menu {
	private $plugin_name;
	private $version;
	private $functions;	
	private $user_functions;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->isMerchStockUuser = $this->user_functions->isMerchStockUser();
	}
}