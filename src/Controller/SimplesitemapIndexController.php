<?php

namespace Drupal\simple_sitemap_index\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\simple_sitemap\Controller\SimplesitemapController;

/**
 * Class SimplesitemapController.
 */
class SimplesitemapIndexController extends SimplesitemapController {

  /**
   * Returns a sitemap_index.xml information if a variant_index type defined.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Returns an XML response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   */
  public function getSitemapIndex(Request $request) : Response {
    $variants = $this->config('simple_sitemap.variants.index')->get('variants');
    if (empty($variants)) {
      throw new NotFoundHttpException();
    }

    return $this->getSitemap($request, \key($variants));
  }

}
