<?php

namespace App\Core\Transformers;

class HideIdTransformer
{
    public function transform(array $data): array
    {
        $hiddenFields = ['id', 'owner_id'];
        foreach ($hiddenFields as $field) {
            unset($data[$field]);
        }
        return $data;
    }
}
