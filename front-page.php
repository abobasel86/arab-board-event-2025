<?php
/**
 * Template Name: صفحة رئيسية للفعالية
 * Template for Arab Board Event 2025
 */

// Get the current page ID
$page_id = get_the_ID();

// Extract event details from meta fields
$event_title_ar = get_post_meta($page_id, '_event_title_ar', true) ?: 'ملتقى المجلس العربي للاختصاصات الصحية 2025';
$event_title_en = get_post_meta($page_id, '_event_title_en', true) ?: 'Arab Board Health Specialties Conference 2025';
$event_date_start = get_post_meta($page_id, '_event_date_start', true);
$event_date_end = get_post_meta($page_id, '_event_date_end', true);

// Function to convert month to Arabic
$arabic_months = array(
    'January' => 'يناير', 'February' => 'فبراير', 'March' => 'مارس', 'April' => 'أبريل',
    'May' => 'مايو', 'June' => 'يونيو', 'July' => 'يوليو', 'August' => 'أغسطس',
    'September' => 'سبتمبر', 'October' => 'أكتوبر', 'November' => 'نوفمبر', 'December' => 'ديسمبر'
);

if ($event_date_start && $event_date_end) {
    // تأكد من أن تاريخ البداية هو الأصغر
    $start_timestamp = strtotime($event_date_start);
    $end_timestamp = strtotime($event_date_end);
    
    if ($start_timestamp > $end_timestamp) {
        // إذا كان تاريخ البداية أكبر من النهاية، قم بتبديلهما
        $temp = $event_date_start;
        $event_date_start = $event_date_end;
        $event_date_end = $temp;
    }
    
    $start_day = date('d', strtotime($event_date_start));
    $end_day = date('d', strtotime($event_date_end));
    $month_en = date('F', strtotime($event_date_start));
    $month_ar = $arabic_months[$month_en];
    $year = date('Y', strtotime($event_date_start));
    $event_date_ar = $start_day . '-' . $end_day . ' ' . $month_ar . ' ' . $year;
} else {
    $event_date_ar = '16-17 أكتوبر 2025';
}

$event_date_en = $event_date_start && $event_date_end ? date('d', strtotime($event_date_start)) . '-' . date('d', strtotime($event_date_end)) . ' ' . date('F Y', strtotime($event_date_start)) : '16-17 October 2025';
$event_location_ar = get_post_meta($page_id, '_event_location_ar', true) ?: 'عمان - المملكة الأردنية الهاشمية';
$event_location_en = get_post_meta($page_id, '_event_location_en', true) ?: 'Amman, Jordan';
$event_description_ar = get_post_meta($page_id, '_event_subtitle_ar', true) ?: 'ملتقى علمي متخصص في الاختصاصات الصحية';
$event_description_en = get_post_meta($page_id, '_event_subtitle_en', true) ?: 'Specialized scientific conference in health specialties';

if (!function_exists('arab_board_get_pdf_proxy_url')) {
    function arab_board_get_pdf_proxy_url($pdf_url, $day_slug = 'document') {
        if (empty($pdf_url)) {
            return '';
        }

        $payload = array(
            'url' => esc_url_raw($pdf_url),
            'day' => sanitize_title($day_slug),
        );

        $encoded = base64_encode(wp_json_encode($payload));

        return trailingslashit(get_template_directory_uri()) . 'pdf-proxy.php?file=' . rawurlencode($encoded);
    }
}

// Day 1 Data
$day1_title_ar = 'اليوم الأول';
$day1_title_en = 'Day 1';
$day1_pdf = get_post_meta($page_id, '_day1_pdf', true);
$day1_pdf_proxy = arab_board_get_pdf_proxy_url($day1_pdf, 'day-1');
$day1_pdf_viewer = $day1_pdf_proxy ? add_query_arg('view', 'embed', $day1_pdf_proxy) : '';
$day1_pdf_download = $day1_pdf_proxy ? add_query_arg('view', 'direct', $day1_pdf_proxy) : '';
$day1_schedule = get_post_meta($page_id, '_day1_schedule', true);
$day1_images = get_post_meta($page_id, '_day1_images', true);

// Day 2 Data
$day2_title_ar = 'اليوم الثاني';
$day2_title_en = 'Day 2';
$day2_pdf = get_post_meta($page_id, '_day2_pdf', true);
$day2_pdf_proxy = arab_board_get_pdf_proxy_url($day2_pdf, 'day-2');
$day2_pdf_viewer = $day2_pdf_proxy ? add_query_arg('view', 'embed', $day2_pdf_proxy) : '';
$day2_pdf_download = $day2_pdf_proxy ? add_query_arg('view', 'direct', $day2_pdf_proxy) : '';
$day2_schedule = get_post_meta($page_id, '_day2_schedule', true);
$day2_images = get_post_meta($page_id, '_day2_images', true);

