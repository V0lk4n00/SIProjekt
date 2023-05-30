<?php
/**
 * Author list controller.
 */

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LandingPageController.
 */
#[Route('/ebay/authors')]
class AuthorController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request          HTTP Request
     * @param AuthorRepository   $authorRepository Task repository
     * @param PaginatorInterface $paginator        Paginator
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'ebay_authors',
        methods: 'GET'
    )]
    public function index(Request $request, AuthorRepository $authorRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $authorRepository->queryAll(),
            $request->query->getInt('page', 1),
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
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
