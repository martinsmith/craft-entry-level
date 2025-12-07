# Entry Level for Craft CMS 5

Auto-parent entries in Structure sections based on Entry Type. Solves the UX problem where entries default to Level 1 when they should be children of a landing page.

## Requirements

- Craft CMS 5.0.0+
- PHP 8.2+

## Installation

### Via Composer

```bash
composer require hyphamedia/entry-level
php craft plugin/install entry-level
```

### Local Development

1. Place the plugin in `plugins/entry-level/`

2. Add to your project's `composer.json`:

```json
{
  "repositories": [
    {"type": "path", "url": "plugins/entry-level"}
  ]
}
```

3. Install:

```bash
composer require hyphamedia/entry-level:@dev
php craft plugin/install entry-level
```

## Configuration

1. Go to **Settings → Plugins → Entry Level**
2. For each Structure section, configure parent-child relationships between Entry Types
3. Choose a parent selection strategy:
   - **Most Recently Updated** - Uses the most recently updated entry of the parent type
   - **First Created** - Uses the oldest entry of the parent type
   - **Specific Entry** - Lets you select a specific entry as the parent

## How It Works

When you create a new entry of a configured "child" Entry Type, the plugin automatically moves that entry under a parent entry of the configured "parent" Entry Type.

### Example: News Section

```
Entry Types:
├── News Landing (root level)
├── News Category (parent: News Landing)
└── News Article (parent: News Category)
```

With this configuration:
- Creating a **News Article** → automatically placed under a **News Category**
- Creating a **News Category** → automatically placed under **News Landing**
- Creating a **News Landing** → stays at root level (no parent configured)

### Multi-Level Hierarchies

The plugin supports unlimited nesting depth. Chain Entry Types to create deep structures:

| Entry Type | Parent Type | Result |
|------------|-------------|--------|
| Landing Page | (none) | Root level |
| Category | Landing Page | Level 2 |
| Sub-Category | Category | Level 3 |
| Article | Sub-Category | Level 4 |

## Parent Selection Strategies

### Most Recently Updated (default)

Finds the parent entry of the configured type that was most recently updated. Useful when you have multiple potential parents and want new entries to go under the most active one.

### First Created

Finds the oldest entry of the parent type. Useful when you have a single landing page that never changes.

### Specific Entry

Lets you pick an exact entry as the parent. Useful when you have multiple landing pages and want all new entries to go under a specific one.

## Technical Details

- Uses `Entry::EVENT_AFTER_PROPAGATE` to ensure the correct Entry Type is detected
- Moves entries using Craft's `StructuresService::append()` for proper hierarchy management
- Only affects new entries at level 1 (root level)
- Respects manually positioned entries (won't override existing parents)
- Settings are stored in Project Config for environment sync

## Troubleshooting

### Entry not being auto-parented

1. Check that the parent Entry Type has at least one entry created
2. Verify the configuration in Settings → Plugins → Entry Level
3. Ensure you're creating a new entry (editing existing entries won't trigger auto-parenting)

### Wrong parent being selected

Try changing the Parent Selection strategy:
- Use **Specific Entry** if you need precise control
- Use **First Created** for stable, predictable behavior
- Use **Most Recently Updated** for dynamic selection

## License

MIT

## Credits

Developed by [Hypha Media](https://hyphamedia.com)

