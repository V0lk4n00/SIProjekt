<?php

/**
 * Landing page controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class LandingPageController.
 */
#[Route('/ebay')]
class LandingPageController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response Response
     */
    #[Route(name: 'ebay_index', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('ebay/index.html.twig');
    }
}
