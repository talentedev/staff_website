# Pheramor Banana System

## Install npm

Using npm:

```bash
$ npm install
```

Using yarn:

```bash
$ yarn
```

## Install composer

```bash
$ composer install
```

## Configuration

 Copy .env file and generate key by following command
```bash
 cp .env.example .env
 php artisan key:generate
```
 
 You should set host url and DB connection, mail server information on .env file.
```bash
 APP_URL=<YOUR_HOST_URL>  // For example, https://banana.pheramor.com

 DB_HOST=<DB_HOST>      // 127.0.0.1 or localhost
 DB_PORT=<DB_PORT>      // 3306 by default
 DB_DATABASE=<DB_NAME>
 DB_USERNAME=<USER>
 DB_PASSWORD=<PASSWORD>

 MAIL_DRIVER=smtp
 MAIL_HOST=<MAIL_HOST>
 MAIL_PORT=<MAIL_PORT>
 MAIL_USERNAME=<MAIL_USERNAME>
 MAIL_PASSWORD=<MAIL_PASSWORD>
 MAIL_ENCRYPTION=tls
```

## Development for js, scss

```bash
$ npm run watch or yarn run watch
```


## Production

```bash
$ npm run prod or yarn prod
```

## Reminer Emailing System Configuration (Linux)

Create tables for emailing.
```bash
$ CREATE TABLE settings ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, setting_key VARCHAR(30) NOT NULL, setting_value VARCHAR(30) NOT NULL );
$ INSERT INTO settings (setting_key, setting_value) VALUES ("ship_update_email", 1), ("sales_update_email", 1), ("account_update_email", 1), ("swab_update_email", 1), ("sequence_update_email", 1), ("first_reminder_email", 7), ("second_reminder_email", 10);
$ CREATE TABLE email_queue ( id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, product_id INTEGER NOT NULL, send_order INTEGER DEFAULT 1, send_date DATETIME NOT NULL );
```

You should create cron job.

in case of the unit in day
```bash
$ crontab -e
$ 0 0 * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```