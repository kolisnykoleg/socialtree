<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    protected static $defaultDescription = 'Creates a new user';
    private $entityManager;
    private $userRepository;
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $username = $io->ask('Username');
            $userExists = $this->userRepository->count(['username' => $username]);
            if ($userExists) {
                $io->warning('Username is already in use');
            }
        } while ($userExists);

        $password = $io->ask('Password');

        $user = new User();
        $user->setUsername($username);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('User created successfully');

        return Command::SUCCESS;
    }
}
