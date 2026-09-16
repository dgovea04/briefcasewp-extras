<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_AI_Upsell_Analytics {

	const SETTINGS_OPTION = 'bewia_ai_upsell_settings';
	const TABLE_NOTICE_TRANSIENT = 'bewia_ai_upsell_table_notice';
	const SCHEMA_NOTICE_TRANSIENT = 'bewia_ai_upsell_schema_notice';

	public function __construct() {
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'bewia_track_order_processed' ), 10, 3 );
		add_action( 'woocommerce_payment_complete', array( $this, 'confirm_order_revenue' ), 10, 1 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'confirm_order_revenue' ), 10, 1 );
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'copy_offer_identity_to_order_item' ), 10, 4 );
		add_action( 'admin_menu', array( $this, 'register_admin_page' ), 60 );
	}

	public static function get_allowed_events() {
		return array( 'shown', 'rendered', 'accepted', 'dismissed', 'failed' );
	}

	public static function get_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'bew_ai_upsell_logs';
	}

	public static function table_exists() {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) ) {
			return false;
		}

		$table_name = self::get_table_name();
		$exists     = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );

		return $exists === $table_name;
	}

	public static function column_exists( $column_name ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) || empty( $column_name ) ) {
			return false;
		}

		if ( ! self::table_exists() ) {
			return false;
		}

		$table_name = self::get_table_name();
		$column     = $wpdb->get_var( $wpdb->prepare( "SHOW COLUMNS FROM {$table_name} LIKE %s", $column_name ) );

		return $column === $column_name;
	}

	public static function ensure_table() {
		$table_exists            = self::table_exists();
		$required_columns = array( 'provider_source', 'campaign_key', 'variant_id', 'offer_id', 'confidence', 'customer_type', 'device_type', 'country', 'order_id' );
		$schema_complete = $table_exists;
		foreach ( $required_columns as $column ) {
			if ( ! $table_exists || ! self::column_exists( $column ) ) { $schema_complete = false; break; }
		}
		if ( $schema_complete ) {
			return true;
		}

		$created = self::create_table();

		if ( is_admin() ) {
			if ( ! $table_exists ) {
				set_transient(
					self::TABLE_NOTICE_TRANSIENT,
					$created ? 'created' : 'failed',
					MINUTE_IN_SECONDS * 5
				);
			} elseif ( $created ) {
				set_transient(
					self::SCHEMA_NOTICE_TRANSIENT,
					'analytics_schema_upgraded',
					MINUTE_IN_SECONDS * 5
				);
			}
		}

		return $created;
	}

	public static function create_table() {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) ) {
			return false;
		}

		$table_name      = self::get_table_name();
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			created_at datetime NOT NULL,
			session_id varchar(191) NOT NULL,
			user_id bigint(20) unsigned NULL,
			product_id bigint(20) unsigned NOT NULL,
			cart_total decimal(18,2) NOT NULL DEFAULT 0.00,
			layout varchar(50) NOT NULL DEFAULT '',
			mode varchar(50) NOT NULL DEFAULT '',
			provider_source varchar(50) NOT NULL DEFAULT '',
			campaign_key varchar(100) NOT NULL DEFAULT '',
			variant_id varchar(100) NOT NULL DEFAULT '',
			offer_id varchar(191) NOT NULL DEFAULT '',
			confidence decimal(5,4) NULL,
			customer_type varchar(30) NOT NULL DEFAULT '',
			device_type varchar(30) NOT NULL DEFAULT '',
			country varchar(10) NOT NULL DEFAULT '',
			event varchar(20) NOT NULL DEFAULT '',
			revenue decimal(18,2) NULL,
			order_id bigint(20) unsigned NULL,
			PRIMARY KEY  (id),
			KEY event (event),
			KEY product_id (product_id),
			KEY created_at (created_at)
		) {$charset_collate};";

		$upgrade_file = ABSPATH . 'wp-admin/includes/upgrade.php';

		if ( ! file_exists( $upgrade_file ) ) {
			return false;
		}

		require_once $upgrade_file;

		if ( ! function_exists( 'dbDelta' ) ) {
			return false;
		}

		dbDelta( $sql );

		$table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );

		return $table_exists === $table_name;
	}

	public static function log_event( $event, $data = array() ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) ) {
			return false;
		}

		$allowed_events = self::get_allowed_events();
		$event          = sanitize_key( $event );

		if ( ! in_array( $event, $allowed_events, true ) ) {
			return false;
		}

		if ( ! self::ensure_table() ) {
			return false;
		}

		$table_name = self::get_table_name();

		$defaults = array(
			'session_id' => self::get_session_id(),
			'user_id'    => get_current_user_id() ? absint( get_current_user_id() ) : null,
			'product_id' => 0,
			'cart_total' => self::get_cart_total(),
			'layout'     => '',
			'mode'       => '',
			'provider_source' => '',
			'campaign_key' => '',
			'variant_id' => '',
			'offer_id' => '',
			'confidence' => null,
			'customer_type' => '',
			'device_type' => '',
			'country' => '',
			'order_id' => null,
			'revenue'    => null,
		);

		$data   = wp_parse_args( is_array( $data ) ? $data : array(), $defaults );
		$event_window = isset( $data['event_window'] ) ? absint( $data['event_window'] ) : 0;

		if ( $event_window > 0 && self::event_exists_recently( $event, $data, $event_window ) ) {
			return true;
		}

		$insert = array(
			'created_at' => current_time( 'mysql' ),
			'session_id' => sanitize_text_field( (string) $data['session_id'] ),
			'user_id'    => null === $data['user_id'] ? null : absint( $data['user_id'] ),
			'product_id' => absint( $data['product_id'] ),
			'cart_total' => round( (float) $data['cart_total'], 2 ),
			'layout'     => sanitize_text_field( (string) $data['layout'] ),
			'mode'       => sanitize_text_field( (string) $data['mode'] ),
			'provider_source' => sanitize_text_field( (string) $data['provider_source'] ),
			'campaign_key' => sanitize_key( (string) $data['campaign_key'] ),
			'variant_id' => sanitize_key( (string) $data['variant_id'] ),
			'offer_id' => sanitize_key( (string) $data['offer_id'] ),
			'confidence' => null === $data['confidence'] ? null : max( 0, min( 1, (float) $data['confidence'] ) ),
			'customer_type' => sanitize_key( (string) $data['customer_type'] ),
			'device_type' => sanitize_key( (string) $data['device_type'] ),
			'country' => strtoupper( sanitize_text_field( (string) $data['country'] ) ),
			'event'      => $event,
			'revenue'    => null === $data['revenue'] ? null : round( (float) $data['revenue'], 2 ),
			'order_id'   => null === $data['order_id'] ? null : absint( $data['order_id'] ),
		);

		$formats = array( '%s', '%s', '%d', '%d', '%f', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%f', '%d' );

		if ( null === $insert['user_id'] ) {
			$insert['user_id'] = null;
		}

		if ( null === $insert['revenue'] ) {
			$insert['revenue'] = null;
		}

		$result = $wpdb->insert( $table_name, $insert, $formats );

		return false !== $result;
	}

	public static function event_exists_recently( $event, $data = array(), $window_seconds = 60 ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) ) {
			return false;
		}

		if ( ! self::ensure_table() ) {
			return false;
		}

		$table_name     = self::get_table_name();
		$session_id     = isset( $data['session_id'] ) ? sanitize_text_field( (string) $data['session_id'] ) : self::get_session_id();
		$product_id     = isset( $data['product_id'] ) ? absint( $data['product_id'] ) : 0;
		$window_seconds = absint( $window_seconds );

		if ( empty( $session_id ) || $product_id <= 0 || $window_seconds <= 0 ) {
			return false;
		}

		$query = $wpdb->prepare(
			"SELECT id
			FROM {$table_name}
			WHERE event = %s
				AND session_id = %s
				AND product_id = %d
				AND created_at >= DATE_SUB(%s, INTERVAL %d SECOND)
			ORDER BY id DESC
			LIMIT 1",
			sanitize_key( $event ),
			$session_id,
			$product_id,
			current_time( 'mysql' ),
			$window_seconds
		);

		$existing = $wpdb->get_var( $query );

		return ! empty( $existing );
	}

	public static function get_summary( $filters = array() ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) ) {
			return array();
		}

		if ( ! self::ensure_table() ) {
			return array(
				'shown'     => 0,
				'rendered'  => 0,
				'accepted'  => 0,
				'dismissed' => 0,
				'failed'    => 0,
				'revenue'   => 0.0,
			);
		}

		$table_name = self::get_table_name();
		$where      = self::build_where_sql( $filters );
		$rows       = $wpdb->get_results( "SELECT event, COUNT(*) AS total, COALESCE(SUM(CASE WHEN event = 'accepted' AND order_id IS NOT NULL THEN revenue ELSE 0 END), 0) AS revenue FROM {$table_name} {$where} GROUP BY event", ARRAY_A );
		$summary    = array(
			'shown'    => 0,
			'rendered' => 0,
			'accepted' => 0,
			'dismissed' => 0,
			'failed'   => 0,
			'revenue'  => 0.0,
		);

		if ( empty( $rows ) || ! is_array( $rows ) ) {
			return $summary;
		}

		foreach ( $rows as $row ) {
			$event = isset( $row['event'] ) ? sanitize_key( $row['event'] ) : '';

			if ( isset( $summary[ $event ] ) ) {
				$summary[ $event ] = absint( $row['total'] );
			}

			if ( 'accepted' === $event ) {
				$summary['revenue'] = isset( $row['revenue'] ) ? (float) $row['revenue'] : 0.0;
			}
		}

		return $summary;
	}

	public static function get_top_accepted_products( $limit = 5, $filters = array() ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) ) {
			return array();
		}

		if ( ! self::ensure_table() ) {
			return array();
		}

		$table_name = self::get_table_name();
		$limit      = absint( $limit );

		if ( $limit <= 0 ) {
			$limit = 5;
		}

		$where = self::build_where_sql( array_merge( $filters, array( 'event' => 'accepted' ) ) );
		$query = $wpdb->prepare(
			"SELECT product_id, COUNT(*) AS total, COALESCE(SUM(revenue), 0) AS revenue
			FROM {$table_name}
			{$where}
			GROUP BY product_id
			ORDER BY total DESC, revenue DESC
			LIMIT %d",
			$limit
		);

		$rows = $wpdb->get_results( $query, ARRAY_A );

		return is_array( $rows ) ? $rows : array();
	}

	public static function get_event_breakdown_by_product( $filters = array(), $limit = 25 ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) || ! self::ensure_table() ) {
			return array();
		}

		$table_name = self::get_table_name();
		$where      = self::build_where_sql( $filters );
		$limit      = absint( $limit );
		if ( $limit <= 0 ) {
			$limit = 25;
		}

		$query = $wpdb->prepare(
			"SELECT product_id,
				SUM(CASE WHEN event = 'shown' THEN 1 ELSE 0 END) AS shown,
				SUM(CASE WHEN event = 'rendered' THEN 1 ELSE 0 END) AS rendered,
				SUM(CASE WHEN event = 'accepted' THEN 1 ELSE 0 END) AS accepted,
				SUM(CASE WHEN event = 'dismissed' THEN 1 ELSE 0 END) AS dismissed,
				SUM(CASE WHEN event = 'failed' THEN 1 ELSE 0 END) AS failed,
				COALESCE(SUM(CASE WHEN event = 'accepted' AND order_id IS NOT NULL THEN revenue ELSE 0 END), 0) AS revenue
			FROM {$table_name}
			{$where}
			GROUP BY product_id
			ORDER BY accepted DESC, rendered DESC, shown DESC
			LIMIT %d",
			$limit
		);

		$rows = $wpdb->get_results( $query, ARRAY_A );

		return is_array( $rows ) ? $rows : array();
	}

	public static function get_dimension_breakdown( $dimension = 'layout', $filters = array() ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) || ! self::ensure_table() ) {
			return array();
		}

		$table_name = self::get_table_name();
		$allowed_dimensions = array( 'layout', 'mode', 'provider_source', 'campaign_key', 'variant_id', 'customer_type', 'device_type', 'country' );
		$dimension  = in_array( $dimension, $allowed_dimensions, true ) ? $dimension : 'layout';

		if ( 'provider_source' === $dimension && ! self::column_exists( 'provider_source' ) ) {
			return array();
		}

		$where      = self::build_where_sql( $filters );

		$rows = $wpdb->get_results(
			"SELECT {$dimension} AS dimension_value,
				SUM(CASE WHEN event = 'shown' THEN 1 ELSE 0 END) AS shown,
				SUM(CASE WHEN event = 'rendered' THEN 1 ELSE 0 END) AS rendered,
				SUM(CASE WHEN event = 'accepted' THEN 1 ELSE 0 END) AS accepted,
				SUM(CASE WHEN event = 'dismissed' THEN 1 ELSE 0 END) AS dismissed,
				SUM(CASE WHEN event = 'failed' THEN 1 ELSE 0 END) AS failed,
				COALESCE(SUM(CASE WHEN event = 'accepted' AND order_id IS NOT NULL THEN revenue ELSE 0 END), 0) AS revenue
			FROM {$table_name}
			{$where}
			GROUP BY {$dimension}
			ORDER BY accepted DESC, rendered DESC, shown DESC",
			ARRAY_A
		);

		return is_array( $rows ) ? $rows : array();
	}

	public static function get_filtered_events( $filters = array(), $limit = 500 ) {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) || ! self::ensure_table() ) {
			return array();
		}

		$table_name = self::get_table_name();
		$where      = self::build_where_sql( $filters );
		$limit      = absint( $limit );
		$select_provider_source = self::column_exists( 'provider_source' );
		if ( $limit <= 0 ) {
			$limit = 500;
		}

		$provider_source_sql = $select_provider_source ? 'provider_source' : "'' AS provider_source";

		$query = $wpdb->prepare(
			"SELECT created_at, session_id, user_id, product_id, cart_total, layout, mode, {$provider_source_sql}, campaign_key, variant_id, confidence, customer_type, device_type, country, event, revenue, order_id
			FROM {$table_name}
			{$where}
			ORDER BY created_at DESC, id DESC
			LIMIT %d",
			$limit
		);

		$rows = $wpdb->get_results( $query, ARRAY_A );

		return is_array( $rows ) ? $rows : array();
	}

	public function register_admin_page() {
		add_submenu_page(
			'bew-templates',
			esc_html__( 'AI Upsells', 'bew-extras' ),
			esc_html__( 'AI Upsells', 'bew-extras' ),
			'manage_options',
			'briefcasewp_ai_upsells',
			array( $this, 'render_admin_page' )
		);
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$this->render_table_status_notice();
		$this->render_schema_status_notice();
		$this->maybe_reset_analytics();
		$this->maybe_save_settings();
		$this->maybe_export_csv();

		$filters             = $this->get_analytics_filters();
		$summary             = self::get_summary( $filters );
		$top_products        = self::get_top_accepted_products( 5, $filters );
		$product_breakdown   = self::get_event_breakdown_by_product( $filters, 20 );
		$layout_breakdown    = self::get_dimension_breakdown( 'layout', $filters );
		$mode_breakdown      = self::get_dimension_breakdown( 'mode', $filters );
		$product_options     = $this->get_admin_product_options();
		$category_options    = $this->get_admin_category_options();
		$total_shown         = isset( $summary['shown'] ) ? absint( $summary['shown'] ) : 0;
		$total_rendered      = isset( $summary['rendered'] ) ? absint( $summary['rendered'] ) : 0;
		$total_accepted      = isset( $summary['accepted'] ) ? absint( $summary['accepted'] ) : 0;
		$total_dismissed     = isset( $summary['dismissed'] ) ? absint( $summary['dismissed'] ) : 0;
		$acceptance_rate     = $total_shown > 0 ? round( ( $total_accepted / $total_shown ) * 100, 2 ) : 0;
		$confirmed_revenue   = isset( $summary['revenue'] ) ? (float) $summary['revenue'] : 0.0;
		$saved_settings      = self::get_saved_settings();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'AI Smart Upsells', 'bew-extras' ); ?></h1>

			<h2><?php echo esc_html__( 'Default Upsell Settings', 'bew-extras' ); ?></h2>
			<p><?php echo esc_html__( 'These defaults are used by the AI upsell shortcode and can serve as a shared baseline for future admin-driven placements.', 'bew-extras' ); ?></p>

			<form method="post" action="">
				<?php wp_nonce_field( 'bewia_save_settings', 'bewia_settings_nonce' ); ?>
				<table class="form-table" style="max-width:920px;">
					<tbody>
						<tr>
							<th scope="row"><label for="bewia_candidate_products"><?php echo esc_html__( 'Candidate Products', 'bew-extras' ); ?></label></th>
							<td>
								<select id="bewia_candidate_products" name="bewia_settings[candidate_product_ids][]" multiple="multiple" size="12" style="min-width: 360px; max-width: 100%;">
									<?php foreach ( $product_options as $product_id => $product_label ) : ?>
										<option value="<?php echo esc_attr( $product_id ); ?>" <?php selected( in_array( (int) $product_id, array_map( 'intval', $saved_settings['candidate_product_ids'] ), true ) ); ?>><?php echo esc_html( $product_label ); ?></option>
									<?php endforeach; ?>
								</select>
								<p class="description"><?php echo esc_html__( 'Select the products to consider for upsells. Hold Ctrl/Cmd to choose multiple items.', 'bew-extras' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="bewia_required_cart_categories"><?php echo esc_html__( 'Required Cart Categories', 'bew-extras' ); ?></label></th>
							<td>
								<select id="bewia_required_cart_categories" name="bewia_settings[required_cart_category_ids][]" multiple="multiple" size="8" style="min-width: 360px; max-width: 100%;">
									<?php foreach ( $category_options as $category_id => $category_label ) : ?>
										<option value="<?php echo esc_attr( $category_id ); ?>" <?php selected( in_array( (int) $category_id, array_map( 'intval', $saved_settings['required_cart_category_ids'] ), true ) ); ?>><?php echo esc_html( $category_label ); ?></option>
									<?php endforeach; ?>
								</select>
								<p class="description"><?php echo esc_html__( 'Only show upsells when the cart contains at least one product from these categories.', 'bew-extras' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Cart Total Range', 'bew-extras' ); ?></th>
							<td>
								<input name="bewia_settings[minimum_cart_total]" type="number" min="0" step="0.01" class="small-text" value="<?php echo esc_attr( $saved_settings['minimum_cart_total'] ); ?>" />
								<span style="padding:0 8px;"><?php echo esc_html__( 'to', 'bew-extras' ); ?></span>
								<input name="bewia_settings[maximum_cart_total]" type="number" min="0" step="0.01" class="small-text" value="<?php echo esc_attr( $saved_settings['maximum_cart_total'] ); ?>" />
								<p class="description"><?php echo esc_html__( 'Leave maximum at 0 to allow any value above the minimum.', 'bew-extras' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="bewia_customer_status"><?php echo esc_html__( 'Logged-In Status', 'bew-extras' ); ?></label></th>
							<td>
								<select id="bewia_customer_status" name="bewia_settings[customer_status]">
									<?php foreach ( array( 'any' => __( 'Any Customer', 'bew-extras' ), 'logged_in' => __( 'Logged-In Only', 'bew-extras' ), 'guest' => __( 'Guest Only', 'bew-extras' ) ) as $value => $label ) : ?>
										<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $saved_settings['customer_status'], $value ); ?>><?php echo esc_html( $label ); ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="bewia_device_type"><?php echo esc_html__( 'Device Type', 'bew-extras' ); ?></label></th>
							<td>
								<select id="bewia_device_type" name="bewia_settings[device_type]">
									<?php foreach ( array( 'any' => __( 'Any Device', 'bew-extras' ), 'desktop' => __( 'Desktop Only', 'bew-extras' ), 'mobile' => __( 'Mobile Only', 'bew-extras' ) ) as $value => $label ) : ?>
										<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $saved_settings['device_type'], $value ); ?>><?php echo esc_html( $label ); ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="bewia_priority_scores"><?php echo esc_html__( 'Priority Scores', 'bew-extras' ); ?></label></th>
							<td>
								<textarea id="bewia_priority_scores" name="bewia_settings[priority_scores]" rows="5" class="large-text"><?php echo esc_textarea( $this->format_priority_scores_for_textarea( $saved_settings['priority_scores'] ) ); ?></textarea>
								<p class="description"><?php echo esc_html__( 'One product per line in product_id:score format. Example: 123:25', 'bew-extras' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
				<?php submit_button( __( 'Save Default Upsell Settings', 'bew-extras' ) ); ?>
			</form>

			<h2 style="margin-top:32px;"><?php echo esc_html__( 'Analytics', 'bew-extras' ); ?></h2>

			<form method="get" action="" style="margin:16px 0; display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
				<input type="hidden" name="page" value="briefcasewp_ai_upsells" />
				<div>
					<label for="bewia_date_from"><strong><?php echo esc_html__( 'From', 'bew-extras' ); ?></strong></label><br />
					<input type="date" id="bewia_date_from" name="date_from" value="<?php echo esc_attr( $filters['date_from'] ); ?>" />
				</div>
				<div>
					<label for="bewia_date_to"><strong><?php echo esc_html__( 'To', 'bew-extras' ); ?></strong></label><br />
					<input type="date" id="bewia_date_to" name="date_to" value="<?php echo esc_attr( $filters['date_to'] ); ?>" />
				</div>
				<div>
					<label for="bewia_event"><strong><?php echo esc_html__( 'Event', 'bew-extras' ); ?></strong></label><br />
					<select id="bewia_event" name="event">
						<option value=""><?php echo esc_html__( 'All events', 'bew-extras' ); ?></option>
						<?php foreach ( self::get_allowed_events() as $event ) : ?>
							<option value="<?php echo esc_attr( $event ); ?>" <?php selected( $filters['event'], $event ); ?>><?php echo esc_html( ucfirst( $event ) ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div>
					<label for="bewia_product_id"><strong><?php echo esc_html__( 'Product ID', 'bew-extras' ); ?></strong></label><br />
					<input type="number" min="0" id="bewia_product_id" name="product_id" value="<?php echo esc_attr( $filters['product_id'] ); ?>" />
				</div>
				<div>
					<?php submit_button( __( 'Apply Filters', 'bew-extras' ), 'secondary', '', false ); ?>
				</div>
				<div>
					<a class="button" href="<?php echo esc_url( add_query_arg( array_merge( $this->get_filter_query_args( $filters ), array( 'bewia_export_csv' => 1 ) ), admin_url( 'admin.php?page=briefcasewp_ai_upsells' ) ) ); ?>"><?php echo esc_html__( 'Export CSV', 'bew-extras' ); ?></a>
				</div>
			</form>

			<?php if ( $this->should_show_reset_analytics() ) : ?>
				<p>
					<a
						href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=briefcasewp_ai_upsells&bewia_reset_analytics=1' ), 'bewia_reset_analytics' ) ); ?>"
						class="button"
						onclick="return confirm('<?php echo esc_js( __( 'Reset all AI upsell analytics data?', 'bew-extras' ) ); ?>');"
					>
						<?php echo esc_html__( 'Reset Analytics', 'bew-extras' ); ?>
					</a>
				</p>
			<?php endif; ?>

			<table class="widefat striped" style="max-width:720px; margin-top:16px;">
				<tbody>
					<tr>
						<td><strong><?php echo esc_html__( 'Total upsells shown', 'bew-extras' ); ?></strong></td>
						<td><?php echo esc_html( $total_shown ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Total accepted', 'bew-extras' ); ?></strong></td>
						<td><?php echo esc_html( $total_accepted ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Total rendered', 'bew-extras' ); ?></strong></td>
						<td><?php echo esc_html( $total_rendered ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Total dismissed', 'bew-extras' ); ?></strong></td>
						<td><?php echo esc_html( $total_dismissed ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Acceptance rate', 'bew-extras' ); ?></strong></td>
						<td><?php echo esc_html( number_format_i18n( $acceptance_rate, 2 ) ); ?>%</td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Confirmed upsell revenue', 'bew-extras' ); ?></strong></td>
						<td><?php echo esc_html( wp_strip_all_tags( wc_price( $confirmed_revenue ) ) ); ?></td>
					</tr>
				</tbody>
			</table>

			<h2 style="margin-top:24px;"><?php echo esc_html__( 'Top Accepted Products', 'bew-extras' ); ?></h2>

			<table class="widefat striped" style="max-width:920px;">
				<thead>
					<tr>
						<th><?php echo esc_html__( 'Product', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Accepted', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Revenue', 'bew-extras' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $top_products ) ) : ?>
						<tr>
							<td colspan="3"><?php echo esc_html__( 'No upsell data available yet.', 'bew-extras' ); ?></td>
						</tr>
					<?php else : ?>
						<?php foreach ( $top_products as $row ) : ?>
							<?php
							$product_id   = isset( $row['product_id'] ) ? absint( $row['product_id'] ) : 0;
							$product      = $product_id ? wc_get_product( $product_id ) : false;
							$product_name = $product ? $product->get_name() : sprintf( __( 'Product #%d', 'bew-extras' ), $product_id );
							$total        = isset( $row['total'] ) ? absint( $row['total'] ) : 0;
							$revenue      = isset( $row['revenue'] ) ? (float) $row['revenue'] : 0.0;
							?>
							<tr>
								<td><?php echo esc_html( $product_name ); ?></td>
								<td><?php echo esc_html( $total ); ?></td>
								<td><?php echo esc_html( wp_strip_all_tags( wc_price( $revenue ) ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>

			<h2 style="margin-top:24px;"><?php echo esc_html__( 'Product Drilldown', 'bew-extras' ); ?></h2>
			<table class="widefat striped" style="max-width:1100px;">
				<thead>
					<tr>
						<th><?php echo esc_html__( 'Product', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Shown', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Rendered', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Accepted', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Dismissed', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Failed', 'bew-extras' ); ?></th>
						<th><?php echo esc_html__( 'Revenue', 'bew-extras' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $product_breakdown ) ) : ?>
						<tr><td colspan="7"><?php echo esc_html__( 'No filtered product data available yet.', 'bew-extras' ); ?></td></tr>
					<?php else : ?>
						<?php foreach ( $product_breakdown as $row ) : ?>
							<?php $this->render_product_breakdown_row( $row, $filters ); ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>

			<h2 style="margin-top:24px;"><?php echo esc_html__( 'Layout Breakdown', 'bew-extras' ); ?></h2>
			<?php $this->render_dimension_table( $layout_breakdown, __( 'Layout', 'bew-extras' ) ); ?>

			<h2 style="margin-top:24px;"><?php echo esc_html__( 'Mode Breakdown', 'bew-extras' ); ?></h2>
			<?php $this->render_dimension_table( $mode_breakdown, __( 'Mode', 'bew-extras' ) ); ?>

			<h2 style="margin-top:24px;"><?php echo esc_html__( 'Provider Breakdown', 'bew-extras' ); ?></h2>
			<?php $this->render_dimension_table( self::get_dimension_breakdown( 'provider_source', $filters ), __( 'Provider', 'bew-extras' ) ); ?>

			<h2 style="margin-top:24px;"><?php echo esc_html__( 'Variant Breakdown', 'bew-extras' ); ?></h2>
			<?php $this->render_dimension_table( self::get_dimension_breakdown( 'variant_id', $filters ), __( 'Variant', 'bew-extras' ) ); ?>
		</div>
		<?php
	}

	public static function get_saved_settings() {
		$saved = get_option( self::SETTINGS_OPTION, array() );
		return BEWIA_AI_Upsell_Engine::normalize_settings( is_array( $saved ) ? $saved : array() );
	}

	private function maybe_save_settings() {
		if ( ! isset( $_POST['bewia_settings_nonce'] ) || ! isset( $_POST['bewia_settings'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'bewia_save_settings', 'bewia_settings_nonce' );

		$raw_settings = wp_unslash( $_POST['bewia_settings'] );

		if ( ! is_array( $raw_settings ) ) {
			$raw_settings = array();
		}

		$settings = array(
			'candidate_product_ids'      => isset( $raw_settings['candidate_product_ids'] ) ? $raw_settings['candidate_product_ids'] : array(),
			'required_cart_category_ids' => isset( $raw_settings['required_cart_category_ids'] ) ? $raw_settings['required_cart_category_ids'] : array(),
			'minimum_cart_total'         => isset( $raw_settings['minimum_cart_total'] ) ? $raw_settings['minimum_cart_total'] : 0,
			'maximum_cart_total'         => isset( $raw_settings['maximum_cart_total'] ) ? $raw_settings['maximum_cart_total'] : 0,
			'customer_status'            => isset( $raw_settings['customer_status'] ) ? $raw_settings['customer_status'] : 'any',
			'device_type'                => isset( $raw_settings['device_type'] ) ? $raw_settings['device_type'] : 'any',
			'priority_scores'            => $this->parse_priority_scores_from_textarea( isset( $raw_settings['priority_scores'] ) ? $raw_settings['priority_scores'] : '' ),
		);

		update_option( self::SETTINGS_OPTION, BEWIA_AI_Upsell_Engine::normalize_settings( $settings ), false );

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'AI upsell settings saved.', 'bew-extras' ) . '</p></div>';
	}

	private function maybe_reset_analytics() {
		if ( ! $this->should_show_reset_analytics() ) {
			return;
		}

		if ( ! isset( $_GET['bewia_reset_analytics'] ) || '1' !== wp_unslash( $_GET['bewia_reset_analytics'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'bewia_reset_analytics' );

		$reset = self::reset_logs();

		if ( $reset ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'AI upsell analytics have been reset.', 'bew-extras' ) . '</p></div>';
		} else {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Unable to reset AI upsell analytics.', 'bew-extras' ) . '</p></div>';
		}
	}

	private function maybe_export_csv() {
		if ( ! isset( $_GET['bewia_export_csv'] ) || '1' !== wp_unslash( $_GET['bewia_export_csv'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$filters = $this->get_analytics_filters();
		$rows    = self::get_filtered_events( $filters, 5000 );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=bew-ai-upsells-' . gmdate( 'Y-m-d' ) . '.csv' );

		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'created_at', 'session_id', 'user_id', 'product_id', 'product_name', 'cart_total', 'layout', 'mode', 'provider_source', 'campaign_key', 'variant_id', 'confidence', 'customer_type', 'device_type', 'country', 'event', 'revenue', 'order_id' ) );

		foreach ( $rows as $row ) {
			$product_id   = isset( $row['product_id'] ) ? absint( $row['product_id'] ) : 0;
			$product      = $product_id ? wc_get_product( $product_id ) : false;
			$product_name = $product ? $product->get_name() : '';

			fputcsv(
				$output,
				array(
					isset( $row['created_at'] ) ? $row['created_at'] : '',
					isset( $row['session_id'] ) ? $row['session_id'] : '',
					isset( $row['user_id'] ) ? $row['user_id'] : '',
					$product_id,
					$product_name,
					isset( $row['cart_total'] ) ? $row['cart_total'] : '',
					isset( $row['layout'] ) ? $row['layout'] : '',
					isset( $row['mode'] ) ? $row['mode'] : '',
					isset( $row['provider_source'] ) ? $row['provider_source'] : '',
					isset( $row['campaign_key'] ) ? $row['campaign_key'] : '',
					isset( $row['variant_id'] ) ? $row['variant_id'] : '',
					isset( $row['confidence'] ) ? $row['confidence'] : '',
					isset( $row['customer_type'] ) ? $row['customer_type'] : '',
					isset( $row['device_type'] ) ? $row['device_type'] : '',
					isset( $row['country'] ) ? $row['country'] : '',
					isset( $row['event'] ) ? $row['event'] : '',
					isset( $row['revenue'] ) ? $row['revenue'] : '',
					isset( $row['order_id'] ) ? $row['order_id'] : '',
				)
			);
		}

		fclose( $output );
		exit;
	}

	public static function reset_logs() {
		global $wpdb;

		if ( ! isset( $wpdb ) || ! is_object( $wpdb ) ) {
			return false;
		}

		if ( ! self::ensure_table() ) {
			return false;
		}

		$table_name = self::get_table_name();
		$result     = $wpdb->query( "TRUNCATE TABLE {$table_name}" );

		return false !== $result;
	}

	public function bewia_track_order_processed( $order_id, $posted_data, $order ) {
		if ( ! $order_id || ! $order ) {
			return;
		}

		$accepted = isset( $_POST['bewia_ai_upsell_offers'] ) ? wp_unslash( $_POST['bewia_ai_upsell_offers'] ) : array();
		$accepted = is_array( $accepted ) ? array_map( array( __CLASS__, 'normalize_offer_identity' ), $accepted ) : array();
		if ( empty( $accepted ) && isset( $_POST['bewia_ai_upsell_ids'] ) ) {
			$accepted = array_map( function( $id ) { return self::normalize_offer_identity( array( 'product_id' => $id ) ); }, explode( ',', sanitize_text_field( wp_unslash( $_POST['bewia_ai_upsell_ids'] ) ) ) );
		}
		if ( ! empty( $accepted ) ) { $order->update_meta_data( '_bewia_ai_smart_upsells', wp_json_encode( $accepted ) ); }
		$order->save();
	}

	public static function normalize_offer_identity( $identity ) {
		$identity = is_array( $identity ) ? $identity : array();
		return array( 'product_id' => absint( isset( $identity['product_id'] ) ? $identity['product_id'] : 0 ), 'mode' => sanitize_key( isset( $identity['mode'] ) ? $identity['mode'] : '' ), 'provider_source' => sanitize_key( isset( $identity['provider_source'] ) ? $identity['provider_source'] : '' ), 'campaign_key' => sanitize_key( isset( $identity['campaign_key'] ) ? $identity['campaign_key'] : '' ), 'variant_id' => sanitize_key( isset( $identity['variant_id'] ) ? $identity['variant_id'] : '' ), 'session_id' => sanitize_text_field( isset( $identity['session_id'] ) ? $identity['session_id'] : '' ), 'offer_id' => sanitize_key( isset( $identity['offer_id'] ) ? $identity['offer_id'] : '' ), 'confidence' => isset( $identity['confidence'] ) ? max( 0, min( 1, (float) $identity['confidence'] ) ) : null );
	}
	public static function build_attribution_match( $identity ) { $identity = self::normalize_offer_identity( $identity ); return $identity['session_id'] . '|' . $identity['campaign_key'] . '|' . $identity['variant_id'] . '|' . $identity['offer_id']; }
	public static function is_confirmed_revenue_event( $row ) { return is_array( $row ) && 'accepted' === ( isset( $row['event'] ) ? $row['event'] : '' ) && ! empty( $row['order_id'] ) && null !== $row['revenue']; }

	public static function is_confirmable_order_status( $status ) { return in_array( sanitize_key( $status ), array( 'processing', 'completed' ), true ); }

	public static function get_confirmable_offer_lines( $items ) {
		$lines = array();
		foreach ( is_array( $items ) ? $items : array() as $item ) {
			$identity = isset( $item['identity'] ) ? self::normalize_offer_identity( $item['identity'] ) : array();
			if ( empty( $identity['product_id'] ) || empty( $item['line_total'] ) ) { continue; }
			$lines[] = array( 'identity' => $identity, 'revenue' => round( max( 0, (float) $item['line_total'] ), 2 ) );
		}
		return $lines;
	}

	public function copy_offer_identity_to_order_item( $item, $cart_item_key, $values, $order ) {
		if ( empty( $values['_bewia_offer_identity'] ) || ! is_array( $values['_bewia_offer_identity'] ) ) { return; }
		$item->add_meta_data( '_bewia_offer_identity', wp_json_encode( self::normalize_offer_identity( $values['_bewia_offer_identity'] ) ), true );
	}

	public function confirm_order_revenue( $order_id, $order = null ) {
		$order = $order ? $order : ( function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : false );
		if ( ! $order || ! self::is_confirmable_order_status( $order->get_status() ) || $order->get_meta( '_bewia_ai_revenue_confirmed' ) ) { return false; }
		foreach ( $order->get_items() as $item ) {
			$raw = $item->get_meta( '_bewia_offer_identity' );
			$identity = json_decode( (string) $raw, true );
			if ( ! is_array( $identity ) ) { continue; }
			$identity = self::normalize_offer_identity( $identity );
			$this->update_confirmed_revenue( $order_id, $identity, (float) $item->get_total() );
		}
		$order->update_meta_data( '_bewia_ai_revenue_confirmed', 'yes' ); $order->save();
		return true;
	}

	private function update_confirmed_revenue( $order_id, $identity, $revenue ) {
		global $wpdb; if ( ! self::ensure_table() ) { return false; }
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM " . self::get_table_name() . " WHERE event=%s AND product_id=%d AND session_id=%s AND campaign_key=%s AND variant_id=%s AND offer_id=%s AND order_id IS NULL ORDER BY id DESC LIMIT 1", 'accepted', $identity['product_id'], $identity['session_id'], $identity['campaign_key'], $identity['variant_id'], $identity['offer_id'] ), ARRAY_A );
		if ( empty( $row ) ) { return false; }
		return false !== $wpdb->update( self::get_table_name(), array( 'revenue' => round( max( 0, $revenue ), 2 ), 'order_id' => absint( $order_id ) ), array( 'id' => absint( $row['id'] ) ), array( '%f', '%d' ), array( '%d' ) );
	}

	public static function get_session_id() {
		if ( class_exists( 'WooCommerce' ) && function_exists( 'WC' ) ) {
			$wc = WC();

			if ( $wc && isset( $wc->session ) && is_object( $wc->session ) && method_exists( $wc->session, 'get_customer_id' ) ) {
				$customer_id = $wc->session->get_customer_id();

				if ( ! empty( $customer_id ) ) {
					return sanitize_text_field( (string) $customer_id );
				}
			}
		}

		$token = function_exists( 'wp_get_session_token' ) ? wp_get_session_token() : '';

		if ( ! empty( $token ) ) {
			return sanitize_text_field( (string) $token );
		}

		return sanitize_text_field( md5( (string) wp_rand() . '|' . microtime( true ) ) );
	}

	public static function get_cart_total() {
		if ( class_exists( 'WooCommerce' ) && function_exists( 'WC' ) ) {
			$wc = WC();

			if ( $wc && isset( $wc->cart ) && is_object( $wc->cart ) ) {
				return (float) $wc->cart->get_total( 'edit' );
			}
		}

		return 0.0;
	}

	private function get_analytics_filters() {
		return array(
			'date_from'  => isset( $_GET['date_from'] ) ? sanitize_text_field( wp_unslash( $_GET['date_from'] ) ) : '',
			'date_to'    => isset( $_GET['date_to'] ) ? sanitize_text_field( wp_unslash( $_GET['date_to'] ) ) : '',
			'event'      => isset( $_GET['event'] ) ? sanitize_key( wp_unslash( $_GET['event'] ) ) : '',
			'product_id' => isset( $_GET['product_id'] ) ? absint( wp_unslash( $_GET['product_id'] ) ) : 0,
		);
	}

	private function should_show_reset_analytics() {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	private function render_table_status_notice() {
		$notice = get_transient( self::TABLE_NOTICE_TRANSIENT );

		if ( empty( $notice ) ) {
			return;
		}

		delete_transient( self::TABLE_NOTICE_TRANSIENT );

		if ( 'created' === $notice ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'The AI upsell analytics table was missing and has been created automatically.', 'bew-extras' ) . '</p></div>';
			return;
		}

		if ( 'failed' === $notice ) {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'The AI upsell analytics table is unavailable and could not be created automatically. Analytics reporting may be incomplete until this is resolved.', 'bew-extras' ) . '</p></div>';
		}
	}

	private function render_schema_status_notice() {
		$notice = get_transient( self::SCHEMA_NOTICE_TRANSIENT );

		if ( empty( $notice ) ) {
			return;
		}

		delete_transient( self::SCHEMA_NOTICE_TRANSIENT );

		if ( in_array( $notice, array( 'provider_source_added', 'analytics_schema_upgraded' ), true ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'The AI upsell analytics schema was upgraded successfully.', 'bew-extras' ) . '</p></div>';
		}
	}

	private function get_filter_query_args( $filters ) {
		$args = array();

		foreach ( array( 'date_from', 'date_to', 'event', 'product_id' ) as $key ) {
			if ( empty( $filters[ $key ] ) ) {
				continue;
			}

			$args[ $key ] = $filters[ $key ];
		}

		return $args;
	}

	private static function build_where_sql( $filters = array() ) {
		global $wpdb;

		$where = array( '1=1' );

		if ( ! empty( $filters['date_from'] ) ) {
			$where[] = $wpdb->prepare( 'created_at >= %s', sanitize_text_field( $filters['date_from'] ) . ' 00:00:00' );
		}

		if ( ! empty( $filters['date_to'] ) ) {
			$where[] = $wpdb->prepare( 'created_at <= %s', sanitize_text_field( $filters['date_to'] ) . ' 23:59:59' );
		}

		if ( ! empty( $filters['event'] ) && in_array( sanitize_key( $filters['event'] ), self::get_allowed_events(), true ) ) {
			$where[] = $wpdb->prepare( 'event = %s', sanitize_key( $filters['event'] ) );
		}

		if ( ! empty( $filters['product_id'] ) ) {
			$where[] = $wpdb->prepare( 'product_id = %d', absint( $filters['product_id'] ) );
		}

		return 'WHERE ' . implode( ' AND ', $where );
	}

	private function render_product_breakdown_row( $row, $filters ) {
		$product_id   = isset( $row['product_id'] ) ? absint( $row['product_id'] ) : 0;
		$product      = $product_id ? wc_get_product( $product_id ) : false;
		$product_name = $product ? $product->get_name() : sprintf( __( 'Product #%d', 'bew-extras' ), $product_id );
		$drilldown_url = add_query_arg(
			array_merge(
				$this->get_filter_query_args( $filters ),
				array(
					'page'       => 'briefcasewp_ai_upsells',
					'product_id' => $product_id,
				)
			),
			admin_url( 'admin.php' )
		);
		?>
		<tr>
			<td><a href="<?php echo esc_url( $drilldown_url ); ?>"><?php echo esc_html( $product_name ); ?></a></td>
			<td><?php echo esc_html( absint( $row['shown'] ) ); ?></td>
			<td><?php echo esc_html( absint( $row['rendered'] ) ); ?></td>
			<td><?php echo esc_html( absint( $row['accepted'] ) ); ?></td>
			<td><?php echo esc_html( absint( $row['dismissed'] ) ); ?></td>
			<td><?php echo esc_html( absint( $row['failed'] ) ); ?></td>
			<td><?php echo esc_html( wp_strip_all_tags( wc_price( (float) $row['revenue'] ) ) ); ?></td>
		</tr>
		<?php
	}

	private function render_dimension_table( $rows, $label ) {
		?>
		<table class="widefat striped" style="max-width:1100px;">
			<thead>
				<tr>
					<th><?php echo esc_html( $label ); ?></th>
					<th><?php echo esc_html__( 'Shown', 'bew-extras' ); ?></th>
					<th><?php echo esc_html__( 'Rendered', 'bew-extras' ); ?></th>
					<th><?php echo esc_html__( 'Accepted', 'bew-extras' ); ?></th>
					<th><?php echo esc_html__( 'Dismissed', 'bew-extras' ); ?></th>
					<th><?php echo esc_html__( 'Failed', 'bew-extras' ); ?></th>
					<th><?php echo esc_html__( 'Revenue', 'bew-extras' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $rows ) ) : ?>
					<tr><td colspan="7"><?php echo esc_html__( 'No data available for this breakdown.', 'bew-extras' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $rows as $row ) : ?>
						<tr>
							<td><?php echo esc_html( ! empty( $row['dimension_value'] ) ? $row['dimension_value'] : __( 'Unspecified', 'bew-extras' ) ); ?></td>
							<td><?php echo esc_html( absint( $row['shown'] ) ); ?></td>
							<td><?php echo esc_html( absint( $row['rendered'] ) ); ?></td>
							<td><?php echo esc_html( absint( $row['accepted'] ) ); ?></td>
							<td><?php echo esc_html( absint( $row['dismissed'] ) ); ?></td>
							<td><?php echo esc_html( absint( $row['failed'] ) ); ?></td>
							<td><?php echo esc_html( wp_strip_all_tags( wc_price( (float) $row['revenue'] ) ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	private function parse_priority_scores_from_textarea( $raw_value ) {
		$priority_scores = array();
		$lines           = preg_split( '/[\r\n,]+/', (string) $raw_value );

		if ( empty( $lines ) || ! is_array( $lines ) ) {
			return $priority_scores;
		}

		foreach ( $lines as $line ) {
			$line = trim( $line );

			if ( '' === $line || false === strpos( $line, ':' ) ) {
				continue;
			}

			list( $product_id, $score ) = array_map( 'trim', explode( ':', $line, 2 ) );
			$product_id = absint( $product_id );

			if ( $product_id <= 0 ) {
				continue;
			}

			$priority_scores[ $product_id ] = (float) $score;
		}

		return $priority_scores;
	}

	private function format_priority_scores_for_textarea( $priority_scores ) {
		$lines = array();

		if ( ! is_array( $priority_scores ) ) {
			return '';
		}

		foreach ( $priority_scores as $product_id => $score ) {
			$product_id = absint( $product_id );

			if ( $product_id <= 0 ) {
				continue;
			}

			$lines[] = $product_id . ':' . (float) $score;
		}

		return implode( "\n", $lines );
	}

	private function get_admin_product_options() {
		$options = array();

		if ( ! function_exists( 'wc_get_products' ) ) {
			return $options;
		}

		$products = wc_get_products(
			array(
				'limit'   => 500,
				'status'  => 'publish',
				'orderby' => 'title',
				'order'   => 'ASC',
				'return'  => 'objects',
			)
		);

		if ( empty( $products ) || ! is_array( $products ) ) {
			return $options;
		}

		foreach ( $products as $product ) {
			if ( ! $product || ! method_exists( $product, 'get_id' ) ) {
				continue;
			}

			$options[ $product->get_id() ] = sprintf( '%s (#%d)', $product->get_name(), $product->get_id() );
		}

		return $options;
	}

	private function get_admin_category_options() {
		$options = array();

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return $options;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => 200,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			if ( empty( $term->term_id ) ) {
				continue;
			}

			$options[ absint( $term->term_id ) ] = sprintf( '%s (#%d)', $term->name, $term->term_id );
		}

		return $options;
	}
}

new BEWIA_AI_Upsell_Analytics();
