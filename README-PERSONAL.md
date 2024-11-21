# Modules
## For generating rest endpoints (core modules)
* JSONAPI
* Rest
## For controlling JSON API endpoints
composer require 'drupal/jsonapi_extras:^3.26'
## For API documentation installed contributed modules
~composer require 'drupal/openapi:^2.2'~
~composer require 'drupal/openapi_ui_redoc:^1.0@RC`~
~composer require 'drupal/openapi_jsonapi:^3.0'~


# Drush
* Run drush command ./vendor/drush/drush
* Admin details - admin/superSeceret@123

# Add Types & Category
/admin/structure/taxonomy/manage/post_types_category/add


Accommodation
Activities
Attractions
Food and Drink
Entertainment
Health, Fitness and Wellness
Nature

Air B n B
Glamping
Hotels
Motels
Resorts

# API URL

## All type & category list
http://localhost/food-business/html/api/post-type-category

## Get child category list after passing parent id
http://localhost/food-business/html/api/post-type-category?filter[parent.id]=a8bdfa4e-a0bb-48b7-9e2b-edc0fd9427a9&include=parent

