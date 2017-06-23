# Migrating from futallaby

Migrating from futallaby should be relatively painless. Login to your MySQL server and execute the following:

```sql
use YOURDATABASE;
alter table YOURPOSTTABLE add ip text;
alter table YOURPOSTTABLE add id text;
alter table YOURPOSTTABLE add fname text;
```

Note that posts made prior to the migration will not have an IP attached to them, which will result in errors when you try to ban by those post numbers.

Also, don't forget to log in with the default manager account and create a new account.
