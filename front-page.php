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

// Day 1 Data
$day1_title_ar = 'اليوم الأول';
$day1_title_en = 'Day 1';
$day1_pdf = get_post_meta($page_id, '_day1_pdf', true);
$day1_schedule = get_post_meta($page_id, '_day1_schedule', true);
$day1_images = get_post_meta($page_id, '_day1_images', true);

// Day 2 Data
$day2_title_ar = 'اليوم الثاني';
$day2_title_en = 'Day 2';
$day2_pdf = get_post_meta($page_id, '_day2_pdf', true);
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
    
    <!-- PDF.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('event-page'); ?>>

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
                        <span class="lang-en" style="display: none;"><?php echo esc_html($event_title_en); ?></span>
                    </h1>
                </div>
                
                <?php if ($event_description_ar || $event_description_en) : ?>
                <div class="event-description">
                    <div class="lang-ar"><?php echo wp_kses_post($event_description_ar); ?></div>
                    <div class="lang-en" style="display: none;"><?php echo wp_kses_post($event_description_en); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="event-dates">
                    <i class="icon-calendar"></i>
                    <span class="lang-ar"><?php echo esc_html($event_date_ar); ?></span>
                    <span class="lang-en" style="display: none;"><?php echo esc_html($event_date_en); ?></span>
                </div>
                
                <div class="event-location">
                    <i class="icon-location"></i>
                    <span class="lang-ar"><?php echo esc_html($event_location_ar); ?></span>
                    <span class="lang-en" style="display: none;"><?php echo esc_html($event_location_en); ?></span>
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
                    <span class="lang-en" style="display: none;">Day 1</span>
                </a></li>
                <li><a href="#day2" class="nav-tab">
                    <span class="lang-ar">اليوم الثاني</span>
                    <span class="lang-en" style="display: none;">Day 2</span>
                </a></li>
                <?php if (!empty($qr_cards)) : ?>
                <li><a href="#qr-cards" class="nav-tab">
                    <span class="lang-ar">بطاقات QR</span>
                    <span class="lang-en" style="display: none;">QR Cards</span>
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
                    <span class="lang-en" style="display: none;"><?php echo esc_html($day1_title_en); ?></span>
                </h2>
            </header>

            <div class="day-content">
                <!-- Schedule Section - Full Width -->
                <?php if (!empty($day1_schedule)) : ?>
                <div class="schedule-section full-width">
                    <h3>
                        <span class="lang-ar">برنامج اليوم الأول</span>
                        <span class="lang-en" style="display: none;">Day One Schedule</span>
                    </h3>
                    
                    <div class="schedule-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <span class="lang-ar">الوقت</span>
                                        <span class="lang-en" style="display: none;">Time</span>
                                    </th>
                                    <th>
                                        <span class="lang-ar">النشاط</span>
                                        <span class="lang-en" style="display: none;">Activity</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($day1_schedule as $item) : ?>
                                <tr>
                                    <td class="time"><?php echo esc_html($item['time']); ?></td>
                                    <td class="activity">
                                        <span class="lang-ar"><?php echo esc_html($item['activity_ar']); ?></span>
                                        <span class="lang-en" style="display: none;"><?php echo esc_html($item['activity_en']); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- PDF Section - Full Width -->
                <?php if ($day1_pdf) : ?>
                <div class="pdf-section full-width">
                    <h3>
                        <span class="lang-ar">ملف PDF - <?php echo esc_html($day1_title_ar); ?></span>
                        <span class="lang-en" style="display: none;">PDF File - <?php echo esc_html($day1_title_en); ?></span>
                    </h3>
                    
                    <div class="pdf-viewer">
                        <div class="pdf-controls">
                            <button class="pdf-btn" id="prev-page-day1">السابق</button>
                            <span class="page-info">
                                <span id="page-num-day1">1</span> / <span id="page-count-day1">0</span>
                            </span>
                            <button class="pdf-btn" id="next-page-day1">التالي</button>
                            <button class="pdf-btn zoom-in" data-target="day1">تكبير</button>
                            <button class="pdf-btn zoom-out" data-target="day1">تصغير</button>
                            <a href="<?php echo esc_url($day1_pdf); ?>?download=1&t=<?php echo time(); ?>" class="pdf-btn download-btn" target="_blank" data-no-idm="true">تحميل</a>
                        </div>
                        
                        <div class="pdf-container">
                            <canvas id="pdf-canvas-day1"></canvas>
                            <iframe id="pdf-iframe-day1" style="display: none; width: 100%; height: 600px; border: 1px solid #ddd; border-radius: 4px;"></iframe>
                        </div>
                        
                        <div class="pdf-loading" id="pdf-loading-day1">
                            <div class="spinner"></div>
                            <p>جاري تحميل الملف...</p>
                        </div>
                    </div>
                    
                    <script>
                        // حل بسيط ومباشر لعرض PDF
                        jQuery(document).ready(function($) {
                            const pdfUrl = '<?php echo $day1_pdf; ?>';
                            const iframe = $('#pdf-iframe-day1');
                            const loading = $('#pdf-loading-day1');
                            const canvas = $('#pdf-canvas-day1');
                            
                            console.log('PDF URL for day1:', pdfUrl);
                            
                            if (pdfUrl && pdfUrl.trim() !== '' && iframe.length) {
                                // إخفاء canvas واستخدام iframe
                                canvas.hide();
                                loading.show();
                                
                                // تحميل PDF في iframe
                                iframe.attr('src', pdfUrl + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH');
                                
                                // إظهار iframe بعد التحميل
                                iframe.on('load', function() {
                                    loading.hide();
                                    iframe.show();
                                    console.log('PDF loaded for day1');
                                });
                                
                                // معالجة الأخطاء
                                setTimeout(function() {
                                    if (loading.is(':visible')) {
                                        loading.html('<div style="text-align: center; padding: 2rem;"><p style="color: #d32f2f;">خطأ في تحميل PDF</p><a href="' + pdfUrl + '" target="_blank" style="background: #156b68; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">فتح في نافذة جديدة</a></div>');
                                    }
                                }, 5000);
                            }
                        });
                    </script>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($day1_images)) : ?>
                <div class="images-section">
                    <h3>
                        <span class="lang-ar">صور اليوم الأول</span>
                        <span class="lang-en" style="display: none;">Day One Images</span>
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
                    <span class="lang-en" style="display: none;"><?php echo esc_html($day2_title_en); ?></span>
                </h2>
            </header>

            <div class="day-content">
                <!-- Schedule Section - Full Width -->
                <?php if (!empty($day2_schedule)) : ?>
                <div class="schedule-section full-width">
                    <h3>
                        <span class="lang-ar">برنامج اليوم الثاني</span>
                        <span class="lang-en" style="display: none;">Day Two Schedule</span>
                    </h3>
                    
                    <div class="schedule-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <span class="lang-ar">الوقت</span>
                                        <span class="lang-en" style="display: none;">Time</span>
                                    </th>
                                    <th>
                                        <span class="lang-ar">النشاط</span>
                                        <span class="lang-en" style="display: none;">Activity</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($day2_schedule as $item) : ?>
                                <tr>
                                    <td class="time"><?php echo esc_html($item['time']); ?></td>
                                    <td class="activity">
                                        <span class="lang-ar"><?php echo esc_html($item['activity_ar']); ?></span>
                                        <span class="lang-en" style="display: none;"><?php echo esc_html($item['activity_en']); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- PDF Section - Full Width -->
                <?php if ($day2_pdf) : ?>
                <div class="pdf-section full-width">
                    <h3>
                        <span class="lang-ar">ملف PDF - <?php echo esc_html($day2_title_ar); ?></span>
                        <span class="lang-en" style="display: none;">PDF File - <?php echo esc_html($day2_title_en); ?></span>
                    </h3>
                    
                    <div class="pdf-viewer">
                        <div class="pdf-controls">
                            <button class="pdf-btn" id="prev-page-day2">السابق</button>
                            <span class="page-info">
                                <span id="page-num-day2">1</span> / <span id="page-count-day2">0</span>
                            </span>
                            <button class="pdf-btn" id="next-page-day2">التالي</button>
                            <button class="pdf-btn zoom-in" data-target="day2">تكبير</button>
                            <button class="pdf-btn zoom-out" data-target="day2">تصغير</button>
                            <a href="<?php echo esc_url($day2_pdf); ?>?download=1&t=<?php echo time(); ?>" class="pdf-btn download-btn" target="_blank" data-no-idm="true">تحميل</a>
                        </div>
                        
                        <div class="pdf-container">
                            <canvas id="pdf-canvas-day2"></canvas>
                            <iframe id="pdf-iframe-day2" style="display: none; width: 100%; height: 600px; border: 1px solid #ddd; border-radius: 4px;"></iframe>
                        </div>
                        
                        <div class="pdf-loading" id="pdf-loading-day2">
                            <div class="spinner"></div>
                            <p>جاري تحميل الملف...</p>
                        </div>
                    </div>
                    
                    <script>
                        // حل بسيط ومباشر لعرض PDF
                        jQuery(document).ready(function($) {
                            const pdfUrl = '<?php echo $day2_pdf; ?>';
                            const iframe = $('#pdf-iframe-day2');
                            const loading = $('#pdf-loading-day2');
                            const canvas = $('#pdf-canvas-day2');
                            
                            console.log('PDF URL for day2:', pdfUrl);
                            
                            if (pdfUrl && pdfUrl.trim() !== '' && iframe.length) {
                                // إخفاء canvas واستخدام iframe
                                canvas.hide();
                                loading.show();
                                
                                // تحميل PDF في iframe
                                iframe.attr('src', pdfUrl + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH');
                                
                                // إظهار iframe بعد التحميل
                                iframe.on('load', function() {
                                    loading.hide();
                                    iframe.show();
                                    console.log('PDF loaded for day2');
                                });
                                
                                // معالجة الأخطاء
                                setTimeout(function() {
                                    if (loading.is(':visible')) {
                                        loading.html('<div style="text-align: center; padding: 2rem;"><p style="color: #d32f2f;">خطأ في تحميل PDF</p><a href="' + pdfUrl + '" target="_blank" style="background: #156b68; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">فتح في نافذة جديدة</a></div>');
                                    }
                                }, 5000);
                            }
                        });
                    </script>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($day2_images)) : ?>
                <div class="images-section">
                    <h3>
                        <span class="lang-ar">صور اليوم الثاني</span>
                        <span class="lang-en" style="display: none;">Day Two Images</span>
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
                    <span class="lang-en" style="display: none;">QR Cards</span>
                </h2>
                <p>
                    <span class="lang-ar">امسح الرموز للوصول السريع للمواقع والملفات</span>
                    <span class="lang-en" style="display: none;">Scan the codes for quick access to websites and files</span>
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
                            <span class="lang-en" style="display: none;"><?php echo esc_html($card['name_en']); ?></span>
                        </h3>
                        
                        <div class="qr-actions">
                            <a href="<?php echo esc_url($card['url']); ?>" target="_blank" class="qr-link-btn">
                                <span class="lang-ar">فتح الرابط</span>
                                <span class="lang-en" style="display: none;">Open Link</span>
                            </a>
                            
                            <button class="qr-share-btn" data-url="<?php echo esc_url($card['url']); ?>" 
                                    data-title-ar="<?php echo esc_attr($card['name_ar']); ?>" 
                                    data-title-en="<?php echo esc_attr($card['name_en']); ?>">
                                <span class="lang-ar">مشاركة</span>
                                <span class="lang-en" style="display: none;">Share</span>
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
    direction: rtl !important;
    text-align: right !important;
    line-height: 1.6;
    font-size: 16px;
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

