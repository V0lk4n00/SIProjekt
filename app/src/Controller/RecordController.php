<?php
/**
 * Record list controller.
 */

namespace App\Controller;

use App\Entity\Record;
use App\Interface\RecordServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * Constructor.
     * @param RecordServiceInterface $recordService
     */
    public function __construct(RecordServiceInterface $recordService)
    {
        $this->recordService = $recordService;
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
        $pagination = $this->recordService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('ebay/records/records.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Record $record
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'ebay_records_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Record $record): Response
    {
        return $this->render(
            'ebay/records/show.html.twig',
            ['record' => $record]
        );
    }
}
