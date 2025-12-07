<?php

namespace martinsmith\entrylevel\models;

use craft\base\Model;

/**
 * Settings model for Entry Type hierarchy configuration.
 */
class Settings extends Model
{
    /**
     * @var array Section config: [sectionUid => [typeHierarchy => [...], typeOrder => [...]]]
     */
    public array $sectionConfig = [];

    public function defineRules(): array
    {
        return [['sectionConfig', 'safe']];
    }

    public function setAttributes($values, $safeOnly = true): void
    {
        // Decode JSON typeOrder values before setting
        if (isset($values['sectionConfig'])) {
            foreach ($values['sectionConfig'] as $uid => &$config) {
                if (isset($config['typeOrder']) && is_string($config['typeOrder'])) {
                    $config['typeOrder'] = json_decode($config['typeOrder'], true) ?: [];
                }
            }
        }
        parent::setAttributes($values, $safeOnly);
    }

    public function getTypeConfig(string $sectionUid, string $entryTypeUid): ?array
    {
        return $this->sectionConfig[$sectionUid]['typeHierarchy'][$entryTypeUid] ?? null;
    }
}

