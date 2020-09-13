<?php

/*
 * This file is part of Monsieur Biz' Settings plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusSettingsPlugin\Form;

use MonsieurBiz\SyliusSettingsPlugin\Settings\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSettingsType extends AbstractType implements SettingsTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'settings',
            'channel',
        ]);

        $resolver->setDefaults([
            'show_default_checkboxes' => true,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param $child
     * @param string|null $type
     * @param array $options
     *
     * @return $this
     */
    public function addWithDefaultCheckbox(FormBuilderInterface $builder, string $child, string $type = null, array $options = []): self
    {
        $data = $builder->getData();
        $builder->add($child, $type, $options);
        if (!$this->isDefaultForm($builder)) {
            $builder->add($child . '___' . Settings::DEFAULT_KEY, DefaultCheckboxType::class, [
                'label' => 'monsieurbiz.settings.ui.use_default_value',
                'related_form_child' => $builder->get($child),
                'data' => !isset($data[$child]),
                'required' => true,
            ]);
        }

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return bool
     */
    protected function isDefaultForm(FormBuilderInterface $builder): bool
    {
        return !$builder->getOption('show_default_checkboxes', true);
    }
}
