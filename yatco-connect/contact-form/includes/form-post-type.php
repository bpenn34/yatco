<?php
// Register Custom Post Type for Forms
function cfp_create_form_post_type()
{
    $args = array(
        'label'               => 'Yatco Forms',
        'description'         => 'Manage custom forms',
        'supports'            => array('title'),
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-feedback',
        'show_in_rest'        => true, // Enable Gutenberg support
        'labels'              => array(
            'name'          => 'Yatco Forms',
            'singular_name' => 'Yatco Form',
            'add_new'       => 'Add New Form', // Change submenu label
            'add_new_item'  => 'Add New Form', // Change heading when adding a form
            'edit_item'     => 'Edit Form',
            'new_item'      => 'New Form',
            'view_item'     => 'View Form',
            'search_items'  => 'Search Forms',
            'not_found'     => 'No forms found',
            'not_found_in_trash' => 'No forms found in Trash',
            'all_items'     => 'All Forms',
            'menu_name'     => 'Yatco Forms',
        ),
    );
    register_post_type('cfp_form', $args);
}
add_action('init', 'cfp_create_form_post_type');
?>