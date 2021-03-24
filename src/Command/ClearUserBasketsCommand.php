<?php declare(strict_types=1);

namespace App\Command;

use App\Service\BasketProductManager;
use Psr\Log\LoggerInterface;
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

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(BasketProductManager $basketProductManager, LoggerInterface $logger)
    {
        $this->basketProductManager = $basketProductManager;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Command clear all unused baskets');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('ClearBasketsInProgress');
        $this->basketProductManager->clearAllUnusedBasket();
        $this->logger->info('ClearBasketsDone');

        return Command::SUCCESS;
    }
}