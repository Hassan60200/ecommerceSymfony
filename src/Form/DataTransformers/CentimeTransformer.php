<?php

namespace App\Form\DataTransformers;

use Symfony\Component\Form\DataTransformerInterface;

class CentimeTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if ($value === null) {
            return;
        }
        return $value / 100;
    }

    public function reverseTransform($value)
    {
        if ($value === null) {
            return;
        }
        return $value * 100;
    }
}