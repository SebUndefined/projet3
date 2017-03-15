<?php

namespace BlogWriter\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ReportingType extends AbstractType 
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('commentConcernedId', HiddenType::class);
		$builder->add('reason', ChoiceType::class, array(
			'choices' => array(
				'Ce commentaire est raciste' => 'Commentaire raciste',
				'Ce commentaire est une incitation à la haine' => 'Incitation à la haine',
				'Ce commentaire m\'attaque personellement' => 'Attaque envers un autre internaute',
				'Autre' => 'Autre',
			),		
		));
		$builder->add('comment', TextareaType::class);
	}
	public function getName()
	{
		return 'reporting';
	}
	
	
}