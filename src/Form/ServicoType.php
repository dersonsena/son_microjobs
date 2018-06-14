<?php

namespace App\Form;

use App\Entity\Categoria;
use App\Entity\Servico;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('valor')
            ->add('descricao', TextareaType::class, ['label' => 'Descrição do MicroJob'])
            ->add('informacoes_adicionais', TextareaType::class, ['label' => 'Informações adicionais do MicroJob'])
            ->add('prazo_entrega', TextType::class, ['label' => 'Prazo de Entrega'])
            ->add('status')
            ->add('imagem', FileType::class)
            ->add('categorias', EntityType::class, [
                'class' => Categoria::class,
                'choice_label' => 'nome',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('btn_salvar', SubmitType::class, [
                'label' => 'Salvar'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Servico::class,
        ]);
    }
}
