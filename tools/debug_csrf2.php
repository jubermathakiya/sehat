<?php

$c = tempnam(sys_get_temp_dir(), 'x');
$ch = curl_init('http://127.0.0.1:8000/superadmin/login');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $c,
    CURLOPT_COOKIEFILE => $c,
    CURLOPT_HEADER => true,
]);
$r = curl_exec($ch);
curl_close($ch);

preg_match_all('/^Set-Cookie:.*$/mi', $r, $m);
echo "Set-Cookie count: ".count($m[0]).PHP_EOL;
foreach ($m[0] as $line) {
    echo $line.PHP_EOL;
}

preg_match('/name="_token" value="([^"]+)"/', $r, $t);
$token = $t[1] ?? '';

$ch = curl_init('http://127.0.0.1:8000/superadmin/login');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $c,
    CURLOPT_COOKIEFILE => $c,
    CURLOPT_HEADER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        '_token' => $token,
        'email' => 'admin@sehat.test',
        'password' => 'Admin@12345',
    ]),
    CURLOPT_HTTPHEADER => ['Accept: text/html'],
]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Login code: {$code}\n";
preg_match('/^Location:\s*(.+)$/mi', $resp, $lm);
echo 'Location: '.trim($lm[1] ?? 'none').PHP_EOL;
if ($code === 419) {
    echo "Cookie file after get:\n".file_get_contents($c).PHP_EOL;
}
