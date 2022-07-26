<?php

namespace Infrastructure\Symfony\Command;

use Infrastructure\Symfony\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Infrastructure\Symfony\Repository\UserRepository;

#[AsCommand(
    name: 'add-user',
    description: 'Ajout d\'un User',
)]
class AddUserCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('user', InputArgument::OPTIONAL, 'User')
            ->addArgument('pass', InputArgument::OPTIONAL, 'Pass Clair')
            ->addArgument('role', InputArgument::OPTIONAL, 'Role')//->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = new SymfonyStyle($input, $output);
        $user = $input->getArgument('user');
        $pass = $input->getArgument('pass');
        $role = $input->getArgument('role');

        if (!$user) {
            $io->error(sprintf('You passed an argument: user'));
            return Command::FAILURE;
        }
        if (!$pass) {
            $io->error(sprintf('You passed an argument: pass'));
            return Command::FAILURE;
        }
        $u = new User();
        $u->setUsername($user);
        $u->setPassword($pass);
        $u->setRoles([$role ?? 'USER']);
        $this->userRepository->add($u, true);
        $io->success(sprintf('User : %s , Pass : %s Role : %s', $user, $pass, $role));

        $io->success('User ajouté');

        return Command::SUCCESS;
    }
}
