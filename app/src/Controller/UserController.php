<?php
/**
 * This is the license block.
 * It can contain licensing information, copyright notices, etc.
 */
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserPasswordType;
use App\Form\Type\UserEmailType;
use App\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[Route('/ebay/users')]
class UserController extends AbstractController
{
    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'ebay_users', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->userService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('ebay/users/users.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit_email', name: 'user_edit_email', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(
            UserEmailType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('user_edit_email', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edit_success')
            );

            return $this->redirectToRoute('ebay_users');
        }

        return $this->render(
            'ebay/users/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit_password', name: 'user_edit_password', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function editPassword(Request $request, User $user): Response
    {
        $form = $this->createForm(
            UserPasswordType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('user_edit_password', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edit_success')
            );

            return $this->redirectToRoute('ebay_users');
        }

        return $this->render(
            'ebay/users/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
