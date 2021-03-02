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

    $psp_auto_settings['psp_auto_project_settings']['psp_new_user_project'] = array(
         'id'       =>   'psp_new_user_projects',
         'name'     =>   __( 'Custom Conditions', 'psp_projects' ),
         'desc'     =>   __( 'Add a Condition' ),
         'type'     =>   'new_user_project_condition',
    );


    return apply_filters( 'wp_auto_settings', array_merge( $settings, $psp_auto_settings ) );


}

function psp_new_user_project_condition_callback( $args ) {

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
     ); ?>

     <div class="psp-new-project-existing">
          <?php
          $conditions = get_option( 'psp_nup_conditions', array() );
          if( !empty($conditions) ): foreach( $conditions as $condition ):
               $rolename = '';
               foreach( $wp_roles->roles as $key => $name ):
                    if( $key == $condition['role'] ) {
                         $rolename = $name['name'];
                    }
               endforeach; ?>
               <div class="psp-new-project-cond">
                    <span class="conditions">
                         <span class="user-role"><?php echo esc_html( $rolename ); ?></span>
                         <span class="project"><a href="<?php echo esc_url( get_the_permalink($condition['project']) ); ?>"><?php echo esc_html( get_the_title($condition['project']) ); ?></a></span>
                    </span>
                    <button class="js-remove-cond">Remove</button>
               </div>
          <?php endforeach; endif; ?>
     </div>

     <div class="psp-new-project-conditions">
          <div class="psp-new-project-conditions__role">
               <label for="psp-role"><?php esc_html_e( 'User Role', 'psp_projects' ); ?></label>
               <select name="psp-role" id="psp-role">
                    <?php
                    foreach( $wp_roles->roles as $key => $name ): ?>
                         <option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($name['name']); ?></option>
                    <?php endforeach; ?>
               </select>
          </div>
          <div class="psp-new-project-conditions__project">
               <label for="psp-new-project"><?php esc_html_e( 'Project to Clone', 'psp_projects' ); ?></label>
               <select name="psp-new-project" id="psp-role">
                    <option value="">---</option>
                    <?php
                    while( $all_projects->have_posts() ): $all_projects->the_post(); ?>
                         <option value="<?php echo esc_attr(get_the_ID()); ?>"><?php echo esc_html(get_the_title()); ?></option>
                    <?php
                    endwhile; ?>
               </select>
          </div>
          <button class="js-add-condition"><?php esc_html_e( 'Add', 'psp_projects' ); ?></button>
     </div>

     <?php

}
