<?php

namespace Modules\Core\src\Traits;

use Modules\Core\src\Models\Activity;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    public function logActivity(string $event)
    {
        Activity::create([
            'log_name' => $this->getLogName(),
            'description' => $this->getActivityDescription($event),
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'causer_type' => auth()->user() ? get_class(auth()->user()) : null,
            'causer_id' => auth()->id(),
            'properties' => $this->getActivityProperties($event),
        ]);
    }

    protected function getLogName(): string
    {
        return strtolower(class_basename($this));
    }

    protected function getActivityDescription(string $event): string
    {
        return ucfirst($event) . ' ' . strtolower(class_basename($this));
    }

    protected function getActivityProperties(string $event): array
    {
        return [
            'attributes' => $this->getAttributes(),
            'old' => $event === 'updated' ? $this->getOriginal() : [],
        ];
    }
}
