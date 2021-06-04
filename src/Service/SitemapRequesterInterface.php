<?php

namespace Drupal\simple_sitemap_index\Service;

/**
 * Interface SitemapRequesterInterface.
 */
interface SitemapRequesterInterface {

  const SITEMAP_UNPUBLISHED = 2;

  const SITEMAP_PUBLISHED = 1;

  const SITEMAP_RUNNING = 2;

}
