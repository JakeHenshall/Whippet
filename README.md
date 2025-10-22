# Whippet - WordPress Performance Optimization Plugin

Modern WordPress plugin for disabling scripts and styles conditionally to improve performance.

## Features

- 🚀 Disable unnecessary scripts and styles on a per-page basis
- 📊 Local Google Analytics for improved privacy and performance
- 📥 Import/Export settings for easy backup and migration
- 🎨 Modern, tabbed admin interface with Alpine.js
- 🛠️ Built with modern PHP OOP practices
- 📱 Responsive admin design

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Node.js & npm (for development)

## Installation

1. Upload the `whippet` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Tools → Whippet to configure settings

## Development Setup

### Install Dependencies

```bash
npm install
composer install
```

### Development Workflow

```bash
# Watch files during development
npm run watch

# Build for production
npm run prod

# Run linters
npm run lint
```

### Compile Assets

The plugin uses Laravel Mix for asset compilation:

```bash
# Development build
npm run dev

# Production build (minified)
npm run prod

# Watch for changes
npm run watch
```

## Code Structure

### Modern OOP Architecture

```
inc/
├── classes/              # Modern PHP classes
│   ├── Plugin.php       # Main plugin class
│   ├── Admin.php        # Admin functionality
│   ├── Assets.php       # Asset management
│   └── Helper.php       # Utility functions
├── admin.php            # Admin page template
└── [legacy files]       # Backward compatibility
```

### Key Features

- **Namespaces**: All classes use `Whippet\` namespace
- **Autoloading**: PSR-4 compatible autoloader
- **Singleton Pattern**: Single instance management
- **Separation of Concerns**: Each class has specific responsibility

## Admin Interface

The plugin features a modern, tabbed interface:

### Dashboard Tab

Main settings for script and style management

### Analytics Tab

Local Google Analytics configuration

### Import/Export Tab

Backup and restore plugin settings

### Documentation Tab

Quick guides and helpful tips

## Technologies Used

### Frontend

- **Alpine.js**: Lightweight JavaScript framework for tabs
- **Tailwind CSS**: Utility-first CSS (custom utilities only)
- **SCSS**: CSS preprocessor

### Backend

- **PHP 7.4+**: Modern PHP with namespaces and type hints
- **WordPress APIs**: Settings API, Options API, Plugin API

### Build Tools

- **Laravel Mix**: Asset compilation
- **Webpack**: Module bundler
- **PostCSS**: CSS processing

## File Organization

```
whippet/
├── assets/              # Plugin assets (icons, screenshots)
├── dist/               # Compiled CSS/JS
├── inc/                # PHP includes
│   ├── classes/        # Modern OOP classes
│   └── [legacy]        # Legacy functional code
├── languages/          # Translation files
├── resources/          # Source files
│   ├── css/           # CSS files
│   ├── js/            # JavaScript files
│   └── scss/          # SCSS source files
├── whippet.php        # Main plugin file
├── package.json       # npm dependencies
└── webpack.mix.js     # Laravel Mix config
```

## Hooks & Filters

### Actions

```php
// After plugin initialization
do_action('whippet_init');

// Before admin page render
do_action('whippet_before_admin_page');
```

### Filters

```php
// Modify plugin options
apply_filters('whippet_options', $options);
```

## Helper Functions

The `Helper` class provides utility methods:

```php
use Whippet\Helper;

// Get plugin option
$value = Helper::get_option('setting_name', 'default');

// Update plugin option
Helper::update_option('setting_name', 'value');

// Check user capability
if (Helper::user_can_manage()) {
    // User can manage plugin
}

// Log debug message
Helper::log('Debug message', 'info');

// Format file size
$size = Helper::format_bytes(1024); // "1 KB"
```

## Extending the Plugin

### Adding a New Tab

1. Edit `inc/admin.php`
2. Add tab button in navigation
3. Add tab content div with `x-show` directive
4. Update Alpine.js data if needed

### Adding a New Class

1. Create file in `inc/classes/`
2. Use `Whippet` namespace
3. Follow WordPress coding standards

Example:

```php
<?php
namespace Whippet;

class MyFeature {
    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        // Your code here
    }
}
```

## Performance Tips

1. **Disable Unused Scripts**: Review and disable scripts not needed on specific pages
2. **Use Local Analytics**: Host Google Analytics locally for faster loading
3. **Export Settings**: Regularly backup your configuration
4. **Test Changes**: Always test on staging before production

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run linters: `npm run lint`
5. Submit a pull request

## Coding Standards

- **PHP**: WordPress Coding Standards
- **JavaScript**: ESLint
- **CSS**: Stylelint
- **Documentation**: PHPDoc blocks required

## License

GPL2 - See [license.txt](license.txt)

## Credits

**Author**: Jake Henshall  
**Website**: https://hashbangcode.com/

## Changelog

See [changelog.md](changelog.md) for version history.

## Support

For issues and questions:

- GitHub Issues: [Create an issue](#)
- Documentation: [Read the docs](#)
- Website: https://hashbangcode.com/

---

Made with ❤️ for WordPress
