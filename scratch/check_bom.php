<?php
$files = [
    'app/core/helpers.php',
    'app/core/Database.php',
    'app/core/Model.php',
    'app/core/Controller.php',
    'app/core/Session.php',
    'app/core/Auth.php',
    'app/core/App.php',
    'app/controllers/TeacherController.php',
    'app/models/TeacherModel.php',
    'public/index.php',
];

$baseDir = __DIR__ . '/../';
foreach ($files as $f) {
    $path = $baseDir . $f;
    $content = file_get_contents($path);
    $bom = (substr($content, 0, 3) === "\xEF\xBB\xBF");
    // Check if file starts with whitespace before <?php
    $startsClean = (substr(ltrim($content), 0, 5) === '<?php');
    $firstBytes = bin2hex(substr($content, 0, 10));
    
    $status = 'OK';
    if ($bom) $status = 'BOM DETECTED!';
    elseif (!$startsClean) $status = 'UNEXPECTED START!';
    
    echo "$status\t$f\t(first bytes: $firstBytes)\n";
}

// Also check PHP output_buffering setting
echo "\noutput_buffering = " . ini_get('output_buffering') . "\n";
echo "headers_sent = " . (headers_sent($f, $l) ? "YES at $f:$l" : "NO") . "\n";
