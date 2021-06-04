<?php

namespace Drupal\simple_sitemap_index;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\simple_sitemap\Form\FormHelper;

/**
 * Manipulates hooks.
 *
 * This class contains primarily bridged hooks for compile-time or
 * cache-clear-time hooks.
 */
class HookFormManager implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * Simple sitemap form helper.
   *
   * @var Drupal\simple_sitemap\Form\FormHelper
   */
  protected $formHelper;

  /**
   * HooksManager constructor.
   */
  public function __construct(
    TranslationInterface $translation,
    FormHelper $form_helper
  ) {
    $this->setStringTranslation($translation);
    $this->formHelper = $form_helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('string_translation'),
      $container->get('simple_sitemap.form_helper')
    );
  }

  /**
   * Alter a form.
   *
   * @param array $form
   *   Nested array of form elements that comprise the form.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *   The arguments that \Drupal::formBuilder()->getForm()
   *   was originally called with are available in the array
   *   $form_state->getBuildInfo()['args'].
   * @param string $form_id
   *   String representing the name of the form itself.
   *   Typically this is the name of the function that generated the form.
   */
  public function alter(array &$form, FormStateInterface $form_state, string $form_id) {
    if ($this->formHelper->processForm($form_state)) {
      if (isset($form['simple_sitemap']) && isset($form['simple_sitemap']['settings']['index'])) {
        unset($form['simple_sitemap']['settings']['index']);
      }
    }
  }

}
