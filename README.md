This is a composer based installer for the [Open Social distribution](https://www.drupal.org/project/social).

# Prerequisites

1. PHP 8.1 & other [Drupal 10 server requirement](https://www.drupal.org/docs/getting-started/system-requirements/overview)
2. [Composer](https://getcomposer.org/download/)

It's just composer, isn't it awesome? :)

## Installation

```
composer install
```
* Update /html/sites/default/settings.php with your database configuration
* Download sample database from /html/db/social-business.sql
* Make sure `files` (/html/sites/default/files) folder have writable permission. ie. `chmod -R 777 files`
* Create virtual host and access site from /html folder OR you can access from localhost/social-business/html (if you cloned it under working localhost setup)
* Login as admin from /user  - `superadmin/123456`
* If you face any issue enable debug from `/html/sites/default/settings.php` or using `drush`
  - Add following code in settings.php - 
  ```
  $config['system.logging']['error_level'] = 'verbose';
  ```
  - Or run drush command from vendor folder, like:
  ```
  ./vendor/drush/drush/drush  cache:rebuild
  ```


## Management
* Category Management - 
   - Add - `/admin/structure/taxonomy/manage/post_types_category/add`
   - List - `/admin/structure/taxonomy/manage/post_types_category/overview`
     ![CategoryManagement](https://github.com/user-attachments/assets/201d1ee8-fbe8-4fdd-8413-de72aff65f49)

* User Management - `/admin/people`
  ![UserManagement](https://github.com/user-attachments/assets/834851fb-bd2d-4a12-bf3c-63ef96867630)

* Rebuild & Cache management - `/admin/config/development/performance`
