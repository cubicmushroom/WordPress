WordPress
=========

Automates various WordPress tasks, like site setup a new site, or download a copy of 
an entire WP site

Important Files
---------------

* app/config - Stores the config the app.  Use the `init` command to create this
               file, or you will be prompted for it's details if running any other
               command before it has been created


Credits
-------

This package relies on the following 3rd party packages...

* symfony/symfony - The Symfony 2 framework
* webcreate/vcs   - Package used to interact with the WordPress SVN repository

Roadmap
-------
Proposed commands...

* init              - Sets up config of local environment.  This should be called
                      automatically if no config file is found when running any other
                      commands
* setup-local       - Sets up a local WordPress environment
* setup-remote      - Sets up a remote WordPress environment via SFTP/FTP
* clone-from-remote - Clones a copy of a remote WP site to local machine
