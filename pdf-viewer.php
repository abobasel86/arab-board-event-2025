<?php
/**
 * PDF Viewer - محسن لمنع IDM والتحميل التلقائي
 * يعرض PDF بطريقة آمنة ومباشرة في المتصفح
 */

// التحقق من المعاملات المطلوبة
if (!isset($_GET['pdf_viewer']) || $_GET['pdf_viewer'] !== '1') {
    http_response_code(404);
    die('صفحة غير موجودة');
}

if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    die('ملف غير محدد');
}

// فك تشفير رابط PDF
$pdf_url = base64_decode($_GET['file']);
if (!$pdf_url || !filter_var($pdf_url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die('رابط ملف غير صحيح');
}

$day = isset($_GET['day']) ? sanitize_text_field($_GET['day']) : 'unknown';

// Headers لمنع IDM والتحميل التلقائي
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// منع الكشف عن نوع الملف لـ IDM
header('Content-Disposition: inline');
header('X-Download-Options: noopen');
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>عارض PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f5f5;
            height: 100vh;
            overflow: hidden;
        }
        
        .pdf-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: white;
        }
        
        .pdf-header {
            background: #2196F3;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: between;
            align-items: center;
            font-size: 14px;
        }
        
        .pdf-viewer {
            flex: 1;
            width: 100%;
            height: calc(100vh - 50px);
            border: none;
            background: white;
        }
        
        .loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            background: #f8f9fa;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2196F3;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error {
            text-align: center;
            padding: 2rem;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            margin: 1rem;
            border-radius: 8px;
        }
        
        .error a {
            color: #2196F3;
            text-decoration: none;
        }
        
        .error a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        <div class="pdf-header">
            <span>📄 عارض PDF - <?php echo htmlspecialchars($day === 'day1' ? 'اليوم الأول' : 'اليوم الثاني'); ?></span>
        </div>
        
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>جاري تحميل الملف...</p>
        </div>
        
        <iframe 
            id="pdf-frame"
            class="pdf-viewer" 
            src="<?php echo htmlspecialchars($pdf_url); ?>#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH"
            style="display: none;"
            onload="hideLoading()"
            onerror="showError()">
        </iframe>
        
        <div class="error" id="error" style="display: none;">
            <h3>⚠️ تعذر عرض الملف</h3>
            <p>لا يمكن عرض ملف PDF في المتصفح.</p>
            <p><a href="<?php echo htmlspecialchars($pdf_url); ?>" target="_blank">انقر هنا لفتح الملف في نافذة جديدة</a></p>
        </div>
    </div>

    <script>
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('pdf-frame').style.display = 'block';
            console.log('✅ تم تحميل PDF بنجاح');
        }
        
        function showError() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('error').style.display = 'block';
            console.error('❌ فشل في تحميل PDF');
        }
        
        // تحقق من تحميل PDF بعد مهلة زمنية
        setTimeout(function() {
            const loading = document.getElementById('loading');
            if (loading.style.display !== 'none') {
                console.warn('⚠️ تأخير في تحميل PDF - إظهار خيارات بديلة');
                showError();
            }
        }, 10000); // 10 ثوان
        
        // منع النقر بالزر الأيمن لحماية إضافية
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        // منع اختصارات لوحة المفاتيح للتحميل
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                e.preventDefault();
                alert('التحميل غير متاح من هذه الصفحة');
            }
        });
    </script>
</body>
</html>