<?php
/**
 * Record list controller.
 */

namespace App\Controller;

use App\Entity\Record;
use App\Entity\User;
use App\Form\Type\RecordType;
use App\Interface\RecordServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RecordController.
 */
#[Route('/ebay/records')]
class RecordController extends AbstractController
{
    /**
     * Record service.
     */
    private RecordServiceInterface $recordService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     * @param RecordServiceInterface $recordService Record service
     * @param TranslatorInterface    $translator    Translator
     */
    public function __construct(RecordServiceInterface $recordService, TranslatorInterface $translator)
    {
        $this->recordService = $recordService;
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
        name: 'ebay_records',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $pagination = $this->recordService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render('ebay/records/records.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Record $record Record
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'ebay_records_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    #[IsGranted('VIEW', subject: 'record')]
    public function show(Record $record): Response
    {
        if ($record->getRental() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('ebay_index');
        }

        return $this->render(
            'ebay/records/show.html.twig',
            ['record' => $record]
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
        name: 'record_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $record = new Record();
        $record->setRental($user);
        $form = $this->createForm(
            RecordType::class,
            $record,
            ['action' => $this->generateUrl('record_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recordService->save($record);

            $this->addFlash(
                'success',
                $this->translator->trans('message.added_success')
            );

            return $this->redirectToRoute('ebay_records');
        }

        return $this->render(
            'ebay/records/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Record  $record  Record entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'record_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('EDIT', subject: 'record')]
    public function edit(Request $request, Record $record): Response
    {
        if ($record->getRental() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('ebay_index');
        }
        $form = $this->createForm(
            RecordType::class,
            $record,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('record_edit', ['id' => $record->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recordService->save($record);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edit_success')
            );

            return $this->redirectToRoute('ebay_records');
        }

        return $this->render(
            'ebay/records/edit.html.twig',
            [
                'form' => $form->createView(),
                'record' => $record,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Record  $record  Record entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'record_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'record')]
    public function delete(Request $request, Record $record): Response
    {
        if ($record->getRental() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('ebay_index');
        }
        $form = $this->createForm(FormType::class, $record, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('record_delete', ['id' => $record->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recordService->delete($record);

            $this->addFlash(
                'success',
                $this->translator->trans('message.delete_success')
            );

            return $this->redirectToRoute('ebay_records');
        }

        return $this->render(
            'ebay/records/delete.html.twig',
            [
                'form' => $form->createView(),
                'record' => $record,
            ]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int, tag_id: int, status_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['genre_id'] = $request->query->getInt('filters_genre_id');
        $filters['author_id'] = $request->query->getInt('filters_author_id');

        return $filters;
    }
}
