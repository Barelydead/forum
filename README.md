[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Barelydead/forum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Barelydead/forum/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Barelydead/forum/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Barelydead/forum/build-status/master)
[![Build Status](https://travis-ci.org/Barelydead/forum.svg?branch=master)](https://travis-ci.org/Barelydead/forum)

About
--------

This project is the last assignment for the framework BTH course. It is a fully functioning forum and inspiration taken from stackoverflow and other smililar forums.



Install your own
---------------------

```
git clone git@github.com:Barelydead/forum.git
```


Setup
--------------

### Get dependencies
```
composer update
```

### Set up a database to the project
1. ```rsync -av vendor/anax/database/config/database.php config```
2. Create a sqlite database in the data folder.
3. run the SQL from ```sql/ddl/reset.sql```
4. Set up the database config file to match new sqlite database.
