<?php

namespace Drupal\simple_sitemap_index\Plugin\simple_sitemap\SitemapGenerator;

use Drupal\simple_sitemap\Plugin\simple_sitemap\SitemapGenerator\SitemapGeneratorBase;

/**
 * Class Sitemap index.
 *
 * @package Drupal\simple_sitemap_index\Plugin\simple_sitemap\SitemapGenerator
 *
 * @SitemapGenerator(
 *   id = "index",
 *   label = @Translation("Sitemap index"),
 *   description = @Translation("Generates a sitemap index of all sitemaps."),
 * )
 */
class IndexSitemapGenerator extends SitemapGeneratorBase {

  /**
   * Generates and returns a sitemap index.
   *
   * @param array $links
   *   All links with their multilingual versions and settings.
   *
   * @return string
   *   Sitemap chunk.
   */
  protected function getXml(array $links) {
    $this->writer->openMemory();
    $this->writer->setIndent(TRUE);
    $this->writer->startSitemapDocument();

    // Add the XML stylesheet to document if enabled.
    if ($this->settings['xsl']) {
      $this->writer->writeXsl();
    }

    $this->writer->writeGeneratedBy();
    $this->writer->startElement('sitemapindex');

    // Add attributes to document.
    $attributes = self::$indexAttributes;
    foreach ($attributes as $name => $value) {
      $this->writer->writeAttribute($name, $value);
    }

    // Add all of the variant links.
    foreach ($links as $link) {
      $this->writer->startElement('sitemap');
      $this->writer->writeElement('loc', $link['loc']);
      $this->writer->writeElement('lastmod', $link['lastmod']);
      $this->writer->endElement();
    }

    $this->writer->endElement();
    $this->writer->endDocument();

    return $this->writer->outputMemory();
  }

}
