<?php

namespace App\Service;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{
    public function __construct(private readonly EmailVerifier $emailVerifier, private readonly UserPasswordHasherInterface $hasher, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * @throws \Exception
     */
    public function sendVerificationEmail(User $user, $path = 'app_setting_email_verify', $template = 'emails/confirmation_email.html.twig'): User
    {
        // Verify last time > 10min
        if ($user->getVerificationEmailSentAt()) {
            $timeDiff = $user->getVerificationEmailSentAt()->diff(new \DateTime());
            if ($timeDiff->i < 10) {
                throw new \Exception('You have to wait at least 10 minutes before sending a new verification email');
            }
        }
        // Send Email
        $this->emailVerifier->sendEmailConfirmation($path, $user, (new TemplatedEmail())
            ->from(new Address('bot@projectflow.com', $this->translator->trans('app.title', [], 'emails')))
            ->to($user->getEmail())
            ->subject($this->translator->trans('confirmation_email.subject', [], 'emails'))
            ->htmlTemplate($template)
        );
        $user->setVerificationEmailSentAt(new \DateTimeImmutable());
        $user->setVerified(false);

        return $user;
    }

    public function generatePassword(User $user, $length = 8): string
    {
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $password = substr(str_shuffle($string), 0, $length);

        return $this->hasher->hashPassword($user, $password);
    }
}
