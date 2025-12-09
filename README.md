<p align="center"><img src="https://raw.githubusercontent.com/martinsmith/craft-entry-level/main/entry-level-icon.svg" width="120" height="120" alt="Entry Level icon"></p>

# Entry Level for Craft CMS 5


**Allows for the automatic placing of new entries to a desired Structure level.**

This plugin will nest a new entry based on the Entry Type.  No more manually repositioning entries after save!

Mostly for those using the [Entry Type Templates pattern](https://craftcms.com/knowledge-base/entry-type-templates) to create landing pages with children (e.g., blogs with articles).

![Entry Level Settings Screenshot](https://raw.githubusercontent.com/martinsmith/craft-entry-level/main/resources/screenshot-settings.png)

![Entry Level Settings Screenshot](https://raw.githubusercontent.com/martinsmith/craft-entry-level/main/resources/screenshot-screen1.png)

![Entry Level Settings Screenshot](https://raw.githubusercontent.com/martinsmith/craft-entry-level/main/resources/screenshot-screen2.png)


## Requirements

- Craft CMS 5.3.0+
- PHP 8.2+

## Installation

Install via Composer:

```bash
composer require "martinsmith/craft-entry-level" -w && php craft plugin/install entry-level
```

```bash
ddev composer require "martinsmith/craft-entry-level" -w && ddev craft plugin/install entry-level
```

## Configuration

1. Go to **Settings → Plugins → Entry Level → Settings**
2. For each Structure section, configure parent-child relationships between Entry Types

## How It Works

When you create a new entry of a configured "child" Entry Type, the plugin automatically moves that entry under a parent entry of the configured "parent" Entry Type.

### Example: News Section

```
Entry Types:
├── News Landing (root level)
└──- News Article (parent: News Landing)
```

With this configuration:
- Creating a **News Landing** → stays at root level (no parent configured)
- Creating a **News Article** → automatically placed under **News Landing** (Level 2)

### Multi-Level Hierarchies

The plugin supports unlimited nesting depth. Chain Entry Types to create deep structures:

| Entry Type | Parent Type | Result |
|------------|-------------|--------|
| Landing Page | (none) | Root level |
| Article | (Landing Page) | Level 2 |


## Troubleshooting

### Entry not being auto-parented

1. Check that the parent Entry Type has at least one entry created
2. Verify the configuration in Settings → Plugins → Entry Level
3. Ensure you're creating a new entry (editing existing entries won't trigger auto-parenting)

## Support

- 📖 [Documentation](https://github.com/martinsmith/craft-entry-level#readme)
- 🐛 [Report Issues](https://github.com/martinsmith/craft-entry-level/issues)

## License

This plugin is licensed under the [MIT License](LICENSE).

## Credits

Developed by [Martin Smith](https://github.com/martinsmith).

---

<p align="center">Made with ❤️ for the Craft CMS community</p>
