<?php

namespace Wexample\SymfonyDesignSystemDemo\Api\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Wexample\SymfonyApi\Api\Attribute\QueryOption\LengthQueryOption;
use Wexample\SymfonyApi\Api\Attribute\QueryOption\PageQueryOption;
use Wexample\SymfonyApi\Api\Attribute\QueryOption\StringQueryOption;
use Wexample\SymfonyApi\Api\Class\ApiResponse;
use Wexample\SymfonyApi\Api\Controller\AbstractApiController;
use Wexample\SymfonyDesignSystemDemo\Api\Normalizer\Entity\DemoMessage\DefaultDemoMessageNormalizer;
use Wexample\SymfonyDesignSystemDemo\Entity\DemoMessage;
use Wexample\SymfonyDesignSystemDemo\Repository\DemoMessageRepository;
use Wexample\SymfonyDesignSystemDemo\Repository\DemoRoomRepository;
use Wexample\SymfonyDesignSystemDemo\Service\DemoChatService;
use Wexample\SymfonyHelpers\Controller\AbstractController;

/**
 * What the demo chat talks to.
 *
 * A room is always named by the caller: the demo has no account and no session,
 * so what parts one thread from another is the only thing the page knows.
 */
#[Route(path: 'api/demo-message/', name: 'api_demo_message_')]
class DemoMessageController extends AbstractApiController
{
    final public const QUERY_OPTION_ROOM = 'room';

    final public const ROUTE_CREATE = 'create';

    final public const ROUTE_LIST = 'list';

    #[Route(path: 'create', name: self::ROUTE_CREATE, methods: [Request::METHOD_POST], options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE)]
    public function create(
        Request $request,
        DemoChatService $demoChatService,
        DemoRoomRepository $demoRoomRepository,
        DefaultDemoMessageNormalizer $normalizer,
    ): ApiResponse {
        $payload = $request->getPayload();

        $room = $demoRoomRepository->find($payload->getString(self::QUERY_OPTION_ROOM));

        if (! $room) {
            return self::apiResponseError('Unknown room.');
        }

        $type = $payload->getString('type', DemoMessage::TYPE_USER);

        if (! in_array($type, DemoMessage::getAllowedTypes(), true)) {
            return self::apiResponseError('Unknown message type.');
        }

        $message = $demoChatService->postMessage(
            $room,
            $type,
            $payload->getString('body')
        );

        // Only a spoken turn is answered: a tool or system line raised from the
        // composer is there to be shown, not to be talked to.
        if (DemoMessage::TYPE_USER === $type) {
            $demoChatService->scheduleReply($message);
        }

        return self::apiResponseSuccess(
            data: $normalizer->normalize($message)
        );
    }

    #[Route(path: 'list', name: self::ROUTE_LIST, methods: AbstractController::ROUTE_OPTIONS_METHOD_ONLY_GET, options: AbstractController::ROUTE_OPTIONS_ONLY_EXPOSE)]
    #[PageQueryOption]
    #[LengthQueryOption]
    #[StringQueryOption(key: self::QUERY_OPTION_ROOM, default: '')]
    public function list(
        Request $request,
        DemoMessageRepository $demoMessageRepository,
        DemoRoomRepository $demoRoomRepository,
        DefaultDemoMessageNormalizer $normalizer,
    ): ApiResponse {
        $room = $demoRoomRepository->find(
            self::getQueryOptionValue($request, self::QUERY_OPTION_ROOM, '')
        );

        if (! $room) {
            return self::apiResponseError('Unknown room.');
        }

        $builder = $demoMessageRepository->queryByRoomOldestFirst($room);

        $pagination = self::getQueryOptionPagination(
            request: $request,
            total: $demoMessageRepository->countAll($builder)
        );

        return self::apiResponsePaginated(
            pagination: $pagination,
            items: $normalizer->normalizeCollection(
                $demoMessageRepository->findPaginated(
                    page: $pagination->page,
                    length: $pagination->length,
                    builder: $builder
                )
            )
        );
    }
}
