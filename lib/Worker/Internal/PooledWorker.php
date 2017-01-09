<?php

namespace Amp\Parallel\Worker\Internal;

use Amp\Parallel\Worker\{ Task, Worker };
use AsyncInterop\Promise;

class PooledWorker implements Worker {
    /** @var callable */
    private $push;

    /** @var \Amp\Parallel\Worker\Worker */
    private $worker;

    /**
     * @param \Amp\Parallel\Worker\Worker $worker
     * @param callable $push Callable to push the worker back into the queue.
     */
    public function __construct(Worker $worker, callable $push) {
        $this->worker = $worker;
        $this->push = $push;
    }

    /**
     * Automatically pushes the worker back into the queue.
     */
    public function __destruct() {
        ($this->push)($this->worker);
    }

    /**
     * {@inheritdoc}
     */
    public function isRunning(): bool {
        return $this->worker->isRunning();
    }

    /**
     * {@inheritdoc}
     */
    public function isIdle(): bool {
        return $this->worker->isIdle();
    }

    /**
     * {@inheritdoc}
     */
    public function start() {
        $this->worker->start();
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(Task $task): Promise {
        return $this->worker->enqueue($task);
    }

    /**
     * {@inheritdoc}
     */
    public function shutdown(): Promise {
        return $this->worker->shutdown();
    }

    /**
     * {@inheritdoc}
     */
    public function kill() {
        $this->worker->kill();
    }
}