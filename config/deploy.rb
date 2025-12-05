# config valid only for current version of Capistrano
lock ['>= 3.17.0', '< 3.20']

set :application, 'colorspace'
set :short_name, 'colorspace'
set :repo_url, 'git@github.com:JackSzwergold/Colorspace-Conversions.git'

# Default value for :scm is :git
# set :scm, :git

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
set :linked_dirs, fetch(:linked_dirs, []).push('cache')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 3

# Disable warnings about the absence of the styleseheets, javscripts & images directories.
set :normalize_asset_timestamps, false

# Set the root deployment path.
set :root_deploy_path, "/home/jackgold"

# The directory on the server into which the actual source code will deployed.
set :web_builds, "#{fetch(:root_deploy_path)}/builds"

# The directory on the server that stores content related data.
set :content_data_path, "#{fetch(:root_deploy_path)}/content"

# Set the site short name.
set :parent_site_path, 'szwergold.com'

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

      # info "If there is no directory & no symbolic link to '#{fetch(:parent_site_path)}/#{fetch(:projects_path)}' then create a directory named '#{fetch(:parent_site_path)}/#{fetch(:projects_path)}'."
      execute "cd #{fetch(:live_root)} && if [ ! -d #{fetch(:parent_site_path)}/#{fetch(:projects_path)} ]; then if [ ! -h #{fetch(:parent_site_path)}/#{fetch(:projects_path)} ]; then mkdir -p ./#{fetch(:parent_site_path)}/#{fetch(:projects_path)}; fi; fi"

      # info "If there is a symbolic link to '#{fetch(:parent_site_path)}/#{fetch(:projects_path)}' then create a symbolic link called '#{fetch(:parent_site_path)}/#{fetch(:projects_path)}'."
      execute "cd #{fetch(:live_root)} && if [ ! -h #{fetch(:parent_site_path)}/#{fetch(:projects_path)} ]; then if [ ! -d #{fetch(:parent_site_path)}/#{fetch(:projects_path)} ]; then ln -sf #{current_path} ./#{fetch(:parent_site_path)}/#{fetch(:projects_path)}; fi; fi"

      # info "If there is a symbolic link to '#{fetch(:parent_site_path)}/#{fetch(:projects_path)}/#{fetch(:short_name)}', delete it. Irregardless, create a new symbolic link to '#{fetch(:parent_site_path)}/#{fetch(:projects_path)}/#{fetch(:short_name)}'."
      execute "cd #{fetch(:live_root)} && if [ -h #{fetch(:parent_site_path)}/#{fetch(:projects_path)}/#{fetch(:short_name)} ]; then rm #{fetch(:parent_site_path)}/#{fetch(:projects_path)}/#{fetch(:short_name)}; fi && ln -sf #{current_path} ./#{fetch(:parent_site_path)}/#{fetch(:projects_path)}/#{fetch(:short_name)}"

    end
  end

end

after "deploy:published", "deploy:create_symlink"

