# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress plugin called "Trade Journal WP" that provides comprehensive trading journal functionality. It tracks trades with full CRUD operations, performance analytics, and external database integration. The plugin is designed to be compatible with an existing PHP trading journal application by using the same external MySQL database structure.

## Architecture & Structure

### Core Components

- **Main Plugin File**: `trade-journal-wp.php` - Contains the primary plugin class, initialization, hooks, and AJAX handlers
- **Database Layer**: `includes/class-trade-journal-database.php` - Handles all external MySQL database operations (not WordPress database)
- **Admin Interface**: `includes/class-trade-journal-admin.php` - Manages WordPress admin settings and configuration
- **Frontend Display**: `includes/class-trade-journal-shortcodes.php` - Handles all shortcode implementations and frontend rendering

### Database Architecture

**CRITICAL**: This plugin uses an **external MySQL database**, NOT the WordPress database. The database configuration is stored in WordPress options but connections are made to a separate MySQL server. The table structure is:

```sql
CREATE TABLE trading_journal_entries (
    id varchar(255) NOT NULL,
    market enum('XAUUSD','EU','GU','UJ','US30','NAS100') NOT NULL,
    session enum('LO','NY','AS') NOT NULL,
    date date NOT NULL,
    time time DEFAULT NULL,
    direction enum('LONG','SHORT') NOT NULL,
    entry_price decimal(10,5) DEFAULT NULL,
    exit_price decimal(10,5) DEFAULT NULL,
    outcome enum('W','L','BE','C') DEFAULT NULL,
    pl_percent decimal(10,2) DEFAULT NULL,
    rr decimal(10,2) DEFAULT NULL,
    tf json DEFAULT NULL,
    chart_htf text DEFAULT NULL,
    chart_ltf text DEFAULT NULL,
    comments text DEFAULT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_date (date),
    KEY idx_market (market),
    KEY idx_outcome (outcome),
    KEY idx_created_at (created_at)
);
```

### Frontend Architecture

- **Shortcodes**: 5 main shortcodes for different display modes
  - `[trade_journal_dashboard]` - Complete interface
  - `[trade_journal_add]` - Add trade form only
  - `[trade_journal_list]` - Trade history table with Phoenix styling
  - `[trade_journal_stats]` - Performance analytics
  - `[trade_journal_checklist]` - Pre-trade checklist

- **AJAX Operations**: All database operations use WordPress AJAX with nonce verification
- **JavaScript**: jQuery-based frontend with Bootstrap tooltip initialization, auto-save, real-time updates, and data validation
- **Styling**: Phoenix framework classes with Bootstrap 5 + FontAwesome icons

## Phoenix Framework Styling System

The frontend uses Phoenix framework CSS classes for professional appearance:

### Table Styling
- **Base Classes**: `table table-sm fs-9 mb-0 overflow-hidden`
- **Header Classes**: `sort ps-3 pe-1 align-middle white-space-nowrap`
- **Row Classes**: `py-2 align-middle` with alternating `bg-light` for zebra striping
- **Badge Classes**: `badge-phoenix badge-phoenix-primary/success/danger/warning/info`

### Button Styling
- **Action Buttons**: `btn btn-subtle-primary/danger/info` for compact appearance
- **Button Groups**: `btn-group btn-group-sm` with custom `ydcoza-btn-group-tiny` class
- **Icon Integration**: FontAwesome icons with consistent sizing and colors

### Column Icons
Each table column header includes a FontAwesome icon:
- Date: `fas fa-calendar-alt text-primary`
- Time: `fas fa-clock text-info`
- Market: `fas fa-coins text-primary`
- Session: `fas fa-globe text-info`
- Direction: `fas fa-arrows-alt-v text-warning`
- Entry/Exit Price: `fas fa-sign-in-alt/sign-out-alt text-success/danger`
- Outcome: `fas fa-trophy text-warning`
- P/L %: `fas fa-percentage text-purple`
- RR: `fas fa-balance-scale text-purple`
- TF: `fas fa-chart-line text-info`
- Chart Links: `fas fa-chart-bar/chart-area text-warning`

