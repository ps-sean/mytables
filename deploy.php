<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/ps-sean/mytables.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('18.133.49.69')
    ->set('remote_user', 'ec2-user')
    ->set('deploy_path', '/var/www/html');

// Tasks

task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run production');
});

// Hooks

after('deploy:failed', 'deploy:unlock');

after('deploy:vendors', 'build');

after('deploy:symlink', 'artisan:horizon:terminate');
