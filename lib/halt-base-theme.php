
<?php
use Halt\Utils;
/**
 * This class contains common setup intended to be used for all themes. 
 */
abstract class HaltBaseTheme {
  
  public function __construct() {
    add_action( 'init', array( $this, 'remove_emojis' ) );
    add_action( 'init', array( $this, 'adjust_acf_json' ) );
    add_filter( 'pings_open', '__return_false', PHP_INT_MAX );
		add_filter( 'wp_headers', array( $this, 'disable_pingbacks' ) );
    add_action( 'after_setup_theme', array( $this, 'basic_supports' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 100 );
    add_action( 'after_setup_theme', array( $this, 'soil_theme_supports' ) );
  }

  /**
   * Halt theme class must implement a function defining the 
   * body class that can be added to the HTML
   *
   * @param array $classes
   * @return void
   */
  abstract protected function body_class( $classes );

  /**
   * Add basic support theme supports
   *
   * @return void
   */
  public function basic_supports() {
    // Enable plugins to manage the document title
    // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
    add_theme_support('title-tag');
    // Enable post thumbnails
    // http://codex.wordpress.org/Post_Thumbnails
    // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
    // http://codex.wordpress.org/Function_Reference/add_image_size
    add_theme_support('post-thumbnails');
    // Enable HTML5 markup support
    // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);
    /**
     * Removes basic WP custom fields box
     * 
     * Since Halt uses ACF the rendering of the custom fields box is extra
     * overhead that can be removed to improve admin area performance
     */
    add_filter('acf/settings/remove_wp_meta_box', '__return_true');
  }
  
  /**
   * Enqueue theme assets
   *
   * @return void
   */
  public function enqueue_assets() {
    wp_enqueue_style('halt/css', Utils\assets('css/main.css'), false, null);
    wp_enqueue_script('halt/js', Utils\assets('js/main.js'), null, null);
  }
  
  /**
   * Enable features from Soil when plugin is activated
   * https://roots.io/plugins/soil/
   *
   * @return void
   */
  public function soil_theme_supports() {
    add_theme_support('soil-clean-up');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');
  }
  
  /**
   * Remove emoji asset enqueues
   *
   * @return void
   */
  public function remove_emojis() 
	{
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}
  /**
   * Disable pingbacks
   *
   * @param array $headers
   * @return array
   */
  public function disable_pingbacks( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
  }
  
  /**
   * Fix location of ACF local JSON.
   *
   * Since Halt does some surgery on the WordPress template locations, ACF looks in
   * the wrong location for the acf-json directory. We will fix this by manually
   * hooking into that functionality and attempting to save in the right spot.
   *
   * @param  string  $path
   * @return string
   */
  public function adjust_acf_json() {
    add_filter('acf/settings/save_json', function ($path) {
      $targetDir = get_template_directory().'/acf-json';
      return (file_exists($targetDir) && is_dir($targetDir)) ? $targetDir : $path;
    });
  }
}