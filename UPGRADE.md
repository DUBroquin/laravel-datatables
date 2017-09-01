## Upgrading from v6.x to v7.x
  - composer require yajra/laravel-vuetable-oracle 
  - composer require yajra/laravel-vuetable-buttons
  - php artisan vendor:publish --tag=vuetable --force
  - php artisan vendor:publish --tag=vuetable-buttons --force

## Upgrading from v5.x to v6.x
  - Change all occurrences of `dubroquin\vuetable;` to `dubroquin\vuetable;`. (Use Sublime's find and replace all for faster update). 
  - Remove `vuetable` facade registration.
  - Temporarily comment out `dubroquin\vuetable\vuetableServiceProvider`.
  - Update package version on your composer.json and use `yajra/laravel-vuetable-oracle: ~6.0`
  - Uncomment the provider `dubroquin\vuetable\vuetableServiceProvider`. 
