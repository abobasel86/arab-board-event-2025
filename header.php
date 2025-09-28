<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <!-- Favicon -->
    <?php if (has_site_icon()) : ?>
        <link rel="icon" href="<?php echo get_site_icon_url(32); ?>" sizes="32x32">
        <link rel="icon" href="<?php echo get_site_icon_url(192); ?>" sizes="192x192">
        <link rel="apple-touch-icon" href="<?php echo get_site_icon_url(180); ?>">
        <meta name="msapplication-TileImage" content="<?php echo get_site_icon_url(270); ?>">
    <?php endif; ?>
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php wp_title('|', true, 'right'); ?>">
    <meta property="og:description" content="<?php bloginfo('description'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo home_url(); ?>">
    <?php if (has_site_icon()) : ?>
        <meta property="og:image" content="<?php echo get_site_icon_url(1200); ?>">
    <?php endif; ?>
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php wp_title('|', true, 'right'); ?>">
    <meta name="twitter:description" content="<?php bloginfo('description'); ?>">
    <?php if (has_site_icon()) : ?>
        <meta name="twitter:image" content="<?php echo get_site_icon_url(1200); ?>">
    <?php endif; ?>
    
    <!-- Additional Meta Tags -->
    <meta name="theme-color" content="#1a5f5c">
    <meta name="msapplication-TileColor" content="#1a5f5c">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Preload Critical Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" as="style">
    
    <!-- PDF.js CDN for PDF viewing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <!-- WordPress Head -->
    <?php wp_head(); ?>
    
    <!-- Emergency Color Fix -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/emergency-fix.css?v=<?php echo time(); ?>">
    
    <!-- Custom CSS Variables -->
    <style>
        :root {
            --site-url: '<?php echo home_url(); ?>';
            --theme-url: '<?php echo get_template_directory_uri(); ?>';
            --primary-color: #156b68 !important;
            --accent-color: #caa453 !important;
            --secondary-color: #0f5855 !important;
        }
        
        /* إصلاح فوري للألوان */
        body { 
            background: linear-gradient(135deg, #f8fafa 0%, #ffffff 100%) !important;
            color: #2c3e50 !important;
            direction: rtl !important;
            font-family: 'Almarai', 'Cairo', Arial, sans-serif !important;
        }
        
        .site-header, header {
            background: linear-gradient(135deg, #156b68 0%, #0f5855 100%) !important;
            color: #ffffff !important;
            padding: 3rem 0 !important;
        }
        
        h1, .site-title, .site-title a {
            color: #caa453 !important;
            font-weight: 800 !important;
            text-decoration: none !important;
        }
        
        .menu-item a, nav a, button {
            background: linear-gradient(135deg, #156b68 0%, #0f5855 100%) !important;
            color: #ffffff !important;
            padding: 1rem 2rem !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            font-weight: 700 !important;
            border: none !important;
            cursor: pointer !important;
            margin: 0 0.5rem !important;
            display: inline-block !important;
        }
        
        .menu-item a:hover, nav a:hover, button:hover {
            background: linear-gradient(135deg, #caa453 0%, #b8934a 100%) !important;
            transform: translateY(-2px) !important;
        }
        
        a { color: #156b68 !important; }
        a:hover { color: #caa453 !important; }
    </style>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    
    <!-- Skip Link for Accessibility -->
    <a class="skip-link screen-reader-text" href="#main">
        <?php _e('Skip to content', 'arab-board-event'); ?>
    </a>

    <div id="page" class="site">
        
        <!-- Loading Screen -->
        <div id="loading-screen" class="loading-screen">
            <div class="loading-content">
                <div class="loading-logo">
                    <?php if (has_site_icon()) : ?>
                        <img src="<?php echo get_site_icon_url(200); ?>" alt="<?php bloginfo('name'); ?>">
                    <?php else : ?>
                        <div class="logo-placeholder">
                            <h2><?php bloginfo('name'); ?></h2>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="loading-spinner">
                    <div class="spinner"></div>
                </div>
                <p class="loading-text">جاري التحميل...</p>
            </div>
        </div>

        <!-- Header -->
        <header id="masthead" class="site-header">
            <div class="container">
                <div class="header-content">
                    
                    <!-- Site Branding -->
                    <div class="site-branding">
                        <?php if (has_custom_logo()) : ?>
                            <div class="site-logo">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="site-info">
                            <?php if (is_front_page() && is_home()) : ?>
                                <h1 class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h1>
                            <?php else : ?>
                                <p class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                            
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                            ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Primary Navigation -->
                    <nav id="site-navigation" class="main-navigation">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="menu-icon"></span>
                            <span class="menu-text"><?php _e('Menu', 'arab-board-event'); ?></span>
                        </button>
                        
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </nav>

                    <!-- Header Actions -->
                    <div class="header-actions">
                        <!-- Search Toggle -->
                        <button class="search-toggle" aria-expanded="false">
                            <span class="search-icon">🔍</span>
                            <span class="screen-reader-text"><?php _e('Search', 'arab-board-event'); ?></span>
                        </button>
                        
                        <!-- Language Toggle (if not on front page) -->
                        <?php if (!is_front_page()) : ?>
                        <div class="language-toggle-header">
                            <button class="lang-btn" data-lang="ar">عربي</button>
                            <button class="lang-btn" data-lang="en">EN</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Search Form -->
                <div class="header-search">
                    <div class="search-form-container">
                        <?php get_search_form(); ?>
                        <button class="search-close">&times;</button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay">
            <div class="mobile-menu-content">
                <div class="mobile-menu-header">
                    <h3><?php bloginfo('name'); ?></h3>
                    <button class="mobile-menu-close">&times;</button>
                </div>
                
                <nav class="mobile-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'mobile-menu',
                        'container'      => false,
                        'fallback_cb'    => 'wp_page_menu',
                    ));
                    ?>
                </nav>
                
                <div class="mobile-menu-footer">
                    <div class="mobile-search">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs (if not on front page) -->
        <?php if (!is_front_page()) : ?>
        <nav class="breadcrumbs">
            <div class="container">
                <ul class="breadcrumb-list">
                    <li><a href="<?php echo home_url(); ?>"><?php _e('Home', 'arab-board-event'); ?></a></li>
                    <?php
                    if (is_category()) {
                        echo '<li>' . single_cat_title('', false) . '</li>';
                    } elseif (is_single()) {
                        echo '<li>' . get_the_title() . '</li>';
                    } elseif (is_page()) {
                        echo '<li>' . get_the_title() . '</li>';
                    } elseif (is_search()) {
                        echo '<li>' . __('Search Results', 'arab-board-event') . '</li>';
                    } elseif (is_archive()) {
                        echo '<li>' . get_the_archive_title() . '</li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <?php endif; ?>

        <!-- Page Progress Bar -->
        <div class="page-progress">
            <div class="progress-bar"></div>
        </div>

        <!-- Accessibility Features -->
        <div class="accessibility-toolbar">
            <button class="accessibility-toggle" title="أدوات إمكانية الوصول">
                ♿
            </button>
            <div class="accessibility-options">
                <button class="font-size-increase" title="تكبير الخط">A+</button>
                <button class="font-size-decrease" title="تصغير الخط">A-</button>
                <button class="high-contrast-toggle" title="تباين عالي">◐</button>
                <button class="focus-highlight-toggle" title="تمييز التركيز">⭘</button>
            </div>
        </div>

        <!-- Site Content Wrapper -->
        <div id="content" class="site-content">