<?php
/**
 * Record list controller.
 */

namespace App\Controller;

use App\Entity\Record;
use App\Repository\RecordRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * Index action.
     *
     * @param Request            $request          HTTP Request
     * @param RecordRepository   $recordRepository Record repository
     * @param PaginatorInterface $paginator        Paginator
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'ebay_records',
        methods: 'GET'
    )]
    public function index(Request $request, RecordRepository $recordRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $recordRepository->queryAll(),
            $request->query->getInt('page', 1),
            RecordRepository::PAGINATOR_ITEMS_PER_PAGE
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