## Configuration & Settings

### Database Configuration
Database settings are stored in `trade_journal_wp_db_config` WordPress option:
- Default host: `sql57.jnb1.host-h.net`
- Connection test functionality available in admin
- Auto-creates table structure on connection

### Plugin Settings
Settings stored in `trade_journal_wp_settings` WordPress option:
- Configurable markets, sessions, timeframes
- All form options are admin-customizable
- Settings affect both admin and frontend forms

## Key Implementation Details

### Security Implementation
- All forms use WordPress nonces (`trade_journal_wp_nonce`)
- Input sanitization with WordPress functions
- Output escaping for XSS prevention
- Capability checks for admin functions
- Prepared statements for all database queries

### Asset Management
- CSS/JS only loaded on pages with shortcodes (conditional loading)
- External CDN dependencies: Bootstrap 5, Font Awesome
- Localized JavaScript for AJAX endpoints and translations
- Bootstrap tooltips initialized for comments display

### Data Flow
1. Frontend forms → AJAX → WordPress handlers
2. WordPress handlers → Database class → External MySQL
3. Statistics calculated on database level
4. Real-time updates via jQuery events
5. Tooltip initialization for interactive elements

### Price Formatting
The `format_price()` method truncates prices to 3 decimal places without rounding:
```php
// Truncate to specified decimals without rounding
$multiplier = pow( 10, $decimals );
$truncated = floor( (float) $value * $multiplier ) / $multiplier;
```

## Development Commands

This is a standard WordPress plugin with no build process:
- No package.json or build tools
- Direct PHP/JavaScript development
- WordPress standard file structure
- Standard WordPress hooks and filters

## Testing Approach

To test functionality:
1. Activate plugin in WordPress admin
2. Configure database connection in Trade Journal → Settings
3. Test connection using "Test Connection" button
4. Create pages with shortcodes to test frontend
5. Verify AJAX operations work correctly
6. Check admin interface for trade management
7. Test Bootstrap tooltips on comments column
8. Verify Phoenix styling renders correctly

## Important Notes

- **External Database**: Always remember this uses external MySQL, not WordPress DB
- **Compatibility**: Designed to work alongside existing PHP trade journal application
- **Phoenix Framework**: Frontend uses Phoenix theme classes - leverage existing CSS before adding custom styles
- **Theme CSS Location**: Additional custom styles go in `/opt/lampp/htdocs/wecoza/wp-content/themes/wecoza_3_child_theme/includes/css/ydcoza-theme.css`
- **Bootstrap Dependency**: Frontend assumes Bootstrap 5 is available
- **PHP Requirements**: Requires PHP 7.4+, WordPress 5.0+
- **No Rounding**: Price formatting truncates values without rounding up

## Common Operations

### Adding New Markets/Sessions
1. Update settings in WordPress admin (Trade Journal → Settings)
2. Database ENUMs may need manual updating for new options
3. Frontend forms automatically reflect admin settings

### Debugging Database Issues
1. Check WordPress debug.log for database connection errors
2. Use "Test Connection" in admin settings
3. Verify external database server is accessible
4. Check database credentials and permissions

### Customizing Display
- All shortcodes support `class` attribute for custom styling
- Views are in `views/frontend/` directory
- Check existing Phoenix theme classes before adding custom CSS
- Bootstrap tooltips require JavaScript initialization in `assets/js/frontend.js`
- Table columns use consistent Phoenix styling patterns

### UI Styling Guidelines
- Use `badge-phoenix` variants for status indicators
- Apply `btn-subtle-*` classes for action buttons
- Maintain `fs-9` and `py-2` classes for compact table rows
- Include FontAwesome icons in column headers for visual consistency
- Group related actions using `btn-group btn-group-sm`
- Comments display as tooltip-enabled buttons to save space