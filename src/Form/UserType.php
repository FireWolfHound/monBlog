<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{

    // Initialisation des paramètre pouvant donner l'utilisateur connecter
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    // Fonction qui permet de rajouter des options plus facilement 
    private function getConfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Récupération de l'utilisateur connecté
        $user = $this->security->getUser();

        $builder
            ->add('username', 
                TextType::class, 
                $this->getConfiguration('Nom D\'utilisateur', 'Nom D\'utilisateur')
            )
            ->add('email', 
                EmailType::class,
                $this->getConfiguration('Email', 'Email')
            )
            ->add('password', 
                PasswordType::class,
                $this->getConfiguration('Mot de passe', 'Mot de passe')
            )
            ->add('confirm_password', 
                PasswordType::class, 
                $this->getConfiguration('Confirmation mot de passe', 'Confirmation mot de passe')
            )
        ;

        // Ajout de l'input qui permet donner le ROLE si connecter en tant qu'admin
        if($user != null && $user->getRoles()[0] == "ROLE_ADMIN"){
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => User::ROLE_ADMIN,
                    'User' => User::ROLE_USER,
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