// QR Cards
$qr_cards = get_post_meta($page_id, '_qr_cards', true);
if ($qr_cards && !is_array($qr_cards)) {
    $qr_cards = maybe_unserialize($qr_cards);
}
if (!$qr_cards || !is_array($qr_cards)) {
    $qr_cards = array();
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;600;700;800;900&family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('event-page lang-ar-active'); ?>>

<main id="main" class="site-main">
    
    <!-- Language Toggle -->
    <div class="language-toggle">
        <button id="lang-ar" class="lang-btn active">العربية</button>
        <button id="lang-en" class="lang-btn">English</button>
    </div>

    <!-- Event Header -->
    <section class="event-header">
        <div class="header-content">
            <div class="header-main">
                <?php 
                $custom_logo = get_template_directory_uri() . '/assets/images/logo.png';
                $logo_exists = file_exists(get_template_directory() . '/assets/images/logo.png');
                
                if ($logo_exists) : ?>
                    <div class="logo-section">
                        <img src="<?php echo $custom_logo; ?>" alt="<?php bloginfo('name'); ?>" class="event-logo">
                    </div>
                <?php elseif (has_site_icon()) : ?>
                    <div class="logo-section">
                        <img src="<?php echo get_site_icon_url(150); ?>" alt="<?php bloginfo('name'); ?>" class="event-logo">
                    </div>
                <?php endif; ?>
                
                <div class="title-section">
                    <h1 class="event-title">
                        <span class="lang-ar"><?php echo esc_html($event_title_ar); ?></span>
                        <span class="lang-en"><?php echo esc_html($event_title_en); ?></span>
                    </h1>
                </div>
                
                <?php if ($event_description_ar || $event_description_en) : ?>
                <div class="event-description">
                    <div class="lang-ar lang-block"><?php echo wp_kses_post($event_description_ar); ?></div>
                    <div class="lang-en lang-block"><?php echo wp_kses_post($event_description_en); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="event-dates">
                    <i class="icon-calendar"></i>
                    <span class="lang-ar"><?php echo esc_html($event_date_ar); ?></span>
                    <span class="lang-en"><?php echo esc_html($event_date_en); ?></span>
                </div>
                
                <div class="event-location">
                    <i class="icon-location"></i>
                    <span class="lang-ar"><?php echo esc_html($event_location_ar); ?></span>
                    <span class="lang-en"><?php echo esc_html($event_location_en); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation Tabs -->
    <nav class="event-navigation">
        <div class="container">
            <ul class="nav-tabs">
                <li><a href="#day1" class="nav-tab active">
                    <span class="lang-ar">اليوم الأول</span>
                    <span class="lang-en">Day 1</span>
                </a></li>
                <li><a href="#day2" class="nav-tab">
                    <span class="lang-ar">اليوم الثاني</span>
                    <span class="lang-en">Day 2</span>
                </a></li>
                <?php if (!empty($qr_cards)) : ?>
                <li><a href="#qr-cards" class="nav-tab">
                    <span class="lang-ar">بطاقات QR</span>
                    <span class="lang-en">QR Cards</span>
                </a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Day 1 Content -->
    <section id="day1" class="day-section">
        <div class="container">
            <header class="section-header">
                <h2>
                    <span class="lang-ar"><?php echo esc_html($day1_title_ar); ?></span>
                    <span class="lang-en"><?php echo esc_html($day1_title_en); ?></span>
                </h2>
            </header>

            <div class="day-content">
                <!-- Schedule Section - Full Width -->
                <?php if (!empty($day1_schedule)) : ?>
                <div class="schedule-section full-width">
                    <h3>
                        <span class="lang-ar">برنامج اليوم الأول</span>
                        <span class="lang-en">Day One Schedule</span>
                    </h3>
                    
                    <div class="schedule-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <span class="lang-ar">الوقت</span>
                                        <span class="lang-en">Time</span>
                                    </th>
                                    <th>
                                        <span class="lang-ar">النشاط</span>
                                        <span class="lang-en">Activity</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($day1_schedule as $item) : ?>
                                <tr>
                                    <td class="time"><?php echo esc_html($item['time']); ?></td>
                                    <td class="activity">
                                        <span class="lang-ar"><?php echo esc_html($item['activity_ar']); ?></span>
                                        <span class="lang-en"><?php echo esc_html($item['activity_en']); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- PDF Section - Full Width -->
                <?php if ($day1_pdf_proxy) : ?>
                <div class="pdf-section full-width">
                    <h3>
                        <span class="lang-ar">ملف PDF - <?php echo esc_html($day1_title_ar); ?></span>
                        <span class="lang-en">PDF File - <?php echo esc_html($day1_title_en); ?></span>
                    </h3>

                    <div class="pdf-viewer">
                        <div class="pdf-actions">
                            <a
                                <?php if ($day1_pdf_download) : ?>
                                    href="<?php echo esc_url($day1_pdf_download); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                <?php else : ?>
                                    href="#"
                                    aria-disabled="true"
                                <?php endif; ?>
                                class="pdf-btn download-btn<?php echo $day1_pdf_download ? '' : ' is-disabled'; ?>"
                            >
                                <span class="lang-ar">تحميل</span>
                                <span class="lang-en">Download</span>
                            </a>
                        </div>

                        <div class="pdf-container">
                            <div class="pdf-loading" id="pdf-loading-day1">
                                <div class="spinner"></div>
                                <p>جاري تحميل الملف...</p>
                            </div>
                            <iframe
                                id="pdf-iframe-day1"
                                class="pdf-frame"
                                title="<?php echo esc_attr($day1_title_ar); ?> - PDF"
                                <?php if ($day1_pdf_viewer) : ?>src="<?php echo esc_url($day1_pdf_viewer); ?>"<?php endif; ?>
                                data-pdf-viewer="<?php echo esc_url($day1_pdf_viewer); ?>"
                                loading="lazy"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($day1_images)) : ?>
                <div class="images-section">
                    <h3>
                        <span class="lang-ar">صور اليوم الأول</span>
                        <span class="lang-en">Day One Images</span>
                    </h3>
                    
                    <div class="images-gallery">
                        <?php foreach ($day1_images as $image_url) : ?>
                        <div class="image-item" data-src="<?php echo esc_url($image_url); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="Day 1 Image" loading="lazy">
                            <div class="image-overlay">
                                <span class="zoom-icon">🔍</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Day 2 Content -->
    <section id="day2" class="day-section">
        <div class="container">
            <header class="section-header">
                <h2>
                    <span class="lang-ar"><?php echo esc_html($day2_title_ar); ?></span>
                    <span class="lang-en"><?php echo esc_html($day2_title_en); ?></span>
                </h2>
            </header>

            <div class="day-content">
                <!-- Schedule Section - Full Width -->
                <?php if (!empty($day2_schedule)) : ?>
                <div class="schedule-section full-width">
                    <h3>
                        <span class="lang-ar">برنامج اليوم الثاني</span>
                        <span class="lang-en">Day Two Schedule</span>
                    </h3>
                    
                    <div class="schedule-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <span class="lang-ar">الوقت</span>
                                        <span class="lang-en">Time</span>
                                    </th>
                                    <th>
                                        <span class="lang-ar">النشاط</span>
                                        <span class="lang-en">Activity</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($day2_schedule as $item) : ?>
                                <tr>
                                    <td class="time"><?php echo esc_html($item['time']); ?></td>
                                    <td class="activity">
                                        <span class="lang-ar"><?php echo esc_html($item['activity_ar']); ?></span>
                                        <span class="lang-en"><?php echo esc_html($item['activity_en']); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- PDF Section - Full Width -->
                <?php if ($day2_pdf_proxy) : ?>
                <div class="pdf-section full-width">
                    <h3>
                        <span class="lang-ar">ملف PDF - <?php echo esc_html($day2_title_ar); ?></span>
                        <span class="lang-en">PDF File - <?php echo esc_html($day2_title_en); ?></span>
                    </h3>

                    <div class="pdf-viewer">
                        <div class="pdf-actions">
                            <a
                                <?php if ($day2_pdf_download) : ?>
                                    href="<?php echo esc_url($day2_pdf_download); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                <?php else : ?>
                                    href="#"
                                    aria-disabled="true"
                                <?php endif; ?>
                                class="pdf-btn download-btn<?php echo $day2_pdf_download ? '' : ' is-disabled'; ?>"
                            >
                                <span class="lang-ar">تحميل</span>
                                <span class="lang-en">Download</span>
                            </a>
                        </div>

                        <div class="pdf-container">
                            <div class="pdf-loading" id="pdf-loading-day2">
                                <div class="spinner"></div>
                                <p>جاري تحميل الملف...</p>
                            </div>
                            <iframe
                                id="pdf-iframe-day2"
                                class="pdf-frame"
                                title="<?php echo esc_attr($day2_title_ar); ?> - PDF"
                                <?php if ($day2_pdf_viewer) : ?>src="<?php echo esc_url($day2_pdf_viewer); ?>"<?php endif; ?>
                                data-pdf-viewer="<?php echo esc_url($day2_pdf_viewer); ?>"
                                loading="lazy"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($day2_images)) : ?>
                <div class="images-section">
                    <h3>
                        <span class="lang-ar">صور اليوم الثاني</span>
                        <span class="lang-en">Day Two Images</span>
                    </h3>
                    
                    <div class="images-gallery">
                        <?php foreach ($day2_images as $image_url) : ?>
                        <div class="image-item" data-src="<?php echo esc_url($image_url); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="Day 2 Image" loading="lazy">
                            <div class="image-overlay">
                                <span class="zoom-icon">🔍</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- QR Cards Section -->
    <?php if (!empty($qr_cards)) : ?>
    <section id="qr-cards" class="qr-section">
        <div class="container">
            <header class="section-header">
                <h2>
                    <span class="lang-ar">بطاقات QR</span>
                    <span class="lang-en">QR Cards</span>
                </h2>
                <p>
                    <span class="lang-ar">امسح الرموز للوصول السريع للمواقع والملفات</span>
                    <span class="lang-en">Scan the codes for quick access to websites and files</span>
                </p>
            </header>

            <div class="qr-cards-grid">
                <?php foreach ($qr_cards as $card) : ?>
                <div class="qr-card">
                    <div class="qr-image">
                        <?php if (!empty($card['qr_image'])) : ?>
                            <img src="<?php echo esc_url($card['qr_image']); ?>" alt="QR Code">
                        <?php else : ?>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode($card['url']); ?>" alt="QR Code">
                        <?php endif; ?>
                    </div>
                    
                    <div class="qr-content">
                        <h3 class="qr-title">
                            <span class="lang-ar"><?php echo esc_html($card['name_ar']); ?></span>
                            <span class="lang-en"><?php echo esc_html($card['name_en']); ?></span>
                        </h3>
                        
                        <div class="qr-actions">
                            <a href="<?php echo esc_url($card['url']); ?>" target="_blank" class="qr-link-btn">
                                <span class="lang-ar">فتح الرابط</span>
                                <span class="lang-en">Open Link</span>
                            </a>
                            
                            <button class="qr-share-btn" data-url="<?php echo esc_url($card['url']); ?>" 
                                    data-title-ar="<?php echo esc_attr($card['name_ar']); ?>" 
                                    data-title-en="<?php echo esc_attr($card['name_en']); ?>">
                                <span class="lang-ar">مشاركة</span>
                                <span class="lang-en">Share</span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Floating Action Buttons -->
    <div class="floating-actions">
        <button class="fab print-btn" title="طباعة">
            <span class="fab-icon">🖨️</span>
        </button>
        <button class="fab scroll-top-btn" title="أعلى الصفحة">
            <span class="fab-icon">⬆️</span>
        </button>
        <button class="fab share-btn" title="مشاركة">
            <span class="fab-icon">📤</span>
        </button>
    </div>
