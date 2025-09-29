<?php
/**
 * وسيط مبسط لعرض وتحميل ملفات PDF من خلال ووردبريس.
 */

if (!defined('ABSPATH')) {
    $wp_path = dirname(dirname(dirname(dirname(__FILE__))));
    require_once $wp_path . '/wp-load.php';
}

if (empty($_GET['file'])) {
    wp_die('ملف غير موجود', 'PDF Proxy', array('response' => 404));
}

$payload = base64_decode(wp_unslash($_GET['file']));
$file_data = json_decode($payload, true);

if (!is_array($file_data) || empty($file_data['url'])) {
    wp_die('بيانات الملف غير صحيحة', 'PDF Proxy', array('response' => 400));
}

$pdf_url = esc_url_raw($file_data['url']);
if (!$pdf_url || !filter_var($pdf_url, FILTER_VALIDATE_URL)) {
    wp_die('رابط غير صالح', 'PDF Proxy', array('response' => 400));
}

$action = 'view';
if (!empty($_GET['action'])) {
    $candidate = sanitize_key(wp_unslash($_GET['action']));
    if (in_array($candidate, array('view', 'download'), true)) {
        $action = $candidate;
    }
}

$context = isset($file_data['context']) ? sanitize_title($file_data['context']) : 'document';
$filename = $context ? $context . '.pdf' : 'document.pdf';
$filename = sanitize_file_name($filename);

$response = wp_remote_get(
    $pdf_url,
    array(
        'timeout'     => 20,
        'redirection' => 3,
        'headers'     => array(
            'Accept'     => 'application/pdf,application/octet-stream;q=0.9,*/*;q=0.8',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ArabBoardPDFProxy/1.0',
        ),
    )
);

if (is_wp_error($response)) {
    wp_die('تعذر الوصول إلى الملف', 'PDF Proxy', array('response' => 502));
}

$status_code = (int) wp_remote_retrieve_response_code($response);
if ($status_code >= 400) {
    wp_die('تعذر تحميل الملف (رمز الاستجابة: ' . $status_code . ')', 'PDF Proxy', array('response' => $status_code));
}

$body = wp_remote_retrieve_body($response);
if ('' === $body) {
    wp_die('ملف فارغ أو غير متوفر', 'PDF Proxy', array('response' => 404));
}

if (function_exists('mb_strlen')) {
    $content_length = mb_strlen($body, '8bit');
} else {
    $content_length = strlen($body);
}

header('Content-Type: application/pdf');
header('Content-Length: ' . $content_length);
header('Content-Disposition: ' . ($action === 'download' ? 'attachment' : 'inline') . '; filename="' . $filename . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
header('Referrer-Policy: no-referrer');
header('X-Robots-Tag: noindex, nofollow');

echo $body;
exit;
