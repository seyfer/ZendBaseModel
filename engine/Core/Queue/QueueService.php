<?php
namespace Core\Queue;

use SimpleQueue\QueueService as SimpleQueueService;

/**
 * Class QueueService
 * @package Core\Queue
 */
class QueueService
{

    /**
     * @var SimpleQueueService
     */
    protected $queue;

    /**
     * @param SimpleQueueService $queue
     */
    public function __construct(SimpleQueueService $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param $queueName
     * @param array $data
     * @return $this
     */
    public function addJob($queueName, array $data)
    {
        $this->queue->enqueue($queueName, $data);

        return $this;
    }

    /**
     * @param $queueName
     * @param $jobsCount
     * @return \Generator
     */
    public function getJobs($queueName, $jobsCount)
    {
        for ($i = 0; $i < $jobsCount; $i++) {
            $message = $this->queue->dequeue($queueName);

            if ($message === null) {
                break;
            }
            yield $message;
        }
    }

    /**
     * @return SimpleQueueService
     */
    public function closeExternalResources()
    {
        return $this->queue->closeExternalResources();
    }
}