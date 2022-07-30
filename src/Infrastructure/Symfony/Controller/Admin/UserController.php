<?php

namespace Infrastructure\Symfony\Controller\Admin;


use Domain\UserDomain\UserDto;
use Domain\UserDomain\Api\UserApi;
use Symfony\Component\Form\FormError;
use Infrastructure\Symfony\Entity\User;
use Infrastructure\Symfony\Form\UserType;
use Adapter\UserAdapter\UserEntityDtoMapping;
use Domain\UserDomain\Exception\ExceptionUser;
use Infrastructure\Symfony\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserApi $userApi): Response
    {

        return $this->render('admin/user/index.html.twig', [
            'users' => $userApi->getListeUser(),
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserApi $userApi, UserEntityDtoMapping $userEntityDtoMapping): Response
    {
        $user = new UserDto();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        //$form->get('username')->addError(new FormError('error de name'));
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userApi->addUser($user, true);
                return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
            } catch (ExceptionUser $e) {
                $form->get($e->getField())->addError(new FormError($e->getMessage()));
            }
        }
        //dd($user,$form);
        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request              $request, User $user, UserApi $userApi,
                         UserEntityDtoMapping $userEntityDtoMapping): Response
    {
        $dto=$userEntityDtoMapping->ToDto($user);
        $form = $this->createForm(UserType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $userApi->updateUser($dto, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
