<?php

namespace App\Traits;

trait ConvertionTraits
{
    /**
     * Convert to int
     *
     * @param string $value
     * @return array
     *
     */
    public function convertToInt($value)
    {
        return (int) $value;
    }
}
