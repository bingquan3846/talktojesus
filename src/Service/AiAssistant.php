<?php

namespace App\Service;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AiAssistant
{
    public function __construct(
        #[Autowire(service: 'ai.agent.chat_assistant')]
        private readonly AgentInterface $agent,
    ) {
    }

    public function ask(string $question): string
    {
        $messages = new MessageBag();
        $messages->add(new Message($question));

        $response = $this->agent->call($messages);

        return $response->getContent();
    }
}