<?php
namespace BlogWriter\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;




class ArticleType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', TextType::class, array(
				'label' => 'Nom de l\'article',
				'attr' => array('class' =>'form-control col-lg-3')
		));
		$builder->add('content', TextareaType::class, array(
				'label' => 'Contenu',
				'attr' => array('class' =>'form-control col-lg-3')
		));
		//die(var_dump($options));
		$builder->add('author', ChoiceType::class, array(
				'label' => 'Author',
				'attr' => array('class' =>'form-control col-lg-3'),
				'choices' => $options['users'],
				'choice_label' => 'username',
				'choice_value' => 'id',
				
		));
		$builder->add('categorie', ChoiceType::class, array(
				'label' => 'Catégorie',
				'attr' => array('class' =>'form-control col-lg-3'),
				'choices' => $options['categories'],
				'choice_label' => 'name',
				'choice_value' => 'id',
				
		));
		$builder->add('img', FileType::class);
		$builder->add('published', CheckboxType::class, array(
				'label'    => 'Activer ?',
				'required' => false,
		));
		
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'categories' => null,
				'users' => null
		));
	}
	public function getName()
	{
		return 'article';
	}
}