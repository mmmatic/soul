<?php
class Soul_Drawer_Navwalker extends Walker_Nav_Menu {

	protected $menu_items_with_grandchildren = [];

	public function walk( $elements, $max_depth, ...$args ) {
		// Preprocess to find all items that have grandchildren
		$children = [];

		foreach ( $elements as $e ) {
			if ( isset( $e->menu_item_parent ) && $e->menu_item_parent ) {
				$children[ $e->menu_item_parent ][] = $e;
			}
		}

		foreach ( $elements as $e ) {
			if ( isset( $children[ $e->ID ] ) ) {
				foreach ( $children[ $e->ID ] as $child ) {
					if ( isset( $children[ $child->ID ] ) ) {
						$this->menu_items_with_grandchildren[ $e->ID ] = true;
						break;
					}
				}
			}
		}

		return parent::walk( $elements, $max_depth, ...$args );
	}

	public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args = [], &$output = '' ) {
		$element->has_children = !empty( $children_elements[ $element->ID ] );
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		$indent = str_repeat( "\t", $depth );
		$classes = [];
		if ( $depth === 0 ) {
			$classes = ['drawer__dropdown'];
		} else {
			$classes[] = 'drawer__dropdown-list nav flex-column';
		}
		// Check if the parent has grandchildren
		if ( isset( $this->menu_items_with_grandchildren[ $args->menu->current_item->ID ] ) ) {
			$classes[] = 'has-submenu';
		}

		$output .= "\n$indent<ul class=\"" . esc_attr(implode(' ', $classes)) . "\">\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$hover_image = get_field('hover_image', $item->ID);
		$hover_image_url = $hover_image ? esc_url(wp_get_attachment_image_url($hover_image, 'full')) : '';

		$item_class = $depth === 0 ? 'drawer__item' : 'drawer__dropdown-item';
		$link_class = $depth === 0 ? 'drawer__link' : 'drawer__dropdown-link';
		$inner_class = $depth === 0 ? 'drawer__link-inner' : 'drawer__dropdown-link-inner';

		$classes = array_filter( (array) $item->classes );
		$classes[] = $item_class;
		$class_attribute = ' class="' . esc_attr( implode(' ', $classes) ) . '"';
		$data_hover_attr = $hover_image_url ? ' data-hover="' . $hover_image_url . '"' : '';

		$output .= '<li' . $class_attribute . '>';

		$has_children = !empty($item->has_children);
		$href = $has_children ? '#' : ( !empty($item->url) ? esc_url($item->url) : '#' );

		// If depth-1 and has children, use <h4> instead of <a>
		if ( $depth === 1 && $has_children ) {
			$output .= '<h4 class="drawer__dropdown-header"' . $data_hover_attr . '>';
			$output .= '<span class="' . esc_attr( $inner_class ) . '">' . esc_html( $item->title ) . '</span>';
			$output .= '</h4>';
		} else {
			$output .= '<a href="' . $href . '" class="' . esc_attr( $link_class ) . '"' . $data_hover_attr . '>';
			$output .= '<span class="' . esc_attr( $inner_class ) . '">' . esc_html( $item->title ) . '</span>';
			$output .= '</a>';
		}

		// Store current item so we know who the parent is in start_lvl
		$args->menu->current_item = $item;
	}

	public function end_el( &$output, $item, $depth = 0, $args = [] ) {
		$output .= "</li>\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = [] ) {
		$output .= str_repeat("\t", $depth) . "</ul>\n";
	}
}
