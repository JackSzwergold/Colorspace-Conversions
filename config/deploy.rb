require 'capistrano/ext/multistage'
set :stages, ['staging', 'production']
set :application, "colorspace_conversions"
set :repository,  "git@github.com:JackSzwergold/Colorspace-Conversions.git"
set :git_enable_submodules, true

set :scm, :git
set :use_sudo, false
set :keep_releases, 3
ssh_options[:forward_agent] = true

set :web_root, "/var/www"
set :deployment_root, "#{web_root}"

namespace :deploy do
  task :restart do
    #nothing
  end
  task :create_release_dir, :except => {:no_release => true} do
    run "mkdir -p #{fetch :releases_path}"
  end
end

# Link the 'images' folder into the current directory; removes whatever 'images' exists in the repo.
# task :link_content do
#   run "cd #{current_release} && if [ -d images ]; then rm -rf images; fi && ln -s #{content_data_path}/images ./images"
# end

# Clean up the stray symlinks: current, log, public & tmp
task :delete_extra_symlink do
  # Get rid of Ruby specific 'current' & 'log' symlinks.
  run "cd #{current_path} && if [ -h current ]; then rm current; fi && if [ -h log ]; then rm log; fi"
  # Get rid of Ruby specific 'public' directory.
  run "cd #{current_path} && if [ -d public ]; then rm -rf public; fi"
  # Get rid of the 'sundry' directory.
  run "cd #{current_path} && if [ -d sundry ]; then rm -rf sundry; fi"
  # Get rid of the 'tmp' directory.
  run "cd #{current_path} && if [ -d tmp ]; then rm -rf tmp; fi"
end

# Delete capistrano config files from release
task :delete_cap_files do
  run "cd #{current_release} && if [ -f Capfile ]; then rm Capfile; fi"
  run "cd #{current_release} && if [ -d config ]; then rm -rf config; fi"
  run "cd #{current_release} && if [ -f README.md ]; then rm README.md; fi"
end

# Link shared cache dir into release
task :make_cache_link do
  run "cd #{current_release} && if [ ! -d #{shared_path}/cache ]; then mkdir -p #{shared_path}/cache; fi && ln -sf #{shared_path}/cache ./cache"
end

# Echo the current path to a file.
task :echo_current_path do
  run "echo #{current_release} > #{current_release}/CURRENT_PATH"
end

before "deploy:update", "deploy:create_release_dir"
before "deploy:create_symlink", :delete_cap_files
# after "deploy:create_symlink", :link_content
after "deploy:update", :delete_extra_symlink
after "deploy:update", :echo_current_path