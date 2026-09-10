<?php

namespace Wexample\SymfonyDesignSystemDemo\Service;

use Wexample\SymfonyDesignSystemDemo\Api\Normalizer\Entity\DemoMessage\DefaultDemoMessageNormalizer;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoRoom;
use Wexample\SymfonyDesignSystemDemo\Repository\DemoMessageRepository;
use Wexample\SymfonyDesignSystemDemo\Repository\DemoRoomRepository;
use Wexample\SymfonyLive\Enum\LiveTopicAction;
use Wexample\SymfonyLive\Helper\LiveTopicHelper;
use Wexample\SymfonyLive\Service\LivePublisherService;

/**
 * The whole of what the live chat demo does on the server.
 *
 * Messages are published on the topic of their room and not on their own: a
 * browser opens the page before a single message exists, so the only name it can
 * subscribe to is the one thing that is already there.
 */
class DemoChatService
{
    final public const EVENT_MESSAGE_CREATED = 'demo-message-created';

    /**
     * What the demo answers with. Generic on purpose: the point of the page is
     * that the answer arrives on its own, not what it says.
     */
    final public const REPLY_BODY = 'Well received. This answer was not in the response to your message: it was written once the request was over, and reached you through the hub.';

    /**
     * The kinds of line the composer cannot produce yet, written once so the page
     * has one of each to show. Order is the order they are said in.
     */
    private const SHOWCASE_THREAD = [
        [DemoMessage::TYPE_SYSTEM, 'Conversation opened with the demo model.'],
        [DemoMessage::TYPE_USER, 'What is the weather in Paris?'],
        [DemoMessage::TYPE_TOOL, 'weather.lookup(city: "Paris") → 18°C, overcast'],
        [DemoMessage::TYPE_ASSISTANT, 'It is 18°C and overcast in Paris right now.'],
        [DemoMessage::TYPE_ERROR, 'The model did not answer: the request timed out after 30 seconds.'],
    ];

    /** @var DemoMessage[] */
    private array $pendingReplies = [];

    public function __construct(
        private readonly DemoMessageRepository $demoMessageRepository,
        private readonly DemoRoomRepository $demoRoomRepository,
        private readonly DefaultDemoMessageNormalizer $normalizer,
        private readonly LivePublisherService $publisher,
    ) {
    }

    /**
     * A demo has no fixtures: the room and its showcase lines are made the first
     * time the page is opened, and found on every visit after.
     */
    public function findOrCreateShowcaseRoom(string $name): DemoRoom
    {
        $room = $this->demoRoomRepository->findOrCreateOneByName($name);

        // The system line is the marker: it can only come from here, so finding one
        // means the showcase has already been written into this room.
        if (! $this->demoMessageRepository->findOneBy(['room' => $room, 'type' => DemoMessage::TYPE_SYSTEM])) {
            foreach (self::SHOWCASE_THREAD as [$type, $body]) {
                $message = new DemoMessage($room);
                $message->setType($type);
                $message->setBody($body);

                $this->demoMessageRepository->save($message);
            }
        }

        return $room;
    }

    public function postMessage(
        DemoRoom $room,
        string $type,
        string $body
    ): DemoMessage {
        $message = new DemoMessage($room);
        $message->setType($type);
        $message->setBody($body);

        $this->demoMessageRepository->save($message);

        $this->publisher->publishEvent(
            LiveTopicHelper::entity($room, LiveTopicAction::EVENT),
            self::EVENT_MESSAGE_CREATED,
            $this->normalizer->normalize($message)
        );

        return $message;
    }

    /**
     * Holds an answer back until the request is over, so it can only reach the
     * browser through the hub — which is the one thing this page demonstrates.
     */
    public function scheduleReply(DemoMessage $message): void
    {
        $this->pendingReplies[] = $message;
    }

    public function postScheduledReplies(): void
    {
        $pending = $this->pendingReplies;
        $this->pendingReplies = [];

        foreach ($pending as $message) {
            $this->postMessage(
                $message->getRoom(),
                DemoMessage::TYPE_ASSISTANT,
                self::REPLY_BODY
            );
        }
    }
}