.pdf-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.8rem;
    padding: 0.8rem;
    background: var(--primary-color);
    color: var(--white);
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

.pdf-btn:hover {
    background: #b8934a;
}

.page-info {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.4rem 0.8rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.9rem;
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

.pdf-container canvas {
    max-width: 100%;
    height: auto;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    box-shadow: var(--shadow);
}

.pdf-container iframe {
    width: 100%;
    height: 600px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    box-shadow: var(--shadow);
    background: white;
    /* Enhanced IDM protection */
    pointer-events: auto;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    /* Hide from download detection */
    content-visibility: auto;
    opacity: 0.999; /* Trick IDM detection */
    filter: blur(0px) brightness(1.001); /* Subtle filter to confuse IDM */
    /* Additional protection */
    -webkit-touch-callout: none;
    -webkit-tap-highlight-color: transparent;
    /* Block contextmenu and selection */
    -webkit-context-menu: none;
    -moz-context-menu: none;
    context-menu: none;
}

/* Hide iframe from IDM when it's loading */
.pdf-container iframe[data-anti-idm="true"] {
    visibility: visible !important;
    display: block !important;
    transform: scale(1.0001); /* Microscopic transform to avoid detection */
}

/* Prevent IDM overlay detection */
.pdf-container iframe::before,
.pdf-container iframe::after {
    content: none !important;
    display: none !important;
}

.pdf-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    color: var(--primary-color);
    padding: 2rem;
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
.lang-en {
    display: none !important;
}

.lang-ar {
    display: block !important;
}

body.en-active .lang-en {
    display: block !important;
}

body.en-active .lang-ar {
    display: none !important;
}

body.ar-active .lang-ar {
    display: block !important;
}

body.ar-active .lang-en {
    display: none !important;
}

/* Override inline styles */
body.en-active .lang-en[style*="display: none"] {
    display: block !important;
}

body.ar-active .lang-ar[style*="display: none"] {
    display: block !important;
}

body.en-active .lang-ar[style*="display: block"] {
    display: none !important;
}

body.ar-active .lang-en[style*="display: block"] {
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
}
</style>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Configure PDF.js with better error handling
if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    // Disable web workers if they cause issues
    pdfjsLib.GlobalWorkerOptions.workerPort = null;
    
    // Set up better CORS handling
    pdfjsLib.getDocument.prototype.httpHeaders = {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET',
        'Access-Control-Allow-Headers': 'Content-Type'
    };
    
    console.log('PDF.js configured successfully');
} else {
    console.error('PDF.js library not loaded!');
}

