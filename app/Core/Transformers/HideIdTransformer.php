<?php

namespace App\Core\Transformers;

class HideIdTransformer
{
    protected array $hiddenFields = ['id', 'owner_id'];
    protected array $keepFields = ['country'];

    public function transform(array $data): array
    {
        return $this->hideFieldsRecursively($data, $this->hiddenFields, $this->keepFields);
    }

     /**
     * Recursively hide fields from nested data structures
     */
    private function hideFieldsRecursively(array $data, array $hiddenFields, array $keepFields = []): array
    {
        foreach ($data as $key => $value) {
            // Skip if this field should be kept
            if (in_array($key, $keepFields)) {
                continue;
            }

            // Remove the field if it's in the hidden fields list
            if (in_array($key, $hiddenFields)) {
                unset($data[$key]);
                continue;
            }

            // Hide fields that end with '_id' (foreign keys) except those in keepFields
            if (str_ends_with($key, '_id') && !in_array($key, $keepFields)) {
                unset($data[$key]);
                continue;
            }

            // If the value is an array, recursively process it
            if (is_array($value)) {
                if ($this->isAssociativeArray($value)) {
                    // It's an object, process recursively
                    $data[$key] = $this->hideFieldsRecursively($value, $hiddenFields, $keepFields);
                } else {
                    // It's a list/array, process each item
                    $data[$key] = array_map(function ($item) use ($hiddenFields, $keepFields) {
                        return is_array($item) ? $this->hideFieldsRecursively($item, $hiddenFields, $keepFields) : $item;
                    }, $value);
                }
            }
        }

        return $data;
    }

    /**
     * Check if array is associative (object-like) or indexed (list-like)
     */
    private function isAssociativeArray(array $array): bool
    {
        if (empty($array)) {
            return true;
        }
        
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
