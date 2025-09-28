/**
 * PDF Viewer for Arab Board Event 2025
 * Handles PDF viewing with controls
 */

(function() {
    'use strict';
    
    // PDF.js configuration
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }
    
    // PDF instances storage
    const pdfInstances = {};
    
    // Initialize PDF viewers when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializePDFViewers();
        setupPDFControls();
    });
    
    /**
     * Initialize all PDF viewers
     */
    function initializePDFViewers() {
        // Check if we have PDF files to load
        if (typeof window.pdfFiles === 'undefined') {
            console.log('No PDF files defined');
            return;
        }
        
        // Load each PDF file
        Object.keys(window.pdfFiles).forEach(function(day) {
            const pdfUrl = window.pdfFiles[day];
            if (pdfUrl) {
                loadPDF(day, pdfUrl);
            }
        });
    }
    
    /**
     * Load PDF file
     */
    function loadPDF(day, url) {
        const canvas = document.getElementById('pdf-canvas-' + day);
        const loadingElement = document.getElementById('pdf-loading-' + day);
        const pageNumElement = document.getElementById('page-num-' + day);
        const pageCountElement = document.getElementById('page-count-' + day);
        
        if (!canvas || typeof pdfjsLib === 'undefined') {
            console.error('PDF canvas not found or PDF.js not loaded for day:', day);
            return;
        }
        
        // Show loading
        if (loadingElement) {
            loadingElement.style.display = 'block';
        }
        
        // Load PDF
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            console.log('PDF loaded for day:', day);
            
            // Store PDF instance
            pdfInstances[day] = {
                pdf: pdf,
                pageNum: 1,
                pageRendering: false,
                pageNumPending: null,
                scale: 1.0,
                canvas: canvas,
                ctx: canvas.getContext('2d')
            };
            
            // Update page count
            if (pageCountElement) {
                pageCountElement.textContent = pdf.numPages;
            }
            
            // Render first page
            renderPage(day, 1);
            
            // Hide loading
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }
            
        }).catch(function(error) {
            console.error('Error loading PDF for day ' + day + ':', error);
            
            // Hide loading and show error
            if (loadingElement) {
                loadingElement.innerHTML = '<p>خطأ في تحميل ملف PDF</p>';
            }
        });
    }
    
    /**
     * Render specific page
     */
    function renderPage(day, num) {
        const instance = pdfInstances[day];
        if (!instance) return;
        
        instance.pageRendering = true;
        
        // Get page
        instance.pdf.getPage(num).then(function(page) {
            const viewport = page.getViewport({ scale: instance.scale });
            instance.canvas.height = viewport.height;
            instance.canvas.width = viewport.width;
            
            // Render PDF page into canvas context
            const renderContext = {
                canvasContext: instance.ctx,
                viewport: viewport
            };
            
            const renderTask = page.render(renderContext);
            
            // Wait for rendering to finish
            renderTask.promise.then(function() {
                instance.pageRendering = false;
                
                if (instance.pageNumPending !== null) {
                    // New page rendering is pending
                    renderPage(day, instance.pageNumPending);
                    instance.pageNumPending = null;
                }
                
                // Update page number display
                const pageNumElement = document.getElementById('page-num-' + day);
                if (pageNumElement) {
                    pageNumElement.textContent = num;
                }
                
                instance.pageNum = num;
            });
        });
    }
    
    /**
     * Queue page rendering
     */
    function queueRenderPage(day, num) {
        const instance = pdfInstances[day];
        if (!instance) return;
        
        if (instance.pageRendering) {
            instance.pageNumPending = num;
        } else {
            renderPage(day, num);
        }
    }
    
    /**
     * Show previous page
     */
    function onPrevPage(day) {
        const instance = pdfInstances[day];
        if (!instance) return;
        
        if (instance.pageNum <= 1) {
            return;
        }
        instance.pageNum--;
        queueRenderPage(day, instance.pageNum);
    }
    
    /**
     * Show next page
     */
    function onNextPage(day) {
        const instance = pdfInstances[day];
        if (!instance || !instance.pdf) return;
        
        if (instance.pageNum >= instance.pdf.numPages) {
            return;
        }
        instance.pageNum++;
        queueRenderPage(day, instance.pageNum);
    }
    
    /**
     * Zoom in
     */
    function zoomIn(day) {
        const instance = pdfInstances[day];
        if (!instance) return;
        
        instance.scale += 0.25;
        if (instance.scale > 3.0) {
            instance.scale = 3.0;
        }
        queueRenderPage(day, instance.pageNum);
    }
    
    /**
     * Zoom out
     */
    function zoomOut(day) {
        const instance = pdfInstances[day];
        if (!instance) return;
        
        instance.scale -= 0.25;
        if (instance.scale < 0.5) {
            instance.scale = 0.5;
        }
        queueRenderPage(day, instance.pageNum);
    }
    
    /**
     * Setup PDF controls
     */
    function setupPDFControls() {
        // Previous page buttons
        document.addEventListener('click', function(e) {
            if (e.target.id === 'prev-page-day1') {
                e.preventDefault();
                onPrevPage('day1');
            } else if (e.target.id === 'prev-page-day2') {
                e.preventDefault();
                onPrevPage('day2');
            }
        });
        
        // Next page buttons
        document.addEventListener('click', function(e) {
            if (e.target.id === 'next-page-day1') {
                e.preventDefault();
                onNextPage('day1');
            } else if (e.target.id === 'next-page-day2') {
                e.preventDefault();
                onNextPage('day2');
            }
        });
        
        // Zoom buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('zoom-in')) {
                e.preventDefault();
                const target = e.target.getAttribute('data-target');
                if (target) {
                    zoomIn(target);
                }
            } else if (e.target.classList.contains('zoom-out')) {
                e.preventDefault();
                const target = e.target.getAttribute('data-target');
                if (target) {
                    zoomOut(target);
                }
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const activeSection = document.querySelector('.day-section:target, .day-section.active');
            if (!activeSection) return;
            
            const dayId = activeSection.id;
            
            switch(e.key) {
                case 'ArrowLeft':
                    if (document.dir === 'rtl' || document.documentElement.dir === 'rtl') {
                        onNextPage(dayId);
                    } else {
                        onPrevPage(dayId);
                    }
                    break;
                case 'ArrowRight':
                    if (document.dir === 'rtl' || document.documentElement.dir === 'rtl') {
                        onPrevPage(dayId);
                    } else {
                        onNextPage(dayId);
                    }
                    break;
                case '+':
                case '=':
                    zoomIn(dayId);
                    break;
                case '-':
                    zoomOut(dayId);
                    break;
            }
        });
        
        // Touch/swipe support for mobile
        setupTouchControls();
    }
    
    /**
     * Setup touch controls for mobile
     */
    function setupTouchControls() {
        const pdfContainers = document.querySelectorAll('.pdf-container');
        
        pdfContainers.forEach(function(container) {
            let startX = 0;
            let startY = 0;
            
            container.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });
            
            container.addEventListener('touchend', function(e) {
                if (!startX || !startY) return;
                
                const endX = e.changedTouches[0].clientX;
                const endY = e.changedTouches[0].clientY;
                
                const diffX = startX - endX;
                const diffY = startY - endY;
                
                // Only process horizontal swipes
                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    const daySection = container.closest('.day-section');
                    if (!daySection) return;
                    
                    const day = daySection.id;
                    
                    if (diffX > 0) {
                        // Swipe left - next page (in RTL, this is previous)
                        if (document.dir === 'rtl' || document.documentElement.dir === 'rtl') {
                            onPrevPage(day);
                        } else {
                            onNextPage(day);
                        }
                    } else {
                        // Swipe right - previous page (in RTL, this is next)
                        if (document.dir === 'rtl' || document.documentElement.dir === 'rtl') {
                            onNextPage(day);
                        } else {
                            onPrevPage(day);
                        }
                    }
                }
                
                startX = 0;
                startY = 0;
            }, { passive: true });
        });
    }
    
    /**
     * Resize PDF canvas on window resize
     */
    window.addEventListener('resize', function() {
        setTimeout(function() {
            Object.keys(pdfInstances).forEach(function(day) {
                const instance = pdfInstances[day];
                if (instance && instance.pageNum) {
                    queueRenderPage(day, instance.pageNum);
                }
            });
        }, 100);
    });
    
    // Export functions for external use
    window.pdfViewer = {
        renderPage: renderPage,
        onPrevPage: onPrevPage,
        onNextPage: onNextPage,
        zoomIn: zoomIn,
        zoomOut: zoomOut,
        getInstance: function(day) {
            return pdfInstances[day];
        }
    };
    
})();