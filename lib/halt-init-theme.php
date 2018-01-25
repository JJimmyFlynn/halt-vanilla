<?php

class HaltTheme extends HaltBaseTheme {

  public function __construct() {
    parent::__construct();
    add_filter('body_class', array( $this, 'body_class' ));
    add_action( 'after_setup_theme', array( $this, 'halt_extras' ) );
  }
  
  /**
   * Define acceptable body classes
   *
   * @param array $classes
   * @return array
   */
  public function body_class($classes) {
  
    $allowed_classes = [
      'home',
      'single',
    ];
  
    $classes = array_intersect($classes, $allowed_classes);
  
    return $classes;
  }

  /**
   * Enable features from Halt Extras plugin
   *
   * @return void
   */
  public function halt_extras() {
    // Remove admin menu items
    add_theme_support('halt-menu', [
      'edit-comments.php',
    ]);
    // Remove dashboard metaboxes
    add_theme_support('halt-dashboard', [
      'dashboard_right_now',
      'dashboard_primary',
      'dashboard_secondary',
      'dashboard_quick_press',
    ]);
    // Clean up homepage edit page
    add_theme_support('halt-clean-homepage');
  }
}
// Go Go Gadget
new HaltTheme();