# php-fpm-status-cli

Simple CLI to access PHP-FPM status page when the HTTP server is not configured to do so.

[![CircleCI](https://circleci.com/gh/wizaplace/php-fpm-status-cli.svg?style=svg)](https://circleci.com/gh/wizaplace/php-fpm-status-cli)

## Install

```
$ composer require wizaplace/php-fpm-status-cli
```

## Usage

```
$ php-fpm-status -h
Usage:
  php-fpm-status [options]

Options:
      --socket=SOCKET   Unix socket or tcp address where php-fpm listens [default: "unix:///run/php/php7.1-fpm.sock"]
      --path=PATH       The URI to view the FPM status page. If this value is not set, no URI will be recognized as a status page. [default: "/status"]
      --full            Show full server status
      --format=FORMAT   Output format for the status, txt by default, [html, json, xml] available
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Get PHP-FPM status
```

The `socket` can be `unix:///path/to/socket` for a UNIX socket or `tcp://127.0.0.1:9000` for a TCP address.

The `path` is the `pm.status_path` configuration option in the main FPM config or in the pool.

## Output

```shell
$ php-fpm-status
pool:                 www
process manager:      dynamic
start time:           18/Oct/2017:13:48:20 +0000
start since:          6
accepted conn:        1
listen queue:         0
max listen queue:     0
listen queue len:     128
idle processes:       1
active processes:     1
total processes:      2
max active processes: 1
max children reached: 0
slow requests:        0
```

Use the `--full` option to retrieve more information.
