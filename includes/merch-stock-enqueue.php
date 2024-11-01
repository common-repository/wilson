<?php
/**
 * The enqueue-specific functionality of the plugin.
 *
 * @link       http://merchandise.nl
 * @since      1.0.0
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/enqueue
 */
/**
 * The enqueue-specific functionality of the plugin.
 *
 * @package    Merch_Stock
 * @subpackage Merch_Stock/ajax
 * @author     <info@merchandise.nl>
 */
class Merch_Stock_Enqueue {
	private $plugin_name;
	private $version;
	private $functions;	
	private $user_functions;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Merch_Stock_Functions();
		$this->user_functions = new Merch_Stock_User_Functions();
		$this->isMerchStockUser = $this->user_functions->isMerchStockUser();
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ($this->isMerchStockUser){
			wp_enqueue_style( 'merch-stock-admin', plugin_dir_url( __FILE__ ) . '../admin/css/merch-stock-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . '../admin/css/bootstrap.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'animate', plugin_dir_url( __FILE__ ) . '../admin/css/animate.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'fontawesome.all.min', plugin_dir_url( __FILE__ ) . '../admin/css/all.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'magnific-popup', plugin_dir_url( __FILE__ ) . '../admin/css/magnific-popup.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'datepicker', plugin_dir_url( __FILE__ ) . '../admin/css/bootstrap-datepicker3.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'jqueryui', plugin_dir_url( __FILE__ ) . '../admin/css/jquery-ui.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . '../admin/css/select2.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'jquerytheme', plugin_dir_url( __FILE__ ) . '../admin/css/jquery-ui.theme.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'bootstrap-multiselect', plugin_dir_url( __FILE__ ) . '../admin/css/bootstrap-multiselect.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'morris', plugin_dir_url( __FILE__ ) . '../admin/css/morris.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'theme', plugin_dir_url( __FILE__ ) . '../admin/css/theme.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'default', plugin_dir_url( __FILE__ ) . '../admin/css/default.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'custom', plugin_dir_url( __FILE__ ) . '../admin/css/custom.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'jquery.dataTables', plugin_dir_url( __FILE__ ) . '../admin/css/jquery.dataTables.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'dataTables.bootstrap4', plugin_dir_url( __FILE__ ) . '../admin/css/dataTables.bootstrap4.css', array(), $this->version, 'all' );
		}			
		else{
			wp_enqueue_style( 'admin-wp', plugin_dir_url( __FILE__ ) . '../admin/css/admin-wp.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'fontawesome.all.min', plugin_dir_url( __FILE__ ) . '../admin/css/all.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'jquery.dataTables', plugin_dir_url( __FILE__ ) . '../admin/css/jquery.dataTables.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'dataTables.bootstrap4', plugin_dir_url( __FILE__ ) . '../admin/css/dataTables.bootstrap4.css', array(), $this->version, 'all' );			
		}
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ($this->isMerchStockUser){
			// wp_enqueue_script( 'jquery-mobile', plugin_dir_url( __FILE__ ) . '../admin/js/jquery.browser.mobile.js', array(), $this->version, false );


			wp_enqueue_script( 'popper', plugin_dir_url( __FILE__ ) . '../admin/js/popper.min.js', array(), $this->version, false ); // Used for dropdown menu
			wp_enqueue_script( 'jquery.magnific-popup', plugin_dir_url( __FILE__ ) . '../admin/js/jquery.magnific-popup.js', array(), $this->version, false ); // User for popups
			wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . '../admin/js/bootstrap.js', array(), $this->version, false );
			// wp_enqueue_script( 'bootstrap-datepicker', plugin_dir_url( __FILE__ ) . '../admin/js/bootstrap-datepicker.js', array(), $this->version, false );
			wp_enqueue_script( 'common', plugin_dir_url( __FILE__ ) . '../admin/js/common.js', array(), $this->version, false );
			
			// wp_enqueue_script( 'jquery.placeholder', plugin_dir_url( __FILE__ ) . '../admin/js/jquery.placeholder.js', array(), $this->version, false );
			// wp_enqueue_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . '../admin/js/jquery-ui.js', array(), $this->version, false );
			// wp_enqueue_script( 'jquery.ui.touch-punch', plugin_dir_url( __FILE__ ) . '../admin/js/jquery.ui.touch-punch.js', array(), $this->version, false );
			// wp_enqueue_script( 'jquery.appear', plugin_dir_url( __FILE__ ) . '../admin/js/jquery.appear.js', array(), $this->version, false );
			// wp_enqueue_script( 'bootstrap-multiselect', plugin_dir_url( __FILE__ ) . '../admin/js/bootstrap-multiselect.js', array(), $this->version, false );
			// wp_enqueue_script( 'raphael', plugin_dir_url( __FILE__ ) . '../admin/js/raphael.js', array(), $this->version, false );
			// wp_enqueue_script( 'morris', plugin_dir_url( __FILE__ ) . '../admin/js/morris.js', array(), $this->version, false );
			// wp_enqueue_script( 'gauge', plugin_dir_url( __FILE__ ) . '../admin/js/gauge.js', array(), $this->version, false );
			wp_enqueue_script( 'snap.svg', plugin_dir_url( __FILE__ ) . '../admin/js/snap.svg.js', array(), $this->version, false );
			wp_enqueue_script( 'custom', plugin_dir_url( __FILE__ ) . '../admin/js/custom.js', array(), $this->version, false );
			wp_enqueue_script( 'modal', plugin_dir_url( __FILE__ ) . '../admin/js/modal.js', array(), $this->version, false );			
			// wp_enqueue_script( 'modal2', plugin_dir_url( __FILE__ ) . '../admin/js/modal2.js', array(), $this->version, false );
		}
		else{
			wp_enqueue_script( 'custom', plugin_dir_url( __FILE__ ) . '../admin/js/custom_admin.js', array(), $this->version, false );
		}
	}
	public function enqueue_datatable_styles(){
		if ($this->isMerchStockUser){
			wp_enqueue_style( 'custom', plugin_dir_url( __FILE__ ) . '../admin/css/custom.css', array(), $this->version, 'all' );
		}
	}
	public function enqueue_datatable_scripts(){
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . '../admin/select2/js/select2.js', array(), $this->version, false );
		wp_enqueue_script( 'jquery.dataTables', plugin_dir_url( __FILE__ ) . '../admin/datatables/media/js/jquery.dataTables.min.js', array(), $this->version, false );
		wp_enqueue_script( 'dataTables.bootstrap4', plugin_dir_url( __FILE__ ) . '../admin/datatables/media/js/dataTables.bootstrap4.min.js', array(), $this->version, false );
		wp_enqueue_script( 'dataTables.buttons', plugin_dir_url( __FILE__ ) . '../admin/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min.js', array(), $this->version, false );
		wp_enqueue_script( 'buttons.bootstrap4', plugin_dir_url( __FILE__ ) . '../admin/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap4.min.js', array(), $this->version, false );
		wp_enqueue_script( 'buttons.html5', plugin_dir_url( __FILE__ ) . '../admin/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min.js"', array(), $this->version, false );
		wp_enqueue_script( 'buttons.print', plugin_dir_url( __FILE__ ) . '../admin/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min.js', array(), $this->version, false );
		wp_enqueue_script( 'jszip', plugin_dir_url( __FILE__ ) . '../admin/datatables/extras/TableTools/JSZip-2.5.0/jszip.min.js', array(), $this->version, false );
		wp_enqueue_script( 'pdfmake', plugin_dir_url( __FILE__ ) . '../admin/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js', array(), $this->version, false );
		wp_enqueue_script( 'vfs_fonts', plugin_dir_url( __FILE__ ) . '../admin/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js', array(), $this->version, false );
	}	


	
	public function my_login_logo() { 
		wp_enqueue_style( 'merch-stock-admin', plugin_dir_url( __FILE__ ) . '../admin/css/style-login.css', array(), $this->version, 'all' );
		?>	
		    <style type="text/css">
		        #login h1 a, .login h1 a {
		            background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'public/images/merchandise_logo_small.svg'; ?>');
		        }
		    </style>
		<?php 
	}		
}
