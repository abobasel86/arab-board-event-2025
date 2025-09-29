/**
 * Arab Board Event 2025 - Main JavaScript
 */

(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        initLanguageToggle();
        initLightbox();
        initPDFControls();
        initScrollAnimations();
        initSmoothScroll();
    });
    
    /**
     * Language Toggle Functionality
     */
    function initLanguageToggle() {
        const languageButtons = $('.language-toggle .lang-btn');

        languageButtons.on('click', function() {
            const selectedLang = $(this).data('lang');

            if (!selectedLang) {
                return;
            }

            const currentLang = ($('html').attr('dir') === 'rtl' || $('body').hasClass('rtl')) ? 'ar' : 'en';

            if (selectedLang !== currentLang) {
                switchLanguage(selectedLang);
            }

            languageButtons.removeClass('active');
            $(this).addClass('active');
        });
    }
    
    /**
     * Switch Language
     */
    function switchLanguage(lang) {
        if (lang === 'ar') {
            $('body').removeClass('ltr').addClass('rtl').attr('dir', 'rtl');
            $('html').attr('lang', 'ar').attr('dir', 'rtl');
        } else {
            $('body').removeClass('rtl').addClass('ltr').attr('dir', 'ltr');
            $('html').attr('lang', 'en').attr('dir', 'ltr');
        }

        // Toggle content visibility based on language
        $('.lang-ar').toggle(lang === 'ar');
        $('.lang-en').toggle(lang === 'en');

        // Save language preference
        try {
            localStorage.setItem('arabBoard2025Lang', lang);
        } catch (error) {
            console.warn('Unable to persist language preference:', error);
        }

        // Update text content dynamically
        updateTextContent(lang);
    }
    
    /**
     * Update text content based on language
     */
    function updateTextContent(lang) {
        const translations = {
            ar: {
                'download-pdf': 'تحميل PDF',
                'fullscreen': 'ملء الشاشة',
                'day-1': 'اليوم الأول',
                'day-2': 'اليوم الثاني',
                'schedule': 'جدول الأعمال',
                'images': 'الصور',
                'documents': 'الوثائق',
                'important-links': 'الروابط المهمة',
                'visit-link': 'زيارة الرابط',
                'time': 'الوقت',
                'activity': 'النشاط'
            },
            en: {
                'download-pdf': 'Download PDF',
                'fullscreen': 'Fullscreen',
                'day-1': 'Day 1',
                'day-2': 'Day 2',
                'schedule': 'Schedule',
                'images': 'Images',
                'documents': 'Documents',
                'important-links': 'Important Links',
                'visit-link': 'Visit Link',
                'time': 'Time',
                'activity': 'Activity'
            }
        };
        
        const texts = translations[lang];
        
        // Update elements with data-translate attribute
        $('[data-translate]').each(function() {
            const key = $(this).data('translate');
            if (texts[key]) {
                $(this).text(texts[key]);
            }
        });
    }
    
    /**
     * Initialize Image Lightbox
     */
    function initLightbox() {
        // Create lightbox HTML
        if ($('#lightbox').length === 0) {
            $('body').append(`
                <div id="lightbox" class="lightbox">
                    <div class="lightbox-content">
                        <span class="lightbox-close">&times;</span>
                        <img id="lightbox-image" src="" alt="">
                    </div>
                </div>
            `);
        }
        
        const $lightbox = $('#lightbox');
        const $lightboxImage = $('#lightbox-image');
        
        // Open lightbox when gallery item is clicked
        $(document).on('click', '.gallery-item', function() {
            const imageSrc = $(this).find('img').attr('src');
            const imageAlt = $(this).find('img').attr('alt');
            
            $lightboxImage.attr('src', imageSrc).attr('alt', imageAlt);
            $lightbox.fadeIn(300);
            $('body').addClass('lightbox-open');
        });
        
        // Close lightbox
        $(document).on('click', '.lightbox-close, #lightbox', function(e) {
            if (e.target === this) {
                $lightbox.fadeOut(300);
                $('body').removeClass('lightbox-open');
            }
        });
        
        // Close lightbox with Escape key
        $(document).on('keyup', function(e) {
            if (e.keyCode === 27 && $lightbox.is(':visible')) {
                $lightbox.fadeOut(300);
                $('body').removeClass('lightbox-open');
            }
        });
    }
    
    /**
     * Initialize PDF Controls
     */
    function initPDFControls() {
        // Fullscreen PDF viewer
        $(document).on('click', '.fullscreen-btn', function() {
            const pdfViewer = $(this).closest('.pdf-section').find('.pdf-viewer')[0];
            
            if (pdfViewer.requestFullscreen) {
                pdfViewer.requestFullscreen();
            } else if (pdfViewer.webkitRequestFullscreen) {
                pdfViewer.webkitRequestFullscreen();
            } else if (pdfViewer.msRequestFullscreen) {
                pdfViewer.msRequestFullscreen();
            }
        });
        
        // Download PDF
        $(document).on('click', '.download-btn', function(e) {
            const pdfUrl = $(this).attr('href');
            
            // Create temporary download link
            const downloadLink = document.createElement('a');
            downloadLink.href = pdfUrl;
            downloadLink.download = pdfUrl.split('/').pop();
            downloadLink.style.display = 'none';
            
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            
            // Prevent default link behavior
            e.preventDefault();
        });
    }
    
    /**
     * Initialize Scroll Animations
     */
    function initScrollAnimations() {
        // Check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        
        // Add animation classes when elements come into view
        function checkAnimations() {
            $('.card, .gallery-item, .qr-card, .schedule-section, .pdf-section').each(function() {
                if (isInViewport(this) && !$(this).hasClass('animated')) {
                    $(this).addClass('animated slide-in-up');
                }
            });
        }
        
        // Initial check
        checkAnimations();
        
        // Check on scroll
        $(window).on('scroll', function() {
            checkAnimations();
        });
    }
    
    /**
     * Initialize Smooth Scroll
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800, 'easeInOutQuad');
            }
        });
    }
    
    /**
     * QR Code Scanner (if device has camera)
     */
    function initQRScanner() {
        if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
            $('.qr-scan-btn').show().on('click', function() {
                // QR scanner implementation would go here
                // This would require additional libraries like QuaggaJS or ZXing
                console.log('QR Scanner would be implemented here');
            });
        }
    }
    
    /**
     * Handle PDF loading errors
     */
    function handlePDFErrors() {
        $('.pdf-viewer').on('error', function() {
            const $pdfSection = $(this).closest('.pdf-section');
            const fallbackMessage = `
                <div class="pdf-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Unable to load PDF. <a href="${$(this).attr('src')}" target="_blank">Click here to download</a></p>
                </div>
            `;
            
            $pdfSection.find('.pdf-viewer').replaceWith(fallbackMessage);
        });
    }
    
    /**
     * Initialize Print Functionality
     */
    function initPrintFunctionality() {
        $('.print-btn').on('click', function() {
            window.print();
        });
    }
    
    /**
     * Initialize Share Functionality
     */
    function initShareFunctionality() {
        if (navigator.share) {
            $('.share-btn').show().on('click', function() {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            });
        }
    }
    
    /**
     * Load saved language preference
     */
    function loadLanguagePreference() {
        let savedLang = null;

        try {
            savedLang = localStorage.getItem('arabBoard2025Lang');
        } catch (error) {
            console.warn('Unable to read stored language preference:', error);
        }

        if (savedLang === 'ar' || savedLang === 'en') {
            $(`.language-toggle .lang-btn[data-lang="${savedLang}"]`).trigger('click');
        } else {
            // Default to Arabic if no preference is saved
            switchLanguage('ar');
            $('.language-toggle .lang-btn[data-lang="ar"]').addClass('active');
        }
    }
    
    // Load language preference on page load
    $(window).on('load', function() {
        loadLanguagePreference();
        handlePDFErrors();
        initPrintFunctionality();
        initShareFunctionality();
        initQRScanner();
    });
    
    // Utility function for easing
    $.easing.easeInOutQuad = function(x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t + b;
        return -c / 2 * ((--t) * (t - 2) - 1) + b;
    };
    
})(jQuery);