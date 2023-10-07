<?php

/**
 * @package   Flower Tree
 * @author    Khoi Tran <khoitq.1992@gmail.com>
 *
 * Plugin Name:     Flower Tree
 * Description:     Flower Tree presentation plugin
 * Version:         1.0
 * Author:          Khoi Tran
 * Text Domain:     flower-tree
 * Domain Path:     /languages
 * Requires PHP:    7.4
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'PN_VERSION', '1.0' );
define( 'PN_TEXTDOMAIN', 'flower-tree' );
define( 'PN_NAME', 'Flower Tree' );
define( 'PN_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'PN_PLUGIN_ABSOLUTE', __FILE__ );



function flower_tree_custom_new_menu() {
	register_nav_menu('flower-tree-menu',__( 'Flower tree' ));
}
add_action( 'init', 'flower_tree_custom_new_menu' );


// Add shortcode that displays Flower Tree menu
function pn_flower_tree_shortcode() {
    ob_start(); ?>

	<style>
		.flower-tree-menu {
			list-style: none;
            padding-left: 0;
			display: flex;
			gap: 50px;
		}
		.flower-tree-menu > li:first-child {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
		}
		.flower-tree-menu .sub-menu {
			display: flex;
			padding-left: 0;
			justify-content: center;
			gap: 50px;
			list-style: none;
			text-align: center;
		}
		.flower-thumbnail {
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.flower-spacing {
			min-height: 100px;
		}
		.flower-tree-menu .menu-item.hidden > .sub-menu {
			display: none;
		}
		.flower-tree-menu .flower-slider {
			transition: all 0.3s ease;
		}
		.flower-tree-menu .menu-item.hidden .flower-slider {
			transform: rotate(180deg);
			transform-origin: center center;
		}
		.flower-tree-menu .menu-item > a:first-child {
			display: none;
		}
		.flower-tree-menu .menu-item a {
			text-decoration: none ;
		}
		.flower-tree-menu .flower-name {
			display: flex; 
			align-items: center; 
			justify-content: center;
			cursor: pointer;
		}
		.flower-start-point {
			background: black;
			width: 1px;
			height: auto;
			aspect-ratio: 1;
			margin: auto;
		}
		.flower-end-point {
			background: gray;
			width: 1px;
			height: auto;
			aspect-ratio: 1;
			margin: auto;
		}
		canvas {
			pointer-events: none !important;
		}
	</style>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script>
		(function($) {
			class FlowerCanvas {
				// constructor
				constructor() {
                    this.canvas = document.createElement('canvas');
					this.canvas.classList.add('flower-relational');
					this.canvas.style.position = 'absolute';
					this.canvas.style.left = '0';
					this.canvas.style.top = '0';
					this.canvas.width = $(document).width();
					this.canvas.height = $(document).height();
					document.body.appendChild(this.canvas);
                }

				drawLineBetweenElements (startElement, endElement) {
					// Get the offset of the start and end elements
					const startOffset = startElement.offset();
					const endOffset = endElement.offset();

					// Calculate the start and end points
					const startPoint = {
						x: startOffset.left,
						y: startOffset.top
					};

					const endPoint = {
						x: endOffset.left,
						y: endOffset.top
					};

					const context = this.canvas.getContext('2d');
					context.strokeStyle = 'black'; // Set line color to black
					context.lineWidth = 2; // Set line width

					context.beginPath();
					context.moveTo(startPoint.x, startPoint.y); // Move to the start point
					context.lineTo(endPoint.x, endPoint.y); // Draw a line to the end point
					context.stroke(); // Stroke the line
				}

				drawRelationOfFlowerEle (flowerEle) {
					const startPoint = flowerEle.children(".flower-end-point");
					const submenu = flowerEle.children(".sub-menu");
					if (submenu.length > 0) {
						let endpoints = submenu.children("li").children(".flower-start-point");
						endpoints.each((index, element) => {
							this.drawLineBetweenElements(startPoint, $(element));
						})
					}
				}

				drawFlowerRelationship () {
					// get flower-tree-menu element
					const flowerTree = $(".flower-tree-menu");
					flowerTree.find(".menu-item").each((index, element) => {
						if (!$($(element)).hasClass("hidden")) {
							this.drawRelationOfFlowerEle($(element));
						}
					});
				}

				clearCanvas () {
					const context = this.canvas.getContext('2d');
                    context.clearRect(0, 0, this.canvas.width, this.canvas.height);
				}

				redrawFlowerRelationship() {
					document.body.removeChild(this.canvas);
					this.canvas = document.createElement('canvas');
					this.canvas.classList.add('flower-relational');
					this.canvas.style.position = 'absolute';
					this.canvas.style.left = '0';
					this.canvas.style.top = '0';
					this.canvas.width = $(document).width();
					this.canvas.height = $(document).height();
					document.body.appendChild(this.canvas);
                    this.drawFlowerRelationship();
				}
			}


			$(document).ready(function() {
				const flowerCanvas = new FlowerCanvas();
				flowerCanvas.redrawFlowerRelationship();

				$(".flower-name").click(function (e) {
					e.stopPropagation();
					$(this).parent().toggleClass('hidden');
					flowerCanvas.redrawFlowerRelationship();
				})

				$(window).resize(function() {
                    flowerCanvas.redrawFlowerRelationship();
                });
            });
		}
		)(jQuery)
	</script>

	<?php

	// render
	wp_nav_menu(
        array(
			'theme_location' => 'flower-tree-menu',
            'menu_class' => 'flower-tree-menu',
            'container' => true,
            'fallback_cb' => 'pn_fallback_cb',
        )
    );

	return ob_get_clean();
}
add_shortcode( 'flower_tree_display', 'pn_flower_tree_shortcode' );


// Activate the plugin and check for ACF plugin
function pn_plugin_activation() {
	if (function_exists('acf_add_local_field_group')) {

	} else {
		deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( 'The Flower Tree plugin requires the Advanced Custom Fields (ACF) plugin. Please install and activate ACF to use this plugin.' );
	}
}
register_activation_hook( __FILE__, 'pn_plugin_activation' );


// Deactivate the plugin when ACF plugin is deactivated
function pn_plugin_deactivation() {
}
register_deactivation_hook( __FILE__, 'pn_plugin_deactivation' );


// Register Custom Post Type
function register_flower_post_type() {
    $labels = array(
        'name'                  => 'Flowers',
        'singular_name'         => 'Flower',
        'menu_name'             => 'Flowers',
        'all_items'             => 'All Flowers',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Flower',
        'edit_item'             => 'Edit Flower',
        'new_item'              => 'New Flower',
        'view_item'             => 'View Flower',
        'search_items'          => 'Search Flowers',
        'not_found'             => 'No flowers found',
        'not_found_in_trash'    => 'No flowers found in Trash',
        'featured_image'        => 'Featured Image',
        'set_featured_image'    => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image'    => 'Use as featured image',
        'archives'              => 'Flower Archives',
        'insert_into_item'      => 'Insert into flower',
        'uploaded_to_this_item' => 'Uploaded to this flower',
        'filter_items_list'     => 'Filter flowers list',
        'items_list_navigation' => 'Flowers list navigation',
        'items_list'            => 'Flowers list',
    );
    $args = array(
        'label'                 => 'Flower',
        'description'           => 'Custom post type for flowers',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'public'                => true,
        'menu_icon'             => 'dashicons-palmtree', // You can change the icon
        'rewrite'               => array( 'slug' => 'flowers' ),
        'has_archive'           => true,
        'hierarchical'          => false,
    );
    register_post_type( 'flower', $args );
}
add_action( 'init', 'register_flower_post_type' );


/**
 *  Menu items - Add "Custom sub-menu" in menu item render output
 *  if menu item has class "menu-item-target"
 */
function wpdocs_flower_tree_item_custom_output( $item_output, $item, $depth, $args ) {
	$has_children = in_array("menu-item-has-children" ,$item->classes);

    if ( isset($args->theme_location) && $args->theme_location == "flower-tree-menu" ) {
		ob_start(); ?>

		<div class="flower-start-point"></div>
		<div class="flower-thumbnail"><?= get_the_post_thumbnail( $item->object_id, [ 100, 100] ) ?></div>
		<div class="flower-name">
			<a href="<?= get_the_permalink($item->object_id); ?>"><?= $item->title; ?> </a>
			<?php echo $has_children ? '<span class="flower-slider dashicons dashicons-arrow-down-alt2"></span>' : ""; ?>
		</div>
		<div class="flower-end-point"></div>
		<div class="flower-spacing"></div>

		<?php
		$item_output .= ob_get_clean();
    }

    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'wpdocs_flower_tree_item_custom_output', 10, 4 );