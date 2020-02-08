<?php

namespace Ekyna\Bundle\MailchimpBundle\Controller;

use Ekyna\Bundle\MailchimpBundle\Event\WebhookEvent;
use Ekyna\Bundle\MailchimpBundle\Model\Webhook;
use Ekyna\Bundle\MailchimpBundle\Repository\AudienceRepository;
use Ekyna\Bundle\MailchimpBundle\Service\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WebhookController
 * @package Ekyna\Bundle\MailchimpBundle\Controller
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class WebhookController
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var AudienceRepository
     */
    private $repository;

    /**
     * @var Logger
     */
    private $logger;


    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param AudienceRepository       $repository
     * @param Logger                   $logger
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        AudienceRepository $repository,
        Logger $logger
    ) {
        $this->dispatcher = $dispatcher;
        $this->repository = $repository;
        $this->logger     = $logger;
    }

    /**
     * Webhook action.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        // Reply to GET requests
        if ($request->isMethod('GET')) {
            return new Response('Hey monkey!');
        }

        $secret = $request->attributes->get('secret');
        $type   = $request->request->get('type');
        $data   = $request->request->get('data');

        $this->logger->debug("Type: $type", $data);

        /* Example:
            data[merges][FNAME]: Foo
            data[merges][LNAME]: Bar
            data[email_type]: html
            data[reason]: manual
            data[email]: foo@gmail.com
            data[id]: 4V9c4EvRLk
            data[list_id]: ba039c6198
            data[web_id]: 3375995
        */

        if (empty($type) || empty($data) || empty($secret) || !array_key_exists('list_id', $data)) {
            throw new AccessDeniedHttpException('Unexpected data');
        }

        if (!$audience = $this->repository->findOneByIdentifierAndSecret($data['list_id'], $secret)) {
            throw new NotFoundHttpException("Unknown list");
        }

        switch ($type) {
            case Webhook::TYPE_SUBSCRIBE:
                $this->dispatcher->dispatch(WebhookEvent::SUBSCRIBE, new WebhookEvent($data));
                break;
            case Webhook::TYPE_UNSUBSCRIBE:
                $this->dispatcher->dispatch(WebhookEvent::UNSUBSCRIBE, new WebhookEvent($data));
                break;
            case Webhook::TYPE_PROFILE:
                $this->dispatcher->dispatch(WebhookEvent::PROFILE, new WebhookEvent($data));
                break;
            case Webhook::TYPE_CLEANED:
                $this->dispatcher->dispatch(WebhookEvent::CLEANED, new WebhookEvent($data));
                break;
            case Webhook::TYPE_UPEMAIL:
                $this->dispatcher->dispatch(WebhookEvent::UPEMAIL, new WebhookEvent($data));
                break;
            case Webhook::TYPE_CAMPAIGN:
                $this->dispatcher->dispatch(WebhookEvent::CAMPAIGN, new WebhookEvent($data));
                break;
            default:
                throw new AccessDeniedHttpException('Unexpected webhook type');
                break;
        }

        return new JsonResponse([
            'type' => $type,
            'data' => $data,
        ]);
    }
}
