set :application, "podcastcollector"
set :repository,  "ssh://git@git.lab.mabarroso.com/podcastcollector.git"
set :scm, :git
set :branch, "master"
set :user, "web"
default_run_options[:pty] = true  # Must be set for the password prompt from git to work
set :use_sudo, false
#ssh_options[:keys] = [File.join(ENV["HOME"], ".ssh", "id_rsa")]

# Multistage
set :stages, %w(production beta)
require 'capistrano/ext/multistage'

set :deploy_via, :copy
set :copy_exclude, %w(.git)


def set_deploy stage_path
  set :common_data_path, "/www/#{stage_path}/site/mabarroso.com/_data/#{application}"
  set :deploy_to, "/www/deploy/#{stage_path}/mabarroso.com/#{application}"
  set :deployed_path, "/www/#{stage_path}/site/mabarroso.com/_cron/#{application}"
  set :deployed_data_path, "#{deployed_path}/feeds"
  set :rails_env, stage_path
end

set :keep_releases, 5
after "deploy:update", "deploy:cleanup"

namespace :deploy do
  task :migrate do
    puts "    not doing migrate because not a Rails application."
  end
  task :finalize_update do
    puts "    not doing finalize_update because not a Rails application."
  end
  task :start do
    puts "    not doing start because not a Rails application."
  end
  task :stop do
    puts "    not doing stop because not a Rails application."
  end
  task :restart do
    puts "    not doing restart because not a Rails application."
  end
  task :create_symlinks, :roles => :app do
    run "rm -f #{deployed_path} && ln -nfs #{release_path} #{deployed_path}"
    run "[ -d #{common_data_path} ] || mkdir #{common_data_path}"
    run "mv #{deployed_data_path} #{deployed_data_path}_old && ln -nfs #{common_data_path} #{deployed_data_path}"
  end
end

after "deploy:finalize_update", "deploy:create_symlinks"
