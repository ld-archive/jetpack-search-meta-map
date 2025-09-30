<?php
// Plugin Name: Jetpack Search Meta Mapping
// Description: Maps multiple custom fields into Jetpack Search so they can be indexed.
//
// Version: 1.2
// Created: 2025-09-30
// Author: [Anthony Arblaster](https://github.com/aarblaster) for [The Lighting Design Archive](https://ld-archive.org)
//


add_action( 'save_post', 'my_update_custom_meta', 50 );

function my_update_custom_meta( $post_id ) {
    // Avoid autosaves and revisions
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;

    // Map custom fields to jetpack-search-meta0 through jetpack-search-meta9
    $fields = [
        'ld_name',
        'designer',
        'director',
        'venue',
        'producer',
        'performance_category',
        'associate_ld',
        'assistant_ld',
        'show_composer',
        'libretto'
    ];

    foreach ( $fields as $index => $field_key ) {
        $value = get_post_meta( $post_id, $field_key, true );

        if ( ! empty( $value ) ) {
            update_post_meta( $post_id, "jetpack-search-meta{$index}", $value );
        } else {
            delete_post_meta( $post_id, "jetpack-search-meta{$index}" );
        }
    }

    // Special mapping for venue_city -> geolocation_city
    $venue_city = get_post_meta( $post_id, 'venue_city', true );
    if ( ! empty( $venue_city ) ) {
        update_post_meta( $post_id, 'geolocation_city', $venue_city );
    } else {
        delete_post_meta( $post_id, 'geolocation_city' );
    }
}
