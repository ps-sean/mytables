<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';

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

task('npm:install', function () {
    run('source ~/.nvm/nvm.sh && cd {{release_path}} && npm install');
});

task('build', function () {
    run('source ~/.nvm/nvm.sh && cd {{release_path}} && npm run build');
});

// Hooks

after('deploy:failed', 'deploy:unlock');

after('deploy:vendors', 'npm:install');
after('npm:install', 'build');

after('deploy:symlink', 'artisan:horizon:terminate');
