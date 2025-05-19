<?php

namespace App\Observers;

use App\Models\DataSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataSettingObserver
{
    /**
     * Handle the DataSetting "created" event.
     */
    public function created(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "updated" event.
     */
    public function updated(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "deleted" event.
     */
    public function deleted(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "restored" event.
     */
    public function restored(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    /**
     * Handle the DataSetting "force deleted" event.
     */
    public function forceDeleted(DataSetting $dataSetting): void
    {
        $this->refreshBusinessSettingsCache();
    }

    private function refreshBusinessSettingsCache()
    {
        $prefix = 'data_settings_';
        $cacheKeys = DB::table('cache')
            ->where('key', 'like', "%" . $prefix . "%")
            ->pluck('key');
        $appName = env('APP_NAME').'_cache';
        $remove_prefix = strtolower(str_replace('=', '', $appName));
        $sanitizedKeys = $cacheKeys->map(function ($key) use ($remove_prefix) {
            $key = str_replace($remove_prefix, '', $key);
            return $key;
        });
        foreach ($sanitizedKeys as $key) {
            Cache::forget($key);
        }
    }
}
