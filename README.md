SIMPLE XML SITEMAP INDEX
=========================

Provide a Sitemap Index Variant for Simple XML sitemap.


INTRODUCTION
------------

The sitemap index is described as follow:
[Sitemap index](https://www.sitemaps.org/protocol.html#index)
[Google explanation](https://developers.google.com/search/docs/advanced/sitemaps/large-sitemaps)


 * For a full description of the module, visit the project page:
   https://drupal.org/project/simple_sitemap_index

 * To submit bug reports and feature suggestions, or to track changes:
   https://drupal.org/project/issues/simple_sitemap_index


REQUIREMENTS
------------

This module currently use a Drupal >= 8 || 9.


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module.


CONFIGURATION
-------------

In the backend area: `/admin/config/search/simplesitemap/variants` add a
new: `variants` with a new type: `index`

Example for a website with 2 sitemap and relative index:
```php
pages | default_hreflang | Pages
products | default_hreflang | Products
index | index | Sitemap index
```

To use the `Sitemap index` as the default sitemap it is necessary
to set the `Default sitemap variant` with the value:
`Sitemap index` in the advanced settings:
`/admin/config/search/simplesitemap/settings`

After hit the button: `Rebuild queue & generate` if everything worked
correctly, the value: `published` will be displayed
in the Status item of the line: `Sitemap index`


TROUBLESHOOTING
---------------

 * Unknown.

FAQ
---

Q: Is a baby panda is cute?

A: Of Course, but less than a baby duck.

MAINTAINERS
-----------

Current maintainers:
 * Adrien Loyant (donaldinou) - https://www.drupal.org/u/donaldinou
