# Set the github branch that will be used for this deployment.
set :branch, "master"

# The details of the destination server you will be deploying to.
server 'sandbox.local', user: ENV["CAP_USER"] || 'vagrant', roles: %w{app db web}, my_property: :my_value

# The directory on the server into which the actual source code will deployed.
set :web_builds, "#{deploy_to}/builds"

# The path where projects get deployed.
set :projects_path, "projects"

# The directory on the server that stores content related data.
set :content_data_path, "#{deploy_to}/content"

# The live, web root directory which the current version will be linked to.
set :live_root, "#{deploy_to}/sandbox.local"

# Set the 'deploy_to' directory for this task.
set :deploy_to, "/var/www/builds/#{fetch(:application)}/production"

# Disable warnings about the absence of the styleseheets, javscripts & images directories.
set :normalize_asset_timestamps, false

# Create the 'create_symlink' task to create symbolic links and other related items.
namespace :deploy do

  desc "Set the symbolic links."
  task :create_symlink do
    on roles(:app) do

        # info "If there is no directory & no symbolic link to 'site/#{fetch(:projects_path)}' then create a directory named 'site/#{fetch(:projects_path)}'."
        execute "cd #{fetch(:live_root)} && if [ ! -d site/#{fetch(:projects_path)} ]; then if [ ! -h site/#{fetch(:projects_path)} ]; then mkdir ./site/#{fetch(:projects_path)}; fi; fi"

        # info "If there is a symbolic link to 'site/#{fetch(:projects_path)}' then create a symbolic link called 'site/#{fetch(:projects_path)}'."
        execute "cd #{fetch(:live_root)} && if [ ! -h site/#{fetch(:projects_path)} ]; then if [ ! -d site/#{fetch(:projects_path)} ]; then ln -sf #{current_path} ./site/#{fetch(:projects_path)}; fi; fi"

        # info "If there is a symbolic link to 'site/#{fetch(:projects_path)}/#{fetch(:short_name)}', delete it. Irregardless, create a new symbolic link to 'site/#{fetch(:projects_path)}/#{fetch(:short_name)}'."
        execute "cd #{fetch(:live_root)} && if [ -h site/#{fetch(:projects_path)}/#{fetch(:short_name)} ]; then rm site/#{fetch(:projects_path)}/#{fetch(:short_name)}; fi && ln -sf #{current_path} ./site/#{fetch(:projects_path)}/#{fetch(:short_name)}"

    end
  end

end

# after :deploy, "deploy:create_symlink"
after "deploy:published", "deploy:create_symlink"