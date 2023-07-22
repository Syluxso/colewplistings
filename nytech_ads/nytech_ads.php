<?php
/*
Plugin Name: NyTech Plugin
Plugin URI: https://www.nybergtechnology.com
Description: A simple plugin to integrate with the Nyberg Technology Ad Engine created for Cole Publishing.
Version: 1.0
Author: Darryn Nyberg
Author URI: https://www.nybergtechnology.com
License: GPL2
*/

/*
 * Basic security code block.
 */
if(!defined('ABSPATH')) {
  exit;
}

// Register rewrite rule for custom path
function nytech_ads_add_rewrite_rule() {
  add_rewrite_rule(
    '^listings/?([^/]+)/([^/]+)/?$',
    'custom-page/?nytech_listing=1&remote_id=$matches[1]&title=$matches[2]',
    'top'
  );
}
add_action( 'init', 'nytech_ads_add_rewrite_rule', 10, 0);

// Register query variable for custom path
function nytech_ads_register_query_var( $vars ) {
  $vars[] = 'nytech_listing';
  $vars[] = 'remote_id';
  $vars[] = 'title';
  return $vars;
}
add_filter( 'query_vars', 'nytech_ads_register_query_var' );

// Filter the main query to display custom content at the custom path
function nytech_ads_filter_main_query( $query ) {
//  var_dump($query); exit;
}
add_action( 'pre_get_posts', 'nytech_ads_filter_main_query' );

// Modify the page title for the custom path
function nytech_ads_modify_page_title( $title ) {
  if ( get_query_var( 'nytech_listing' ) ) {
    $remote_id = get_query_var( 'remote_id' );
    $custom_title = get_query_var( 'title' );
    $title = $custom_title . ' - Listing ' . $remote_id;
  }
  return $title;
}
add_filter( 'pre_get_document_title', 'nytech_ads_modify_page_title' );


// Render custom content when the custom content query var is set
function nytech_ads_plugin_render_content() {

  if ( get_query_var( 'nytech_listing' ) ) {
    $remote_id = get_query_var( 'remote_id' );
    $data = nytech_ads_get_listings_single($remote_id);
//    var_dump($data);
    nytech_listing_single_template($data);
  }
}
//add_action('template_redirect', 'nytech_ads_plugin_render_content');
// Add custom meta boxes for the custom fields
function nytech_ads_add_custom_meta_boxes() {
  add_meta_box(
    'nytech_ads_custom_fields',
    'NYTech Listing Custom Fields',
    'nytech_ads_render_custom_fields',
    'nytech_listing',
    'normal',
    'default'
  );
}
add_action('add_meta_boxes', 'nytech_ads_add_custom_meta_boxes');

// Render the custom fields in the meta box
function nytech_ads_render_custom_fields($post) {
  // Retrieve the existing values for the fields, if any
  $remote_id = get_post_meta($post->ID, 'remote_id', true);
  $status = get_post_meta($post->ID, 'status', true);
  ?>
  <label for="nytech_remote_id">Remote ID:</label>
  <input type="text" id="nytech_remote_id" name="nytech_remote_id" value="<?php echo esc_attr($remote_id); ?>" style="width:100%;" />

  <br />

  <label for="nytech_status">Status:</label>
  <input type="text" id="nytech_status" name="nytech_status" value="<?php echo esc_attr($status); ?>" style="width:100%;" />
  <?php
}

// Save the custom field values when the post is saved
function nytech_ads_save_custom_fields($post_id) {
  if (isset($_POST['nytech_remote_id'])) {
    update_post_meta($post_id, 'remote_id', sanitize_text_field($_POST['nytech_remote_id']));
  }
  if (isset($_POST['nytech_status'])) {
    update_post_meta($post_id, 'status', sanitize_text_field($_POST['nytech_status']));
  }
}
add_action('save_post_nytech_listing', 'nytech_ads_save_custom_fields');

