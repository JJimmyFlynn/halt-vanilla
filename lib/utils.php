<?php
/**
 * 
 * Contains utilility functions for working with Halt and/or WordPress
 * 
 */
namespace Halt\Utils;


/**
 * Gets the url of an image based on its ID and size requested.
 * Useful for working with ACF images
 * @param  int $imageId   The ID of the image requested (from get_field/get_sub_field)
 * @param  string $imageSize The name of the image size requested, defaults to the master image
 * @return string            The url of the image in the requested size
 */
function imageSizeFromAcf($imageId, $imageSize = '') {
  // Get the array of available images for the image ID and size
  $imageArray = wp_get_attachment_image_src($imageId, $imageSize);
  // Grab the url for the requested image size
  $imageUrl = $imageArray[0];

  return $imageUrl;
}

/**
 * Returns the path to theme/dist
 * @return String
 */
function assets($path, $uri=true) {
  if ($uri) {
    return trailingslashit(get_stylesheet_directory_uri()).'dist/'.$path;
  }
  return trailingslashit(get_stylesheet_directory()).'dist/'.$path;
}
add_filter('assets', __NAMESPACE__ . '\\assets');