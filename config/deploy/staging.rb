# Set the github branch that will be used for this deployment.
set :branch, "develop"

# The details of the destination server you will be deploying to.
server 'www.preworn.com', user: ENV["CAP_USER"] || 'sysop', roles: %w{app db web}, my_property: :my_value

# The directory on the server into which the actual source code will deployed.
set :web_builds, "#{deploy_to}/builds"

# The directory on the server that stores content related data.
set :content_data_path, "#{deploy_to}/content"

# The live, web root directory which the current version will be linked to.
set :live_root, "#{deploy_to}/staging.preworn.com"

# Set the 'deploy_to' directory for this task.
set :deploy_to, "/var/www/builds/#{fetch(:application)}/staging"

# Disable warnings about the absence of the styleseheets, javscripts & images directories.
set :normalize_asset_timestamps, false

# Create the 'create_symlink' task to create symbolic links and other related items.
namespace :deploy do

  desc "Set the symbolic links."
  task :create_symlink do
    on roles(:app) do

        info "If there is no directory & no symbolic link to 'site' then create a directory named 'site'."
        execute "cd #{fetch(:live_root)} && if [ ! -d site ]; then if [ ! -h site ]; then mkdir ./site; fi; fi"

        info "If there is a symbolic link to 'site' then create a symbolic link called 'site'."
        execute "cd #{fetch(:live_root)} && if [ ! -h site ]; then if [ ! -d site ]; then ln -sf #{current_path} ./site; fi; fi"

        info "If there is a symbolic link to 'site/#{fetch(:short_name)}', delete it. Irregardless, create a new symbolic link to 'site/#{fetch(:short_name)}'."
        execute "cd #{fetch(:live_root)} && if [ -h site/#{fetch(:short_name)} ]; then rm site/#{fetch(:short_name)}; fi && ln -sf #{current_path} ./site/#{fetch(:short_name)}"

    end
  end

end

# after :deploy, "deploy:create_symlink"
after "deploy:published", "deploy:create_symlink"