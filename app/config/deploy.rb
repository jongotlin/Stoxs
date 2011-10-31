set :application, "stoxs"
set :domain,      "#{application}.se"
set :deploy_to,   "/var/src/"
set :app_path,    "app"
set :shared_files, ["app/config/parameters.ini"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors, true
set :dump_assetic_assets, true

set :user,        "www-data"

set :repository,  "git@github.com:jongotlin/Stoxs.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, `subversion` or `none`

ssh_options[:forward_agent] = true

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set  :keep_releases,  3