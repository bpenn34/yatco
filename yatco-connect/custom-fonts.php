<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cfm_output_custom_fonts() {
    $fonts = get_option( 'cfm_fonts', array() );
    if ( empty( $fonts ) ) {
        return;
    }
    
    echo '<style type="text/css">';
    foreach ( $fonts as $font ) {
        if ( ! empty( $font['font_family'] ) ) {
            // Sanitize values
            $font_family = esc_attr( $font['font_family'] );
            $font_weight = esc_attr( $font['font_weight'] );
            $font_style  = esc_attr( $font['font_style'] );
            
            // Build source list based on available files.
            $sources = array();
            if ( ! empty( $font['woff2_file'] ) ) {
                $sources[] = "url('" . esc_url( $font['woff2_file'] ) . "') format('woff2')";
            }
            if ( ! empty( $font['woff_file'] ) ) {
                $sources[] = "url('" . esc_url( $font['woff_file'] ) . "') format('woff')";
            }
            if ( ! empty( $font['otf_file'] ) ) {
                $sources[] = "url('" . esc_url( $font['otf_file'] ) . "') format('opentype')";
            }
            if ( ! empty( $font['ttf_file'] ) ) {
                $sources[] = "url('" . esc_url( $font['ttf_file'] ) . "') format('truetype')";
            }
            if ( empty( $sources ) ) {
                continue; // Skip if no valid file is provided.
            }
            $src = implode( ', ', $sources );
            
            // Output the @font-face rule.
            echo "@font-face { font-family: '{$font_family}'; ";
            echo "src: {$src}; ";
            echo "font-weight: {$font_weight}; ";
            echo "font-style: {$font_style}; }";
        }
    }
    echo '</style>';
}
add_action( 'wp_head', 'cfm_output_custom_fonts' );

function cfm_allow_custom_font_mimes($mimes) {
    $mimes['woff']  = 'application/font-woff';
    $mimes['woff2'] = 'application/font-woff2';
    $mimes['otf']   = 'application/x-font-otf';
    $mimes['ttf']   = 'application/x-font-ttf';
    return $mimes;
}
add_filter('upload_mimes', 'cfm_allow_custom_font_mimes');

class Custom_Font_Manager {

    // Option name that will store all fonts as an array.
    private $option_name = 'cfm_fonts';

    public function __construct() {
        // Process form actions.
        add_action( 'admin_post_cfm_save_font', array( $this, 'save_font' ) );
        add_action( 'admin_post_cfm_delete_font', array( $this, 'delete_font' ) );
    }

    /**
     * Main page callback. Displays either the fonts list or the add/edit form.
     */
    public function fonts_page() {
        $action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : 'list';
        $id     = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';

        if ( $action === 'add' || ( $action === 'edit' && $id ) ) {
            $this->font_form( $action, $id );
        } else {
            $this->font_list();
        }
    }

