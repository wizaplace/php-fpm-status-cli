<?php

use Symfony\Component\Console\Output\OutputInterface;

$app = new Silly\Application();
$app->command('run [--socket=] [--path=] [--full] [--format=] [-i|--header]', function (OutputInterface $output, $socket, $path, $full, $format, $header) {
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

    $stderr = $output->getErrorOutput();

    try {
        $response = $fastcgi->send([
            'REQUEST_METHOD'  => 'GET',
            'SCRIPT_NAME' => $path,
            'SCRIPT_FILENAME' => $path,
            'QUERY_STRING' => implode('&', $query),
        ]);
    } catch (\Exception $e) {
        $stderr->writeln('<error>Tried sending to PHP-FPM but got the following error: ' . $e->getMessage());

        return 1;
    }

    $headers = $fastcgi->getResponseHeaders();

    if ($header) {
        $output->writeln(formatHeaders($headers)."\n");
    }

    if (isset($headers['status'])) {
        $stderr->writeln('<error>PHP-FPM returns status: '.$headers['status'].'</error>');

        return 1;
    }

    $output->write($response, false, OutputInterface::OUTPUT_RAW);
})->defaults([
    'socket' => 'unix:///run/php/php7.1-fpm.sock',
    'path' => '/status',
])->descriptions('Get PHP-FPM status', [
    '--socket' => 'Unix socket or tcp address where php-fpm listens',
    '--path' => 'The URI to view the FPM status page. If this value is not set, no URI will be recognized as a status page.',
    '--full' => 'Show full server status',
    '--format' => 'Output format for the status, txt by default, [html, json, xml] available',
    '--header' => 'Output fastcgi headers',
]);
$app->setDefaultCommand('run');

return $app;

function formatHeaders($headers)
{
    return implode("\n", array_map(function ($key, $value) {
        return "{$key}: {$value}";
    }, array_keys($headers), array_values($headers)));
}
