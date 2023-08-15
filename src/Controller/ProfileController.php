<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/send-verification-email', name: 'app_profile_send_verification_email')]
    public function sendVerificationEmail(EmailVerifier $emailVerifier, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            // Verify last time > 10min
            if ($user->getVerificationEmailSentAt()) {
                $timeDiff = $user->getVerificationEmailSentAt()->diff(new \DateTime());
                if ($timeDiff->i < 10) {
                    throw new \Exception('You have to wait at least 10 minutes before sending a new verification email');
                }
            }
            // Send Email
            $emailVerifier->sendEmailConfirmation('app_verify_email', $user, (new TemplatedEmail())
                ->from(new Address('contact@symfoniacreatures.com', 'Symfonia Creatures'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $user->setVerificationEmailSentAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Verification email sent !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());

        }

        return $this->redirectToRoute('app_profile');
    }
}
