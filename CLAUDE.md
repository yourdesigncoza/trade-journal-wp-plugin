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
trading_journal_entries (
  id varchar(255) PRIMARY KEY,
  market enum('XAUUSD','EU','GU','UJ','US30','NAS100'),
  session enum('LO','NY','AS'),
  date, time, direction enum('LONG','SHORT'),
  entry_price, exit_price decimal(10,5),
  outcome enum('W','L','BE','C'),
  pl_percent, rr decimal(10,2),
  tf json, chart_htf text, chart_ltf text,
  comments text, created_at, updated_at timestamps
)
```

### Frontend Architecture

- **Shortcodes**: 5 main shortcodes for different display modes
  - `[trade_journal_dashboard]` - Complete interface
  - `[trade_journal_add]` - Add trade form only
  - `[trade_journal_list]` - Trade history table
  - `[trade_journal_stats]` - Performance analytics
  - `[trade_journal_checklist]` - Pre-trade checklist

- **AJAX Operations**: All database operations use WordPress AJAX with nonce verification
- **JavaScript**: jQuery-based frontend with auto-save, real-time updates, and data validation
- **Styling**: Bootstrap 5 + custom CSS with dark mode support

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

### Data Flow
1. Frontend forms → AJAX → WordPress handlers
2. WordPress handlers → Database class → External MySQL
3. Statistics calculated on database level
4. Real-time updates via jQuery events

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

## Important Notes

- **External Database**: Always remember this uses external MySQL, not WordPress DB
- **Compatibility**: Designed to work alongside existing PHP trade journal application
- **Bootstrap Dependency**: Frontend assumes Bootstrap 5 is available
- **PHP Requirements**: Requires PHP 7.4+, WordPress 5.0+
- **Database Password**: Hard-coded default credentials in database class (should be configured via admin)

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
- CSS customizations go in `assets/css/frontend.css`