# Laravel Fractal

A Laravel Service Provider for [League/Fractal](http://fractal.thephpleague.com).

---

## Installation

Add laravel-fractal to your composer.json file:

```json
"require": {
    "gathercontent/laravel-fractal": "~0.1"
}
```

Get composer to install the package:

```
$ composer update gathercontent/laravel-fractal
```

### Registering the Package

Register the service provider within the `providers` array found in `app/config/app.php`:

```php
'providers' => array(
    // ...
    'GatherContent\LaravelFractal\LaravelFractalServiceProvider'
)
```

Add an alias within the `aliases` array found in `app/config/app.php`:


```php
'aliases' => array(
    // ...
    'Fractal' => 'GatherContent\LaravelFractal\LaravelFractalFacade',
)
```

### Configuration

To override the default configuration, you can publish the config files to your application.
Artisan can do this automatically for you via the command line:

```bash
$ php artisan config:publish gathercontent/laravel-fractal
```

## Usage

### Basic Example

Formatting a single item:

```php
// routes.php
Route::get('/me', array('before' => 'auth', function () {
    return Fractal::item(Auth::user(), new UserTransformer);
}));
```

Formatting a collection:

```php
// routes.php
Route::get('/comments', function () {
    return Fractal::collection(Comment::all(), new CommentTransformer);
});
```

Adding meta data:

```php
// routes.php
Route::get('/comments', function () {
    return Fractal::collection(Comment::all(), new CommentTransformer, function ($resources) {
        $resources->setMetaValue('foo', 'bar');
    });
});
```

Returning a paginated collection:

```php
// routes.php
Route::get('/comments', function () {
    return Fractal::collection(Comment::paginate(), new CommentTransformer);
});
```

Using a custom pagination adapter:

```php
// routes.php
Route::get('/comments', function () {
    $comments = Comment::paginate();
    $adapter = new MyIlluminatePaginationAdapter($comments);
    return Fractal::collection($comments, new CommentTransformer, null, $adapter);
});
```
