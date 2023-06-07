<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/ps-sean/mytables.git');
set('keep_releases', 3);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('mytables.co.uk')
    ->set('remote_user', 'ubuntu')
    ->set('deploy_path', '/var/www/mytables');

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
