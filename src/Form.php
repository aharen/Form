<?php

namespace aharen\Form;

use aharen\Form\Exceptions\InvalidConfigException;

class Form
{
    public $config = [];

    public $name = 'form';

    public $fields = [];

    public $tmp_field = [];

    public function __construct($config = [])
    {
        $this->validateAndSetConfig($config);
    }

    public function __call($method, $params)
    {
        $value = isset($params[0]) ? $params[0] : null;
        $this->tmp_field->$method($value);
        return $this;
    }

    protected function validateAndSetConfig($config)
    {
        $diff = array_diff_key($this->configOptions(), $config);
        if (count($diff) > 0) {
            foreach ($diff as $key => $value) {
                if ($value === true) {
                    throw new InvalidConfigException('Required configuration parameter not set.');
                }
            }
        }

        $config_out = [];
        foreach ($this->configOptions() as $key => $value) {
            $config_out[$key] = (isset($config[$key])) ? $config[$key] : $value;
        }

        $this->config = $config_out;
    }

    protected function configOptions()
    {
        return [
            'name' => true,
            'grid' => false,
        ];
    }

    public function field($name = null)
    {
        $this->tmp_field = [];
        $this->tmp_field = new Field($name);
        return $this;
    }

    public function add()
    {
        $validateAdd = $this->tmp_field->validateAdd();
        if ($validateAdd !== true) {
            throw new InvalidConfigException($validateAdd);
        }

        $this->fields[] = $this->tmp_field;
        $this->tmp_field = [];
        return $this;
    }

    public function toArray()
    {
        unset($this->tmp_field);
        foreach ($this->fields as $key => $value) {
            $this->fields[$key] = (array) $value;
        }
        // return collect($this)->toArray();
        return (array) $this;
    }

    public function fields()
    {
        return (array) $this->toArray()['fields'];
    }
}
