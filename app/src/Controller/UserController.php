<?php
/**
 * User list controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 */
#[Route('/ebay/users')]
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request        HTTP Request
     * @param UserRepository     $userRepository Task repository
     * @param PaginatorInterface $paginator      Paginator
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'ebay_users',
        methods: 'GET'
    )]
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $userRepository->queryAll(),
            $request->query->getInt('page', 1),
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('ebay/users/users.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param User $user
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'ebay_users_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(User $user): Response
    {
        return $this->render(
            'ebay/users/show.html.twig',
            ['user' => $user]
        );
    }
}
