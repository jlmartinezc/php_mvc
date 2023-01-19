<?php 

$protocol = (empty($_SERVER['HTTPS'])) ? 'http' : 'https';
$domain = $_SERVER['HTTP_HOST'];
$url = sprintf('%s://%s', $protocol, $domain);
$root = dirname(__FILE__, 2);

return [
    'app' => [
        'name' => ''
    ],
    'path' => [
        'root' => $root
    ],
    'url' => [
        'base' => $url
    ]
];