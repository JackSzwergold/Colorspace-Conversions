# Capistrano

The purpose of this document is to explain the basics of getting Capistrano installed on Mac OS X.

Capistrano is a Ruby GEM that is used to deploy code from a code repository—such as GitHub, GitLab, BitBuckete, etc…—to a destination server. Run this command to install Capistrano on Mac OS X:

    sudo gem install capistrano:3.11.0

With that done, you should be all set to deploy code. But if you are deploying and get a message like this:

> Text will be echoed in the clear. Please install the HighLine or Termios libraries to suppress echoed text.

Then install Highline to make sure SSH passwords are not echoed in the clear when deploying:

    sudo gem install highline:1.7.8

And some default installs of Nokogiri—a Ruby XML and HTML library—on Mac OS X are out of date which will much things up. So you might have to force install an update of Nokogiri like this:

    sudo gem pristine nokogiri --version 1.8.4

With that done, deployment would be handled as follows; in this case `staging` is being deployed:

    cap production deploy

Or if you want to deploy as a different user, just set the `CAP_USER` environment variable like this:

    export CAP_USER=username && cap production deploy

### Installing SSHPASS on Mac OS X if necessary.

If somehow the deployment process still fails, you might need to install SSHPASS on Mac OS X.

Installing SSHPASS will require you to install the full version of Xcode or Xcode Command Line Tools to compile the source code. So be sure to have either “Xcode” or “Xcode Command Line Tools” installed before proceeding.

First, grab a copy of the SSHPASS source code like this:

	curl -O http://heanet.dl.sourceforge.net/project/sshpass/sshpass/1.06/sshpass-1.06.tar.gz

Decompress the archive like this:

    tar -xf sshpass-1.06.tar.gz
    
Then go into the newly decompressed directory:

    cd sshpass-1.06

Configure the source code:

    ./configure

Run `make` to compile the source code:

	make

And finally install SSHPASS like this:

	sudo make install

You can check if it was installed properly by checking the version number like this:

	sshpass -V

The output would look something like this:

	sshpass 1.06
	(C) 2006-2011 Lingnu Open Source Consulting Ltd.
	(C) 2015-2016 Shachar Shemesh
	This program is free software, and can be distributed under the terms of the GPL
	See the COPYING file for more information.

***

## For Reference Only

**NOTE:** *As of May 15, 2015 the details below are for reference only since much of the advice has been obsoleted by a recent upgrade of the deployment scripts to use Capistrano 3; specifically version 3.4.0 and above.*

The purpose of this document is to explain how to setup `capistrano` on your local system to allow for clean and easy deployment of code from GitHub to a destination server.

### Installing Capistrano

There are only three (3) Ruby GEMs that need to be installed to facilitate `capistrano` on your local machine. But for compatibility reasons they should be specific versions. Details follow.

#### Capistrano’s version should be 2.12 or higher.

Ruby 1.9/1.8 method of installing a specific version of `capistrano`.

    sudo gem install capistrano --no-rdoc --no-ri rdoc-data --version=2.15.5

Ruby 2.0 method of installing a specific version of `capistrano`.

    sudo gem install capistrano:2.15.5 --no-rdoc --no-ri rdoc-data

#### Capistrano EXT’s version should be 1.2.1 or higher.


Ruby 1.9/1.8 method of installing a specific version of `capistrano-ext`.

    sudo gem install capistrano-ext --no-rdoc --no-ri rdoc-data --version=1.2.1

Ruby 2.0 method of installing a specific version of `capistrano-ext`.

    sudo gem install capistrano-ext:1.2.1 --no-rdoc --no-ri rdoc-data

#### Net SSH’s version should be exactly at 2.7.0.

Ruby 1.9/1.8 method of installing a specific version of `net-ssh`.

    sudo gem install net-ssh --no-rdoc --no-ri rdoc-data --version=2.7.0

Ruby 2.0 method of installing a specific version of `net-ssh`.

    sudo gem install net-ssh:2.7.0 --no-rdoc --no-ri rdoc-data

**NOTE:** If Capistrano somehow installs a version of `net-ssh` higher than 2.7.0, the connections will fail saying something like this:

> connection failed for: www.preworn.com (Net::SSH::AuthenticationFailed: Authentication failed for user sysop@www.preworn.com)

This bizarre error comes from versions of `net-ssh` erroneously disabling `KbdInteractiveAuthentication` (aka: keyboard interaction) when `ChallengeResponseAuthentication` is set on your host system. The most viable thing to do is to ensure `net-ssh` is only using version 2.7.0 by uninstalling any other version `capistrano` might install.

    sudo gem uninstall net-ssh --version 2.9.1

Another potential fix that might allow one to use versions of `net-ssh` higher than 2.7.0 is to add this line to the `deploy.rb` file:

    ssh_options[:config] = false

But this fix for one issue might open up the script the new errors stemming from newer versions of `net-ssh`. So best play it safe and just be sure `net-ssh` is the one being used.

### Understanding Capistrano

First, not all repositories use `capistrano` for deployment. But more of them do than not. And the ones that do follow the basic usage pattern outline below. Repositories that have `capistrano` scripts in place will have a file and a directory at their root named as follows:

- **Capfile:** The main `capistrano` file. No need to touch this.
- **config:** The directory that contains the actual `capistrano` deployment scripts.

And if you look in the `config` directory you will find the following files and directories:

- **deploy/production.rb:** The `production` specific deployment script.
- **deploy/staging.rb:** The `staging` specific deployment script.
- **deploy.rb:** The main deployment script.

While those files are all set to do what they have to do to get code deployed properly, feel free to look inside to get a better idea of what they are and how they work. It’s basically a pile of Ruby script code as well as `bash` directives that work together to deploy code. At it’s heart, `capistrano` basically creates a high level method of creating `bash` procedures that effect a deployment.

The code can be confusing at first, but when you look at the code and understand this “recipe” takes over the headache one often faces when manually SSHing or FTPing into a server, the value becomes clear: It helps you smooth over the ugliest, most error prone part of a deployment so deployments can proceed as smoothly as possible.

### Using Capistrano

To use `capistrano` one must already have access to a repository on GitHub as well as have basic SSH access to the destination server. This document won’t go into the detail of how either things are setup, but if you don’t have access to those things you simply cannot deploy code. But if you do have access to GitHub as well as basic SSH access to the destination server, then you simply need to do the following.

First, open up the terminal and go into the locally copied repository. Once in there, you can issue a command as follows to deploy a `staging` branch:

    cap staging deploy

That will allow you to deploy the `staging` branch to the destination server as long as the user name on your local machine matches the user name you are using for SSH access on the remote machine. But in some cases that might not be the case, so it is best to explicitly set the user name for deployment like this:

    cap -s user=sysop staging deploy

Just enter your SSH password for the remote server and you are off.

As for what a successful deployment looks like, it’s actually easier to explain that when things go correctly the text on the screen—mainly green and tan in color—will simply reflect the stages of the deployment as they are done. If an error occurs, red text explicitly indicating there was an error will pop up.

Now errors are not good, but you shouldn’t worry too much. The reality is `capistrano` is very error resilient and at no point will it deploy a “half baked” deployment. Meaning, the deployment won’t be finalized until all steps are clean and error free.

So let’s say your Internet connection is bad and drops out during a deployment. No need to worry about your codebase on the destination server going bad. The old deployment—whenever that was made—will always be in place until a newer deployment is successfully completely.