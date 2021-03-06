<?php

namespace App\EventListener\Notifier;

use App\Entity\Book;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class BookUpdatedNotifier
{
    private LoggerInterface $logger;
    /**
     * @TODO
     * Use Messenger
     * Create Log entity
     * Install RabbitMQ
     *
     */
    public function __invoke(Book $book, LifecycleEventArgs $lifecycleEventArgs)
    {
        $this->logger->info(
            sprintf(
                '%s was updated by %s at %s',
                $book->getIsbn(),
                $book->getCreatedBy()->getUsername(),
                $book->getUpdatedAt()->format('d/m/Y H:i')
            )
        );
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
