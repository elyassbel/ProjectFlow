<?php

namespace App\Controller;

use App\DataTable\UserTableType;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users')]
class UserController extends AbstractController
{

    #[Route('/', name: 'app_admin_user')]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->createFromType(UserTableType::class)->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/user/index.html.twig', [
            'datatable' => $table,
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserService $userService): Response
    {
        $user = new User();
        $userProfile = new UserProfile();
        $userProfile->setCreatedAt(new \DateTimeImmutable());
        $user->setUserProfile($userProfile);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userService->generatePassword($user));
            $entityManager->persist($user);
            $entityManager->persist($userProfile);
            $entityManager->flush();

            $user = $userService->sendVerificationEmail($user);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'flashes.new');

            return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser()->getId() == $user->getId() && !$user->isEnabled()) {
                $user->setEnabled(true); //Can't deactivate current session
                $this->addFlash('warning', 'flashes.user.cant_deactivate');
            }
            $entityManager->flush();
            $this->addFlash('success', 'flashes.edit');

            return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: 'app_admin_user_delete')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()->getId() == $user->getId()) {
            $this->addFlash('danger', 'You can\'t delete yourself');

            return $this->redirectToRoute('app_admin_user_edit', ['id' => $user->getId()]);
        }

//        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
//        }

        $this->addFlash('success', 'flashes.delete');

        return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
    }
}
