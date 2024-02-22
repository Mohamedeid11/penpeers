<?php

namespace App\View\Components\Admin\Forms;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\View\Component;

class AdminFormGroup extends Component
{
    public $field;
    public $name;
    public $item;
    public $default;
    public $label;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($field, $name, $item, $default=null, $label=null)
    {
        $this->field = $field;
        $this->name = $name;
        $this->item = $item;
        $this->default = $default;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.admin.forms.admin-form-group');
    }

    public function type()
    {
        return $this->field->get('type');
    }
    public function label(){
        return $this->label ?:__('admin.forms.'.$this->name);
    }
    public function errorClasses($errors)
    {
        if ($errors->has($this->name))
            return " parsley-error ";
        else {
            return "";
        }
    }

    public function isSelected($key, $old)
    {
        if ($old) {
            if ($old == $key) {

                return " selected ";
            }
        } else {
            if ($this->item->exists) {
                if ($this->item->toArray()[$this->name] == $key) {
                    return " selected ";
                }
            }
        }
        return "";
    }

    public function value($old)
    {
        if ($old) {
            return $this->type() !== 'password' ? $old : '';
        }
        else{
            if ($this->item->exists) {
                if($this->type() !== 'password') {
                    $name = $this->item->toArray()[$this->name];
                    return is_array($name) ? implode(' ', $name) : $name;
                }else {
                    return "";
                }
            }else{
                return  $this->default ?: "";
            }
        }
    }
    public function disabled(){
        return $this->field->get('disabled', null) ? 'disabled' : '';
    }
    public function isRequired(){
        $required = $this->field->get('required', null);
        if(! $required){
            return false;
        }
        if($this->item->exists){
            return $required == 1 || $required == 3 ? true : false;
        }
        else {
            return $required == 2 || $required == 3 ? true : false;
        }
    }
    public function required(){
        return $this->isRequired() ?' required ' : '';
    }
    public function isChecked($old){
        if ($old){
            return ' checked ';
        }
        else {
            if($this->item->exists){
                return $this->item->toArray()[$this->name] ? ' checked ' : '';
            }
        }
        return $this->default ?' checked ': "";
    }
    public function isImage(){
        if ($this->type() == 'file') {
            $val = $this->value(null);
//            die("a".$val."b");
            if ($val && $val !== '') {
                return true;
            }
        }
    }
}
