
# Laravel skeleton 

This repository has three protected branches.
- main is the initial state of the project. You cannot push to it or delete it.
- dev is the staging branch. Please merge your work here to ensure the web server is never updated with wrong code. You cannot delete it.
- stable is the default branch and the release branch. Each update here is automatically forwarded to the web server.
 - composer install is run if composer.json is updated
 - npm install is run if package.json is updated
 - .env.prod is converted to .env, an application key is generated
 - npm run build is called to produce public assets (css++)
 - database is migrated
 - /!\ You can add new steps to .gitlab-ci.yml if necessary
- initially, the three branches are synchronized. (same content)

Skeleton deploys three branches:
- / : welcome, the standard Laravel welcome screen
- /logs/<file> : a page to view access.log, error.log and laravel.log (the last one can be deleted for better reliability)
 -> /logs/access /log/error /log/laravel

To begin:
- clone the repository
 - copy laravel/.env.example to laravel/.env
 - tweak it at will
 - setup your database
  - default is sqlite
  - you can switch to mysql is prefered
  - run the migrations (=> php artisan migrate)
 - never, ever, commit .env to the repository. It contains personal/machine specific informations.

To start working:
- checkout dev branch
- create your work branch
 - create or modify files
 - commit files (repeat as long as needed)
 - push
- merge with dev
 - checkout dev
 - pull the latest version
 - merge you branch into dev
 - test & fix if necessary
 - push back to dev
- merge with stable
 - You can do a merge request from the gitlab GUI, but it's not enforced in 2A
 - You can checkout stable, merge dev into stable and push back
- test the web application
- start again with a new feature (clone is not necessary, of course!)


## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

## Add your files

- [ ] [Create](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#create-a-file) or [upload](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#upload-a-file) files
- [ ] [Add files using the command line](https://docs.gitlab.com/topics/git/add_files/#add-files-to-a-git-repository) or push an existing Git repository with the following command:

```
cd existing_repo
git remote add origin https://git.unicaen.fr/iut-gon-info/sae3-2025/groupetest.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

- [ ] [Set up project integrations](https://git.unicaen.fr/iut-gon-info/sae3-2025/groupetest/-/settings/integrations)

## Collaborate with your team

- [ ] [Create a new merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html)
- [ ] [Automatically close issues from merge requests](https://docs.gitlab.com/ee/user/project/issues/managing_issues.html#closing-issues-automatically)
- [ ] [Enable merge request approvals](https://docs.gitlab.com/ee/user/project/merge_requests/approvals/)
- [ ] [Set auto-merge](https://docs.gitlab.com/user/project/merge_requests/auto_merge/)


