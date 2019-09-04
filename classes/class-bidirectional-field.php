<?php

mai_guides_bidirectional_field( 'field_5d6ec36cc4267' );

function mai_guides_bidirectional_field( $key ) {
	new Mai_Guides_Bidirectional_Field( $key );
}

/**
 * Bi-directional fields!
 *
 * @version  0.1.0
 */
class Mai_Guides_Bidirectional_Field {

	protected $key;
	protected $hook;

	function __construct( $key ) {
		$this->key  = $key;
		$this->hook = sprintf( 'acf/update_value/key=%s', $this->key );
		$this->hooks();
	}

	function hooks() {
		add_filter( $this->hook, array( $this, 'update_value' ), 10, 3 );
	}

	function update_value( $value, $post_id, $field  ) {

		// Vars.
		$field_name = $field['name'];

		// Avoid infinite loop when updateing field.
		remove_filter( $this->hook, array( $this, 'update_value' ), 10, 3 );

		// Loop over selected posts and add this $post_id.
		if ( is_array( $value ) ) {

			foreach( $value as $post_id2 ) {

				// Load existing related posts.
				$value2 = get_field( $field_name, $post_id2, false );

				// Allow for selected posts to not contain a value.
				if ( empty( $value2 ) ) {
					$value2 = array();
				}

				// Skip if the current $post_id is already found in selected post's $value2.
				if ( in_array( $post_id, $value2 ) ) {
					continue;
				}

				// Append the current $post_id to the selected post's 'related_posts' value.
				$value2[] = $post_id;

				// Update the selected post's value (use field's key for performance).
				update_field( $this->key, $value2, $post_id2 );
			}

		}

		// Find posts which have been removed.
		$old_value = get_field( $field_name, $post_id, false );

		// Loop over selected posts and remove this $post_id.
		if ( is_array( $old_value ) ) {

			foreach( $old_value as $post_id2 ) {

				// Skip if this value has not been removed.
				if ( is_array( $value ) && in_array( $post_id2, $value ) ) {
					continue;
				}

				// Load existing related posts
				$value2 = get_field( $field_name, $post_id2, false );

				// Skip if no value.
				if ( empty( $value2 ) ) {
					continue;
				}

				// Find the position of $post_id within $value2 so we can remove it.
				$pos = array_search( $post_id, $value2 );

				// Remove.
				unset( $value2[ $pos ] );

				// Update the un-selected post's value (use field's key for performance).
				update_field( $this->key, $value2, $post_id2 );
			}

		}

		// Add back the to function as per normal.
		add_filter( $this->hook, array( $this, 'update_value' ), 10, 3 );

		// Return.
		return $value;
	}

}