</main>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close">&times;</button>
        <img id="lightbox-image" src="" alt="">
        <div class="lightbox-nav">
            <button class="lightbox-prev">❮</button>
            <button class="lightbox-next">❯</button>
        </div>
    </div>
</div>

<!-- CLEAN CSS - NO DUPLICATES -->
<style>
/* CSS Variables */
:root {
    --primary-color: #156b68;
    --accent-color: #caa453;
    --secondary-color: #0f5855;
    --white: #ffffff;
    --text-color: #2c3e50;
    --text-light: #5a6c7d;
    --border-color: #e1e8ed;
    --shadow: 0 4px 16px rgba(21, 107, 104, 0.15);
    --shadow-lg: 0 8px 32px rgba(21, 107, 104, 0.2);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Almarai', 'Cairo', Arial, sans-serif !important;
    background: linear-gradient(135deg, #f8fafa 0%, #ffffff 100%) !important;
    color: var(--text-color) !important;
    line-height: 1.6;
    font-size: 16px;
}

body.lang-ar-active {
    direction: rtl;
    text-align: right;
}

body.lang-en-active {
    direction: ltr;
    text-align: left;
}

/* Hide WordPress elements */
.admin-bar, .wp-toolbar, #wpadminbar {
    display: none !important;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Language Toggle */
.language-toggle {
    position: fixed;
    top: 2rem;
    right: 2rem;
    z-index: 1000;
    display: flex;
    background: var(--white);
    border-radius: 20px;
    padding: 0.5rem;
    box-shadow: var(--shadow);
    border: 2px solid var(--primary-color);
}

.lang-btn {
    background: transparent;
    color: var(--primary-color);
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 15px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.9rem;
    transition: var(--transition);
    min-width: 60px;
}

.lang-btn.active {
    background: var(--primary-color);
    color: var(--white);
}

.lang-btn:hover {
    background: var(--accent-color);
    color: var(--white);
}

/* Event Header */
.event-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--white);
    padding: 0.5rem 0 !important;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border-bottom: 4px solid var(--accent-color);
}

