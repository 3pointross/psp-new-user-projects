<?php
add_filter( 'psp_settings_sections_addons', 'psp_auto_settings_section' );
add_filter( 'psp_settings_addons', 'psp_auto_settings' );

function psp_auto_settings_section( $sections ) {

    $sections['psp_auto_project_settings'] = __( 'New User Projects', 'psp-auto' );

    return $sections;

}

function psp_auto_settings( $settings ) {

    $psp_auto_settings['psp_auto_project_settings'] = array(
        'psp_auto_title'    =>  array(
            'id'    =>  'psp_auto_title',
            'name'  =>  '<h2>' . __( 'New User Project Generator', 'psp-auto' ) . '</h2>',
            'type'  =>  'html'
        ),
    );

    global $wp_roles;

    if ( ! isset( $wp_roles ) ) {
        $wp_roles = new WP_Roles();
    }

    $args = array(
        'post_type'         =>  'psp_projects',
        'posts_per_page'    =>  -1,
        'meta_key'          =>  '_psp_auto_template',
    );
    $all_projects = new WP_Query( $args );

    $project_options = array(
        'false' =>  __( 'None', 'psp-auto' ),
    );

    while( $all_projects->have_posts() ) {

        $all_projects->the_post();

        $project_options[get_the_ID()] = get_the_title();

    }

    foreach( $wp_roles->roles as $key => $name ) {

        $psp_auto_settings['psp_auto_project_settings'][ $key . '_project' ] = array(
            'id'    =>  $key . '_project',
            'name'  =>  $name['name'],
            'desc'   =>  __( 'Project to Clone' ),
            'type'  =>  'select',
            'options'   =>  $project_options
        );

    }

    return apply_filters( 'wp_auto_settings', array_merge( $settings, $psp_auto_settings ) );


}
