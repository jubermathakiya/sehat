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
echo "Has csrf input: ".(str_contains($r, '_token') ? 'YES' : 'NO').PHP_EOL;
if (preg_match('/name="_token" value="([^"]+)"/', $r, $m)) {
    echo 'Token: '.$m[1].PHP_EOL;
} else {
    echo "Token not matched\n";
    if (preg_match('/_token.*?value="([^"]+)"/s', $r, $m2)) {
        echo 'Alt token: '.$m2[1].PHP_EOL;
    }
}
echo "Cookie file:\n".file_get_contents($c).PHP_EOL;
