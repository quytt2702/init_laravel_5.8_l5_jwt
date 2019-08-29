<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

trait TransformerTrait
{
    /**
     * @param Model $model
     * @param array $customFields
     * @param array $removeFields
     * @return array
     */
    public function parseTransformer(Model $model, $customFields = [ ], $removeFields = [])
    {
        $customFields['id'] = $model->id;

        $hidden = $model->getHidden();

        // Filter data
        $fields = array_filter($model->getFillable(), function ($item) use ($hidden, $removeFields) {
            return !in_array($item, $hidden) && !in_array($item, $removeFields);
        });

        $result = [];

        foreach ($fields as $field) {
            if ($model->$field instanceof Carbon) {
                $result[$field] = $model->$field->toDateTimeString();
            } else {
                $result[$field] = $model->$field;
            }
        }

        return array_merge($customFields, $result);
    }
}