    /**
     * Renders the list of fonts.
     */
    private function font_list() {
        $fonts = get_option( $this->option_name, array() );
        ?>
        <div class="wrap">
            <h1>Custom Fonts Manager</h1>
            <a href="<?php echo admin_url( 'admin.php?page=yatco-font-options&action=add' ); ?>" class="page-title-action">Add New Font</a>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Font Family</th>
                        <th>Font Weight</th>
                        <th>Font Style</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ( ! empty( $fonts ) ) : ?>
                    <?php foreach ( $fonts as $font ) : ?>
                        <tr>
                            <td><?php echo esc_html( $font['id'] ); ?></td>
                            <td><?php echo esc_html( $font['font_family'] ); ?></td>
                            <td><?php echo esc_html( $font['font_weight'] ); ?></td>
                            <td><?php echo esc_html( $font['font_style'] ); ?></td>
                            <td>
                                <a href="<?php echo admin_url( 'admin.php?page=yatco-font-options&action=edit&id=' . esc_attr( $font['id'] ) ); ?>">Edit</a> | 
                                <a href="<?php echo wp_nonce_url( admin_url( 'admin-post.php?action=cfm_delete_font&id=' . esc_attr( $font['id'] ) ), 'cfm_delete_font_' . $font['id'] ); ?>" onclick="return confirm('Are you sure you want to delete this font?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="5">No fonts found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Renders the add/edit font form.
     *
     * @param string $action 'add' or 'edit'
     * @param string $id     Font ID for edit mode.
     */
    private function font_form( $action, $id = '' ) {
        $fonts = get_option( $this->option_name, array() );
        $font  = array(
            'id'           => '',
            'font_family'  => '',
            'woff_file'    => '',
            'woff2_file'   => '',
            'otf_file'     => '',
            'ttf_file'     => '',
            'font_weight'  => '400',
            'font_style'   => 'normal',
        );
        if ( $action === 'edit' && $id ) {
            // Look up the font entry by ID.
            foreach ( $fonts as $f ) {
                if ( (int)$f['id'] === (int)$id ) {
                    $font = $f;
                    break;
                }
            }
        }
        
        ?>
        <div class="wrap">
            <h1><?php echo ( $action === 'add' ) ? 'Add New Font' : 'Edit Font'; ?></h1>
            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="font_family">Font Family</label></th>
                        <td>
                            <input type="text" name="cfm_font[font_family]" id="font_family" value="<?php echo esc_attr( $font['font_family'] ); ?>" class="regular-text" placeholder="e.g., Helvetica or Proxima Nova" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="woff_file">WOFF Font File</label></th>
                        <td>
                            <input type="file" name="cfm_font[woff_file]" id="woff_file" accept=".woff">
                            <?php if ( ! empty( $font['woff_file'] ) ) : ?>
                                <br><a href="<?php echo esc_url( $font['woff_file'] ); ?>" target="_blank">View current file</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="woff2_file">WOFF2 Font File</label></th>
                        <td>
                            <input type="file" name="cfm_font[woff2_file]" id="woff2_file" accept=".woff2">
                            <?php if ( ! empty( $font['woff2_file'] ) ) : ?>
                                <br><a href="<?php echo esc_url( $font['woff2_file'] ); ?>" target="_blank">View current file</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="otf_file">OpenType (.otf) Font File</label></th>
                        <td>
                            <input type="file" name="cfm_font[otf_file]" id="otf_file" accept=".otf">
                            <?php if ( ! empty( $font['otf_file'] ) ) : ?>
                                <br><a href="<?php echo esc_url( $font['otf_file'] ); ?>" target="_blank">View current file</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="ttf_file">TrueType (.ttf) Font File</label></th>
                        <td>
                            <input type="file" name="cfm_font[ttf_file]" id="ttf_file" accept=".ttf">
                            <?php if ( ! empty( $font['ttf_file'] ) ) : ?>
                                <br><a href="<?php echo esc_url( $font['ttf_file'] ); ?>" target="_blank">View current file</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="font_weight">Font Weight</label></th>
                        <td>
                            <select name="cfm_font[font_weight]" id="font_weight" required>
                                <?php
                                // Common font weights.
                                $weights = array( '100', '200', '300', '400', '500', '600', '700', '800', '900' );
                                foreach ( $weights as $weight ) {
                                    $selected = ( $font['font_weight'] === $weight ) ? 'selected' : '';
                                    echo "<option value='{$weight}' {$selected}>{$weight}</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="font_style">Font Style</label></th>
                        <td>
                            <select name="cfm_font[font_style]" id="font_style" required>
                                <?php
                                $styles = array( 'normal', 'italic', 'oblique' );
                                foreach ( $styles as $style ) {
                                    $selected = ( $font['font_style'] === $style ) ? 'selected' : '';
                                    echo "<option value='{$style}' {$selected}>{$style}</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php
                // Pass action and font ID (if editing).
                $redirect = admin_url( 'admin.php?page=yatco-font-options' );
                ?>
                <input type="hidden" name="action" value="cfm_save_font">
                <input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>">
                <?php if ( $action === 'edit' ) : ?>
                    <input type="hidden" name="cfm_font[id]" value="<?php echo esc_attr( $font['id'] ); ?>">
                <?php endif; ?>
                <?php submit_button( ( $action === 'add' ) ? 'Add Font' : 'Save Changes' ); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Processes form submission for adding or editing a font.
     */
    public function save_font() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized user' );
        }

        // Get current fonts array.
        $fonts = get_option( $this->option_name, array() );
        $cfm_font = isset( $_POST['cfm_font'] ) ? $_POST['cfm_font'] : array();

        // If editing, reuse the existing id; otherwise, generate a new one.
        $id = isset( $cfm_font['id'] ) && ! empty( $cfm_font['id'] ) ? $cfm_font['id'] : time();
        $cfm_font['id'] = $id;

        // Process file uploads for each file field.
        $file_fields = array( 'woff_file', 'woff2_file', 'otf_file', 'ttf_file' );
        foreach ( $file_fields as $field ) {
            if ( ! empty( $_FILES['cfm_font']['name'][ $field ] ) ) {
                $file = array(
                    'name'     => $_FILES['cfm_font']['name'][ $field ],
                    'type'     => $_FILES['cfm_font']['type'][ $field ],
                    'tmp_name' => $_FILES['cfm_font']['tmp_name'][ $field ],
                    'error'    => $_FILES['cfm_font']['error'][ $field ],
                    'size'     => $_FILES['cfm_font']['size'][ $field ]
                );
                $upload = wp_handle_upload( $file, array( 'test_form' => false ) );
                if ( isset( $upload['url'] ) ) {
                    $cfm_font[ $field ] = $upload['url'];
                }
            } else {
                // If editing, try to retain the existing file URL.
                if ( $action = 'edit' ) {
                    foreach ( $fonts as $existing ) {
                        if ( $existing['id'] == $id && isset( $existing[ $field ] ) ) {
                            $cfm_font[ $field ] = $existing[ $field ];
                        }
                    }
                }
            }
        }

        // Sanitize text fields.
        $cfm_font['font_family'] = sanitize_text_field( $cfm_font['font_family'] );
        $cfm_font['font_weight'] = sanitize_text_field( $cfm_font['font_weight'] );
        $cfm_font['font_style']  = sanitize_text_field( $cfm_font['font_style'] );

        // Update the fonts array: if editing, replace the entry; if adding, append new.
        $found = false;
        foreach ( $fonts as $key => $font ) {
            if ( $font['id'] == $id ) {
                $fonts[ $key ] = $cfm_font;
                $found = true;
                break;
            }
        }
        if ( ! $found ) {
            $fonts[] = $cfm_font;
        }

        update_option( $this->option_name, $fonts );

        $redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : admin_url( 'admin.php?page=yatco-font-options' );
        wp_redirect( $redirect_to );
        exit;
    }

    /**
     * Utility function to convert a file URL to a server file path.
     *
     * @param string $url The URL of the uploaded file.
     * @return string|false The file system path or false if not found.
     */
    private function get_file_path_from_url( $url ) {
        $upload_dir = wp_upload_dir();
        if ( strpos( $url, $upload_dir['baseurl'] ) !== false ) {
            return str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url );
        }
        return false;
    }

    /**
     * Processes deletion of a font.
     */
    public function delete_font() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized user' );
        }
        $id = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
        if ( ! $id ) {
            wp_redirect( admin_url( 'admin.php?page=yatco-font-options' ) );
            exit;
        }
        // Verify nonce.
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'cfm_delete_font_' . $id ) ) {
            wp_die( 'Security check failed' );
        }
        $fonts = get_option( $this->option_name, array() );
        foreach ( $fonts as $key => $font ) {
            if ( $font['id'] == $id ) {
                // Attempt to delete uploaded files.
                $file_fields = array( 'woff_file', 'woff2_file', 'otf_file', 'ttf_file' );
                foreach ( $file_fields as $field ) {
                    if ( ! empty( $font[$field] ) ) {
                        // Convert URL to file system path.
                        $file_path = $this->get_file_path_from_url( $font[$field] );
                        if ( $file_path && file_exists( $file_path ) ) {
                            unlink( $file_path );
                        }
                    }
                }
                unset( $fonts[ $key ] );
                break;
            }
        }
        update_option( $this->option_name, $fonts );
        wp_redirect( admin_url( 'admin.php?page=yatco-font-options' ) );
        exit;
    }
}

//new Custom_Font_Manager();
