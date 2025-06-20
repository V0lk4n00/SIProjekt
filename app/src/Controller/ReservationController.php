<?php

/**
 * Reservation list controller.
 */

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\Type\ReservationType;
use App\Interface\ReservationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ReservationController.
 */
#[Route('/ebay/reservations')]
class ReservationController extends AbstractController
{
    /**
     * Reservation service.
     */
    private ReservationServiceInterface $reservationService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param ReservationServiceInterface $reservationService Reservation service
     * @param TranslatorInterface         $translator         Translator
     */
    public function __construct(ReservationServiceInterface $reservationService, TranslatorInterface $translator)
    {
        $this->reservationService = $reservationService;
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
        name: 'ebay_reservations',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->reservationService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('ebay/reservations/reservations.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Reservation $reservation Reservation
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'ebay_reservations_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Reservation $reservation): Response
    {
        return $this->render(
            'ebay/reservations/show.html.twig',
            ['reservation' => $reservation]
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
        name: 'reservation_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->save($reservation);

            $this->addFlash(
                'success',
                $this->translator->trans('message.added_success')
            );

            return $this->redirectToRoute('ebay_index');
        }

        return $this->render(
            'ebay/reservations/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Delete action.
     *
     * @param Request     $request     HTTP request
     * @param Reservation $reservation Reservation entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'reservation_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(FormType::class, $reservation, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('reservation_delete', ['id' => $reservation->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->delete($reservation);

            $this->addFlash(
                'success',
                $this->translator->trans('message.delete_success')
            );

            return $this->redirectToRoute('ebay_reservations');
        }

        return $this->render(
            'ebay/reservations/delete.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }
}
