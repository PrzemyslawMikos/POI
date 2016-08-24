<?php
namespace PoiBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdministratorFields implements EventSubscriberInterface
{

    public static function getSubscribedEvents(){
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event){
        $administrator = $event->getData();
        $form = $event->getForm();

        //New
        if (!$administrator || null === $administrator->getId()) {
            $form->add('username', TextType::class)
                 ->add('plainPassword', RepeatedType::class, array(
                            'type' => PasswordType::class
                        )
                    );
        //Edit
        }else{
            $form->add('firstlogin', CheckboxType::class, array(
                            'required' => false
                        )
                    );
        }
    }
}