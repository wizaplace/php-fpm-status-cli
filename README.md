# php-fpm-status-cli

Simple CLI to access PHP-FPM status page when the HTTP server is not configured to do so.

## Usage

```shell
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
