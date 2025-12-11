<?php

namespace martinsmith\entrylevel;

use Craft;
use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\models\Section;
use martinsmith\entrylevel\models\Settings;
use yii\base\Event;

/**
 * Auto-parents entries in Structure sections based on Entry Type hierarchy.
 */
class Plugin extends BasePlugin
{
    public static ?Plugin $plugin = null;
    public bool $hasCpSettings = true;
    public string $schemaVersion = '1.0.0';

    private array $processedEntries = [];

    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Event::on(Entry::class, Entry::EVENT_AFTER_PROPAGATE, [$this, 'handleEntryAfterPropagate']);
    }

    public function handleEntryAfterPropagate(ModelEvent $event): void
    {
        /** @var Entry $entry */
        $entry = $event->sender;
        $entryKey = "{$entry->id}-{$entry->siteId}";

        // Early exits: already processed, not first save, draft/revision, or has parent
        if (isset($this->processedEntries[$entryKey]) ||
            !$entry->firstSave ||
            $entry->getIsDraft() ||
            $entry->getIsRevision() ||
            ($entry->level ?? 1) > 1) {
            return;
        }

        // Only Structure sections
        $section = $entry->getSection();
        if (!$section || $section->type !== 'structure') {
            return;
        }

        // Get type config
        $typeConfig = $this->getSettings()->getTypeConfig($section->uid, $entry->getType()?->uid ?? '');
        if (!$typeConfig || empty($typeConfig['parentTypeUid'])) {
            return;
        }

        // Find and assign parent
        $parent = $this->findParentEntry($section, $typeConfig);
        if (!$parent) {
            return;
        }

        $this->processedEntries[$entryKey] = true;

        Craft::info("Entry Level: defaultPlacement = '{$section->defaultPlacement}', BEGINNING constant = '" . Section::DEFAULT_PLACEMENT_BEGINNING . "'", __METHOD__);

        if ($section->defaultPlacement === Section::DEFAULT_PLACEMENT_BEGINNING) {
            Craft::info("Entry Level: Using PREPEND for entry {$entry->id}", __METHOD__);
            Craft::$app->getStructures()->prepend($section->structureId, $entry, $parent);
        } else {
            Craft::info("Entry Level: Using APPEND for entry {$entry->id}", __METHOD__);
            Craft::$app->getStructures()->append($section->structureId, $entry, $parent);
        }
    }

    private function findParentEntry(Section $section, array $config): ?Entry
    {
        $parentType = Craft::$app->getEntries()->getEntryTypeByUid($config['parentTypeUid']);
        if (!$parentType) {
            return null;
        }

        // Find most recent entry of the parent type
        return Entry::find()
            ->sectionId($section->id)
            ->typeId($parentType->id)
            ->status(null)
            ->orderBy('dateCreated DESC')
            ->one();
    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        $sections = array_filter(
            Craft::$app->getEntries()->getAllSections(),
            fn($s) => $s->type === 'structure'
        );

        return Craft::$app->getView()->renderTemplate('entry-level/settings', [
            'settings' => $this->getSettings(),
            'sections' => $sections,
        ]);
    }
}

