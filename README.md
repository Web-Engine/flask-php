# Warning: this project is not stable now.
this project is developing now.  
please don't use, just looking.  
thank you.


# flask-php
PHP Library like Flask of Python

# How to Use

## Server Settings

### Apache: .htaccess
create .htaccess file at same directory to index.php and write:

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond $1 !^(index\.php)
    RewriteRule ^(.*)$ /index.php/$1 [L]

### IIS
not ready yet.

### NGINX
not ready yet.

## Install

    composer install webengine/flask-php

Cannot use without composer

## Example Code

    <?php
    use FlaskPHP\FlaskPHP;
    use FlaskPHP\Template\TwigTemplate;
    
    $app = new FlaskPHP();
    
    $app->route('/index', function () {
        return 'This is test.';
    });
    
    $app->route('/members/<name>',
    function($name) {
        return render_template('member.html', ['name' => $name]);
    });
