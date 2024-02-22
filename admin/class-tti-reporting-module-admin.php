<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ministryinsights.com/
 * @since      1.0.0
 *
 * @package    Tti_Reporting_Module
 * @subpackage Tti_Reporting_Module/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tti_Reporting_Module
 * @subpackage Tti_Reporting_Module/admin
 * @author     Ministry Insights <support@ministryinsights.com>
 */
class Tti_Reporting_Module_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Total Users.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $total_users    Total users on the site.
	 */
	private $total_users;

	/**
	 * Courses With steps inside.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $all_courses    All courses with steps inside.
	 */
	private $all_courses;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->data_slug   = 'user-courses';

		// $this->find_out_all_courses_with_steps();

		add_filter( 'learndash_data_reports_headers', array( $this, 'add_course_enrolled_date' ), 99, 2 );

		apply_filters( 'learndash_csv_data', $course_progress_data, $this->data_slug );

		add_filter( 'learndash_csv_data', array( $this, 'remove_course_not_enrolled_in_2023' ), 99, 2 );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tti-reporting-module-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_style(
			'learndash_style',
			LEARNDASH_LMS_PLUGIN_URL . 'assets/css/style' . learndash_min_asset() . '.css',
			array(),
			LEARNDASH_SCRIPT_VERSION_TOKEN
		);
		wp_style_add_data( 'learndash_style', 'rtl', 'replace' );
		$learndash_assets_loaded['styles']['learndash_style'] = __FUNCTION__;

		wp_enqueue_style(
			'sfwd-module-style',
			LEARNDASH_LMS_PLUGIN_URL . 'assets/css/sfwd_module' . learndash_min_asset() . '.css',
			array(),
			LEARNDASH_SCRIPT_VERSION_TOKEN
		);
		wp_style_add_data( 'sfwd-module-style', 'rtl', 'replace' );
		$learndash_assets_loaded['styles']['sfwd-module-style'] = __FUNCTION__;

		wp_enqueue_script(
			'learndash-admin-settings-data-reports-script',
			LEARNDASH_LMS_PLUGIN_URL . 'assets/js/learndash-admin-settings-data-reports' . learndash_min_asset() . '.js',
			array( 'jquery' ),
			LEARNDASH_SCRIPT_VERSION_TOKEN,
			true
		);
		$learndash_assets_loaded['scripts']['learndash-admin-settings-data-reports-script'] = __FUNCTION__;

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tti-reporting-module-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Method to add admin menu.
	 *
	 * @return void
	 */
	public function add_admin_menu() {

		add_menu_page(
			'TTI Reporting',
			'TTI Reporting',
			'manage_options',
			'tti-reporting-module',
			array( $this, 'render_admin_page' ),
			'dashicons-admin-generic',
			30
		);
	}

	/**
	 * Menu Content.
	 *
	 * @return void
	 */
	public function render_admin_page() {

		// $partial_path = plugin_dir_path( __FILE__ ) . 'partials/tti-reporting-module-admin-display.php';

		// if ( file_exists( $partial_path ) ) {
		// include $partial_path;
		// } else {
		// echo 'Admin page content partial file is missing.';
		// }
		?>

		<div id="learndash-settings" class="wrap learndash-settings-page-wrap">
			<h1 class="learndash-empty-page-title"></h1>
			<form method="post" action="options.php">
				<div id="poststuff">
					<div id="advanced-sortables" class="meta-box-sortables">
						<div id="sfwd-courses_metabox" class="postbox ld_settings_postbox learndash-settings-postbox">
							<div class="postbox-header">
								<h2 class="hndle ui-sortable-handle"><?php esc_html_e( 'User Reports', 'learndash' ); ?></h2>
							</div>
							<div class="inside">
								<div class="sfwd sfwd_options sfwd-courses_settings">
									<?php wp_nonce_field( 'learndash-data-reports-nonce-' . get_current_user_id(), 'learndash-data-reports-nonce' ); ?>
									<table id="learndash-data-reports" class="wc_status_table widefat" cellspacing="0">
										<tr id="learndash-data-reports-container-<?php echo esc_attr( $this->data_slug ); ?>" class="learndash-data-reports-container">
											<td class="learndash-data-reports-button-container" style="width: 20%">
												<button class="learndash-data-reports-button button button-primary" data-nonce="<?php echo esc_attr( wp_create_nonce( 'learndash-data-reports-' . $this->data_slug . '-' . get_current_user_id() ) ); ?>" data-slug="<?php echo esc_attr( $this->data_slug ); ?>">
												<?php
												printf(
												// translators: Export User Course Data Label.
													esc_html_x( 'Export User %s Data', 'Export User Course Data Label', 'learndash' ),
													LearnDash_Custom_Label::get_label( 'course' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
												);
												?>
												</button></td>
											<td class="learndash-data-reports-status-container" style="width: 80%">
												<div style="display:none;" class="meter learndash-data-reports-status">
													<div class="progress-meter">
														<span class="progress-meter-image"></span>
													</div>
													<div class="progress-label"></div>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
	}

	public function find_out_all_courses_with_steps() {

		global $wpdb;

		$query = "SELECT ID FROM $wpdb->posts WHERE post_type = 'sfwd-courses'";

		$course_ids = $wpdb->get_col( $query );

		foreach ( $course_ids as $course_id ) {
			$this->all_courses[ $course_id ]        = learndash_get_course_steps_count( $course_id );
			$this->all_courses[ '00' . $course_id ] = learndash_course_get_sections( $course_id );

		}

	}

	public function add_course_enrolled_date( $data_headers, $data_slug ) {

		$specific_key = 'course_completed';

		// New key-value pair to add
		$new_key   = 'course_enrolled_on';
		$new_value = array(
			'label'   => esc_html__( 'course_enrolled_on', 'learndash' ),
			'default' => '',
			'display' => array( $this, 'add_course_enrolled_date_value' ),
		);

		$keys               = array_keys( $data_headers );
		$specific_key_index = array_search( $specific_key, $keys );
		$before_keys        = array_slice( $keys, 0, $specific_key_index );
		$after_keys         = array_slice( $keys, $specific_key_index );

		$new_associative_array = array_merge(
			array_intersect_key( $data_headers, array_flip( $before_keys ) ),
			array( $new_key => $new_value ),
			array_intersect_key( $data_headers, array_flip( $after_keys ) )
		);

		return $new_associative_array;

	}

	public function add_course_enrolled_date_value( $column_value, $column_key, $report_item, $report_user ) {

		if ( ( property_exists( $report_item, 'post_id' ) ) && ( ! empty( $report_item->post_id ) ) ) {
			$course_id = absint( $report_item->post_id );
		} else {
			$course_id = 0;
		}

		if ( 'course_enrolled_on' === $column_key ) {
			return date( 'n/j/Y', intval( get_user_meta( $report_user->ID, 'course_' . $course_id . '_access_from', true ) ) );
		}

		return $column_value;

	}

	public function remove_course_not_enrolled_in_2023( $course_progress_data, $data_slug ) {

		if( $course_progress_data['course_enrolled_on']['label'] === 'course_enrolled_on' ) {
			return $course_progress_data;
		}

		foreach ( $course_progress_data as $key => $row ) {
			if ( strtotime( $row['course_enrolled_on'] ) < strtotime( '1/1/2023' ) || strtotime( $row['course_enrolled_on'] ) > strtotime( '1/1/2024' ) ) {
				unset( $course_progress_data[ $key ] );
			}
		}

		return $course_progress_data;

	}


}
