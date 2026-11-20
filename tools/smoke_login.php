<?php

function exchange(string $url, string $cookie, array $opts = []): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_HEADER => $opts['header'] ?? false,
        CURLOPT_FOLLOWLOCATION => $opts['follow'] ?? false,
        CURLOPT_POST => isset($opts['post']),
        CURLOPT_POSTFIELDS => $opts['post'] ?? null,
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [$code, $body];
}

$cookie = tempnam(sys_get_temp_dir(), 'sa');
[, $html] = exchange('http://127.0.0.1:8000/superadmin/login', $cookie);
preg_match('/name="_token" value="([^"]+)"/', $html, $m);
[$code, $resp] = exchange('http://127.0.0.1:8000/superadmin/login', $cookie, [
    'post' => http_build_query([
        '_token' => $m[1] ?? '',
        'email' => 'admin@sehat.test',
        'password' => 'Admin@12345',
    ]),
    'header' => true,
]);
preg_match('/^Location:\s*(.+)$/mi', $resp, $lm);
echo "SuperAdmin login HTTP {$code} -> ".trim($lm[1] ?? 'none').PHP_EOL;

[$code, $dash] = exchange('http://127.0.0.1:8000/superadmin', $cookie, ['follow' => true]);
echo 'Dashboard HTTP '.$code.' Customers: '.(str_contains($dash, 'Customers') ? 'YES' : 'NO').PHP_EOL;

$cookie2 = tempnam(sys_get_temp_dir(), 'tn');
[, $html] = exchange('http://127.0.0.1:8000/clinic/login', $cookie2);
preg_match('/name="_token" value="([^"]+)"/', $html, $m);
[$code, $resp] = exchange('http://127.0.0.1:8000/clinic/login', $cookie2, [
    'post' => http_build_query([
        '_token' => $m[1] ?? '',
        'tenant_code' => 'CLN9NEZW',
        'email' => 'demo@clinic.test',
        'password' => 'Clinic@12345',
    ]),
    'header' => true,
]);
preg_match('/^Location:\s*(.+)$/mi', $resp, $lm);
echo "Clinic login HTTP {$code} -> ".trim($lm[1] ?? 'none').PHP_EOL;

[$code, $dash] = exchange('http://127.0.0.1:8000/clinic', $cookie2, ['follow' => true]);
echo 'Clinic dashboard HTTP '.$code.' content ok: '.(str_contains($dash, 'sehat-') || str_contains($dash, 'Dashboard') ? 'YES' : 'NO').PHP_EOL;
if ($code >= 400) {
    echo substr(strip_tags($dash), 0, 400).PHP_EOL;
}
