<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SettingEmailType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/app/settings')]
class SettingController extends AbstractController
{
    #[Route('/', name: 'app_setting')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userProfile);
            $entityManager->flush();
            $this->addFlash('success', 'Profile edited');

            return $this->redirectToRoute('app_setting');
        }

        return $this->render('setting/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/email', name: 'app_setting_email')]
    public function email(Request $request, UserService $userService, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $currentEmail = $user->getEmail();
        $form = $this->createForm(SettingEmailType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($currentEmail == $form->getData()->getEmail()) {
                    $this->addFlash('info', 'This is already your current email address');

                    return $this->redirectToRoute('app_setting_email');
                }
                $user = $userService->sendVerificationEmail($user);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Email changed, check your inbox to verify the new email address');

                return $this->redirectToRoute('app_setting_email');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('setting/email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/email/send-verification', name: 'app_setting_email_send_verification')]
    public function sendVerificationEmail(UserService $userService, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $user = $userService->sendVerificationEmail($user);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Verification email sent !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_setting_email');
    }

    #[Route('/email/verify', name: 'app_setting_email_verify')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository, EmailVerifier $emailVerifier): Response
    {
        $id = $request->query->get('id');
        if (null === $id) {
            return $this->redirectToRoute('app_setting_email');
        }

        $user = $userRepository->find($id);
        if (null === $user) {
            return $this->redirectToRoute('app_setting_email');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('app_setting_email');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_setting_email');
    }

    #[Route('/security', name: 'app_setting_security')]
    public function security(): Response
    {
        return $this->render('setting/security.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }

    #[Route('/preferences', name: 'app_setting_preferences')]
    public function preferences(): Response
    {
        return $this->render('setting/preferences.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }
}
