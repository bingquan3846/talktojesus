<?php

namespace App\Service;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Result\Stream\Delta\TextDelta;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\AI\Platform\Result\StreamResult;

class AiAssistant
{
    public function __construct(
        #[Autowire(service: 'ai.agent.chat_assistant')]
        private readonly AgentInterface $agent,
    ) {
    }

    public function ask(string $question): string
    {
        $messages = new MessageBag(Message::ofUser($question));
        return $this->agent->call($messages)->getContent();
    }

    /**
     * @return \Generator<string>
     */
    public function askStreamed(string $question): \Generator
    {
        $messages = new MessageBag(Message::ofUser($question));
        $result = $this->agent->call($messages, ['stream' => true]);

        if (!$result instanceof StreamResult) {
            throw new \RuntimeException('Expected a stream result when using stream mode.');
        }

        foreach ($result->getContent() as $delta) {
            if ($delta instanceof TextDelta) {
                yield $delta->getText();
                continue;
            }

            yield (string) $delta;
        }
    }
}