.event-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 25% 25%, rgba(202, 164, 83, 0.1) 0%, transparent 50%);
}

.header-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 0 1rem;
}

.header-main {
    position: relative;
    margin-bottom: 0.5rem !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.1rem !important;
    padding: 0 !important;
}

.logo-section {
    text-align: center;
}

.title-section {
    width: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.event-logo {
    height: 150px;
    width: auto;
    max-width: 180px;
    object-fit: contain;
    border-radius: 8px;
    background: transparent;
    padding: 0;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
}

.event-title {
    font-size: 1.8rem;
    font-weight: 900;
    margin: 0;
    color: var(--accent-color);
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
    line-height: 1.2;
}

.event-description {
    text-align: center !important;
    font-size: 1.4rem !important;
    margin: 0.8rem auto 1.5rem auto !important;
    width: 100% !important;
    display: block !important;
    position: relative !important;
    opacity: 0.95 !important;
    line-height: 1.6 !important;
    font-weight: 600 !important;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3) !important;
}

.event-description div {
    text-align: center !important;
    width: 100% !important;
    margin: 0 auto !important;
    line-height: 1.6 !important;
    padding: 0 1rem !important;
    display: block !important;
    max-width: none !important;
    font-size: inherit !important;
    font-weight: inherit !important;
}

