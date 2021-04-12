<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'vendor/deployer/recipes/recipe/slack.php';
require 'vendor/deployer/recipes/recipe/npm.php';

// Project name
set('application', 'myTables');

// Project repository
set('repository', 'https://str94@bitbucket.org/str94/mytables.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

//only keep last 3 releases
set('keep_releases', 3);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

task('webpack', function () {
    run("cd {{release_path}} && npm run production");
});

task('horizon', function () {
    run("sudo supervisorctl restart horizon");
});

// Hosts

host('18.133.49.69')
    ->user('ec2-user')
    ->identityFile('~/.ssh/BlackBull.pem')
    ->set('deploy_path', '/var/www/html');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('deploy:vendors', 'npm:install');

after('npm:install', 'webpack');

after('deploy:symlink', 'horizon');

