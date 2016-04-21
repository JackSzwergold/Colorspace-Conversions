# Set the github branch that will be used for this deployment.
set :branch, "develop"

# The details of the destination server you will be deploying to.
server 'prod0.preworn.com', user: ENV["CAP_USER"] || 'sysop', roles: %w{app db web}, my_property: :my_value

# Set the name for the deployment type.
set :deployment_type, "staging"

# The live, web root directory which the current version will be linked to.
set :live_root, "#{deploy_to}/staging.preworn.com"

# Set the 'deploy_to' directory for this task.
set :deploy_to, "/var/www/builds/#{fetch(:application)}/#{fetch(:deployment_type)}"
