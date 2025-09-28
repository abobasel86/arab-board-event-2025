/**
 * Arab Board Event 2025 - Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        initMediaUploader();
        initScheduleManager();
        initQRCardsManager();
        initFormValidation();
        initDragAndDrop();
        initColorPickers();
    });
    
    /**
     * Initialize Media Uploader
     */
    function initMediaUploader() {
        let mediaUploader;
        
        // PDF Upload
        $(document).on('click', '.upload-pdf-btn', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const targetInput = $('#' + button.data('target'));
            const previewContainer = $('#' + button.data('target') + '_preview');
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: 'اختر ملف PDF',
                button: {
                    text: 'اختيار الملف'
                },
                multiple: false,
                library: {
                    type: 'application/pdf'
                }
            });
            
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                targetInput.val(attachment.url);
                previewContainer.html(`<a href="${attachment.url}" target="_blank">عرض PDF: ${attachment.filename}</a>`);
                showNotification('تم رفع ملف PDF بنجاح', 'success');
            });
            
            mediaUploader.open();
        });
        
        // Remove PDF
        $(document).on('click', '.remove-pdf-btn', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const targetInput = $('#' + button.data('target'));
            const previewContainer = $('#' + button.data('target') + '_preview');
            
            targetInput.val('');
            previewContainer.empty();
            showNotification('تم حذف ملف PDF', 'info');
        });
        
        // Images Upload
        $(document).on('click', '.add-images-btn', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const targetContainer = $('#' + button.data('target'));
            const inputName = button.data('name');
            
            const imageUploader = wp.media({
                title: 'اختر الصور',
                button: {
                    text: 'إضافة الصور'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });
            
            imageUploader.on('select', function() {
                const attachments = imageUploader.state().get('selection').toJSON();
                
                attachments.forEach(function(attachment) {
                    const imageItem = `
                        <div class="image-item">
                            <img src="${attachment.url}" style="max-width: 100px;" />
                            <input type="hidden" name="${inputName}[]" value="${attachment.url}" />
                            <button type="button" class="button remove-image-btn">حذف</button>
                        </div>
                    `;
                    targetContainer.append(imageItem);
                });
                
                showNotification(`تم إضافة ${attachments.length} صورة`, 'success');
            });
            
            imageUploader.open();
        });
        
        // Remove Image
        $(document).on('click', '.remove-image-btn', function(e) {
            e.preventDefault();
            $(this).closest('.image-item').remove();
            showNotification('تم حذف الصورة', 'info');
        });
        
        // QR Image Upload
        $(document).on('click', '.upload-qr-btn', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const qrCard = button.closest('.qr-card-item');
            const hiddenInput = qrCard.find('.qr-image-input');
            const previewContainer = qrCard.find('.qr-image-preview');
            
            const qrUploader = wp.media({
                title: 'اختر صورة QR',
                button: {
                    text: 'اختيار الصورة'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            qrUploader.on('select', function() {
                const attachment = qrUploader.state().get('selection').first().toJSON();
                hiddenInput.val(attachment.url);
                previewContainer.html(`<img src="${attachment.url}" style="max-width: 100px;" />`);
                showNotification('تم رفع صورة QR بنجاح', 'success');
            });
            
            qrUploader.open();
        });
        
        // Remove QR Image
        $(document).on('click', '.remove-qr-image-btn', function(e) {
            e.preventDefault();
            
            const qrCard = $(this).closest('.qr-card-item');
            qrCard.find('.qr-image-input').val('');
            qrCard.find('.qr-image-preview').empty();
            showNotification('تم حذف صورة QR', 'info');
        });
    }
    
    /**
     * Initialize Schedule Manager
     */
    function initScheduleManager() {
        let scheduleRowIndex = 1000; // Start with high number to avoid conflicts
        
        // Add Schedule Row
        $(document).on('click', '.add-schedule-row', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const day = button.data('day');
            const tbody = button.closest('.schedule-admin').find('.schedule-tbody');
            
            const newRow = `
                <tr>
                    <td><input type="time" name="day${day}_schedule[${scheduleRowIndex}][time]" value="" /></td>
                    <td><input type="text" name="day${day}_schedule[${scheduleRowIndex}][activity_ar]" value="" placeholder="النشاط بالعربي" /></td>
                    <td><input type="text" name="day${day}_schedule[${scheduleRowIndex}][activity_en]" value="" placeholder="Activity in English" /></td>
                    <td><button type="button" class="button remove-schedule-row">حذف</button></td>
                </tr>
            `;
            
            tbody.append(newRow);
            scheduleRowIndex++;
            showNotification('تم إضافة صف جديد للجدول', 'success');
        });
        
        // Remove Schedule Row
        $(document).on('click', '.remove-schedule-row', function(e) {
            e.preventDefault();
            
            if (confirm('هل أنت متأكد من حذف هذا الصف؟')) {
                $(this).closest('tr').remove();
                showNotification('تم حذف الصف من الجدول', 'info');
            }
        });
        
        // Sort Schedule Rows
        $('.schedule-tbody').sortable({
            handle: 'td:first-child',
            placeholder: 'ui-state-highlight',
            update: function(event, ui) {
                showNotification('تم تغيير ترتيب الجدول', 'info');
            }
        });
    }
    
    /**
     * Initialize QR Cards Manager
     */
    function initQRCardsManager() {
        let qrCardIndex = 1000; // Start with high number to avoid conflicts
        
        // Add QR Card
        $('.add-qr-card').on('click', function(e) {
            e.preventDefault();
            
            const container = $('#qr_cards_admin');
            
            const newCard = `
                <div class="qr-card-item">
                    <table class="form-table">
                        <tr>
                            <th><label>اسم البطاقة (عربي):</label></th>
                            <td><input type="text" name="qr_cards[${qrCardIndex}][name_ar]" value="" placeholder="مثال: موقع المجلس العربي" /></td>
                        </tr>
                        <tr>
                            <th><label>اسم البطاقة (إنجليزي):</label></th>
                            <td><input type="text" name="qr_cards[${qrCardIndex}][name_en]" value="" placeholder="Example: Arab Board Website" /></td>
                        </tr>
                        <tr>
                            <th><label>الرابط:</label></th>
                            <td><input type="url" name="qr_cards[${qrCardIndex}][url]" value="" placeholder="https://example.com" /></td>
                        </tr>
                        <tr>
                            <th><label>صورة QR:</label></th>
                            <td>
                                <input type="hidden" name="qr_cards[${qrCardIndex}][qr_image]" value="" class="qr-image-input" />
                                <button type="button" class="button upload-qr-btn">رفع صورة QR</button>
                                <button type="button" class="button remove-qr-image-btn">حذف الصورة</button>
                                <div class="qr-image-preview"></div>
                            </td>
                        </tr>
                    </table>
                    <button type="button" class="button remove-qr-card">حذف البطاقة</button>
                    <hr />
                </div>
            `;
            
            container.append(newCard);
            qrCardIndex++;
            showNotification('تم إضافة بطاقة QR جديدة', 'success');
        });
        
        // Remove QR Card
        $(document).on('click', '.remove-qr-card', function(e) {
            e.preventDefault();
            
            if (confirm('هل أنت متأكد من حذف هذه البطاقة؟')) {
                $(this).closest('.qr-card-item').remove();
                showNotification('تم حذف بطاقة QR', 'info');
            }
        });
        
        // Sort QR Cards
        $('#qr_cards_admin').sortable({
            items: '.qr-card-item',
            handle: '.qr-card-item',
            placeholder: 'ui-state-highlight',
            update: function(event, ui) {
                showNotification('تم تغيير ترتيب بطاقات QR', 'info');
            }
        });
    }
    
    /**
     * Initialize Form Validation
     */
    function initFormValidation() {
        // Validate URLs
        $(document).on('blur', 'input[type="url"]', function() {
            const url = $(this).val();
            if (url && !isValidURL(url)) {
                $(this).addClass('error');
                showNotification('الرجاء إدخال رابط صحيح', 'error');
            } else {
                $(this).removeClass('error');
            }
        });
        
        // Validate dates
        $(document).on('change', '#event_date_start, #event_date_end', function() {
            const startDate = $('#event_date_start').val();
            const endDate = $('#event_date_end').val();
            
            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                $('#event_date_end').addClass('error');
                showNotification('تاريخ النهاية يجب أن يكون بعد تاريخ البداية', 'error');
            } else {
                $('#event_date_end').removeClass('error');
            }
        });
        
        // Auto-save functionality
        let autoSaveTimer;
        $('input, textarea, select').on('change', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                // Auto-save logic would go here
                console.log('Auto-saving...');
            }, 2000);
        });
    }
    
    /**
     * Initialize Drag and Drop
     */
    function initDragAndDrop() {
        // Make images gallery sortable
        $('.images-gallery-admin').sortable({
            items: '.image-item',
            placeholder: 'ui-state-highlight',
            update: function(event, ui) {
                showNotification('تم تغيير ترتيب الصور', 'info');
            }
        });
        
        // Drag and drop file upload
        $('.images-gallery-admin, .pdf-preview').on({
            dragover: function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            },
            dragleave: function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            },
            drop: function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
                
                const files = e.originalEvent.dataTransfer.files;
                handleFileUpload(files, $(this));
            }
        });
    }
    
    /**
     * Initialize Color Pickers
     */
    function initColorPickers() {
        $('.color-picker').wpColorPicker({
            change: function(event, ui) {
                showNotification('تم تغيير اللون', 'info');
            }
        });
    }
    
    /**
     * Handle File Upload
     */
    function handleFileUpload(files, container) {
        if (files.length === 0) return;
        
        const file = files[0];
        const fileType = file.type;
        
        if (fileType.startsWith('image/')) {
            // Handle image upload
            const reader = new FileReader();
            reader.onload = function(e) {
                // Upload logic would go here
                showNotification('جاري رفع الصورة...', 'info');
            };
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            // Handle PDF upload
            showNotification('جاري رفع ملف PDF...', 'info');
        } else {
            showNotification('نوع الملف غير مدعوم', 'error');
        }
    }
    
    /**
     * Show Notification
     */
    function showNotification(message, type = 'info') {
        const notificationClass = `notice notice-${type} is-dismissible`;
        const notification = `
            <div class="${notificationClass}">
                <p>${message}</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">إغلاق الإشعار</span>
                </button>
            </div>
        `;
        
        // Remove existing notifications
        $('.notice').remove();
        
        // Add new notification
        $('.wrap h1').after(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            $('.notice').fadeOut();
        }, 5000);
    }
    
    /**
     * Validate URL
     */
    function isValidURL(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    /**
     * Generate QR Code Preview
     */
    function generateQRPreview(url, container) {
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${encodeURIComponent(url)}`;
        const previewHtml = `
            <div class="qr-preview">
                <img src="${qrApiUrl}" alt="QR Code Preview" style="max-width: 100px;" />
                <p>معاينة QR Code</p>
            </div>
        `;
        container.html(previewHtml);
    }
    
    /**
     * Auto-generate QR preview when URL is entered
     */
    $(document).on('blur', 'input[name*="[url]"]', function() {
        const url = $(this).val();
        if (url && isValidURL(url)) {
            const qrCard = $(this).closest('.qr-card-item');
            const previewContainer = qrCard.find('.qr-image-preview');
            
            if (!qrCard.find('.qr-image-input').val()) {
                generateQRPreview(url, previewContainer);
            }
        }
    });
    
    /**
     * Copy to Clipboard functionality
     */
    function initClipboard() {
        $(document).on('click', '.copy-btn', function(e) {
            e.preventDefault();
            
            const textToCopy = $(this).data('copy');
            navigator.clipboard.writeText(textToCopy).then(function() {
                showNotification('تم نسخ النص', 'success');
            }, function() {
                showNotification('فشل في نسخ النص', 'error');
            });
        });
    }
    
    // Initialize clipboard functionality
    initClipboard();
    
})(jQuery);