# Updating

The update process is currently very easy, but it might change in the future. Please check the version of this file corresponding to the version you're upgrading to before updating (i.e. the version in the repository).

## SQL updates
If you're coming from a really old version, your tables might miss some columns. Connect to your MySQL server and execute the following __BEFORE__ doing the steps under the next heading:

```sql
use YOURDATABASE;
alter table YOURPOSTTABLE add ip text;
alter table YOURPOSTTABLE add id text;
alter table YOURPOSTTABLE add fname text;
```

## Updating
1. Navigate to the folder where fikaba is installed
2. Run `git pull`
3. If `config.example.php` has changed, add the new options to your `config.php` and configure them however you want
4. If you're coming from an old version where the admin password was still defined in the config file, login to your admin panel with the default account, and create yourself a new account.
