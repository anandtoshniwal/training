<?php

namespace Drupal\d8_training\EventSubscriber;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\d8_training\Event\NodeInsertEvent;

/**
 * Event Subscriber RequestHandlerSubscriber.
 */
class RequestHandlerSubscriber implements EventSubscriberInterface {

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
    protected $logger;

  /**
   * RequestHandlerSubscriber constructor.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   * @param \Drupal\Core\Logger\LoggerChannelFactory $logger
   */
  public function __construct(RouteMatchInterface $route_match, LoggerChannelFactory $logger) {
    $this->routeMatch = $route_match;
    $this->logger = $logger->get('d8_training');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onRespond'];
    $events[NodeInsertEvent::NODE_INSERT][] = ['onNodeInsert'];
    return $events;
  }

  /**
   * {@inheritdoc}
   */
  public function onRespond(FilterResponseEvent $event) {
    if ($this->routeMatch->getRouteName() == 'entity.node.canonical') {
      $response = $event->getResponse();
      $response->headers->set('Access-Control-Allow-Origin', '*');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function onNodeInsert(NodeInsertEvent $events) {
    $this->logger->notice('Node ' . $events->getEntity()->getTitle() . ' has been created.');
  }
}
