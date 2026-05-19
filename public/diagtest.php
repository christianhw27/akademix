<?php
// Diagnose what's happening with headers
$info = [];
$info['output_buffering'] = ini_get('output_buffering');
$info['zlib_output_compression'] = ini_get('zlib.output_compression');
$info['ob_level_before'] = ob_get_level();
$info['headers_sent_before'] = headers_sent($f, $l) ? "YES at $f:$l" : "NO";

// Try to send a file download
while (ob_get_level()) ob_end_clean();

$info['ob_level_after_clean'] = ob_get_level();
$info['headers_sent_after_clean'] = headers_sent($f2, $l2) ? "YES at $f2:$l2" : "NO";

// If we get here, try the actual download
$filename = "Test_File_Export.xls";
$content = '<html><head><meta http-equiv="Content-type" content="text/html;charset=utf-8"></head><body>';
$content .= '<table border="1"><tr><th>No</th><th>Nama</th><th>Nilai</th></tr>';
$content .= '<tr><td>1</td><td>Test Siswa</td><td>90</td></tr>';
$content .= '</table></body></html>';

if (!headers_sent()) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($content));
    header('Cache-Control: no-cache');
    echo $content;
    exit;
} else {
    echo "<pre>DIAGNOSIS:\n";
    foreach ($info as $k => $v) echo "$k = $v\n";
    echo "HEADERS ALREADY SENT - cannot download\n";
    echo "</pre>";
}
