<?php
/**
 * Arab Board Event 2025 Theme Functions
 * 
 * @package Arab_Board_Event_2025
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function arab_board_2025_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array(
        'search-form',
        'comment-form', 
        'comment-list',
        'gallery',
        'caption'
    ));
    
    // Load text domain for translations
    load_theme_textdomain('arab-board-event-2025', get_template_directory() . '/languages');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('القائمة الرئيسية', 'arab-board-event-2025'),
        'footer' => __('قائمة التذييل', 'arab-board-event-2025')
    ));
}
add_action('after_setup_theme', 'arab_board_2025_setup');

// Enqueue styles and scripts
function arab_board_2025_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;600;700;800;900&family=Cairo:wght@300;400;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap',
        array(),
        '1.0.0'
    );
    
    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
    
    // Theme stylesheet
    wp_enqueue_style(
        'arab-board-2025-style',
        get_stylesheet_uri(),
        array(),
        '1.0.0'
    );
    
    // Custom CSS file
    wp_enqueue_style(
        'arab-board-2025-custom',
        get_template_directory_uri() . '/assets/css/custom.css',
        array('arab-board-2025-style'),
        '3.0.0'
    );
    
    // Event Clean CSS file
    wp_enqueue_style(
        'event-clean-style',
        get_template_directory_uri() . '/assets/css/event-clean.css',
        array('arab-board-2025-custom'),
        time() // Always fresh - no cache
    );
    
    // Print CSS file
    wp_enqueue_style(
        'arab-board-2025-print',
        get_template_directory_uri() . '/assets/css/print.css',
        array(),
        '1.0.0',
        'print'
    );
    
    // Immediate fix CSS (high priority)
    wp_enqueue_style(
        'arab-board-2025-immediate-fix',
        get_template_directory_uri() . '/assets/css/immediate-fix.css',
        array(),
        '1.0.0'
    );
    
    // jQuery (WordPress includes it)
    wp_enqueue_script('jquery');
    
    // Anti-IDM Protection Script (High Priority)
    wp_enqueue_script(
        'arab-board-2025-anti-idm',
        get_template_directory_uri() . '/assets/js/anti-idm-protection.js',
        array(),
        '2.0.0',
        false // Load in head for maximum protection
    );
    
    // Custom JavaScript
    wp_enqueue_script(
        'arab-board-2025-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery', 'arab-board-2025-anti-idm'),
        '1.0.0',
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('arab-board-2025-script', 'arabBoard2025Ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('arab_board_2025_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'arab_board_2025_scripts');

// Add admin styles and scripts
function arab_board_2025_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    
    // Admin custom CSS
    wp_enqueue_style(
        'arab-board-2025-admin',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        '1.0.0'
    );
    
    // Admin custom JS
    wp_enqueue_script(
        'arab-board-2025-admin-js',
        get_template_directory_uri() . '/assets/js/admin.js',
        array('jquery', 'wp-color-picker'),
        '1.0.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'arab_board_2025_admin_scripts');

// Enhanced PDF Anti-IDM Protection Functions
function arab_board_2025_pdf_protection() {
    // Add anti-IDM headers for PDF requests
    if (isset($_GET['pdf']) || strpos($_SERVER['REQUEST_URI'], '.pdf') !== false) {
        header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-cache, no-store, must-revalidate, private');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Download-Options: noopen');
        header('Content-Security-Policy: frame-ancestors \'self\'');
    }
}
add_action('init', 'arab_board_2025_pdf_protection');

// Add anti-IDM JavaScript to head
function arab_board_2025_anti_idm_script() {
    echo '<script>\n';
    echo '// Ultimate IDM Blocker - Enhanced Version\n';
    echo 'if (typeof window.external !== "undefined") { window.external = null; }\n';
    echo 'Object.defineProperty(window, "chrome", { value: null, writable: false });\n';
    echo 'document.addEventListener("DOMContentLoaded", function() {\n';
    echo '    const observer = new MutationObserver(function(mutations) {\n';
    echo '        mutations.forEach(function(mutation) {\n';
    echo '            mutation.addedNodes.forEach(function(node) {\n';
    echo '                if (node.nodeType === 1 && (node.className.includes("idm") || node.id.includes("idm"))) {\n';
    echo '                    node.remove();\n';
    echo '                }\n';
    echo '            });\n';
    echo '        });\n';
    echo '    });\n';
    echo '    observer.observe(document.body, { childList: true, subtree: true });\n';
    echo '});\n';
    echo '</script>\n';
}
add_action('wp_head', 'arab_board_2025_anti_idm_script', 1);

// Enhanced PDF MIME type handling
function arab_board_2025_pdf_mime_types($mimes) {
    $mimes['pdf'] = 'application/pdf';
    return $mimes;
}
add_filter('upload_mimes', 'arab_board_2025_pdf_mime_types');

// Add custom PDF protection headers
function arab_board_2025_pdf_headers() {
    if (is_admin()) return;
    
    // Anti-IDM meta tags
    echo '<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">\n';
    echo '<meta http-equiv="X-Download-Options" content="noopen">\n';
    echo '<meta http-equiv="X-Content-Type-Options" content="nosniff">\n';
    echo '<meta name="referrer" content="no-referrer">\n';
}
add_action('wp_head', 'arab_board_2025_pdf_headers', 2);

// PDF proxy endpoint for enhanced security
function arab_board_2025_pdf_proxy() {
    if (!isset($_GET['pdf_proxy']) || !isset($_GET['file'])) {
        return;
    }
    
    $file_url = sanitize_url($_GET['file']);
    $allowed_domains = array(
        get_site_url(),
        'localhost',
        '127.0.0.1'
    );
    
    $domain_allowed = false;
    foreach ($allowed_domains as $domain) {
        if (strpos($file_url, $domain) !== false) {
            $domain_allowed = true;
            break;
        }
    }
    
    if (!$domain_allowed) {
        wp_die('غير مسموح بالوصول لهذا الملف');
    }
    
    // Anti-IDM headers
    header('Content-Type: text/html; charset=UTF-8');
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-cache, private');
    header('X-Frame-Options: SAMEORIGIN');
    header('Content-Security-Policy: frame-ancestors \'self\'');
    
    // Output HTML iframe wrapper
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>PDF Viewer</title>';
    echo '<style>body{margin:0;padding:0;}iframe{width:100%;height:100vh;border:none;}</style>';
    echo '</head><body>';
    echo '<iframe src="' . esc_url($file_url) . '#toolbar=1&navpanes=1&scrollbar=1" title="PDF Viewer"></iframe>';
    echo '</body></html>';
    exit;
}
add_action('init', 'arab_board_2025_pdf_proxy');

// Simple Anti-IDM Protection - Direct in functions.php
function simple_anti_idm_protection() {
    if (!is_admin()) {
        ?>
        <script type="text/javascript">
        console.log('🔒 Simple Anti-IDM Protection Active');
        // Basic IDM blocking
        if (typeof window.external !== 'undefined') {
            window.external = null;
        }
        if (window.chrome && window.chrome.webstore) {
            delete window.chrome.webstore;
        }
        // Monitor for IDM elements
        if (document.addEventListener) {
            document.addEventListener('DOMContentLoaded', function() {
                setInterval(function() {
                    const idmElements = document.querySelectorAll('[class*="idm"], [id*="idm"]');
                    idmElements.forEach(function(el) { 
                        try { el.remove(); } catch(e) {} 
                    });
                }, 1000);
            });
        }
        </script>
        <style type="text/css">
        [class*="idm"], [id*="idm"] { display: none !important; }
        iframe[src*=".pdf"] { 
            user-select: none !important; 
            -webkit-user-select: none !important; 
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'simple_anti_idm_protection', 1);

// Add custom meta boxes
function arab_board_2025_add_meta_boxes() {
    $screens = array('page');
    
    foreach ($screens as $screen) {
        // Event Settings Meta Box
        add_meta_box(
            'event_settings',
            __('إعدادات الفعالية', 'arab-board-event-2025'),
            'arab_board_2025_event_settings_callback',
            $screen,
            'normal',
            'high'
        );
        
        // Day 1 Content Meta Box  
        add_meta_box(
            'day_1_content',
            __('محتوى اليوم الأول', 'arab-board-event-2025'),
            'arab_board_2025_day_1_content_callback',
            $screen,
            'normal',
            'high'
        );
        
        // Day 2 Content Meta Box
        add_meta_box(
            'day_2_content', 
            __('محتوى اليوم الثاني', 'arab-board-event-2025'),
            'arab_board_2025_day_2_content_callback',
            $screen,
            'normal',
            'high'
        );
        
        // QR Cards Meta Box
        add_meta_box(
            'qr_cards',
            __('بطاقات QR', 'arab-board-event-2025'),
            'arab_board_2025_qr_cards_callback',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'arab_board_2025_add_meta_boxes');

// Event Settings Callback
function arab_board_2025_event_settings_callback($post) {
    wp_nonce_field('arab_board_2025_meta_nonce', 'arab_board_2025_meta_nonce');
    
    $event_title_ar = get_post_meta($post->ID, '_event_title_ar', true);
    $event_title_en = get_post_meta($post->ID, '_event_title_en', true);
    $event_subtitle_ar = get_post_meta($post->ID, '_event_subtitle_ar', true);
    $event_subtitle_en = get_post_meta($post->ID, '_event_subtitle_en', true);
    $event_date_start = get_post_meta($post->ID, '_event_date_start', true);
    $event_date_end = get_post_meta($post->ID, '_event_date_end', true);
    $event_location_ar = get_post_meta($post->ID, '_event_location_ar', true);
    $event_location_en = get_post_meta($post->ID, '_event_location_en', true);
    $event_language = get_post_meta($post->ID, '_event_language', true) ?: 'ar';
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="event_language"><?php _e('لغة الموقع:', 'arab-board-event-2025'); ?></label></th>
            <td>
                <select id="event_language" name="event_language" class="regular-text">
                    <option value="ar" <?php selected($event_language, 'ar'); ?>><?php _e('العربية', 'arab-board-event-2025'); ?></option>
                    <option value="en" <?php selected($event_language, 'en'); ?>><?php _e('English', 'arab-board-event-2025'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="event_title_ar"><?php _e('عنوان الفعالية (عربي):', 'arab-board-event-2025'); ?></label></th>
            <td><input type="text" id="event_title_ar" name="event_title_ar" value="<?php echo esc_attr($event_title_ar); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_title_en"><?php _e('عنوان الفعالية (إنجليزي):', 'arab-board-event-2025'); ?></label></th>
            <td><input type="text" id="event_title_en" name="event_title_en" value="<?php echo esc_attr($event_title_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_subtitle_ar"><?php _e('العنوان الفرعي (عربي):', 'arab-board-event-2025'); ?></label></th>
            <td><input type="text" id="event_subtitle_ar" name="event_subtitle_ar" value="<?php echo esc_attr($event_subtitle_ar); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_subtitle_en"><?php _e('العنوان الفرعي (إنجليزي):', 'arab-board-event-2025'); ?></label></th>
            <td><input type="text" id="event_subtitle_en" name="event_subtitle_en" value="<?php echo esc_attr($event_subtitle_en); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_date_start"><?php _e('تاريخ البداية:', 'arab-board-event-2025'); ?></label></th>
            <td><input type="date" id="event_date_start" name="event_date_start" value="<?php echo esc_attr($event_date_start); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_date_end"><?php _e('تاريخ النهاية:', 'arab-board-event-2025'); ?></label></th>
            <td><input type="date" id="event_date_end" name="event_date_end" value="<?php echo esc_attr($event_date_end); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_location_ar"><?php _e('المكان (عربي):', 'arab-board-event-2025'); ?></label></th>
            <td><input type="text" id="event_location_ar" name="event_location_ar" value="<?php echo esc_attr($event_location_ar); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_location_en"><?php _e('المكان (إنجليزي):', 'arab-board-event-2025'); ?></label></th>
            <td><input type="text" id="event_location_en" name="event_location_en" value="<?php echo esc_attr($event_location_en); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Day 1 Content Callback
function arab_board_2025_day_1_content_callback($post) {
    $day1_pdf = get_post_meta($post->ID, '_day1_pdf', true);
    $day1_images = get_post_meta($post->ID, '_day1_images', true) ?: array();
    $day1_schedule = get_post_meta($post->ID, '_day1_schedule', true) ?: array();
    
    ?>
    <div class="day-content-admin">
        <!-- PDF Upload -->
        <h4><?php _e('ملف PDF لليوم الأول:', 'arab-board-event-2025'); ?></h4>
        <input type="hidden" id="day1_pdf" name="day1_pdf" value="<?php echo esc_attr($day1_pdf); ?>" />
        <button type="button" class="button upload-pdf-btn" data-target="day1_pdf"><?php _e('رفع PDF', 'arab-board-event-2025'); ?></button>
        <button type="button" class="button remove-pdf-btn" data-target="day1_pdf"><?php _e('حذف PDF', 'arab-board-event-2025'); ?></button>
        <div class="pdf-preview" id="day1_pdf_preview">
            <?php if ($day1_pdf): ?>
                <a href="<?php echo esc_url($day1_pdf); ?>" target="_blank"><?php _e('عرض PDF', 'arab-board-event-2025'); ?></a>
            <?php endif; ?>
        </div>
        
        <!-- Images Gallery -->
        <h4><?php _e('صور اليوم الأول:', 'arab-board-event-2025'); ?></h4>
        <div class="images-gallery-admin" id="day1_images_gallery">
            <?php foreach ($day1_images as $image): ?>
                <div class="image-item">
                    <img src="<?php echo esc_url($image); ?>" style="max-width: 100px;" />
                    <input type="hidden" name="day1_images[]" value="<?php echo esc_attr($image); ?>" />
                    <button type="button" class="button remove-image-btn"><?php _e('حذف', 'arab-board-event-2025'); ?></button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button add-images-btn" data-target="day1_images_gallery" data-name="day1_images"><?php _e('إضافة صور', 'arab-board-event-2025'); ?></button>
        
        <!-- Schedule Table -->
        <h4><?php _e('جدول أعمال اليوم الأول:', 'arab-board-event-2025'); ?></h4>
        <div class="schedule-admin" id="day1_schedule">
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('الوقت', 'arab-board-event-2025'); ?></th>
                        <th><?php _e('النشاط (عربي)', 'arab-board-event-2025'); ?></th>
                        <th><?php _e('النشاط (إنجليزي)', 'arab-board-event-2025'); ?></th>
                        <th><?php _e('إجراءات', 'arab-board-event-2025'); ?></th>
                    </tr>
                </thead>
                <tbody class="schedule-tbody">
                    <?php foreach ($day1_schedule as $index => $item): ?>
                        <tr>
                            <td><input type="time" name="day1_schedule[<?php echo $index; ?>][time]" value="<?php echo esc_attr($item['time'] ?? ''); ?>" /></td>
                            <td><input type="text" name="day1_schedule[<?php echo $index; ?>][activity_ar]" value="<?php echo esc_attr($item['activity_ar'] ?? ''); ?>" /></td>
                            <td><input type="text" name="day1_schedule[<?php echo $index; ?>][activity_en]" value="<?php echo esc_attr($item['activity_en'] ?? ''); ?>" /></td>
                            <td><button type="button" class="button remove-schedule-row"><?php _e('حذف', 'arab-board-event-2025'); ?></button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="button" class="button add-schedule-row" data-day="1"><?php _e('إضافة صف', 'arab-board-event-2025'); ?></button>
        </div>
    </div>
    <?php
}

// Day 2 Content Callback
function arab_board_2025_day_2_content_callback($post) {
    $day2_pdf = get_post_meta($post->ID, '_day2_pdf', true);
    $day2_images = get_post_meta($post->ID, '_day2_images', true) ?: array();
    $day2_schedule = get_post_meta($post->ID, '_day2_schedule', true) ?: array();
    
    ?>
    <div class="day-content-admin">
        <!-- PDF Upload -->
        <h4><?php _e('ملف PDF لليوم الثاني:', 'arab-board-event-2025'); ?></h4>
        <input type="hidden" id="day2_pdf" name="day2_pdf" value="<?php echo esc_attr($day2_pdf); ?>" />
        <button type="button" class="button upload-pdf-btn" data-target="day2_pdf"><?php _e('رفع PDF', 'arab-board-event-2025'); ?></button>
        <button type="button" class="button remove-pdf-btn" data-target="day2_pdf"><?php _e('حذف PDF', 'arab-board-event-2025'); ?></button>
        <div class="pdf-preview" id="day2_pdf_preview">
            <?php if ($day2_pdf): ?>
                <a href="<?php echo esc_url($day2_pdf); ?>" target="_blank"><?php _e('عرض PDF', 'arab-board-event-2025'); ?></a>
            <?php endif; ?>
        </div>
        
        <!-- Images Gallery -->
        <h4><?php _e('صور اليوم الثاني:', 'arab-board-event-2025'); ?></h4>
        <div class="images-gallery-admin" id="day2_images_gallery">
            <?php foreach ($day2_images as $image): ?>
                <div class="image-item">
                    <img src="<?php echo esc_url($image); ?>" style="max-width: 100px;" />
                    <input type="hidden" name="day2_images[]" value="<?php echo esc_attr($image); ?>" />
                    <button type="button" class="button remove-image-btn"><?php _e('حذف', 'arab-board-event-2025'); ?></button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button add-images-btn" data-target="day2_images_gallery" data-name="day2_images"><?php _e('إضافة صور', 'arab-board-event-2025'); ?></button>
        
        <!-- Schedule Table -->
        <h4><?php _e('جدول أعمال اليوم الثاني:', 'arab-board-event-2025'); ?></h4>
        <div class="schedule-admin" id="day2_schedule">
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('الوقت', 'arab-board-event-2025'); ?></th>
                        <th><?php _e('النشاط (عربي)', 'arab-board-event-2025'); ?></th>
                        <th><?php _e('النشاط (إنجليزي)', 'arab-board-event-2025'); ?></th>
                        <th><?php _e('إجراءات', 'arab-board-event-2025'); ?></th>
                    </tr>
                </thead>
                <tbody class="schedule-tbody">
                    <?php foreach ($day2_schedule as $index => $item): ?>
                        <tr>
                            <td><input type="time" name="day2_schedule[<?php echo $index; ?>][time]" value="<?php echo esc_attr($item['time'] ?? ''); ?>" /></td>
                            <td><input type="text" name="day2_schedule[<?php echo $index; ?>][activity_ar]" value="<?php echo esc_attr($item['activity_ar'] ?? ''); ?>" /></td>
                            <td><input type="text" name="day2_schedule[<?php echo $index; ?>][activity_en]" value="<?php echo esc_attr($item['activity_en'] ?? ''); ?>" /></td>
                            <td><button type="button" class="button remove-schedule-row"><?php _e('حذف', 'arab-board-event-2025'); ?></button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="button" class="button add-schedule-row" data-day="2"><?php _e('إضافة صف', 'arab-board-event-2025'); ?></button>
        </div>
    </div>
    <?php
}

// QR Cards Callback
function arab_board_2025_qr_cards_callback($post) {
    $qr_cards = get_post_meta($post->ID, '_qr_cards', true) ?: array();
    
    ?>
    <div class="qr-cards-admin" id="qr_cards_admin">
        <?php foreach ($qr_cards as $index => $card): ?>
            <div class="qr-card-item">
                <table class="form-table">
                    <tr>
                        <th><label><?php _e('اسم البطاقة (عربي):', 'arab-board-event-2025'); ?></label></th>
                        <td><input type="text" name="qr_cards[<?php echo $index; ?>][name_ar]" value="<?php echo esc_attr($card['name_ar'] ?? ''); ?>" /></td>
                    </tr>
                    <tr>
                        <th><label><?php _e('اسم البطاقة (إنجليزي):', 'arab-board-event-2025'); ?></label></th>
                        <td><input type="text" name="qr_cards[<?php echo $index; ?>][name_en]" value="<?php echo esc_attr($card['name_en'] ?? ''); ?>" /></td>
                    </tr>
                    <tr>
                        <th><label><?php _e('الرابط:', 'arab-board-event-2025'); ?></label></th>
                        <td><input type="url" name="qr_cards[<?php echo $index; ?>][url]" value="<?php echo esc_attr($card['url'] ?? ''); ?>" /></td>
                    </tr>
                    <tr>
                        <th><label><?php _e('صورة QR:', 'arab-board-event-2025'); ?></label></th>
                        <td>
                            <input type="hidden" name="qr_cards[<?php echo $index; ?>][qr_image]" value="<?php echo esc_attr($card['qr_image'] ?? ''); ?>" class="qr-image-input" />
                            <button type="button" class="button upload-qr-btn"><?php _e('رفع صورة QR', 'arab-board-event-2025'); ?></button>
                            <button type="button" class="button remove-qr-image-btn"><?php _e('حذف الصورة', 'arab-board-event-2025'); ?></button>
                            <div class="qr-image-preview">
                                <?php if (!empty($card['qr_image'])): ?>
                                    <img src="<?php echo esc_url($card['qr_image']); ?>" style="max-width: 100px;" />
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </table>
                <button type="button" class="button remove-qr-card"><?php _e('حذف البطاقة', 'arab-board-event-2025'); ?></button>
                <hr />
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button-primary add-qr-card"><?php _e('إضافة بطاقة QR جديدة', 'arab-board-event-2025'); ?></button>
    <?php
}

// Save meta box data
function arab_board_2025_save_meta_boxes($post_id) {
    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Verify nonce
    if (!isset($_POST['arab_board_2025_meta_nonce']) || !wp_verify_nonce($_POST['arab_board_2025_meta_nonce'], 'arab_board_2025_meta_nonce')) {
        return;
    }
    
    // Save event settings
    $event_fields = array(
        'event_title_ar', 'event_title_en', 'event_subtitle_ar', 'event_subtitle_en',
        'event_date_start', 'event_date_end', 'event_location_ar', 'event_location_en', 'event_language'
    );
    
    foreach ($event_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save day content
    for ($day = 1; $day <= 2; $day++) {
        // Save PDF
        if (isset($_POST["day{$day}_pdf"])) {
            update_post_meta($post_id, "_day{$day}_pdf", esc_url_raw($_POST["day{$day}_pdf"]));
        }
        
        // Save images
        if (isset($_POST["day{$day}_images"]) && is_array($_POST["day{$day}_images"])) {
            $images = array_map('esc_url_raw', $_POST["day{$day}_images"]);
            update_post_meta($post_id, "_day{$day}_images", $images);
        } else {
            delete_post_meta($post_id, "_day{$day}_images");
        }
        
        // Save schedule
        if (isset($_POST["day{$day}_schedule"]) && is_array($_POST["day{$day}_schedule"])) {
            $schedule = array();
            foreach ($_POST["day{$day}_schedule"] as $item) {
                if (!empty($item['time']) || !empty($item['activity_ar']) || !empty($item['activity_en'])) {
                    $schedule[] = array(
                        'time' => sanitize_text_field($item['time']),
                        'activity_ar' => sanitize_text_field($item['activity_ar']),
                        'activity_en' => sanitize_text_field($item['activity_en'])
                    );
                }
            }
            update_post_meta($post_id, "_day{$day}_schedule", $schedule);
        } else {
            delete_post_meta($post_id, "_day{$day}_schedule");
        }
    }
    
    // Save QR cards
    if (isset($_POST['qr_cards']) && is_array($_POST['qr_cards'])) {
        $qr_cards = array();
        foreach ($_POST['qr_cards'] as $card) {
            if (!empty($card['name_ar']) || !empty($card['name_en']) || !empty($card['url'])) {
                $qr_cards[] = array(
                    'name_ar' => sanitize_text_field($card['name_ar']),
                    'name_en' => sanitize_text_field($card['name_en']),
                    'url' => esc_url_raw($card['url']),
                    'qr_image' => esc_url_raw($card['qr_image'])
                );
            }
        }
        update_post_meta($post_id, '_qr_cards', $qr_cards);
    } else {
        delete_post_meta($post_id, '_qr_cards');
    }
}
add_action('save_post', 'arab_board_2025_save_meta_boxes');

// Helper function to get event data
function arab_board_2025_get_event_data($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!$post_id) {
        $post_id = get_option('page_on_front');
    }
    
    if (!$post_id) {
        return array();
    }
    
    return array(
        'language' => get_post_meta($post_id, '_event_language', true) ?: 'ar',
        'title_ar' => get_post_meta($post_id, '_event_title_ar', true),
        'title_en' => get_post_meta($post_id, '_event_title_en', true),
        'subtitle_ar' => get_post_meta($post_id, '_event_subtitle_ar', true),
        'subtitle_en' => get_post_meta($post_id, '_event_subtitle_en', true),
        'date_start' => get_post_meta($post_id, '_event_date_start', true),
        'date_end' => get_post_meta($post_id, '_event_date_end', true),
        'location_ar' => get_post_meta($post_id, '_event_location_ar', true),
        'location_en' => get_post_meta($post_id, '_event_location_en', true),
        'day1_pdf' => get_post_meta($post_id, '_day1_pdf', true),
        'day1_images' => get_post_meta($post_id, '_day1_images', true) ?: array(),
        'day1_schedule' => get_post_meta($post_id, '_day1_schedule', true) ?: array(),
        'day2_pdf' => get_post_meta($post_id, '_day2_pdf', true),
        'day2_images' => get_post_meta($post_id, '_day2_images', true) ?: array(),
        'day2_schedule' => get_post_meta($post_id, '_day2_schedule', true) ?: array(),
        'qr_cards' => get_post_meta($post_id, '_qr_cards', true) ?: array()
    );
}