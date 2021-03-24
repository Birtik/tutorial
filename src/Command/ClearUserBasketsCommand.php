<?php declare(strict_types=1);

namespace App\Command;

use App\Service\BasketProductManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearUserBasketsCommand extends Command
{
    protected static $defaultName = 'app:clear-baskets';

    /**
     * @var BasketProductManager
     */
    private BasketProductManager $basketProductManager;

    public function __construct(BasketProductManager $basketProductManager)
    {
        $this->basketProductManager = $basketProductManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Command clear all unused baskets');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('ClearBasketsInProgress...');
        $this->basketProductManager->clearAllUnusedBasket();
        $output->writeln('ClearBasketsDone');

        return Command::SUCCESS;
    }
}