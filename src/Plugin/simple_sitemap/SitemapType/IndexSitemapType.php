<?php

namespace Drupal\simple_sitemap_index\Plugin\simple_sitemap\SitemapType;

/**
 * Class IndexSitemapType.
 *
 * @package Drupal\simple_sitemap_index\Plugin\simple_sitemap\SitemapType
 *
 * @SitemapType(
 *   id = "index",
 *   label = @Translation("Variant Index"),
 *   description = @Translation("The sitemap for indexing other sitemaps."),
 *   sitemapGenerator = "index",
 *   urlGenerators = {
 *     "index"
 *   },
 * )
 */
class IndexSitemapType extends SitemapTypeBase {
}
