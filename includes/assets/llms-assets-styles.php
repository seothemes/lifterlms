<?php
/**
 * The main LifterLMS Stylesheet Asset Definition list
 *
 * This file returns an array of stylesheet asset definition arrays.
 *
 * The array key of each definition is the asset's "handle" which
 * is used by both LifterLMS and WordPress to identify the asset
 * during registration and enqueue.
 *
 * The remaining items in each definition are optional and will be
 * automatically populated with default values. See `LLMS_Assets::get_defaults()`
 * for information on the default values of the asset.
 *
 * See `LLMS_Assets::get()` for full documentation on the properties
 * of an asset definition.
 *
 * @package LifterLMS/Assets
 *
 * @since 4.4.0
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

return array(

	// Core.
	'lifterlms-styles' => array(
		'file_name' => 'lifterlms',
	),
	'certificates'     => array(),

	// Vendor.
	'llms-iziModal'    => array(
		'file_name' => 'iziModal',
		'path'      => 'assets/vendor/izimodal',
		'version'   => '1.5.1',
		'rtl'       => false,
	),
	'webui-popover'    => array(
		'file_name' => 'jquery.webui-popover',
		'path'      => 'assets/vendor/webui-popover',
		'version'   => '1.2.15',
		'rtl'       => false,
	),

);
