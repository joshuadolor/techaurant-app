<?php

namespace App\Core\Transformers;

class HideIdTransformer
{
    public function transform(array $data): array
    {
        unset($data['id']);
        return $data;
    }
}
