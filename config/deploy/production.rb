# branch: Set the github branch that will be used for this deployment.
# server: The name of the destination server you will be deploying to.
# web_builds: The directory on the server into which the actual source code will deployed.
# live_root: The live directory which the current version will be linked to.

set :branch, "master"

server 'www.preworn.com', user: 'sysop', roles: %w{app db web}, my_property: :my_value

set :web_builds, "#{deploy_to}/builds"
# set :content_data_path, "#{deploy_to}/content"
set :live_root, "#{deploy_to}/www.preworn.com"

set :deploy_to, "/var/www/builds/#{fetch(:application)}/production"

# Disable warnings about the absence of the styleseheets, javscripts & images directories.
set :normalize_asset_timestamps, false

# Set symbollic links and other related items.
namespace :deploy do

  desc "Set the symbolic links."
  task :create_symlink do
    on roles(:app) do

        info "If there is no directory & no symbolic link to 'site' then create a directory named 'site'."
        execute "cd #{fetch(:live_root)} && if [ ! -d site ]; then if [ ! -h site ]; then mkdir ./site; fi; fi"

        info "If there is a symbolic link to 'site' then create a symbolic link called 'site'."
        execute "cd #{fetch(:live_root)} && if [ ! -h site ]; then if [ ! -d site ]; then ln -sf #{current_path} ./site; fi; fi"

        info "If there is a symbolic link to 'site/colorspace', delete it. Irregardless, create a new symbolic link to 'site/colorspace'."
        execute "cd #{fetch(:live_root)} && if [ -h site/colorspace ]; then rm site/colorspace; fi && ln -sf #{current_path} ./site/colorspace"

    end
  end

end

after :deploy, "deploy:create_symlink"