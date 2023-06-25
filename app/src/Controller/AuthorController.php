<?php
/**
 * Author list controller.
 */

namespace App\Controller;

use App\Entity\Author;
use App\Form\Type\AuthorType;
use App\Interface\AuthorServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param AuthorServiceInterface $authorService Author service
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(AuthorServiceInterface $authorService, TranslatorInterface $translator)
    {
        $this->authorService = $authorService;
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
     * @param  Author $author
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

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'author_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->save($author);

            $this->addFlash(
                'success',
                $this->translator->trans('Author added successfully!')
            );

            return $this->redirectToRoute('ebay_authors');
        }

        return $this->render(
            'ebay/authors/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Author  $author  Author entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'author_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Author $author): Response
    {
        $form = $this->createForm(
            AuthorType::class,
            $author,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('author_edit', ['id' => $author->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->save($author);

            $this->addFlash(
                'success',
                $this->translator->trans('Edited successfully!')
            );

            return $this->redirectToRoute('ebay_authors');
        }

        return $this->render(
            'ebay/authors/edit.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Author  $author  Author entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'author_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Author $author): Response
    {
        if (!$this->authorService->canBeDeleted($author)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('Author cannot be deleted because they have a record assigned to them.')
            );

            return $this->redirectToRoute('ebay_authors');
        }

        $form = $this->createForm(FormType::class, $author, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('author_delete', ['id' => $author->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->authorService->delete($author);

            $this->addFlash(
                'success',
                $this->translator->trans('Deleted successfully!')
            );

            return $this->redirectToRoute('ebay_authors');
        }

        return $this->render(
            'ebay/authors/delete.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }
}
