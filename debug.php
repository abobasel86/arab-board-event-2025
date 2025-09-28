<?php
/**
 * Debug script to check which template is being used
 */

// Show which template file is being loaded
add_action('wp_head', function() {
    if (current_user_can('administrator')) {
        global $template;
        echo "<!-- Current template: " . $template . " -->\n";
        echo "<!-- Is front page: " . (is_front_page() ? 'Yes' : 'No') . " -->\n";
        echo "<!-- Is home: " . (is_home() ? 'Yes' : 'No') . " -->\n";
        echo "<!-- Page ID: " . get_the_ID() . " -->\n";
        echo "<!-- Front page ID: " . get_option('page_on_front') . " -->\n";
    }
});
?>