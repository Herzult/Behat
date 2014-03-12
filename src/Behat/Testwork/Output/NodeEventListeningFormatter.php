<?php

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Testwork\Output;

use Behat\Testwork\EventDispatcher\TestworkEventDispatcher;
use Behat\Testwork\Output\Node\EventListener\EventListener;
use Behat\Testwork\Output\Printer\OutputPrinter;
use Symfony\Component\EventDispatcher\Event;

/**
 * Testwork EventListener-based pretty formatter.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
final class NodeEventListeningFormatter implements Formatter
{
    /**
     * @var OutputPrinter
     */
    private $printer;
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var EventListener
     */
    private $listener;
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $description;

    /**
     * Initializes formatter.
     *
     * @param string        $name
     * @param string        $description
     * @param array         $parameters
     * @param OutputPrinter $printer
     * @param EventListener $listener
     */
    public function __construct($name, $description, array $parameters, OutputPrinter $printer, EventListener $listener)
    {
        $this->name = $name;
        $this->description = $description;
        $this->parameters = $parameters;
        $this->printer = $printer;
        $this->listener = $listener;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(TestworkEventDispatcher::BEFORE_ALL_EVENTS => 'listenEvent');
    }

    /**
     * Returns formatter name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns formatter description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns formatter output printer.
     *
     * @return OutputPrinter
     */
    public function getOutputPrinter()
    {
        return $this->printer;
    }

    /**
     * Sets formatter parameter.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Returns parameter name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    /**
     * Proxies event to the listener.
     *
     * @param Event $event
     */
    public function listenEvent(Event $event)
    {
        if (1 < func_num_args()) {
            $eventName = func_get_arg(1);
        } else {
            $eventName = $event->getName();
        }

        $this->listener->listenEvent($this, $event, $eventName);
    }
}