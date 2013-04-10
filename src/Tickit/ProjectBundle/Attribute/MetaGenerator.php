<?php

namespace Tickit\ProjectBundle\Attribute;

use Symfony\Component\HttpFoundation\Request;
use Tickit\ProjectBundle\Form\Type\AbstractAttributeFormType;

/**
 * Meta data generator for attributes.
 *
 * Provides functionality for parsing a form request and extracting meta data for an
 * attribute.
 *
 * @package Tickit\ProjectBundle\Attribute
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @see     Tickit\ProjectBundle\Controller\AttributeController
 */
class MetaGenerator
{
    /**
     * Builds a meta data object for a project attribute from provided request data
     *
     * @param Request                   $request  The request that was sent from the form submission
     * @param AbstractAttributeFormType $formType The form that sent the request
     *
     * @return \stdClass
     */
    public function generateFromRequest(Request $request, AbstractAttributeFormType $formType)
    {
        $type = $formType->getName();

        // process type and return meta object
    }
}
