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
* Login as admin from /user  - `superadmin/123456`

## Management
* Category Management - 
   - Add - `/admin/structure/taxonomy/manage/post_types_category/add`
   - List - `/admin/structure/taxonomy/manage/post_types_category/overview`
* User Management - `/admin/people`
* Clear

