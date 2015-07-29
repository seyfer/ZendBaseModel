<?php

namespace ZendBaseModel\Form\Element;

use Zend\Form\Element\Select;

/**
 * Description of StatusSelect
 *
 * @author seyfer
 */
class StatusSelect extends Select
{

    const NAME = 'status';

    public function __construct($options = [])
    {
        parent::__construct(self::NAME, $options);

        $this->configure($options);
    }

    /**
     * получить селект с контрактами
     *
     * @param array $options
     * @return $this
     */
    private function configure($options = [])
    {
        $optionsConfig = [
            'label'                     => 'Статус',
            'value_options'             => $options,
            'disable_inarray_validator' => true,
            "attributes"                => [
                "value" => 0,
            ],
            'empty_option'              => '---',
        ];

        $this->setAttribute("id", self::NAME);
        $this->setOptions($optionsConfig);

        return $this;
    }

    /**
     * @return $this
     */
    public function configureWithData()
    {
        $options        = \ZendBaseModel\Enum\Status::getConstants();
        $optionsFlipped = array_flip($options);

        $this->configure($optionsFlipped);

        return $this;
    }

}
