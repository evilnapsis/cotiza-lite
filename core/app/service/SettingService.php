<?php
namespace App\Service;

use SettingData;
use Upload;

/**
 * Servicio para la configuración general y comercial del sistema.
 */
class SettingService {

    public function getAll(): array {
        $settings = SettingData::getAll();
        $map = [];
        foreach ($settings as $s) {
            $map[$s->name] = $s->val;
        }
        return $map;
    }

    public function get(string $name, string $default = ''): string {
        $setting = SettingData::getByPreffix($name);
        return $setting && !empty($setting->val) ? $setting->val : $default;
    }

    public function updateAll(array $post, array $files = []): void {
        foreach ($post as $key => $value) {
            if ($key === 'csrf_token') continue;
            SettingData::updateValFromName($key, (string)$value);
        }

        if (!empty($files)) {
            $uploadDir = dirname(__DIR__, 3) . '/storage/configuration/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($files as $key => $fileInfo) {
                if (!empty($fileInfo['name']) && !empty($fileInfo['tmp_name'])) {
                    $upload = new Upload($fileInfo);
                    if ($upload->uploaded) {
                        $upload->file_new_name_body = 'logo_' . time();
                        $upload->image_resize = true;
                        $upload->image_x = 300;
                        $upload->image_ratio_y = true;
                        $upload->Process($uploadDir);
                        if ($upload->processed) {
                            SettingData::updateValFromName($key, $upload->file_dst_name);
                            $upload->clean();
                        }
                    }
                }
            }
        }
    }
}
