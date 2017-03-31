<?php

namespace BlogWriter\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('username', TextType::class, array(
				'label' => 'Nom d\'utilisateur',
		));
		$builder->add('email', EmailType::class);
		$builder->add('firstName', TextType::class, array(
				'label' => 'Prénom',)
				);
		$builder->add('lastName', TextType::class, array(
				'label' => 'Nom',)
				);
		$builder->add('password', RepeatedType::class, array(
				'type'            => PasswordType::class,
				'invalid_message' => 'Les mots de passe doivent être identique !!',
				'options'         => array('required' => true),
				'first_options'   => array('label' => 'Mot de passe'),
				'second_options'  => array('label' => 'Mot de passe (vérification)'),
		));
		$builder->add('role', ChoiceType::class, array(
				'choices' => array('Admin' => 'ROLE_ADMIN', 'User' => 'ROLE_USER')
		));
	}
	
	public function getName()
	
	{
		return 'user';
	}
}