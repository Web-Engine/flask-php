# flask-php
PHP Library like Flask of Python

# How to Use

## Server Settings

### Apache: .htaccess
create .htaccess file at same directory to index.php and write:

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php/$1 [L]
    
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
    
    $app = new FlaskPHP();
    
    $app->route('/index', function () {
        return 'This is index.';
    });
    
    $app->get('/members/<name>',
    function($name) {
        return render_php('/pages/members.php', ['name' => $name]);
    });
    
    $app->post('/members/<name>',
    function($name) {
        return render_twig('/pages/members.twig', ['name' => $name]);
    });
