<?php

namespace Drupal\simple_sitemap_index\Plugin\simple_sitemap\UrlGenerator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\simple_sitemap\EntityHelper;
use Drupal\simple_sitemap\Logger;
use Drupal\simple_sitemap\Simplesitemap;
use Drupal\simple_sitemap\Plugin\simple_sitemap\UrlGenerator\EntityUrlGeneratorBase;
use Drupal\simple_sitemap_index\Service\SitemapRequesterInterface;

/**
 * Class IndexUrlGenerator.
 *
 * @package Drupal\simple_sitemap_index\Plugin\simple_sitemap\UrlGenerator
 *
 * @UrlGenerator(
 *   id = "index",
 *   label = @Translation("Index sitemap URL generator"),
 *   description = @Translation("Generates URLs for indexing other sitemap."),
 * )
 */
class IndexUrlGenerator extends EntityUrlGeneratorBase {

  /**
   * Path validator.
   *
   * @var \Drupal\Core\Path\PathValidator
   */
  protected $pathValidator;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    Simplesitemap $generator,
    Logger $logger,
    LanguageManagerInterface $language_manager,
    EntityTypeManagerInterface $entity_type_manager,
    EntityHelper $entity_helper,
    SitemapRequesterInterface $sitemap_requester
  ) {
    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $generator,
      $logger,
      $language_manager,
      $entity_type_manager,
      $entity_helper
    );

    $this->sitemapRequester = $sitemap_requester;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('simple_sitemap.generator'),
      $container->get('simple_sitemap.logger'),
      $container->get('language_manager'),
      $container->get('entity_type.manager'),
      $container->get('simple_sitemap.entity_helper'),
      $container->get('simple_sitemap_index.requester')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDataSets() {
    $sitemap_settings = [
      'base_url' => $this->generator->getSetting('base_url', ''),
      'default_variant' => $this->generator->getSetting('default_variant', NULL),
    ];
    $sitemap_types = $this->generator->getSitemapManager()->getSitemapTypes();
    $sitemap_published = $this->sitemapRequester->getPublishedSitemaps();

    foreach ($sitemap_types as $type_name => $type_definition) {
      $variants = $this->generator->getSitemapManager()->getSitemapVariants($type_name, FALSE);
      if (!empty($variants)) {
        $sitemap_generator = $this->generator->getSitemapGenerator($type_definition['sitemapGenerator']);
        $sitemap_generator->setSettings($settings);
        if ('index' !== $type_name) {
          foreach ($variants as $variant_name => $variant_definition) {
            if (isset($sitemap_published[$variant_name])) {
              $data_set[$variant_name]['type'] = $type_name;
              $data_set[$variant_name]['definition'] = $variant_definition;
              $data_set[$variant_name]['url'] = $sitemap_generator->setSitemapVariant($variant_name)->getSitemapUrl();
            }
          }
        }
      }
    }

    return $data_set ?? [];
  }

  /**
   * {@inheritdoc}
   */
  protected function processDataSet($data_set) {
    return [
      'loc' => $data_set['url'],
      'lastmod' => DrupalDateTime::createFromFormat('c', 'now'),
    ];
  }

}
