<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Network Host
    |--------------------------------------------------------------------------
    |
    | IP address of this machine on the local network (WiFi).
    | Used by Vite to serve assets over the network.
    | Find your IP with: ipconfig (Windows) or ifconfig (Mac/Linux)
    |
    */

    'host' => env('NETWORK_HOST', '192.168.0.243'),

    /*
    |--------------------------------------------------------------------------
    | Allowed IPs (Whitelist)
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of IP addresses that are allowed to access
    | the application when running in network mode.
    |
    | Localhost (127.0.0.1, ::1) is always allowed automatically.
    |
    | Leave empty to allow ALL devices on the network (no restriction).
    |
    | Examples:
    |   ALLOWED_IPS=192.168.0.100                    (single device)
    |   ALLOWED_IPS=192.168.0.100,192.168.0.101      (multiple devices)
    |   ALLOWED_IPS=                                  (allow all - no restriction)
    |
    */

    'allowed_ips' => array_filter(
        array_map('trim', explode(',', env('ALLOWED_IPS', '')))
    ),

];