.event-dates {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
    padding: 0.6rem 1rem;
    margin: 0.8rem auto 0rem auto !important;
    font-weight: 300;
    font-size: 1.1rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    background: none !important;
    background-color: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
    width: 100%;
    text-align: center;
}

.event-location {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
    padding: 0.6rem 1rem;
    margin: -0.8rem auto 1.5rem auto !important;
    font-weight: 300;
    font-size: 1.1rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    background: none !important;
    background-color: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
    width: 100%;
    text-align: center;
}

/* Navigation */
.event-navigation {
    background: var(--white);
    border-bottom: 3px solid var(--primary-color);
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow);
}

.nav-tabs {
    display: flex;
    justify-content: center;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0.5rem;
}

.nav-tabs li {
    margin: 0;
}

.nav-tab {
    display: inline-block;
    padding: 1rem 2rem;
    color: var(--text-color);
    text-decoration: none;
    border-bottom: 4px solid transparent;
    transition: var(--transition);
    font-weight: 700;
    position: relative;
    font-size: 1rem;
}

.nav-tab:hover,
.nav-tab.active {
    color: var(--white);
    border-bottom-color: var(--accent-color);
    background: var(--primary-color);
}

/* Day Sections */
.day-section {
    margin: 3rem 0;
    padding: 3rem 0;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}

.day-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(135deg, var(--accent-color) 0%, #b8934a 100%);
}

.section-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 0 2rem;
}

.section-header h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
}

.section-header h2::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: var(--accent-color);
    border-radius: 2px;
}

.day-content {
    padding: 0 2rem;
}

.row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* PDF Section - Enhanced Anti-IDM Protection */
.pdf-section {
    background: #f8fafa;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 2px solid var(--primary-color);
    box-shadow: var(--shadow);
    /* Hide from download managers */
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    /* Prevent IDM detection */
    position: relative;
    z-index: 10;
}

.pdf-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent;
    z-index: -1;
    pointer-events: none;
}

.pdf-section h3 {
    color: var(--primary-color);
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
    border-bottom: 2px solid var(--accent-color);
    padding-bottom: 0.5rem;
}

.pdf-viewer {
    background: var(--white);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.pdf-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.8rem;
    padding: 0.8rem;
    background: var(--primary-color);
    flex-wrap: wrap;
}

.pdf-btn {
    background: var(--accent-color);
    color: var(--white);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
    text-decoration: none;
}

.pdf-btn.is-disabled,
.pdf-btn[aria-disabled="true"] {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}

.pdf-btn:hover {
    background: #b8934a;
}

.pdf-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: var(--border-radius);
    overflow: auto;
}

.pdf-container iframe {
    width: 100%;
    height: 600px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    box-shadow: var(--shadow);
    background: white;
    transition: opacity 0.3s ease;
}

.pdf-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    color: var(--primary-color);
    padding: 2rem;
}

.pdf-loading.hidden {
    display: none !important;
}

.spinner {
    width: 30px;
    height: 30px;
    border: 3px solid var(--border-color);
    border-top: 3px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Schedule Section */
.schedule-section {
    background: #f8fafa;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    border: 2px solid var(--accent-color);
    box-shadow: var(--shadow);
}

.schedule-section h3 {
    color: var(--primary-color);
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 0.5rem;
}

.schedule-table {
    overflow-x: auto;
}

.schedule-table table {
    width: 100%;
    border-collapse: collapse;
    background: var(--white);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.schedule-table th {
    background: var(--primary-color);
    color: var(--white);
    padding: 0.8rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.schedule-table td {
    padding: 0.8rem;
    border-bottom: 1px solid var(--border-color);
    text-align: center;
    font-size: 0.9rem;
}

.schedule-table .time {
    background: rgba(21, 107, 104, 0.05);
    font-weight: 700;
    color: var(--primary-color);
    width: 25%;
}

.schedule-table .activity {
    text-align: right;
    padding-right: 1rem;
}

/* Images Gallery */
.images-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--border-color);
}

.images-section h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    position: relative;
}

.images-section h3::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: var(--accent-color);
    border-radius: 2px;
}

