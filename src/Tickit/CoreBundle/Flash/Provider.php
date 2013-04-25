<?php

namespace Tickit\CoreBundle\Flash;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Yaml\Yaml;

/**
 * Flash message provider.
 *
 * Provides a way of encapsulating all flash message notifications across the
 * application.
 *
 * Messages templates are read from app/extra/messages.yml
 *
 * @package Tickit\CoreBundle\Flash
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Provider implements ProviderInterface
{
    /**
     * Array of message templates
     *
     * @var array
     */
    protected $messages = array();

    /**
     * The session
     *
     * @var Session
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param Session $session     The session object
     * @param string  $configPath  The full path to the messages configuration folder
     * @param string  $environment The current environment name
     */
    public function __construct(Session $session, $configPath, $environment)
    {
        $this->session = $session;
        $this->loadMessages($configPath, $environment);
    }

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was created
     *
     * @return void
     */
    public function addEntityCreatedMessage($entityName)
    {
        $this->addMessageToFlashBag('entityCreated', array('entity' => $entityName));
    }

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was updated
     *
     * @return void
     */
    public function addEntityUpdatedMessage($entityName)
    {
        $this->addMessageToFlashBag('entityUpdated', array('entity' => $entityName));
    }

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was deleted
     *
     * @return void
     */
    public function addEntityDeletedMessage($entityName)
    {
        $this->addMessageToFlashBag('entityDeleted', array('entity' => $entityName));
    }

    /**
     * Parses a message string with replacement options
     *
     * @param string $messageType The message type to parse (must exist in Generator::$messages)
     * @param array  $replacement An array of placeholders => replacement values
     *
     * @see Generator::$messages
     *
     * @throws \RuntimeException If an empty $message is provided or there is a missing replacement value
     *
     * @return void
     */
    protected function addMessageToFlashBag($messageType, array $replacement)
    {
        if (empty($this->messages[$messageType])) {
            throw new \RuntimeException('Flash message generator cannot parse an empty message');
        }

        $messageTemplate = $this->messages[$messageType];

        // currently we only replace entity names, but we might add more replacements in future
        if (empty($replacement['entity']) && strpos($messageTemplate, '%entity%') !== false) {
            throw new \RuntimeException(
                sprintf('Flash message generator requires an "entity" placeholder value for type "%s"', $messageType)
            );
        }

        $message = str_replace('%entity%', $replacement['entity'], $this->messages[$messageType]);

        $this->session->getFlashBag()->add('notice', $message);
    }

    /**
     * Loads messages from the app/extra/messages.yml
     *
     * @param string $folderPath  The path to the folder containing the messages configuration file
     * @param string $environment The current environment name
     *
     * @return void
     */
    protected function loadMessages($folderPath, $environment)
    {
        $yml = new Yaml();
        $messagePath = sprintf('%s/messages_%s.yml', $folderPath, $environment);
        if (!file_exists($messagePath)) {
            $messagePath = sprintf('%s/messages.yml', $folderPath);
        }

        $messages = $yml->parse($messagePath);

        $this->messages = $messages;
    }
}
