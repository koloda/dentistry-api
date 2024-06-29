<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:koloda/dentistry-api.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('ec2-16-171-22-91.eu-north-1.compute.amazonaws.com')
    ->set('remote_user', 'ubuntu')
    ->set('deploy_path', '~/api-dental');

// Hooks

after('deploy:failed', 'deploy:unlock');