.images-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.image-item {
    position: relative;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    cursor: pointer;
    transition: var(--transition);
    background: var(--white);
}

.image-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.image-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    transition: var(--transition);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(21, 107, 104, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.image-item:hover .image-overlay {
    opacity: 1;
}

.zoom-icon {
    font-size: 1.5rem;
    color: var(--white);
}

/* QR Section */
.qr-section {
    background: #f8fafa;
    padding: 3rem 0;
    margin-top: 3rem;
    border-radius: var(--border-radius);
    position: relative;
    overflow: hidden;
}

.qr-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(135deg, var(--accent-color) 0%, #b8934a 100%);
}

.qr-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.qr-card {
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--shadow);
    border: 2px solid var(--primary-color);
    transition: var(--transition);
}

.qr-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
}

.qr-image {
    margin-bottom: 1rem;
    display: flex;
    justify-content: center;
}

.qr-image img {
    width: 120px;
    height: 120px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.qr-title {
    color: var(--primary-color);
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.8rem;
}

.qr-actions {
    display: flex;
    gap: 0.8rem;
    justify-content: center;
    flex-wrap: wrap;
}

.qr-link-btn,
.qr-share-btn {
    background: var(--primary-color);
    color: var(--white);
    border: none;
    padding: 0.6rem 1rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    font-size: 0.9rem;
}

.qr-share-btn {
    background: var(--accent-color);
}

.qr-link-btn:hover {
    background: var(--secondary-color);
}

.qr-share-btn:hover {
    background: #b8934a;
}

/* Floating Actions */
.floating-actions {
    position: fixed;
    bottom: 2rem;
    left: 2rem;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.fab {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--primary-color);
    color: var(--white);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.fab:hover {
    transform: scale(1.1);
    box-shadow: var(--shadow-lg);
}

.print-btn {
    background: var(--accent-color);
}

/* Lightbox */
.lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.lightbox.active {
    display: flex;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
}

.lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: rgba(0, 0, 0, 0.5);
    border: none;
    color: var(--white);
    font-size: 1.5rem;
    cursor: pointer;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    transition: var(--transition);
}

.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

#lightbox-image {
    max-width: 100%;
    max-height: 100%;
    border-radius: var(--border-radius);
}

/* Language visibility control */
.lang-ar,
.lang-en {
    display: none;
}

body.lang-ar-active .lang-ar,
body.lang-en-active .lang-en {
    display: inline;
}

.lang-block {
    display: block;
}

body.lang-ar-active .lang-ar.lang-block,
body.lang-en-active .lang-en.lang-block {
    display: block;
}

