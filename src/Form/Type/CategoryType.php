<?php

namespace BlogWriter\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CategoryType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', TextType::class, array(
				'label' => 'Nom de la catégorie',
				'attr' => array(
						'maxlength' => '255')
		));
	}

	public function getName()
	{
		return 'category';
	}
}