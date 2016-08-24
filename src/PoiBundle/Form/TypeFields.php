<?php
namespace PoiBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TypeFields implements EventSubscriberInterface
{

    public static function getSubscribedEvents(){
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event){
        $type = $event->getData();
        $form = $event->getForm();

        if (!$type || null === $type->getId()) {
            $form->add('image', null, array('data_class' => null));
        }else{
            $form->add('image', null, array('data_class' => null, 'required' => false));
        }
    }
}