.lang-hidden {
    display: none !important;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .event-header {
        padding: 0.3rem 0 !important;
    }
    
    .header-main {
        text-align: center;
        gap: 0.05rem !important;
        margin-bottom: 0.2rem !important;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .logo-section {
        margin-bottom: 0.5rem;
    }
    
    .event-logo {
        height: 130px;
        width: auto;
        max-width: 160px;
    }
    
    .title-section {
        padding: 0;
        text-align: center !important;
        width: 100% !important;
        margin: 0;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .event-title {
        font-size: 1.3rem;
        text-align: center !important;
        width: 100% !important;
        margin: 0 auto !important;
    }
    
    .event-title span {
        text-align: center !important;
        display: block !important;
        width: 100% !important;
    }
    
    .event-description {
        text-align: center !important;
        width: 100% !important;
        margin: 0.8rem auto 1rem auto !important;
        padding: 0 0.5rem !important;
        font-size: 1.2rem !important;
    }
    
    .event-description div,
    .event-description .lang-ar,
    .event-description .lang-en {
        text-align: center !important;
        margin: 0 auto !important;
        width: 100% !important;
        padding: 0 0.5rem !important;
    }
    
    .event-dates {
        width: 100%;
        justify-content: center;
        text-align: center;
        font-size: 1rem;
        padding: 0.1rem;
        margin: 0.1rem auto 0rem auto !important;
    }
    
    .event-location {
        width: 100%;
        justify-content: center;
        text-align: center;
        font-size: 1rem;
        padding: 0.1rem;
        margin: -0.5rem auto 0.8rem auto !important;
    }
    
    .language-toggle {
        top: 1rem;
        right: 1rem;
        padding: 0.3rem;
    }
    
    .lang-btn {
        padding: 0.5rem 0.8rem;
        font-size: 0.8rem;
    }
    
    .row {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .nav-tab {
        padding: 0.8rem 1.2rem;
        font-size: 0.9rem;
    }
    
    .day-section {
        margin: 2rem 0;
        padding: 2rem 0;
    }
    
    .day-content {
        padding: 0 1rem;
    }
    
    .images-gallery {
        grid-template-columns: 1fr;
    }
    
    .qr-cards-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .floating-actions {
        bottom: 1rem;
        left: 1rem;
    }

    .fab {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }

    .pdf-actions {
        justify-content: center;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var body = document.body;
    if (!body || !body.classList) {
        return;
    }

    var toArray = function(list) {
        return Array.prototype.slice.call(list || []);
    };

    var langButtons = toArray(document.querySelectorAll('.lang-btn'));
    var languageElements = toArray(document.querySelectorAll('.lang-ar, .lang-en'));
    var lightbox = document.getElementById('lightbox');
    var lightboxImage = document.getElementById('lightbox-image');
    var lightboxPrev = document.querySelector('.lightbox-prev');
    var lightboxNext = document.querySelector('.lightbox-next');
    var galleryState = { images: [], index: 0 };
    var storageKey = 'arab_board_language';
    var storageAvailable = false;

    try {
        var testKey = storageKey + '_test';
        window.localStorage.setItem(testKey, '1');
        window.localStorage.removeItem(testKey);
        storageAvailable = true;
    } catch (error) {
        storageAvailable = false;
    }

    function setLanguageClasses(isArabic) {
        if (isArabic) {
            body.classList.add('lang-ar-active');
            body.classList.remove('lang-en-active');
        } else {
            body.classList.add('lang-en-active');
            body.classList.remove('lang-ar-active');
        }
    }

    function updateButtons(lang) {
        for (var i = 0; i < langButtons.length; i++) {
            var button = langButtons[i];
            if (!button || !button.classList) {
                continue;
            }

            if (button.id === 'lang-' + lang) {
                button.classList.add('active');
                button.setAttribute('aria-pressed', 'true');
            } else {
                button.classList.remove('active');
                button.setAttribute('aria-pressed', 'false');
            }
        }
    }

    function updateLanguageElements(isArabic) {
        for (var i = 0; i < languageElements.length; i++) {
            var element = languageElements[i];
            if (!element || !element.classList) {
                continue;
            }

            var isArabicElement = element.classList.contains('lang-ar');
            var shouldShow = isArabic ? isArabicElement : !isArabicElement;

            if (shouldShow) {
                element.classList.remove('lang-hidden');
                element.removeAttribute('aria-hidden');
            } else {
                element.classList.add('lang-hidden');
                element.setAttribute('aria-hidden', 'true');
            }
        }
    }

    function updateLightbox(index) {
        if (!galleryState.images.length || !lightboxImage) {
            return;
        }

        if (index < 0) {
            index = galleryState.images.length - 1;
        } else if (index >= galleryState.images.length) {
            index = 0;
        }

        galleryState.index = index;
        var currentImage = galleryState.images[index];
        if (currentImage) {
            lightboxImage.setAttribute('src', currentImage.getAttribute('src'));
        }
        if (lightbox) {
            lightbox.classList.add('active');
        }
    }

    function openGallery(images, startIndex) {
        if (!images || !images.length || !lightbox || !lightboxImage) {
            return;
        }

        galleryState.images = images;
        galleryState.index = startIndex >= 0 && startIndex < images.length ? startIndex : 0;
        updateLightbox(galleryState.index);
    }

    function persistLanguage(lang) {
        if (!storageAvailable) {
            return;
        }

        try {
            window.localStorage.setItem(storageKey, lang);
        } catch (error) {
            // Ignore storage errors (e.g., private browsing)
        }
    }

    function activateLanguage(lang, shouldPersist) {
        var isArabic = lang === 'ar';
        setLanguageClasses(isArabic);
        updateButtons(lang);
        updateLanguageElements(isArabic);

        if (shouldPersist) {
            persistLanguage(lang);
        }
    }

    var storedLang = null;
    if (storageAvailable) {
        try {
            storedLang = window.localStorage.getItem(storageKey);
        } catch (error) {
            storedLang = null;
        }
    }

    activateLanguage(storedLang === 'en' ? 'en' : 'ar', false);

    for (var i = 0; i < langButtons.length; i++) {
        (function(button) {
            if (!button) {
                return;
            }

            button.addEventListener('click', function(event) {
                event.preventDefault();
                var lang = button.id === 'lang-en' ? 'en' : 'ar';
                activateLanguage(lang, true);
            });
        })(langButtons[i]);
    }

    var navTabs = toArray(document.querySelectorAll('.nav-tab'));
    for (var j = 0; j < navTabs.length; j++) {
        (function(tab) {
            if (!tab) {
                return;
            }

            tab.addEventListener('click', function(event) {
                event.preventDefault();
                var targetSelector = tab.getAttribute('href');

                for (var k = 0; k < navTabs.length; k++) {
                    if (!navTabs[k] || !navTabs[k].classList) {
                        continue;
                    }
                    if (navTabs[k] === tab) {
                        navTabs[k].classList.add('active');
                    } else {
                        navTabs[k].classList.remove('active');
                    }
                }

                if (targetSelector) {
                    var target = document.querySelector(targetSelector);
                    if (target) {
                        var offsetTop = target.getBoundingClientRect().top + window.pageYOffset - 100;
                        window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                    }
                }
            });
        })(navTabs[j]);
    }

    var imageItems = toArray(document.querySelectorAll('.image-item'));
    for (var m = 0; m < imageItems.length; m++) {
        (function(item) {
            if (!item) {
                return;
            }

            item.addEventListener('click', function() {
                var clickedImage = item.querySelector('img');
                if (!clickedImage) {
                    return;
                }

                var gallery = item.parentElement;
                while (gallery && (!gallery.classList || !gallery.classList.contains('images-gallery'))) {
                    gallery = gallery.parentElement;
                }

                var galleryImages = [];
                if (gallery) {
                    galleryImages = toArray(gallery.querySelectorAll('.image-item img'));
                }

                if (!galleryImages.length) {
                    return;
                }

                var startIndex = 0;
                for (var idx = 0; idx < galleryImages.length; idx++) {
                    if (galleryImages[idx] === clickedImage) {
                        startIndex = idx;
                        break;
                    }
                }

                openGallery(galleryImages, startIndex);
            });
        })(imageItems[m]);
    }

    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            if (galleryState.images.length) {
                updateLightbox(galleryState.index - 1);
            }
        });
    }

    if (lightboxNext) {
        lightboxNext.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            if (galleryState.images.length) {
                updateLightbox(galleryState.index + 1);
            }
        });
    }

    var lightboxElements = toArray(document.querySelectorAll('.lightbox-close, #lightbox'));
    for (var n = 0; n < lightboxElements.length; n++) {
        (function(element) {
            if (!element) {
                return;
            }

            element.addEventListener('click', function(event) {
                if (event.target === element) {
                    var lightbox = document.getElementById('lightbox');
                    if (lightbox) {
                        lightbox.classList.remove('active');
                    }
                    galleryState.images = [];
                    galleryState.index = 0;
                }
            });
        })(lightboxElements[n]);
    }

    var scrollTopBtn = document.querySelector('.scroll-top-btn');
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    var printBtn = document.querySelector('.print-btn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }

    var shareBtn = document.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } else if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('تم نسخ الرابط');
                });
            }
        });
    }

    var shareButtons = toArray(document.querySelectorAll('.qr-share-btn'));
    for (var p = 0; p < shareButtons.length; p++) {
        (function(button) {
            if (!button) {
                return;
            }

            button.addEventListener('click', function() {
                var url = button.getAttribute('data-url');
                var title = button.getAttribute('data-title-ar') || document.title;

                if (!url) {
                    return;
                }

                if (navigator.share) {
                    navigator.share({ title: title, url: url });
                } else if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function() {
                        alert('تم نسخ الرابط');
                    });
                }
            });
        })(shareButtons[p]);
    }

    var pdfFrames = toArray(document.querySelectorAll('.pdf-frame'));
    for (var q = 0; q < pdfFrames.length; q++) {
        (function(frame) {
            if (!frame) {
                return;
            }

            var viewerUrl = frame.getAttribute('data-pdf-viewer');
            var container = frame.parentElement;
            while (container && (!container.classList || !container.classList.contains('pdf-container'))) {
                container = container.parentElement;
            }

            var loading = container ? container.querySelector('.pdf-loading') : null;
            var viewerWrapper = container;
            while (viewerWrapper && (!viewerWrapper.classList || !viewerWrapper.classList.contains('pdf-viewer'))) {
                viewerWrapper = viewerWrapper.parentElement;
            }

            var downloadLink = viewerWrapper ? viewerWrapper.querySelector('.download-btn') : null;

            if (!viewerUrl) {
                if (loading) {
                    loading.classList.add('error');
                    loading.innerHTML = '<p>ملف PDF غير متوفر.</p>';
                }
                if (downloadLink) {
                    downloadLink.setAttribute('aria-disabled', 'true');
                    downloadLink.classList.add('is-disabled');
                }
                return;
            }

            frame.addEventListener('load', function() {
                if (loading) {
                    loading.classList.add('hidden');
                    loading.style.display = 'none';
                }
                frame.classList.add('is-ready');
            });

            frame.addEventListener('error', function() {
                if (loading) {
                    loading.classList.add('error');
                    loading.innerHTML = '<p>تعذر تحميل ملف PDF.</p>';
                }
            });

            if (!frame.getAttribute('src')) {
                frame.setAttribute('src', viewerUrl);
            }
        })(pdfFrames[q]);
    }

    var disabledDownloads = toArray(document.querySelectorAll('.download-btn[aria-disabled="true"]'));
    for (var r = 0; r < disabledDownloads.length; r++) {
        (function(button) {
            if (!button) {
                return;
            }

            button.addEventListener('click', function(event) {
                event.preventDefault();
            });
        })(disabledDownloads[r]);
    }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
