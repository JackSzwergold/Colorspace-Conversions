# GitHub Setup and Usage

### Setup on a Local Machine

Setup SSH keys on local machine as per the official GitHub instructions:

    https://help.github.com/articles/generating-ssh-keys

After you do that, be sure to add the public key generated in `~/.ssh/id_rsa.pub` to GitHub.

Now test the connection by running this command:

    ssh -T git@github.com

It will ask to add `github.com` to your system’s list of known hosts. Type in `yes` as the answer and the response returned should be:

> Hi `username`! You've successfully authenticated, but GitHub does not provide shell access.

Once you see that, you are good to go for GitHub SSH access from your local machine.

### Setup on a Remote Machine

If you wish, you can manually add the `~/.ssh/id_rsa.pub` public key to an any machine you want to have unfettered GitHub access to. But the easier way to deal with GitHub access on multiple machines is to use the `-A` flag with `ssh` to enables forwarding of the authentication agent connection from your local setup.

Meaning, whatever keys you have on your local machine—such as `id_rsa.pub`—will magically be carried along the SSH connection chain from whatever machine you are on. 

To use it, just add it to the `ssh` command like this:

    ssh -A sysop@preworn.com

Then when you login to `preworn.com` all of your local SSH keys will be respected down the line.

But wait! One more thing! After you have logged into `preworn.com` you need to make sure to add `github.com` to your systems list of known hosts. And the process is the same as the local machine setup. First login to the remote machine via an SSH command like this:

    ssh -A sysop@preworn.com

Then test the connection by running this command:

    ssh -T git@github.com

It will ask to add `github.com` to your system’s list of known hosts. Type in `yes` as the answer and the response returned should be:

> Hi `username`! You've successfully authenticated, but GitHub does not provide shell access.

Once you see that, you are good to go for GitHub SSH access from that remote machine. You will have to repeat this process for each remote machine you need GitHub access to, but it’s a one time process that just takes a few minutes to handle.
