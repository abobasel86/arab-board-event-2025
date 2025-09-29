<?php
/**
 * وسيط مبسط لعرض وتحميل ملفات PDF من خلال ووردبريس.
 */

if (!defined('ABSPATH')) {
    $wp_path = dirname(dirname(dirname(dirname(__FILE__))));
    require_once $wp_path . '/wp-load.php';
}

// التحقق من وجود معاملات
if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(404);
    die('ملف غير موجود');
}

// فك تشفير الملف
$file_data = base64_decode($_GET['file']);
if (!$file_data) {
    http_response_code(400);
    die('بيانات غير صحيحة');
}

// استخراج المعلومات
$file_info = json_decode($file_data, true);
if (!$file_info || !isset($file_info['url'])) {
    http_response_code(400);
    die('بيانات الملف غير صحيحة');
}

$pdf_url = $file_info['url'];
$day = isset($file_info['day']) ? $file_info['day'] : 'unknown';

// التحقق من صحة الرابط
if (!filter_var($pdf_url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die('رابط غير صحيح');
}

// Headers مضادة لـ IDM
header('X-Robots-Tag: noindex, nofollow, nosnippet, noarchive');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: no-referrer');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// معرف فريد لمنع الكشف
$unique_id = uniqid('pdf_', true);
header('X-Request-ID: ' . $unique_id);
header('X-Content-Source: embedded');

// إذا كان الطلب لعرض مباشر
if (isset($_GET['view']) && $_GET['view'] === 'direct') {
    // تحديد نوع المحتوى
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="document_' . $day . '.pdf"');
    
    // إنشاء context للطلب مع Headers مخفية
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: ar,en-US;q=0.7,en;q=0.3',
                'Accept-Encoding: identity',
                'DNT: 1',
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1',
                'Sec-Fetch-Dest: document',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-Site: cross-site'
            ],
            'timeout' => 30,
            'ignore_errors' => true
        ]
    ]);
    
    // جلب الملف وإرساله
    $file_content = file_get_contents($pdf_url, false, $context);
    
    if ($file_content === false) {
        http_response_code(404);
        die('لا يمكن الوصول للملف');
    }
    
    // إرسال المحتوى
    echo $file_content;
    exit;
}

// عرض الصفحة المدمجة
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>عارض PDF - <?php echo htmlspecialchars($day, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            height: 100vh;
            overflow: hidden;
            /* حماية من IDM */
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }
        
        .viewer-container {
            width: 100%;
            height: 100vh;
            position: relative;
            background: white;
            /* إخفاء من IDM */
            opacity: 0.999;
            transform: translateZ(0);
            will-change: transform;
        }
        
        .pdf-frame {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
            /* حماية متقدمة من IDM */
            pointer-events: auto;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            /* فلاتر دقيقة لتضليل IDM */
            filter: brightness(1.001) contrast(1.001);
            transform: scale(1.0001);
        }
        
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 18px;
            color: #156b68;
            z-index: 10;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #156b68;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* حماية من overlays IDM */
        body::before,
        body::after,
        .viewer-container::before,
        .viewer-container::after {
            content: none !important;
            display: none !important;
        }
        
        /* منع IDM من إدراج عناصر */
        [class*="idm"], [id*="idm"], [class*="download"], [id*="download"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            position: absolute !important;
            left: -9999px !important;
        }
    </style>
</head>
<body>
    <div class="viewer-container">
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>جاري تحميل المستند...</p>
        </div>
        
        <iframe 
            id="pdfFrame" 
            class="pdf-frame"
            style="display: none;"
            data-no-idm="true"
            data-anti-download="true"
            sandbox="allow-same-origin allow-scripts allow-forms allow-popups"
            loading="lazy">
        </iframe>
    </div>

    <script>
        // منع IDM من العمل
        (function() {
            'use strict';
            
            // حماية النافذة من IDM
            Object.defineProperty(window, 'external', {
                value: null,
                writable: false,
                configurable: false
            });
            
            // منع اختصارات IDM
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey && (e.key === 's' || e.key === 'd')) || 
                    (e.altKey && e.type === 'click')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }, true);
            
            // منع النقر الأيمن
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            }, true);
            
            // منع السحب والإفلات
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            }, true);
            
            // مراقب IDM
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) {
                            // حذف عناصر IDM
                            if (node.className && typeof node.className === 'string') {
                                if (node.className.toLowerCase().includes('idm') || 
                                    node.className.toLowerCase().includes('download')) {
                                    node.remove();
                                }
                            }
                        }
                    });
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true
            });
            
            // تحميل PDF المحمي
            window.addEventListener('load', function() {
                const frame = document.getElementById('pdfFrame');
                const loading = document.getElementById('loading');
                
                // إنشاء رابط محمي
                const protectedUrl = window.location.href + '&view=direct&t=' + Date.now();
                
                // تحميل في iframe
                frame.src = protectedUrl + '#toolbar=0&navpanes=0&scrollbar=1&view=FitH&embedded=true';
                
                // إخفاء loading بعد تحميل
                setTimeout(function() {
                    loading.style.display = 'none';
                    frame.style.display = 'block';
                }, 2000);
                
                // معالج تحميل iframe
                frame.onload = function() {
                    loading.style.display = 'none';
                    frame.style.display = 'block';
                };
                
                frame.onerror = function() {
                    loading.innerHTML = '<p style="color: red;">خطأ في تحميل المستند</p>';
                };
            });
        })();
    </script>
</body>
</html>