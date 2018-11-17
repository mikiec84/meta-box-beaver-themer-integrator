<?php
/**
 * Integrates Meta Box custom fields with Beaver Themer.
 *
 * @package    Meta Box
 * @subpackage Meta Box Beaver Themer Integrator
 */

/**
 * The plugin main class.
 */
class MBBTI_Posts extends MBBTI_Base {
	/**
	 * Parse settings to get field ID and object ID.
	 *
	 * @param  object $settings Themer settings.
	 * @return array            Field ID and object ID.
	 */
	public function parse_settings( $settings ) {
		return array( get_the_ID(), $settings->field );
	}

	/**
	 * Format list of fields to compatible with Beaver Themer's format.
	 *
	 * @param array $list List of fields, categoried by post type.
	 * @return array
	 */
	public function format( $list ) {
		$sources = array();

		if ( empty( $list ) ) {
			return $sources;
		}

		foreach ( $list as $post_type => $fields ) {
			$post_type_object = get_post_type_object( $post_type );
			$options          = array();
			foreach ( $fields as $field ) {
				$options[ $field['id'] ] = $field['name'] ? $field['name'] : $field['id'];
			}
			$sources[ $post_type ] = array(
				'label'   => $post_type_object->labels->singular_name,
				'options' => $options,
			);
		}

		return $sources;
	}

	/**
	 * Filter fields if neccessary.
	 * Remove fields for non-existing post types.
	 *
	 * @param  array $list List of fields, categoried by post type.
	 * @return array
	 */
	public function filter( $list ) {
		return array_filter( $list, 'post_type_exists', ARRAY_FILTER_USE_KEY );
	}
}