jQuery(document).ready(function($) {
    // حل مبسط وعملي لتبديل اللغة
    $('#lang-ar').click(function() {
        console.log('تبديل إلى العربية');
        
        // تغيير حالة الأزرار
        $('.lang-btn').removeClass('active');
        $(this).addClass('active');
        
        // إخفاء الإنجليزية وإظهار العربية
        $('.lang-en').hide();
        $('.lang-ar').show();
        
        // تغيير اتجاه الصفحة
        $('body').css('direction', 'rtl').css('text-align', 'right');
        
        console.log('تم التبديل إلى العربية');
    });
    
    $('#lang-en').click(function() {
        console.log('Switch to English');
        
        // تغيير حالة الأزرار
        $('.lang-btn').removeClass('active');
        $(this).addClass('active');
        
        // إخفاء العربية وإظهار الإنجليزية
        $('.lang-ar').hide();
        $('.lang-en').show();
        
        // تغيير اتجاه الصفحة
        $('body').css('direction', 'ltr').css('text-align', 'left');
        
        console.log('Switched to English');
    });
    
    // تعيين اللغة الافتراضية - العربية
    $('.lang-ar').show();
    $('.lang-en').hide();
    $('#lang-ar').addClass('active');
    
    // Navigation tabs
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        
        $('.nav-tab').removeClass('active');
        $(this).addClass('active');
        
        $('html, body').animate({
            scrollTop: $(target).offset().top - 100
        }, 500);
    });
    
    // Image lightbox
    $('.image-item').on('click', function() {
        var imgSrc = $(this).find('img').attr('src');
        $('#lightbox-image').attr('src', imgSrc);
        $('#lightbox').addClass('active');
    });
    
    $('.lightbox-close, #lightbox').on('click', function(e) {
        if (e.target === this) {
            $('#lightbox').removeClass('active');
        }
    });
    
    // Floating actions
    $('.scroll-top-btn').on('click', function() {
        $('html, body').animate({scrollTop: 0}, 500);
    });
    
    $('.print-btn').on('click', function() {
        window.print();
    });
    
    $('.share-btn').on('click', function() {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert('تم نسخ الرابط');
        }
    });
    
    // حل بسيط وعملي لعرض PDF
    console.log('تهيئة عارض PDF البسيط...');
    
    // وظيفة بسيطة لتحميل PDF في iframe
    function loadSimplePDF(day, pdfUrl) {
        console.log('تحميل PDF لليوم:', day, 'الرابط:', pdfUrl);
        
        const iframe = document.getElementById('pdf-iframe-' + day);
        const loading = document.getElementById('pdf-loading-' + day);
        const canvas = document.getElementById('pdf-canvas-' + day);
        
        if (!iframe || !pdfUrl) {
            console.error('عنصر iframe غير موجود أو لا يوجد رابط PDF');
            return;
        }
        
        // إخفاء canvas وإظهار loading
        if (canvas) canvas.style.display = 'none';
        if (loading) loading.style.display = 'flex';
        
        // تحميل PDF في iframe مباشرة
        iframe.src = pdfUrl + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH';
        
        // معالجة التحميل الناجح
        iframe.onload = function() {
            console.log('تم تحميل PDF بنجاح لليوم:', day);
            if (loading) loading.style.display = 'none';
            iframe.style.display = 'block';
        };
        
        // معالجة الأخطاء
        iframe.onerror = function() {
            console.error('خطأ في تحميل PDF لليوم:', day);
            if (loading) {
                loading.innerHTML = `
                    <div style="text-align: center; padding: 2rem; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <p style="color: #d32f2f; font-weight: bold; margin-bottom: 1rem;">لا يمكن عرض ملف PDF في المتصفح</p>
                        <p style="color: #666; margin-bottom: 1rem; font-size: 0.9em;">قد يكون السبب: الملف محمي، مشكلة في الشبكة، أو إعدادات المتصفح</p>
                        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                            <a href="${pdfUrl}" target="_blank" style="background: #156b68; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; font-weight: bold;">📄 تحميل الملف</a>
                            <button onclick="loadTestContent('${day}')" style="background: #caa453; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">📋 عرض محتوى تجريبي</button>
                            <button onclick="location.reload()" style="background: #666; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">🔄 إعادة المحاولة</button>
                        </div>
                    </div>
                `;
            }
        };
        
        // Timeout للكشف عن المشاكل
        setTimeout(function() {
            if (loading && loading.style.display !== 'none') {
                console.warn('تحميل PDF يستغرق وقتاً طويلاً لليوم:', day);
                iframe.style.display = 'block';
                loading.style.display = 'none';
            }
        }, 5000);
    }
    
    // وظيفة تحميل المحتوى التجريبي
    window.loadTestContent = function(day) {
        console.log('تحميل محتوى تجريبي لليوم:', day);
        const iframe = document.getElementById('pdf-iframe-' + day);
        const loading = document.getElementById('pdf-loading-' + day);
        
        if (iframe && loading) {
            loading.style.display = 'none';
            iframe.src = 'test-pdf-content.html';
            iframe.style.display = 'block';
            console.log('تم تحميل المحتوى التجريبي بنجاح');
        }
    };
    
    // تهيئة PDF البسيطة - حذف جميع التعقيدات
    console.log('تم حذف جميع التعقيدات - استخدام حل بسيط');
    function debugPDFSetup() {
        console.log('=== PDF Debug Information ===');
        console.log('PDF.js available:', typeof pdfjsLib !== 'undefined');
        console.log('Window.pdfFiles available:', typeof window.pdfFiles !== 'undefined');
        
        if (typeof window.pdfFiles !== 'undefined') {
            console.log('PDF files:', window.pdfFiles);
            for (let day in window.pdfFiles) {
                const url = window.pdfFiles[day];
                console.log(`${day} PDF URL:`, url);
                
                // For localhost, don't try fetch (will fail due to CORS)
                if (url.includes('localhost') || url.includes('127.0.0.1')) {
                    console.log(`${day} PDF: localhost detected, will use iframe directly`);
                } else {
                    // Test if URL is accessible for external URLs
                    fetch(url, { method: 'HEAD' })
                        .then(response => {
                            console.log(`${day} PDF accessibility:`, response.ok ? 'OK' : 'FAILED');
                            console.log(`${day} PDF status:`, response.status);
                        })
                        .catch(error => {
                            console.error(`${day} PDF fetch error:`, error.message);
                        });
                }
            }
        } else {
            console.log('No PDF files configured');
        }
        console.log('=== End PDF Debug ===');
    }
    
    // Prevent IDM from intercepting PDF links
    function preventIDMInterception() {
        // Override XMLHttpRequest to add headers that prevent IDM detection
        const originalXHROpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(method, url, async, user, password) {
            if (url && url.includes('.pdf')) {
                console.log('Protecting PDF request from IDM:', url);
            }
            return originalXHROpen.apply(this, arguments);
        };
        
        const originalXHRSend = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function(data) {
            if (this._url && this._url.includes('.pdf')) {
                this.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                this.setRequestHeader('Content-Type', 'application/pdf');
                this.setRequestHeader('Cache-Control', 'no-cache');
            }
            return originalXHRSend.apply(this, arguments);
        };
        
        // Override fetch for PDF requests
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            if (url && url.includes('.pdf')) {
                console.log('Protecting PDF fetch from IDM:', url);
                options.headers = options.headers || {};
                options.headers['X-Requested-With'] = 'XMLHttpRequest';
                options.headers['Content-Type'] = 'application/pdf';
                options.headers['Cache-Control'] = 'no-cache';
                options.mode = 'cors';
                options.credentials = 'omit';
            }
            return originalFetch.apply(this, arguments);
        };
    }
    
    // Enhanced IDM protection
    function enhancedIDMProtection() {
        // Block IDM from detecting PDF links
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    const pdfLinks = document.querySelectorAll('a[href*=".pdf"], iframe[src*=".pdf"]');
                    pdfLinks.forEach(function(element) {
                        element.setAttribute('data-no-idm', 'true');
                        element.style.pointerEvents = 'auto';
                        if (element.tagName === 'IFRAME') {
                            element.oncontextmenu = function(e) { e.preventDefault(); };
                        }
                    });
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Override document.createElement to protect dynamically created elements
        const originalCreateElement = document.createElement;
        document.createElement = function(tagName) {
            const element = originalCreateElement.call(this, tagName);
            if (tagName.toLowerCase() === 'iframe') {
                element.setAttribute('data-anti-idm', 'true');
                element.oncontextmenu = function(e) { e.preventDefault(); };
            }
            return element;
        };
        
        // Disable right-click on PDF elements
        document.addEventListener('contextmenu', function(e) {
            if (e.target.closest('[data-no-idm], .pdf-container, .pdf-viewer')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Block drag and drop on PDF elements
        document.addEventListener('dragstart', function(e) {
            if (e.target.closest('[data-no-idm], .pdf-container, .pdf-viewer')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Ultimate IDM blocker - intercept any external download attempts
        Object.defineProperty(window, 'external', {
            value: null,
            writable: false,
            configurable: false
        });
        
        // Block IDM browser helpers
        if (window.chrome && window.chrome.webstore) {
            delete window.chrome.webstore;
        }
        
        // Disable IDM keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Block Ctrl+S, Ctrl+D, Alt+Click on PDF areas
            if (e.target.closest('.pdf-container, .pdf-viewer')) {
                if ((e.ctrlKey && (e.key === 's' || e.key === 'd')) || 
                    (e.altKey && e.type === 'click')) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('IDM shortcut blocked');
                    return false;
                }
            }
        });
        
        // Monitor and block any IDM injected elements
        const idmBlocker = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        // Check for IDM-like elements and remove them
                        if (node.className && typeof node.className === 'string') {
                            if (node.className.includes('idm') || 
                                node.className.includes('download') ||
                                node.className.includes('IDM')) {
                                console.log('Removing IDM element:', node);
                                node.remove();
                            }
                        }
                        
                        // Check for IDM overlay divs
                        if (node.style && node.style.position === 'absolute' && 
                            node.style.zIndex > 1000) {
                            const rect = node.getBoundingClientRect();
                            const pdfContainers = document.querySelectorAll('.pdf-container');
                            pdfContainers.forEach(function(container) {
                                const containerRect = container.getBoundingClientRect();
                                if (rect.left < containerRect.right && 
                                    rect.right > containerRect.left &&
                                    rect.top < containerRect.bottom && 
                                    rect.bottom > containerRect.top) {
                                    console.log('Removing IDM overlay:', node);
                                    node.remove();
                                }
                            });
                        }
                    }
                });
            });
        });
        
        idmBlocker.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'style']
        });
    }
    
    // Initialize all IDM protections
    preventIDMInterception();
    enhancedIDMProtection();
    
    // Initialize PDF viewers using protected proxy
    console.log('Initializing protected PDF viewers...');
    
    setTimeout(function() {
        if (typeof window.pdfFiles !== 'undefined' && Object.keys(window.pdfFiles).length > 0) {
            console.log('Protected PDF files found:', Object.keys(window.pdfFiles));
            
            for (let day in window.pdfFiles) {
                const iframe = document.getElementById('pdf-iframe-' + day);
                const loading = document.getElementById('pdf-loading-' + day);
                const canvas = document.getElementById('pdf-canvas-' + day);
                
                if (iframe && loading) {
                    console.log('Initializing protected PDF for:', day);
                    
                    // إخفاء canvas واستخدام iframe فقط
                    if (canvas) canvas.style.display = 'none';
                    
                    // تحميل PDF المحمي
                    loading.style.display = 'flex';
                    iframe.style.display = 'none';
                    
                    // استخدام الـ proxy المحمي
                    iframe.src = window.pdfFiles[day];
                    iframe.setAttribute('data-protected-pdf', 'true');
                    iframe.setAttribute('sandbox', 'allow-same-origin allow-scripts allow-forms allow-popups');
                    
                    // إعداد معالجات الأحداث
                    iframe.onload = function() {
                        console.log('Protected PDF loaded successfully for', day, 'URL:', window.pdfFiles[day]);
                        loading.style.display = 'none';
                        iframe.style.display = 'block';
                        
                        // إضافة رسالة نجاح
                        setTimeout(function() {
                            const successMsg = document.createElement('div');
                            successMsg.innerHTML = '<div style=\"background: #4caf50; color: white; padding: 0.5rem; text-align: center; border-radius: 4px; margin-bottom: 0.5rem;\">تم تحميل المستند بنجاح ✓</div>';
                            iframe.parentNode.insertBefore(successMsg, iframe);
                            setTimeout(() => successMsg.remove(), 3000);
                        }, 100);
                    };
                    
                    iframe.onerror = function() {
                        console.error('Protected PDF failed for', day, 'URL:', window.pdfFiles[day]);
                        loading.innerHTML = '<div style=\"color: #d32f2f; text-align: center; padding: 2rem; background: #ffebee; border-radius: 8px; border: 1px solid #ffcdd2;\"><p style=\"font-weight: bold; margin-bottom: 0.5rem;\">خطأ في تحميل المستند</p><p style=\"font-size: 0.9em; color: #666; margin-bottom: 1rem;\">تحقق من إعدادات الملفات أو اتصال الإنترنت</p><button onclick=\"location.reload()\" style=\"background: #156b68; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;\">إعادة المحاولة</button><br><small style=\"margin-top: 1rem; display: block; color: #999;\">Day: ' + day + '</small></div>';
                    };
                    
                    // إضافة timeout للكشف عن المشاكل
                    setTimeout(function() {
                        if (loading.style.display !== 'none') {
                            console.warn('PDF loading taking too long for', day);
                            loading.innerHTML = '<div style=\"color: #ff9800; text-align: center; padding: 2rem;\"><div class=\"spinner\"></div><p>التحميل يستغرق وقتاً أطول من المعتاد...</p><p style=\"font-size: 0.8em; color: #666;\">إذا استمر التحميل، جرب إعادة تحميل الصفحة</p></div>';
                        }
                    }, 10000); // 10 seconds timeout
                    
                    // إخفاء أزرار التنقل لأن الـ proxy يتولى العرض
                    const controls = document.querySelector('#' + day.replace('day', 'day') + ' .pdf-controls');
                    if (controls) {
                        const buttons = controls.querySelectorAll('.pdf-btn:not(.download-btn)');\n                        buttons.forEach(btn => btn.style.display = 'none');
                    }
                } else {
                    console.error('Missing elements for protected PDF:', day);
                }
            }
        } else {
            console.log('No protected PDF files configured');
        }
    }, 300);
    
    // Enhanced protection for download links from IDM
    $('.download-btn[data-no-idm]').on('click', function(e) {
        e.preventDefault();
        const link = this;
        const originalHref = link.href;
        
        // Create dynamic anti-IDM URL
        const antiIdmParams = [
            'viewer=browser',
            'display=inline', 
            'content-disposition=inline',
            'x-download=false',
            'embed=true',
            'nodownload=1',
            'browser-view=1',
            'anti-idm=' + Math.random().toString(36).substring(7),
            't=' + Date.now()
        ].join('&');
        
        const protectedUrl = originalHref + (originalHref.includes('?') ? '&' : '?') + antiIdmParams;
        
        // Open in new window with specific features to avoid IDM
        const newWindow = window.open('', '_blank', 'width=1000,height=800,scrollbars=yes,resizable=yes,toolbar=no,menubar=no,location=no,status=no');
        if (newWindow) {
            newWindow.document.write(`
                <html>
                    <head>
                        <title>PDF Viewer</title>
                        <style>
                            body { margin: 0; padding: 0; }
                            iframe { width: 100%; height: 100vh; border: none; }
                        </style>
                    </head>
                    <body>
                        <iframe src="${protectedUrl}#toolbar=1&navpanes=1&scrollbar=1" 
                                data-no-download="true" 
                                data-anti-idm="true"
                                sandbox="allow-same-origin allow-scripts allow-forms">
                        </iframe>
                    </body>
                </html>
            `);
            newWindow.document.close();
        } else {
            // Fallback: direct link
            window.location.href = protectedUrl;
        }
    });
    
    // QR share buttons
    $('.qr-share-btn').on('click', function() {
        var url = $(this).data('url');
        var title = $(this).data('title-ar');
        
        if (navigator.share) {
            navigator.share({
                title: title,
                url: url
            });
        } else {
            navigator.clipboard.writeText(url);
            alert('تم نسخ الرابط');
        }
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html>