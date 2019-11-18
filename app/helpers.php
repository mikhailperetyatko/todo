<?php
    
if (! function_exists('flash')) {
    function flash(string $type, string $message = 'Операция прошла успешно')
    {
        session()->flash('type', $type);
        session()->flash('message', $message);
    }    
}

if (! function_exists('conversionRightToNumber')) {
    function conversionRightToNumber(string $rightAccess)
    {
        switch ($rightAccess) {
            case 'r' :
                return 1;
            case 'w' :
                return 2;
            case 'm' : 
                return 3;
            default : 
                return 0;
        }
    }    
}