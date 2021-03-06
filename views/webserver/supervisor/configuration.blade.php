[program:{!! $host_identifier !!}]
command={!! $php_path !!} {!! $base_path !!}/artisan queue:work redis --daemon --sleep=3 --hostname={!! $host_identifier !!} --queue="{!! $host_identifier !!}"
autostart=true
autoreload=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{!! $host_identifier !!}.log

[program:{!! $host_identifier !!}.gravatar]
command={!! $php_path !!} {!! $base_path !!}/artisan queue:work redis --daemon --sleep=3 --hostname={!! $host_identifier !!} --queue="{!! $host_identifier !!}.gravatar"
autostart=true
autoreload=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{!! $host_identifier !!}.log

[program:{!! $host_identifier !!}.twitter]
command={!! $php_path !!} {!! $base_path !!}/artisan queue:work redis --daemon --sleep=3 --hostname={!! $host_identifier !!} --queue="{!! $host_identifier !!}.twitter"
autostart=true
autoreload=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{!! $host_identifier !!}.log

[program:{!! $host_identifier !!}.activity]
command={!! $php_path !!} {!! $base_path !!}/artisan queue:work redis --daemon --sleep=3 --hostname={!! $host_identifier !!} --queue="{!! $host_identifier !!}.activity"
autostart=true
autoreload=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{!! $host_identifier !!}.log

[program:{!! $host_identifier !!}.notification]
command={!! $php_path !!} {!! $base_path !!}/artisan queue:work redis --daemon --sleep=3 --hostname={!! $host_identifier !!} --queue="{!! $host_identifier !!}.notification"
autostart=true
autoreload=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{!! $host_identifier !!}.log