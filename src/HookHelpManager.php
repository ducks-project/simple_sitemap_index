<?php

namespace Drupal\simple_sitemap_index;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Manipulates hooks.
 *
 * This class contains primarily bridged hooks for compile-time or
 * cache-clear-time hooks.
 */
class HookHelpManager implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * HooksManager constructor.
   */
  public function __construct(TranslationInterface $translation) {
    $this->setStringTranslation($translation);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('string_translation')
    );
  }

  /**
   * Provide online user help.
   *
   * @param string $route_name
   *   For page-specific help, use the route name as identified in the
   *   module's routing.yml file. For module overview help, the route name
   *   will be in the form of "help.page.$modulename".
   * @param Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match. This can be used to generate different help
   *   output for different pages that share the same route.
   *
   * @return string|array
   *   A render array, localized string, or object that can be rendered into
   *   a string, containing the help text.
   */
  public function help($route_name, RouteMatchInterface $route_match) {
    if (\Drupal::hasService('readme_help.utils.readme')) {
      switch ($route_name) {
        case 'help.page.simple_sitemap_index':
          $output = \Drupal::service('readme_help.utils.readme')->getFormattedReadmeOutput('simple_sitemap_index');
          break;

        default:
          $output = '';
          break;
      }
    }
    return $output ?? '';
  }

}
