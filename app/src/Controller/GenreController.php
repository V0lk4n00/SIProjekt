<?php
/**
 * Genre list controller.
 */

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GenreController.
 */
#[Route('/ebay/genres')]
class GenreController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request         HTTP Request
     * @param GenreRepository    $genreRepository Genre repository
     * @param PaginatorInterface $paginator       Paginator
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'ebay_genres',
        methods: 'GET'
    )]
    public function index(Request $request, GenreRepository $genreRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $genreRepository->queryAll(),
            $request->query->getInt('page', 1),
            GenreRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('ebay/genres/genres.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Genre $genre
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'ebay_genres_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Genre $genre): Response
    {
        return $this->render(
            'ebay/genres/show.html.twig',
            ['genre' => $genre]
        );
    }
}