// Flush rewrite rules when the plugin is activated or deactivated
function nytech_ads_flush_rewrite_rules() {
  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'nytech_ads_flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'nytech_ads_flush_rewrite_rules');


class NyTechPlugin {
  public static function uninstall() {
    $posts = get_posts([
      'post_type' => 'nytech_listing',
      'numberposts' => -1,
    ]);
    foreach($posts as $post) {
      wp_delete_post($post->ID, false);
    }
  }
}
$NyTechPlugin = new NyTechPlugin();
register_uninstall_hook( __FILE__,    ['NyTechPlugin', 'uninstall' ]);


// Create the shortcode function
function nytech_ads_shortcode_active() {
  ob_start(); // Start output buffering
  $data = nytech_ads_get_listings();
  if(!empty($data)) {
    foreach($data as $listing) {
      nytech_listing_template($listing);
    }
  }
  return ob_get_clean(); // Return the buffered content
}

function nytech_ads_shortcode_completed() {
  ob_start(); // Start output buffering
  $data = nytech_ads_get_listings_completed();
  foreach($data as $listing) {
    nytech_listing_template($listing);
  }
  return ob_get_clean(); // Return the buffered content
}

function nytech_ads_shortcode_sold() {
  ob_start(); // Start output buffering
  $data = nytech_ads_get_listings_sold();
  foreach($data as $listing) {
    nytech_listing_template($listing);
  }
  return ob_get_clean(); // Return the buffered content
}

function nytech_ads_shortcode_listing_single($ad_id, $atts = false) {
  ob_start(); // Start output buffering
  $data = nytech_ads_get_listings_single($ad_id);
  // Extract shortcode attributes
  extract(shortcode_atts(array(
    'nytech_ads_id' => '',
    'nytech_ads_title' => ''
  ), $atts));

  // Output the values of the URL variables
  nytech_listing_single_template($data);
  return ob_get_clean(); // Return the buffered content
}
add_shortcode('bikeexif_listings_active', 'nytech_ads_shortcode_active');
add_shortcode('bikeexif_listings_completed', 'nytech_ads_shortcode_completed');
add_shortcode('bikeexif_listings_sold', 'nytech_ads_shortcode_sold');

function nytech_listing_template($ad, $zebra = 'odd') {
  include plugin_dir_path(__FILE__) . 'templates/listing.php';
}

function nytech_listing_single_template($ad) {
  include plugin_dir_path(__FILE__) . 'templates/listing_single.php';
}

// Enqueue CSS file
function nytech_ads_enqueue_styles() {
  $nocache = rand(1, 999999);
  wp_enqueue_style( 'nytech-ads-styles', plugins_url( 'css/styles.css?ver=' . $nocache, __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'nytech_ads_enqueue_styles' );

function nytech_ads_get_listings() {
  $api = new NyTechAPIStatus;
  $response = wp_remote_get($api->url . '/api/v1/listings/current');
  if (is_wp_error($response)) {
    return;
  }
  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

function nytech_ads_get_listings_completed() {
  $api = new NyTechAPIStatus;
  $response = wp_remote_get($api->url . '/api/v1/listings/completed');
  if (is_wp_error($response)) {
    return;
  }
  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

function nytech_ads_get_listings_sold() {
  $api = new NyTechAPIStatus;
  $response = wp_remote_get($api->url . '/api/v1/listings/sold');
  if (is_wp_error($response)) {
    return;
  }
  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

function nytech_ads_get_listings_single($ad_id) {
  $api = new NyTechAPIStatus;
  $response = wp_remote_get($api->url . '/api/v1/listings/' . $ad_id . '/view');
  if (is_wp_error($response)) {
    return;
  }
  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

// Add menu item to the admin menu
function nytech_ads_add_menu_item() {
  add_menu_page(
    'NyTech Ads Settings',
    'NyTech Ads',
    'manage_options',
    'nytech-ads',
    'nytech_ads_settings_page',
    'dashicons-megaphone',
    99
  );
}
add_action('admin_menu', 'nytech_ads_add_menu_item');

// Display the settings page
function nytech_ads_settings_page() {
  if (!current_user_can('manage_options')) {
    return;
  }
  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
      <?php
      settings_fields('nytech_ads_settings');
      do_settings_sections('nytech-ads');
      submit_button('Save Settings');
      ?>
    </form>
  </div>
  <?php
}

// Register and initialize the settings
function nytech_ads_register_settings() {
  register_setting('nytech_ads_settings', 'nytech_ads_options');
  add_settings_section(
    'nytech_ads_general_section',
    'General Settings',
    'nytech_ads_general_section_callback',
    'nytech-ads'
  );
  add_settings_field(
    'live_listing_url_root',
    'Live API Path',
    'nytech_ads_live_listing_url_root_callback',
    'nytech-ads',
    'nytech_ads_general_section'
  );
  add_settings_field(
    'dev_listing_url_root',
    'Dev API Path',
    'nytech_ads_dev_listing_url_root_callback',
    'nytech-ads',
    'nytech_ads_general_section'
  );
  add_settings_field(
    'local_listing_url_root',
    'Local API Path',
    'nytech_ads_local_listing_url_root_callback',
    'nytech-ads',
    'nytech_ads_general_section'
  );
  add_settings_field(
    'nytech_ads_status',
    'Ads API Status',
    'nytech_ads_status_callback',
    'nytech-ads',
    'nytech_ads_general_section'
  );
}
add_action('admin_init', 'nytech_ads_register_settings');

// Callback function for the general section
function nytech_ads_general_section_callback() {
  echo '<p>General settings for NyTech Ads.</p>';
}

// Callback function for the test listing URL root field
function nytech_ads_dev_listing_url_root_callback() {
  $options = get_option('nytech_ads_options');
  $dev_listing_url_root = isset($options['dev_listing_url_root']) ? esc_attr($options['dev_listing_url_root']) : '';
  echo '<input type="text" name="nytech_ads_options[dev_listing_url_root]" value="' . $dev_listing_url_root . '" />';
}

// Callback function for the live listing URL root field
function nytech_ads_live_listing_url_root_callback() {
  $options = get_option('nytech_ads_options');
  $live_listing_url_root = isset($options['live_listing_url_root']) ? esc_attr($options['live_listing_url_root']) : '';
  echo '<input type="text" name="nytech_ads_options[live_listing_url_root]" value="' . $live_listing_url_root . '" />';
}

// Callback function for the local listing URL root field
function nytech_ads_local_listing_url_root_callback() {
  $options = get_option('nytech_ads_options');
  $local_listing_url_root = isset($options['local_listing_url_root']) ? esc_attr($options['local_listing_url_root']) : '';
  echo '<input type="text" name="nytech_ads_options[local_listing_url_root]" value="' . $local_listing_url_root . '" />';
}

// Callback function for the ads status field
function nytech_ads_status_callback() {
  $options = get_option('nytech_ads_options');
  $ads_status = isset($options['nytech_ads_status']) ? esc_attr($options['nytech_ads_status']) : '';
  ?>
  <select name="nytech_ads_options[nytech_ads_status]">
    <option value="live" <?php selected($ads_status, 'live'); ?>>Live</option>
    <option value="dev" <?php selected($ads_status, 'dev'); ?>>Dev</option>
    <option value="local" <?php selected($ads_status, 'local'); ?>>Local</option>
  </select>
  <?php
}


class NyTechAPIStatus {
  public $status;
  public $url;
  public $options;

  function __construct() {
    $this->settings();
    $this->url();
  }

  private function settings() {
    $this->options = get_option('nytech_ads_options');
    $this->status = isset($this->options['nytech_ads_status']) ? esc_attr($this->options['nytech_ads_status']) : '';

  }

  private function url() {
    switch($this->status) {
      case 'live':
        $url = isset($this->options['live_listing_url_root'])  ? esc_attr($this->options['live_listing_url_root']) : '';
        break;
      case 'dev':
        $url = isset($this->options['dev_listing_url_root'])   ? esc_attr($this->options['dev_listing_url_root'])   : '';
        break;
      case 'local':
        $url = isset($this->options['local_listing_url_root']) ? esc_attr($this->options['local_listing_url_root']) : '';
        break;
      default:
        $url = isset($this->options['local_listing_url_root']) ? esc_attr($this->options['local_listing_url_root']) : '';
        break;
    }
    $this->url = $url;
  }
}

class NyTechCleanUrl {
  public $string;
  function __construct($string) {
    $this->string = $string;
    $this->clean($string);
  }

  function clean($string, $separator = '-') {
    $string = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', strtolower($string));
    $string = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $string);
    $this->url = trim($string, $separator);
  }
}

// Load custom templates from the plugin
function nytech_ads_load_custom_templates($template) {
  if (is_singular('nytech_listing')) {
    $custom_template = plugin_dir_path(__FILE__) . 'templates/single-nytech_listing.php';
    if (file_exists($custom_template)) {
      return $custom_template;
    }
  }
  return $template;
}
add_filter('single_template', 'nytech_ads_load_custom_templates');

// Register the custom page template
function nytech_ads_register_custom_page() {
    $page_template = 'nytech-ads-template.php'; // Change this to your actual template file name
    $page_slug = 'my-ads'; // Change this to your desired page slug

    $page_check = get_page_by_path($page_slug);

    if (!$page_check) {
        $page_data = array(
            'post_type'     => 'page',
            'post_name'     => $page_slug,
            'post_title'    => 'Listings',
            'post_status'   => 'publish',
            'post_content'  => '',
            'post_author'   => 1,
            'page_template' => $page_template,
        );

        wp_insert_post($page_data);
    }
}

register_activation_hook(__FILE__, 'nytech_ads_register_custom_page');

// Register the custom page template
function nytech_ads_register_custom_template($templates) {
    $templates[plugin_dir_path(__FILE__) . 'templates/nytech-ads-template.php'] = 'Nytech Ads Template';
    return $templates;
}

add_filter('theme_page_templates', 'nytech_ads_register_custom_template');

/**
 * Change the page template to the selected template on the dropdown
 *
 * @param $template
 *
 * @return mixed
 */
function nytech_ads_change_page_template($template) {
    if (is_page()) {
        $meta = get_post_meta(get_the_ID());

        if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {
            $template = $meta['_wp_page_template'][0];
        }
    }

    return $template;
}

add_filter( 'template_include', 'nytech_ads_change_page_template', 99 );
