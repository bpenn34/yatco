<?php

// Output the GA code in the head section
add_action('wp_head', function() {
    if (!is_admin()) {
        $ga_code = get_option('yatco_connect_google_analytics_code');
        if ($ga_code) {
            echo $ga_code;
        }
    }
});

?>