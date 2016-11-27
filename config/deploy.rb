# config valid only for current version of Capistrano
lock '3.4.0'

set :application, 'colorspace_conversions'
set :short_name, 'colorspace'
set :repo_url, 'git@github.com:JackSzwergold/Colorspace-Conversions.git'

# Set the 'deploy_to' directory.
set :deploy_to, '/var/www'

# Default value for :scm is :git
set :scm, :git

# Default value for :format is :pretty
set :format, :pretty

# Default value for :log_level is :debug
set :log_level, :debug

# Default value for :pty is false
set :pty, false

# Default value for :linked_files is []
# set :linked_files, fetch(:linked_files, []).push('config/database.yml', 'config/secrets.yml')

# Default value for linked_dirs is []
# set :linked_dirs, fetch(:linked_dirs, []).push('log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'vendor/bundle', 'public/system')
# set :linked_dirs, fetch(:linked_dirs, []).push('cache')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 3

# Disable warnings about the absence of the styleseheets, javscripts & images directories.
set :normalize_asset_timestamps, false

# The directory on the server into which the actual source code will deployed.
set :web_builds, "#{deploy_to}/builds"

# The directory on the server that stores content related data.
set :content_data_path, "#{deploy_to}/content"

# The path where projects get deployed.
set :projects_path, "projects"

namespace :deploy do

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end

  # Create the 'create_symlink' task to create symbolic links and other related items.
  desc "Set the symbolic links."
  task :create_symlink do
    on roles(:app) do

      # info "If there is no directory & no symbolic link to 'site/#{fetch(:projects_path)}' then create a directory named 'site/#{fetch(:projects_path)}'."
      execute "cd #{fetch(:live_root)} && if [ ! -d site/#{fetch(:projects_path)} ]; then if [ ! -h site/#{fetch(:projects_path)} ]; then mkdir -p ./site/#{fetch(:projects_path)}; fi; fi"

      # info "If there is a symbolic link to 'site/#{fetch(:projects_path)}' then create a symbolic link called 'site/#{fetch(:projects_path)}'."
      execute "cd #{fetch(:live_root)} && if [ ! -h site/#{fetch(:projects_path)} ]; then if [ ! -d site/#{fetch(:projects_path)} ]; then ln -sf #{current_path} ./site/#{fetch(:projects_path)}; fi; fi"

      # info "If there is a symbolic link to 'site/#{fetch(:projects_path)}/#{fetch(:short_name)}', delete it. Irregardless, create a new symbolic link to 'site/#{fetch(:projects_path)}/#{fetch(:short_name)}'."
      execute "cd #{fetch(:live_root)} && if [ -h site/#{fetch(:projects_path)}/#{fetch(:short_name)} ]; then rm site/#{fetch(:projects_path)}/#{fetch(:short_name)}; fi && ln -sf #{current_path} ./site/#{fetch(:projects_path)}/#{fetch(:short_name)}"

    end
  end

end

after "deploy:published", "deploy:create_symlink"
