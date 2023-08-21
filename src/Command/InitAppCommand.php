<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:init-app')]
class InitAppCommand extends Command
{
    public function __construct(
        private readonly UserPasswordHasherInterface $pwdHasher,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Project Flow : Initialization start ! ✨');

        // CREATE USER
        $pwd = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 6);
        $user = new User();
        $user->setEmail('admin@projectflow.com')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setVerificationEmailSentAt(new \DateTimeImmutable())
            ->setVerified(true)
            ->setEnabled(true)
            ->setFirstName('Elyass')
            ->setLastName('Belahcen')
            ->setRoles(['ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH', 'ROLE_USER']);

        $user->setPassword($this->pwdHasher->hashPassword($user, $pwd));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('✅ User %s has been created !', $user->getUserIdentifier()));

        return Command::SUCCESS;
    }
}
