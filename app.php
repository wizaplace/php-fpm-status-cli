<?php

use Symfony\Component\Console\Output\OutputInterface;

$app = new Silly\Application();
$app->command('run [--socket=] [--path=] [--full] [--format=]', function (OutputInterface $output, $socket, $path, $full, $format) {
    $query = [];

    if ($full) {
        $query[] = 'full';
    }

    if (null !== $format) {
        if (!in_array($format, ['html', 'xml', 'json'])) {
            throw new \InvalidArgumentException(sprintf('Format "%s" does not exist', $format));
        }

        $query[] = $format;
    }

    $fastcgi = new Hoa\Fastcgi\Responder(
        new Hoa\Socket\Client($socket)
    );

    $response = $fastcgi->send([
        'REQUEST_METHOD'  => 'GET',
        'SCRIPT_NAME' => $path,
        'SCRIPT_FILENAME' => $path,
        'QUERY_STRING' => implode('&', $query),
    ]);

    $output->write($response, false, OutputInterface::OUTPUT_RAW);
})->defaults([
    'socket' => 'unix:///run/php/php7.1-fpm.sock',
    'path' => '/status',
])->descriptions('Get PHP-FPM status', [
    '--socket' => 'Unix socket or tcp address where php-fpm listens',
    '--path' => 'The URI to view the FPM status page. If this value is not set, no URI will be recognized as a status page.',
    '--full' => 'Show full server status',
    '--format' => 'Output format for the status, txt by default, [html, json, xml] available',
]);
$app->setDefaultCommand('run');

return $app;
