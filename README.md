# notTV Beta
Next generation notTV Prototype

[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2Febda2b06-9802-464b-b2ec-041cc237c2ec%3Fdate%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/701295/sites/2043538)

# README

Welcome to the notTV MVP (Minimum Viable Product).  
[not.tv](https://not.tv)

Thank you for taking the time to trial, test, and/or contribute to our app. We support freedom, cooperation, creativity, and community. We also strive for world-class quality. Let's work to keep the code and product the best in the world.

- Travis Michael Ernest Cross, tec21 (October 7, 2022)  
  [@tec21](https://github.com/tec21)

---

## 1. Images

Unzip the README.zip

The following images included in the archive will unzip to `/storage/app/public/images`:

- `logo_black_311.png`
- `logo_white_512.png`
- `EBU_Colorbars.svg.png`
- `Ping.png`
- `logo_white.svg`

```plaintext
   README.zip Contents:
   2499   2024-02-09 21:33    README.txt
   3310   2022-10-07 22:24    storage/app/public/images/EBU_Colorbars.svg.png
   97422  2022-10-07 23:08    storage/app/public/images/Ping.png
   13866  2022-04-09 03:39    storage/app/public/images/logo_black_311.png
   26851  2022-06-12 03:22    storage/app/public/images/logo_white_512.png
   2078   2024-02-03 19:55    .env.working.example
   4566   2024-03-27 20:31    storage/app/public/images/logo_white.svg
```
**NOTE:** The images are required before you seed the database.

## 2. Setup the `.env` File

Create and configure the `.env` file as per your environment settings.

## 3. Nginx Configuration

Add `client_max_body_size xxM` inside the http section in `/etc/nginx/nginx.conf`, where `xx` is the size (in megabytes) that you want to allow.

## 4. Run the Storage Link

```bash
$ php artisan storage:link
```
## 5. Seed the Database

```bash
$ php artisan migrate
$ php artisan db:seed --class=FirstRunSeeder
$ php artisan slugs:update
```
# An Administrator account will be created: #

- **Email:** admin@not.tv
- **Password:** nottv123

## 6. Change CDN Endpoint and Cloud Folder

Update the CDN Endpoint and Cloud Folder in the Admin Settings.

### Additional Seeders

```bash
$ php artisan db:seed --class=UserSeeder
$ php artisan db:seed --class=CreatorSeeder
$ php artisan db:seed --class=TeamSeeder
$ php artisan db:seed --class=TeamMemberSeeder
$ php artisan db:seed --class=ShowSeeder
$ php artisan db:seed --class=ShowEpisodeSeeder
$ php artisan db:seed --class=MovieSeeder
$ php artisan db:seed --class=MasterSeeder # sets up categories
```
## User Accounts Created with the UserSeeder

- **Email:** user@not.tv  
  **Password:** nottv123

- **Email:** subscriber@not.tv  
  **Password:** nottv123

- **Email:** vip@not.tv  
  **Password:** nottv123

- **Email:** creator@not.tv  
  **Password:** nottv123


## Setting up notTV from a Git Repo
  __More detail will be found in the Admin/Settings page, login as admin@not.tv after you run the database migration.__

You will need to provide your SSH key to development team (@tec21) to gain access to the repo. This is until the foundation is in place, then we will expand the project for open development.

1. Unzip `README.zip` (it will add images to the `/storage/app/public/images` folder)
2. Install PHP 8.1
3. Install PHP Extensions: `curl`, `mbstring`, ...
4. Install Composer
5. Run `composer update`
6. Create `.env` file (modify `.env.example`):
    - `APP_NAME`
    - `DB`
    - `PUSHER_LOCAL_DEV`
7. Run Sail:
    - `sail up`
    - `sail artisan migrate`
    - `sail artisan db:seed`
