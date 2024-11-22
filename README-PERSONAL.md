# Library installation
 - sudo apt-get install sendmail
# Modules
## For generating rest endpoints (core modules)
* JSONAPI
* Rest
* simple_oauth - Activate this module & generate public/private key from /admin/config/people/simple_oauth. Created "simple-auth-keys" folder & update path from setting.

## Other contributed api
* composer require 'drupal/jsonapi_extras:^3.26' - For controlling JSON API endpoints
* composer require 'drupal/email_registration:^1.4' - Remove username from registration which comes with core drupal registration
* composer require 'drupal/otp:^1.0' - OTP based registration
* composer require 'drupal/simple_oauth_password_grant:^1.0' - Provide "password" grant type
* ~composer require 'drupal/push_notifications:^1.0@alpha' - For sending push notification (May require in future, before installing please check "Activity Send Push Notification" module)~


## For API documentation installed contributed modules

* ~composer require 'drupal/openapi:^2.2'~
* ~composer require 'drupal/openapi_ui_redoc:^1.0@RC`~
* ~composer require 'drupal/openapi_jsonapi:^3.0'~


# Drush
* Run drush command ./vendor/drush/drush
* Admin details - admin/superSeceret@123

# Add Types & Category
/admin/structure/taxonomy/manage/post_types_category/add


 - Accommodation
   - Air B n B
   - Glamping
   - Hotels
   - Motels
   - Resorts
 - Activities
 - Attractions
 - Food and Drink
 - Entertainment
 - Health, Fitness and Wellness
 - Nature


# API URL

## All type & category list
http://localhost/food-business/html/api/post-type-category

## Get child category list after passing parent id
http://localhost/food-business/html/api/post-type-category?filter[parent.id]=a8bdfa4e-a0bb-48b7-9e2b-edc0fd9427a9&include=parent

## List all api documentation on postman. 
Export attached .json file and check after updating base URL.

File path: `/Drupal Social - Business.postman_collection.json`
