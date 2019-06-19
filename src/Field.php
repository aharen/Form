<?php

namespace aharen\Form;

use aharen\Form\Exceptions\UndefinedException;

class Field
{
    public $name = null;

    public $label = null;

    public $value = null;

    public $type = null;

    public $size = 1;

    public $class = '';

    public $required = false;

    public $validation = null;

    public $editable = true;

    public $options = null;

    public $db_updatable = false;

    public $primary = false;

    public function __construct($name = null)
    {
        $this->name($name);
    }

    public function __call($method, $params)
    {
        if (property_exists($this, $method)) {
            $this->$method = $params[0];
        } else {
            throw new UndefinedException("The property \"$method\" was not found.", 422);
        }
    }

    public function name($value)
    {
        $this->name = $value;
        if ($this->label === null) {
            $this->label(ucfirst($value));
        }
    }

    public function primary()
    {
        $this->primary = true;
        $this->type = 'integer';
        $this->editable = false;
    }

    public function validateAdd()
    {
        if ($this->name === null) {
            return 'Required field "name" cannot be null.';
        }

        return true;
    }
}
