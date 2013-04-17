<?php

namespace Tickit\ProjectBundle\Form\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttribute;


/**
 * AttributeValueForm event subscriber.
 *
 * Responsible for hooking into the PRE_SET_DATA event and building the
 * attribute value form based on the type of attribute that the value is for.
 *
 * @package Tickit\ProjectBundle\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\ProjectBundle\Form\Type\AttributeValueFormType
 */
class AttributeValueFormSubscriber implements EventSubscriberInterface
{
    /**
     * Gets subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'buildFields');
    }

    /**
     * Builds fields for the AttributeValueForm subscriber.
     *
     * Looks at the type of attribute that the value is associated with and
     * builds form fields relevant for that type.
     *
     * @param FormEvent $event The form event
     *
     * @return void
     */
    public function buildFields(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        switch (get_class($data)) {
            case 'Tickit\ProjectBundle\Entity\ChoiceAttributeValue':
                $this->buildChoiceValueFields($form, $data->getAttribute());
                break;
            case 'Tickit\ProjectBundle\Entity\EntityAttributeValue':
                $this->buildEntityValueFields($form, $data->getAttribute());
                break;
            case 'Tickit\ProjectBundle\Entity\LiteralAttributeValue':
                $this->buildLiteralValueFields($form, $data->getAttribute());
                break;
        }
    }

    /**
     * Builds fields on the form for a literal attribute
     *
     * @param FormInterface    $form      The form to build fields on
     * @param LiteralAttribute $attribute The attribute that fields need adding for
     *
     * @return void
     */
    protected function buildLiteralValueFields(FormInterface $form, LiteralAttribute $attribute)
    {
        $assert = $this->buildAssertionsForAttribute($attribute);

        switch ($attribute->getValidationType()) {
            case 'date':
                $assert->add(new Assert\Date());
                $fieldType = 'date';
                break;
            case 'datetime':
                $assert->add(new Assert\DateTime());
                $fieldType = 'datetime';
                break;
            case 'file':
                $assert->add(new Assert\File());
                $fieldType = 'file';
                break;
            case 'email':
                $assert->add(new Assert\Email());
                $fieldType = 'email';
                break;
            case 'number':
                $assert->add(new Assert\Type(array('type' => 'integer')));
                $fieldType = 'number';
                break;
            case 'url':
                $assert->add(new Assert\Url());
                $fieldType = 'url';
                break;
            case 'ip':
                $assert->add(new Assert\Ip());
                $fieldType = 'text';
                break;
            default:
                $fieldType = 'text';
        }

        $form->add('value', $fieldType, array('constraints' => $assert->toArray()));
    }

    /**
     * Builds fields on the form for a choice attribute
     *
     * @param FormInterface   $form      The form to build fields on
     * @param EntityAttribute $attribute The attribute that fields need adding for
     *
     * @return void
     */
    protected function buildEntityValueFields(FormInterface $form, EntityAttribute $attribute)
    {
        //todo
    }

    /**
     * Builds fields on the form for a choice attribute
     *
     * @param FormInterface   $form      The form to build fields on
     * @param ChoiceAttribute $attribute The attribute that fields need adding for
     *
     * @return void
     */
    protected function buildChoiceValueFields(FormInterface $form, ChoiceAttribute $attribute)
    {
        $choices = $attribute->getChoices();
        $form->add('value', 'choice', array(
            'choices' => array()
        ));

        var_dump($choices);
    }

    /**
     * Builds base assertions for all attribute types
     *
     * @param AbstractAttribute $attribute The attribute
     *
     * @return ArrayCollection
     */
    private function buildAssertionsForAttribute(AbstractAttribute $attribute)
    {
        $collection = new ArrayCollection();

        if (false === $attribute->getAllowBlank()) {
            $collection->add(new Assert\NotBlank());
        }

        return $collection;
    }
}
