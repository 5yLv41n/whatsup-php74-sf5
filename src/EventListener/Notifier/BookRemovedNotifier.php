<?php

namespace App\EventListener\Notifier;

use App\Entity\Book;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class BookRemovedNotifier
{
    private LoggerInterface $logger;
    /**
     * @TODO
     * Use Messenger
     * Create Log entity
     * Install RabbitMQ
     *
     */
    public function __invoke(LifecycleEventArgs $lifecycleEventArgs)
    {
        /** @var Book $book */
        $book = $lifecycleEventArgs->getObject();
        $this->logger->info(
            sprintf(
                '%s was removed by %s at %s',
                $book->getIsbn(),
                $book->getCreatedBy()->getUsername(),
                $book->getDeletedAt()->format('d/m/Y H:i')
            )
        );
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
