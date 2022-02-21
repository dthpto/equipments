<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class valid_sn implements Rule
{

    private $data;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mask2regex = [
            'N' => '[0-9]',
            'A' => '[A-Z]',
            'a' => '[a-z]',
            'X' => '[A-Z0-9]',
            'Z' => '[_@-]'
        ];
        $type_id = $this->data['equipment_type_id'];
        // getting mask of current type
        $eq_type = DB::table('equipment_types')->find($type_id);
        if(!$eq_type) return false;
        $eq_sn_mask = $eq_type->sn_mask;

        $regex = '';
        for($i = 0; $i < strlen($eq_sn_mask) - 1; $i++){
            $char = substr($eq_sn_mask, $i, 1);
            if(isset($mask2regex[$char])){
                $regex .= $mask2regex[$char];
            } else {
                return false; // неверный символ маски
            }
        }
       return preg_match('/' . $regex .'/m', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute does not match its type mask.';
    }
}
