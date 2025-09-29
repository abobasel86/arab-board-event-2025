<?php
/**
 * PDF Proxy - واجهة وسيطة لعرض ملفات PDF دون أن تعترضها برامج التحميل.
 */

// تأكد من تحميل ووردبريس عند الوصول المباشر للملف.
if (!defined('ABSPATH')) {
    $wp_path = dirname(dirname(dirname(dirname(__FILE__))));
    require_once $wp_path . '/wp-load.php';
}

// التحقق من وجود البيانات المطلوبة.
if (!isset($_GET['file']) || empty($_GET['file'])) {
    wp_die('ملف غير موجود', 'PDF Proxy', array('response' => 404));
}

$file_data = base64_decode(wp_unslash($_GET['file']));
if (!$file_data) {
    wp_die('بيانات غير صحيحة', 'PDF Proxy', array('response' => 400));
}

$file_info = json_decode($file_data, true);
if (!is_array($file_info) || empty($file_info['url'])) {
    wp_die('بيانات الملف غير صحيحة', 'PDF Proxy', array('response' => 400));
}

$pdf_url = $file_info['url'];
$day = isset($file_info['day']) ? sanitize_title($file_info['day']) : 'document';

if (!filter_var($pdf_url, FILTER_VALIDATE_URL)) {
    wp_die('رابط غير صالح', 'PDF Proxy', array('response' => 400));
}

$view = 'embed';
if (isset($_GET['view'])) {
    $candidate = sanitize_key(wp_unslash($_GET['view']));
    if (in_array($candidate, array('direct', 'embed'), true)) {
        $view = $candidate;
    }
}

$headers = array(
    'X-Robots-Tag: noindex, nofollow, nosnippet, noarchive',
    'X-Content-Type-Options: nosniff',
    'Referrer-Policy: no-referrer',
    'Cache-Control: no-store, no-cache, must-revalidate, max-age=0',
    'Pragma: no-cache',
    'Expires: Thu, 01 Jan 1970 00:00:00 GMT',
);

foreach ($headers as $header) {
    header($header);
}

if ($view === 'direct') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . sanitize_file_name('document-' . $day . '.pdf') . '"');

    $context = stream_context_create(
        array(
            'http' => array(
                'method'  => 'GET',
                'header'  => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36\r\n" .
                             "Accept: application/pdf,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
                             "Accept-Language: ar,en-US;q=0.8,en;q=0.6\r\n" .
                             "Cache-Control: no-store\r\n",
                'timeout' => 30,
            ),
        )
    );

    $file_stream = @fopen($pdf_url, 'rb', false, $context);
    if (!$file_stream) {
        wp_die('تعذر الوصول إلى الملف', 'PDF Proxy', array('response' => 404));
    }

    while (!feof($file_stream)) {
        echo fread($file_stream, 8192);
        flush();
    }

    fclose($file_stream);
    exit;
}

$scheme = is_ssl() ? 'https://' : 'http://';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
$current_url = $scheme . $host . $request_uri;
$base_url = remove_query_arg(array('view', 't'), $current_url);
$direct_url = add_query_arg('view', 'direct', $base_url);

?><!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html(sprintf(__('عارض PDF - %s', 'arab-board-event'), ucwords(str_replace('-', ' ', $day)))); ?></title>
    <style>
        :root {
            color-scheme: light;
            font-family: 'Almarai', 'Cairo', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7f8;
            color: #134a47;
            min-height: 100vh;
        }

        .viewer-shell {
            position: relative;
            width: 100%;
            height: 100vh;
            max-height: 100vh;
            background: #ffffff;
            display: flex;
            flex-direction: column;
        }

        .pdf-frame {
            flex: 1 1 auto;
            width: 100%;
            border: none;
            background: #ffffff;
            display: block;
        }

        .loading-state,
        .error-state {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(255, 255, 255, 0.96);
            padding: 1.5rem 2rem;
            border-radius: 14px;
            box-shadow: 0 18px 48px rgba(15, 84, 81, 0.14);
            min-width: 260px;
        }

        .loading-state[hidden],
        .error-state[hidden] {
            display: none;
        }

        .spinner {
            width: 44px;
            height: 44px;
            border: 4px solid rgba(21, 107, 104, 0.16);
            border-top-color: #156b68;
            border-radius: 50%;
            margin: 0 auto 1rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fallback-link {
            display: inline-block;
            margin-top: 1rem;
            background: #156b68;
            color: #ffffff;
            padding: 0.55rem 1.4rem;
            border-radius: 999px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.25s ease;
        }

        .fallback-link:hover,
        .fallback-link:focus {
            background: #0f5451;
        }

        p {
            margin: 0;
            line-height: 1.6;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="viewer-shell">
        <div class="loading-state" id="loading-state">
            <div class="spinner" aria-hidden="true"></div>
            <p>جاري تحميل ملف PDF...</p>
        </div>

        <iframe id="pdf-viewer" class="pdf-frame" title="عارض PDF" hidden loading="lazy"></iframe>

        <div class="error-state" id="error-state" hidden>
            <p id="error-message">تعذر تحميل ملف PDF. يمكنك تحميله مباشرة.</p>
            <a href="<?php echo esc_url($direct_url); ?>" class="fallback-link" target="_blank" rel="noopener noreferrer">فتح الملف مباشرة</a>
        </div>
    </div>

    <script>
    (function() {
        var directUrl = <?php echo wp_json_encode($direct_url); ?>;
        var frame = document.getElementById('pdf-viewer');
        var loadingState = document.getElementById('loading-state');
        var errorState = document.getElementById('error-state');
        var errorMessage = document.getElementById('error-message');
        var objectUrl = null;

        function revokeObjectUrl() {
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }
        }

        function showError(message) {
            if (loadingState) {
                loadingState.hidden = true;
            }
            if (frame) {
                frame.hidden = true;
                frame.removeAttribute('src');
            }
            if (errorState) {
                errorState.hidden = false;
            }
            if (errorMessage && message) {
                errorMessage.textContent = message;
            }
        }

        if (!window.fetch || !window.URL || !window.URL.createObjectURL) {
            showError('المتصفح لا يدعم عرض ملف PDF هنا. استخدم رابط التحميل المباشر.');
            return;
        }

        window.addEventListener('beforeunload', revokeObjectUrl);
        window.addEventListener('pagehide', revokeObjectUrl);

        var fetchUrl = directUrl + (directUrl.indexOf('?') === -1 ? '?' : '&') + 't=' + Date.now();

        fetch(fetchUrl, {
            method: 'GET',
            cache: 'no-store',
            credentials: 'include'
        }).then(function(response) {
            if (!response.ok) {
                throw new Error('response-' + response.status);
            }
            return response.blob();
        }).then(function(blob) {
            if (!blob || !blob.size) {
                throw new Error('empty-blob');
            }

            objectUrl = URL.createObjectURL(blob);
            frame.src = objectUrl + '#toolbar=0&navpanes=0&scrollbar=1&view=FitH';
            frame.hidden = false;
            frame.addEventListener('load', function() {
                if (loadingState) {
                    loadingState.hidden = true;
                }
            }, { once: true });
        }).catch(function() {
            showError('تعذر تحميل ملف PDF. يمكنك تحميله مباشرة من الرابط أدناه.');
        });
    })();
    </script>
</body>
</html>
