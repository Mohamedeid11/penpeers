<?php
namespace App\Traits;

trait HasAdminForm {
    function getFormFieldsAttribute(){
        return $this->form_fields();
    }
}
