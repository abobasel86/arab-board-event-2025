        </div><!-- #content -->

        <!-- Site Footer -->
        <footer id="colophon" class="site-footer">
            <div class="container">
                
                <!-- Footer Widgets -->
                <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
                <div class="footer-widgets">
                    <div class="footer-widget-area">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (is_active_sidebar('footer-2')) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (is_active_sidebar('footer-3')) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Footer Main Content -->
                <div class="footer-main">
                    <div class="footer-content">
                        
                        <!-- Event Info -->
                        <div class="footer-section event-info">
                            <h3>
                                <span class="lang-ar">ملتقى المجلس العربي للاختصاصات الصحية 2025</span>
                                <span class="lang-en" style="display: none;">Arab Board Health Specialties Conference 2025</span>
                            </h3>
                            
                            <div class="event-details">
                                <p class="event-location">
                                    <i class="icon-location"></i>
                                    <span class="lang-ar">المملكة العربية السعودية</span>
                                    <span class="lang-en" style="display: none;">Saudi Arabia</span>
                                </p>
                                
                                <p class="event-date">
                                    <i class="icon-calendar"></i>
                                    <span>2025</span>
                                </p>
                            </div>
                            
                            <div class="event-description">
                                <p class="lang-ar">
                                    ملتقى علمي متخصص يجمع أبرز الخبراء والاختصاصيين في المجال الصحي من جميع أنحاء العالم العربي.
                                </p>
                                <p class="lang-en" style="display: none;">
                                    A specialized scientific conference bringing together leading experts and specialists in the health field from across the Arab world.
                                </p>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="footer-section quick-links">
                            <h4>
                                <span class="lang-ar">روابط سريعة</span>
                                <span class="lang-en" style="display: none;">Quick Links</span>
                            </h4>
                            
                            <ul class="footer-menu">
                                <li><a href="#day1">
                                    <span class="lang-ar">اليوم الأول</span>
                                    <span class="lang-en" style="display: none;">Day One</span>
                                </a></li>
                                <li><a href="#day2">
                                    <span class="lang-ar">اليوم الثاني</span>
                                    <span class="lang-en" style="display: none;">Day Two</span>
                                </a></li>
                                <li><a href="#qr-cards">
                                    <span class="lang-ar">بطاقات QR</span>
                                    <span class="lang-en" style="display: none;">QR Cards</span>
                                </a></li>
                                <li><a href="<?php echo admin_url(); ?>" target="_blank">
                                    <span class="lang-ar">لوحة التحكم</span>
                                    <span class="lang-en" style="display: none;">Admin Panel</span>
                                </a></li>
                            </ul>
                        </div>

                        <!-- Contact Info -->
                        <div class="footer-section contact-info">
                            <h4>
                                <span class="lang-ar">معلومات التواصل</span>
                                <span class="lang-en" style="display: none;">Contact Information</span>
                            </h4>
                            
                            <div class="contact-details">
                                <p class="contact-item">
                                    <i class="icon-globe"></i>
                                    <a href="https://www.arabboard.org" target="_blank">www.arabboard.org</a>
                                </p>
                                
                                <p class="contact-item">
                                    <i class="icon-envelope"></i>
                                    <a href="mailto:info@arabboard.org">info@arabboard.org</a>
                                </p>
                                
                                <p class="contact-item">
                                    <i class="icon-location"></i>
                                    <span class="lang-ar">الأردن - عمان</span>
                                    <span class="lang-en" style="display: none;">Jordan - Amman</span>
                                </p>
                            </div>
                            
                            <!-- Social Media Links -->
                            <div class="social-media">
                                <h5>
                                    <span class="lang-ar">تابعنا</span>
                                    <span class="lang-en" style="display: none;">Follow Us</span>
                                </h5>
                                <div class="social-links">
                                    <a href="#" class="social-link facebook" target="_blank" title="Facebook">
                                        <span class="social-icon">📘</span>
                                    </a>
                                    <a href="#" class="social-link twitter" target="_blank" title="Twitter">
                                        <span class="social-icon">🐦</span>
                                    </a>
                                    <a href="#" class="social-link linkedin" target="_blank" title="LinkedIn">
                                        <span class="social-icon">💼</span>
                                    </a>
                                    <a href="#" class="social-link youtube" target="_blank" title="YouTube">
                                        <span class="social-icon">📺</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- App Download -->
                        <div class="footer-section app-download">
                            <h4>
                                <span class="lang-ar">حمل التطبيق</span>
                                <span class="lang-en" style="display: none;">Download App</span>
                            </h4>
                            
                            <div class="app-buttons">
                                <a href="#" class="app-button google-play" target="_blank">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-play-badge.png" alt="Google Play" style="height: 40px;">
                                </a>
                                <a href="#" class="app-button app-store" target="_blank">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/app-store-badge.png" alt="App Store" style="height: 40px;">
                                </a>
                            </div>
                            
                            <!-- QR Code for App Download -->
                            <div class="app-qr">
                                <p>
                                    <span class="lang-ar">امسح للتحميل</span>
                                    <span class="lang-en" style="display: none;">Scan to Download</span>
                                </p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?php echo urlencode(home_url()); ?>" alt="QR Code">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <div class="footer-bottom-content">
                        
                        <!-- Copyright -->
                        <div class="copyright">
                            <p>
                                <span class="lang-ar">
                                    © <?php echo date('Y'); ?> المجلس العربي للاختصاصات الصحية. جميع الحقوق محفوظة.
                                </span>
                                <span class="lang-en" style="display: none;">
                                    © <?php echo date('Y'); ?> Arab Board of Health Specializations. All rights reserved.
                                </span>
                            </p>
                        </div>

                        <!-- Footer Menu -->
                        <nav class="footer-nav">
                            <ul class="footer-nav-menu">
                                <li><a href="#">
                                    <span class="lang-ar">سياسة الخصوصية</span>
                                    <span class="lang-en" style="display: none;">Privacy Policy</span>
                                </a></li>
                                <li><a href="#">
                                    <span class="lang-ar">شروط الاستخدام</span>
                                    <span class="lang-en" style="display: none;">Terms of Use</span>
                                </a></li>
                                <li><a href="#">
                                    <span class="lang-ar">اتصل بنا</span>
                                    <span class="lang-en" style="display: none;">Contact Us</span>
                                </a></li>
                            </ul>
                        </nav>

                        <!-- Language Toggle -->
                        <div class="footer-language">
                            <button class="footer-lang-btn" data-lang="ar">العربية</button>
                            <span class="lang-separator">|</span>
                            <button class="footer-lang-btn" data-lang="en">English</button>
                        </div>

                    </div>
                </div>

                <!-- Scroll to Top Button -->
                <button class="scroll-to-top" title="العودة إلى الأعلى">
                    <span class="scroll-icon">⬆️</span>
                </button>

            </div>
        </footer>

        <!-- Back to Top Indicator -->
        <div class="back-to-top-indicator">
            <div class="indicator-progress"></div>
        </div>

    </div><!-- #page -->

    <!-- Print Styles -->
    <style media="print">
        .language-toggle,
        .floating-actions,
        .scroll-to-top,
        .footer-bottom,
        .social-media,
        .app-download {
            display: none !important;
        }
        
        .event-header {
            background: white !important;
            color: black !important;
        }
        
        .pdf-controls {
            display: none !important;
        }
        
        @page {
            margin: 1cm;
        }
        
        body {
            font-size: 12pt;
            line-height: 1.4;
        }
        
        h1, h2, h3 {
            page-break-after: avoid;
        }
        
        .qr-card {
            page-break-inside: avoid;
        }
    </style>

    <!-- WordPress Footer -->
    <?php wp_footer(); ?>

    <!-- Custom Footer Scripts -->
    <script>
        // Initialize app on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading screen
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                setTimeout(function() {
                    loadingScreen.style.opacity = '0';
                    setTimeout(function() {
                        loadingScreen.style.display = 'none';
                    }, 500);
                }, 1000);
            }
            
            // Initialize page progress
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset;
                const docHeight = document.body.scrollHeight - window.innerHeight;
                const scrollPercent = (scrollTop / docHeight) * 100;
                
                const progressBar = document.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.style.width = scrollPercent + '%';
                }
            });
            
            // Mobile menu toggle
            const menuToggle = document.querySelector('.menu-toggle');
            const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
            const mobileMenuClose = document.querySelector('.mobile-menu-close');
            
            if (menuToggle && mobileMenuOverlay) {
                menuToggle.addEventListener('click', function() {
                    mobileMenuOverlay.classList.add('active');
                    document.body.classList.add('menu-open');
                });
            }
            
            if (mobileMenuClose && mobileMenuOverlay) {
                mobileMenuClose.addEventListener('click', function() {
                    mobileMenuOverlay.classList.remove('active');
                    document.body.classList.remove('menu-open');
                });
            }
            
            // Search toggle
            const searchToggle = document.querySelector('.search-toggle');
            const headerSearch = document.querySelector('.header-search');
            const searchClose = document.querySelector('.search-close');
            
            if (searchToggle && headerSearch) {
                searchToggle.addEventListener('click', function() {
                    headerSearch.classList.toggle('active');
                    const searchInput = headerSearch.querySelector('input[type="search"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                });
            }
            
            if (searchClose && headerSearch) {
                searchClose.addEventListener('click', function() {
                    headerSearch.classList.remove('active');
                });
            }
            
            // Footer language toggle
            const footerLangButtons = document.querySelectorAll('.footer-lang-btn');
            footerLangButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const lang = this.getAttribute('data-lang');
                    if (typeof window.switchLanguage === 'function') {
                        window.switchLanguage(lang);
                    }
                });
            });
            
            // Accessibility features
            initAccessibilityFeatures();
        });
        
        // Accessibility Features
        function initAccessibilityFeatures() {
            const accessibilityToggle = document.querySelector('.accessibility-toggle');
            const accessibilityOptions = document.querySelector('.accessibility-options');
            
            if (accessibilityToggle && accessibilityOptions) {
                accessibilityToggle.addEventListener('click', function() {
                    accessibilityOptions.classList.toggle('active');
                });
                
                // Font size controls
                const fontIncrease = document.querySelector('.font-size-increase');
                const fontDecrease = document.querySelector('.font-size-decrease');
                
                if (fontIncrease) {
                    fontIncrease.addEventListener('click', function() {
                        adjustFontSize(1.1);
                    });
                }
                
                if (fontDecrease) {
                    fontDecrease.addEventListener('click', function() {
                        adjustFontSize(0.9);
                    });
                }
                
                // High contrast toggle
                const contrastToggle = document.querySelector('.high-contrast-toggle');
                if (contrastToggle) {
                    contrastToggle.addEventListener('click', function() {
                        document.body.classList.toggle('high-contrast');
                    });
                }
                
                // Focus highlight toggle
                const focusToggle = document.querySelector('.focus-highlight-toggle');
                if (focusToggle) {
                    focusToggle.addEventListener('click', function() {
                        document.body.classList.toggle('focus-highlight');
                    });
                }
            }
        }
        
        // Adjust font size
        function adjustFontSize(factor) {
            const currentSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
            const newSize = currentSize * factor;
            if (newSize >= 12 && newSize <= 24) {
                document.documentElement.style.fontSize = newSize + 'px';
            }
        }
    </script>

</body>
</html>