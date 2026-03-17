<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\PaymentTemplate\Form;

use Generated\Shared\Transfer\PaymentTemplateCreditCardTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use Spryker\Shared\PaymentTemplate\PaymentTemplateConfig;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;

/**
 * @method \Spryker\Yves\PaymentTemplate\PaymentTemplateConfig getConfig()
 */
class PaymentTemplateCreditCardSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    protected const string FIELD_PAYMENT_METHOD_TOKEN = 'paymentMethodToken';

    protected const string CREDIT_CARD = 'credit_card';

    public function getPropertyPath(): string
    {
        return PaymentTransfer::PAYMENT_TEMPLATE_CREDIT_CARD;
    }

    public function getName(): string
    {
        return PaymentTransfer::PAYMENT_TEMPLATE_CREDIT_CARD;
    }

    public function getProviderName(): string
    {
        return PaymentTemplateConfig::PAYMENT_PROVIDER_NAME;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentTemplateCreditCardTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPaymentMethodTokenField($builder);
    }

    protected function addPaymentMethodTokenField(FormBuilderInterface $builder): self
    {
        $builder->add(
            static::FIELD_PAYMENT_METHOD_TOKEN,
            HiddenType::class,
            [
                'label' => 'Payment Method Token',
                'required' => true,
                'constraints' => [
                    new Blank(),
                ],
                'attr' => [
                    'placeholder' => 'Token from payment provider SDK',
                ],
            ],
        );

        return $this;
    }

    protected function getTemplatePath(): string
    {
        return PaymentTemplateConfig::PAYMENT_PROVIDER_NAME . DIRECTORY_SEPARATOR . static::CREDIT_CARD;
    }
}
