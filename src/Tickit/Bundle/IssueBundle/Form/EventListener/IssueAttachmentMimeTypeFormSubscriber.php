<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\IssueBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * Issue attachment mime type form subscriber.
 *
 * Sets the mime type of the issue attachment file after the data
 * has been bound to the form.
 *
 * @package Tickit\Bundle\IssueBundle\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentMimeTypeFormSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SUBMIT => 'setMimeType'];
    }

    /**
     * Sets the mime type of the attachment after submit
     *
     * @param FormEvent $event The form event
     */
    public function setMimeType(FormEvent $event)
    {
        $attachment = $event->getData();

        if (!is_array($attachment) || !isset($attachment['file'])) {
            return;
        }

        /** @var UploadedFile $file */
        $file = $attachment['file'];
        $attachment['mimeType'] = $file->getMimeType();

        $event->setData($attachment);
    }
}
