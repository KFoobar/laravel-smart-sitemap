# Dynamic Sitemap Generator for Laravel

A lightweight Laravel package for generating sitemaps dynamically and on demand.

Designed with simplicity in mind, this package makes it easy to build and serve up-to-date sitemaps directly from your routes or controllers—without relying on caching, scheduled tasks, or pre-generated files.

Ideal for applications with frequently changing content, multi-language setups, or dynamic routing.

## Installation

Install the package via Composer:

```bash
composer require kfoobar/laravel-smart-sitemap
```

## Usage

This package provides three functions: **add()**, **generate()** and **view()**.

- **add(url, modified)** – Adds a URL to the sitemap. The second parameter is optional and represents the last modified date.
- **generate()** – Returns the sitemap as a string.
- **view()** – Returns the sitemap as a proper XML response.

Note: The `priority` and `changefreq` fields are intentionally excluded, as Google no longer considers them relevant.

### Using the Facade

Add URLs and generate the sitemap using the provided facade:

```php
use KFoobar\SmartSitemap\Facades\Sitemap;

Sitemap::add('https://example.com', now());
Sitemap::generate();
```

### Using the Factory

Alternatively, instantiate the factory directly for more granular control:

```php
use KFoobar\SmartSitemap\SitemapFactory;

(new SitemapFactory)
    ->add('https://example.com')
    ->add('https://example.com/about')
    ->generate();
```

### Example: Serving a Dynamic Sitemap

Here’s a simple way to implement a dynamic sitemap in your Laravel application. Just define a route and return the sitemap directly from a controller—no need for pre-generating files or scheduled jobs.

Define a route in `routes/web.php`:

```php
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
```

Create the controller `app/Http/Controllers/SitemapController.php`:

```php
namespace App\Http\Controllers;

use App\Models\Content\Post;
use KFoobar\SmartSitemap\Factories\SitemapFactory;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = new SitemapFactory;

        foreach (['home', 'about'] as $route) {
            $sitemap->add(route($route));
        }

        foreach (Post::all() as $post) {
            $sitemap->add(
                route('posts.show', $post),
                $post->updated_at->toIso8601String()
            );
        }

        return $sitemap->view();
    }
}
```

## Contributing

Contributions are welcome!

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
