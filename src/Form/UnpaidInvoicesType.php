<?php

namespace App\Form;

use App\Entity\UnpaidInvoices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnpaidInvoicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('due_date')
            ->add('unpaid_amount')
            ->add('alert_date')
            ->add('alert_sent')
            ->add('interest_amount')
            ->add('invoices')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UnpaidInvoices::class,
        ]);
    }
}
