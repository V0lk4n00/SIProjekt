<?php
/**
 * Genre list controller.
 */

namespace App\Controller;

use App\Entity\Genre;
use App\Form\Type\GenreType;
use App\Interface\GenreServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class GenreController.
 */
#[Route('/ebay/genres')]
class GenreController extends AbstractController
{
    /**
     * Genre service.
     */
    private GenreServiceInterface $genreService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param GenreServiceInterface $genreService Genre service
     * @param TranslatorInterface   $translator   Translator
     */
    public function __construct(GenreServiceInterface $genreService, TranslatorInterface $translator)
    {
        $this->genreService = $genreService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'ebay_genres',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->genreService->getPaginatedList(
            $request->query->getInt('page', 1)
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

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'genre_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->genreService->save($genre);

            $this->addFlash(
                'success',
                $this->translator->trans('Success!')
            );

            return $this->redirectToRoute('ebay_genres');
        }

        return $this->render(
            'ebay/genres/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Genre   $genre   Genre entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'genre_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Genre $genre): Response
    {
        $form = $this->createForm(
            GenreType::class,
            $genre,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('genre_edit', ['id' => $genre->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->genreService->save($genre);

            $this->addFlash(
                'success',
                $this->translator->trans('Edited successfully!')
            );

            return $this->redirectToRoute('ebay_genres');
        }

        return $this->render(
            'ebay/genres/edit.html.twig',
            [
                'form' => $form->createView(),
                'genre' => $genre,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Genre   $genre   Genre entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'genre_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Genre $genre): Response
    {
        $form = $this->createForm(FormType::class, $genre, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('genre_delete', ['id' => $genre->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->genreService->delete($genre);

            $this->addFlash(
                'success',
                $this->translator->trans('Deleted successfully!')
            );

            return $this->redirectToRoute('ebay_genres');
        }

        return $this->render(
            'ebay/genres/delete.html.twig',
            [
                'form' => $form->createView(),
                'genre' => $genre,
            ]
        );
    }
}
