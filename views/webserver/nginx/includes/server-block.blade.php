
server {

    listen {{ $config->port->http or 80 }};

    @if(isset($Ssl))
    listen {{ $config->port->https or 443 }} ssl spdy;
    ssl_certificate_key {{ $Ssl->pathKey }};
    ssl_certificate {{ $Ssl->pathPem }};
    @endif



    # server hostnames
    @if(isset($Host))
        server_name {{ $Host->hostname }};
    @else
        server_name {{ $Hosts->implode('hostname', ' ') }};
    @endif

    # allow cross origin access
    add_header Access-Control-Allow-Origin *;
    add_header Access-Control-Request-Method GET;

    # redirect any www domain to non-www
    if ( $host ~* ^www\.(.*) ) {
        set             $host_nowww     $1;
        rewrite         ^(.*)$          $scheme://$host_nowww$1 permanent;
    }

    # root path of website; serve files from here
    root                        {{ public_path() }};
    index                       index.php;


    # log handling
    access_log          {{ $log_path }}.access.log;
    error_log           {{ $log_path }}.error.log notice;

    location / {
        index           index.php;
        try_files       $uri $uri/ $uri/index.php?$args /index.php?$args;
    }

    # pass the PHP scripts to FastCGI server from upstream phpfcgi
    location ~ \.php(/|$) {
        fastcgi_pass    unix:/var/run/php/php7.0-fpm.{{ $Host->identifier }}.sock;
        include         fastcgi_params;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 300;
    }
}