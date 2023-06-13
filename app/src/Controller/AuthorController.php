<?php
/**
 * Author list controller.
 */

namespace App\Controller;

use App\Entity\Author;
use App\Interface\AuthorServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController.
 */
#[Route('/ebay/authors')]
class AuthorController extends AbstractController
{
    /**
     * Author service.
     */
    private AuthorServiceInterface $authorService;

    /**
     * Constructor.
     * @param AuthorServiceInterface $authorService
     */
    public function __construct(AuthorServiceInterface $authorService)
    {
        $this->authorService = $authorService;
    }


    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'ebay_authors',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->authorService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('ebay/authors/authors.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Author $author
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'ebay_authors_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Author $author): Response
    {
        return $this->render(
            'ebay/authors/show.html.twig',
            ['author' => $author]
        );
    }
}
