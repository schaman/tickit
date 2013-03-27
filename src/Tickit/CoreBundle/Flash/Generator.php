<?php

namespace Tickit\CoreBundle\Flash;

use Symfony\Component\Yaml\Yaml;

/**
 * Flash message generator.
 *
 * Provides a way of encapsulating all flash message notifications across the
 * application.
 *
 * Messages templates are read from app/extra/messages.yml
 *
 * @package Tickit\CoreBundle\Flash
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Generator implements GeneratorInterface
{
    /**
     * Array of message templates
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Constructor.
     */
    public function __construct($configPath)
    {
        $this->loadMessages($configPath);
    }

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was created
     *
     * @return string
     */
    public function getEntityCreatedMessage($entityName)
    {
        return $this->parseMessage('entityCreated', array('entity' => $entityName));
    }

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was updated
     *
     * @return string
     */
    public function getEntityUpdatedMessage($entityName)
    {
        return $this->parseMessage('entityUpdated', array('entity' => $entityName));
    }

    /**
     * Generates and returns flash message for the creation of entities
     *
     * @param string $entityName The name of the entity that was deleted
     *
     * @return string
     */
    public function getEntityDeletedMessage($entityName)
    {
        return $this->parseMessage('entityDeleted', array('entity' => $entityName));
    }

    /**
     * Parses a message string with replacement options
     *
     * @see Generator::$messages
     *
     * @param string $messageType The message type to parse (must exist in Generator::$messages)
     * @param array  $replacement An array of placeholders => replacement values
     *
     * @throws \RuntimeException If an empty $message is provided or there is a missing replacement value
     *
     * @return string
     */
    protected function parseMessage($messageType, array $replacement)
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

        return $message;
    }

    /**
     * Loads messages from the app/extra/messages.yml
     *
     * @param string $folderPath The path to the folder containing the messages configuration file
     *
     * @return void
     */
    protected function loadMessages($folderPath)
    {
        $yml = new Yaml();
        $messages = $yml->parse($folderPath . '/messages.yml');

        $this->messages = $messages;
    }
}
