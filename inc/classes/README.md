# Whippet Plugin Classes

This directory contains the modern, object-oriented structure for the Whippet plugin.

## Architecture Overview

The plugin follows a modern OOP approach with:

- **Namespaces** for proper code organization
- **PSR-4 autoloading** for automatic class loading
- **Singleton pattern** for the main Plugin class
- **Separation of concerns** with dedicated classes for different functionality

## Class Structure

### Plugin.php

The main plugin class that handles initialization and bootstrapping.

**Responsibilities:**

- Define plugin constants
- Initialize hooks
- Load dependencies
- Handle plugin activation
- Manage singleton instance

**Usage:**

```php
$plugin = Whippet\Plugin::instance();
```

### Admin.php

Handles all admin-related functionality.

**Responsibilities:**

- Register admin menu pages
- Render admin templates
- Add settings links to plugins page
- Initialize WP_Filesystem

**Key Methods:**

- `register_menu()` - Registers the admin menu
- `render_admin_page()` - Renders the main admin interface
- `add_settings_link()` - Adds settings link to plugins page

### Assets.php

Manages enqueuing of styles and scripts.

**Responsibilities:**

- Enqueue admin CSS files
- Enqueue admin JavaScript files
- Localize scripts with data
- Conditional loading based on admin page

**Key Methods:**

- `enqueue_admin_assets()` - Main method called on `admin_enqueue_scripts`
- `enqueue_styles()` - Enqueues CSS files
- `enqueue_scripts()` - Enqueues JavaScript files

### Helper.php

Utility class with static helper methods.

**Responsibilities:**

- Sanitize data
- Manage plugin options
- Check user capabilities
- Logging (when WP_DEBUG is enabled)
- Format data

**Useful Methods:**

```php
// Get plugin option
Helper::get_option('setting_name', 'default_value');

// Update plugin option
Helper::update_option('setting_name', 'value');

// Check if user can manage plugin
if (Helper::user_can_manage()) {
    // Do admin stuff
}

// Log message (only in debug mode)
Helper::log('Something happened', 'info');

// Format file size
$size = Helper::format_bytes(1024); // "1 KB"
```

## Autoloading

The plugin uses SPL autoloading with the `Whippet\` namespace. Classes are automatically loaded when needed.

**Autoloader location:** `whippet.php`

```php
function whippet_autoloader($class) {
    $prefix = 'Whippet\\';
    $base_dir = plugin_dir_path(__FILE__) . 'inc/classes/';
    // ... autoloading logic
}
spl_autoload_register('whippet_autoloader');
```

## Adding New Classes

To add a new class:

1. Create the file in `/inc/classes/`
2. Use the `Whippet` namespace
3. Follow WordPress coding standards
4. Document the class with proper PHPDoc blocks

**Example:**

```php
<?php
namespace Whippet;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * My New Class
 */
class MyNewClass {

    public function __construct() {
        // Initialization
    }

    public function my_method() {
        // Implementation
    }
}
```

## Modern Features

- **Namespacing**: All classes use the `Whippet` namespace
- **Type Hinting**: Modern PHP type hints where applicable
- **Constants**: Plugin constants (VERSION, PATH, URL) for easy access
- **Singleton Pattern**: Single instance of main Plugin class
- **Dependency Injection**: Classes receive dependencies through constructor
- **Static Utilities**: Helper class with static methods for common tasks
- **Hooks Organization**: All hooks registered in dedicated methods
- **Separation of Concerns**: Each class has a single, clear responsibility

## Benefits of This Structure

1. **Maintainability**: Easy to find and update specific functionality
2. **Testability**: Classes can be unit tested in isolation
3. **Extensibility**: Easy to add new features without modifying existing code
4. **Performance**: Autoloading means classes are only loaded when needed
5. **Standards**: Follows modern PHP and WordPress best practices
6. **Readability**: Clear structure makes code easy to understand

## Migration Notes

The old functional code still exists in the `/inc/` directory but is being phased out in favor of this class-based structure. Legacy files included for backward compatibility:

- `settings.php`
- `functions.php`
- `script-manager.php`
- `import-export.php`
- `tutorials.php`
- `save-ga-local.php`

These will be gradually refactored into proper classes.
