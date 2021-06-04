<?php

namespace Drupal\simple_sitemap_index\Service;

use Drupal\Core\Database\Connection;

/**
 * Class SitemapRequester in order to request sitemap tables.
 */
class SitemapRequester implements SitemapRequesterInterface {

  /**
   * Database connection.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Internal use.
   *
   * @var array
   */
  private $dataSitemaps;

  /**
   * Constructor.
   *
   * @param Drupal\Core\Database\Connection $database
   *   Database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Return the common query string use for requester.
   *
   * @return string
   *   The query.
   */
  protected function getQueryString() : string {
    $query = <<<'QUERY'
SELECT type, status, SUM(link_count) as link_count
FROM {simple_sitemap}
GROUP BY type, status
ORDER BY type ASC, status ASC
QUERY;

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function getSitemaps(bool $force = FALSE) : array {
    if ($force || !$this->dataSitemaps) {
      $result = $this->database
        ->query($this->getQueryString())
        ->fetchAll();

      foreach ($result as $line) {
        $this->dataSitemaps[$line->type] = [
          'status' => (isset($this->dataSitemaps[$line->type])) ? static::SITEMAP_RUNNING : (int) $line->status,
          'link_count' => (int) $line->link_count,
        ];
      }
    }

    return $this->dataSitemaps;
  }

  /**
   * {@inheritdoc}
   */
  public function getPublishedSitemaps(bool $force = FALSE) : array {
    return \array_filter($this->getSitemaps($force), function ($sitemap) {
      return (static::SITEMAP_PUBLISHED === $sitemap['status']);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function getUnpublishedSitemaps(bool $force = FALSE) : array {
    return \array_filter($this->getSitemaps($force), function ($sitemap) {
      return (static::SITEMAP_UNPUBLISHED === $sitemap['status']);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function getRunningSitemaps(bool $force = FALSE) : array {
    return array_filter($this->getSitemaps($force), function ($sitemap) {
      return (static::SITEMAP_RUNNING === $sitemap['status']);
    });
  }

}
