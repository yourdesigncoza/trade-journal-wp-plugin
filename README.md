# Trade Journal WP - WordPress Plugin

A comprehensive trading journal WordPress plugin that provides complete trade tracking and performance analytics functionality. This plugin maintains compatibility with the original Trade Journal PHP application by using the same external database structure.

## Features

- **Complete Trade Tracking**: 17-field trade entry forms with validation
- **Performance Analytics**: Real-time statistics (win rate, profit factor, best/worst trades)  
- **Data Management**: Full CRUD operations with sortable, filterable tables
- **Responsive Design**: Bootstrap 5 with Phoenix theme components and dark mode support
- **Color-Coded UI**: Intuitive organization by data categories
- **Auto-Save**: Draft persistence to prevent data loss
- **External Database**: Uses separate MySQL database, not WordPress database
- **Multiple Shortcodes**: Flexible display options for different page layouts
- **Security**: Proper WordPress nonces, sanitization, and escaping throughout

## Shortcodes

### Main Shortcodes

- `[trade_journal_dashboard]` - Complete dashboard with form, stats, and trade list
- `[trade_journal_add]` - Trade entry form only
- `[trade_journal_list]` - Trade history table with sorting/filtering
- `[trade_journal_stats]` - Performance analytics display
- `[trade_journal_checklist]` - Pre-trade strategy checklist

### Shortcode Attributes

All shortcodes support customization attributes:

```php
[trade_journal_dashboard show_form="true" show_list="true" show_stats="true" class="my-custom-class"]
[trade_journal_add show_title="false" class="compact-form"]
[trade_journal_list per_page="10" show_search="true" show_filters="true"]
[trade_journal_stats layout="horizontal" show_title="false"]
[trade_journal_checklist show_title="true" class="checklist-widget"]
```

## Installation

1. **Upload Plugin Files**: Copy the `trade-journal-wp-plugin` folder to your WordPress `wp-content/plugins/` directory

2. **Activate Plugin**: Go to WordPress Admin → Plugins → Activate "Trade Journal WP"

3. **Configure Database**: Navigate to Trade Journal → Settings and configure your external MySQL database connection:
   - Database Host
   - Database Username  
   - Database Password
   - Database Name
   - Database Port (usually 3306)

4. **Test Connection**: Use the "Test Connection" button to verify database connectivity

5. **Add Shortcodes**: Create pages/posts and add the desired shortcodes

## Database Configuration

The plugin uses an **external MySQL database** (not the WordPress database) to maintain compatibility with the original Trade Journal PHP application. This allows you to:

- Share data between the WordPress plugin and standalone PHP application
- Keep trading data separate from WordPress data
- Maintain existing trade history when migrating to WordPress

### Default Configuration
- Host: `sql57.jnb1.host-h.net`
- Username: `demockdugf_2` 
- Database: `demockdugf_db2`
- Port: `3306`

Update these settings via the WordPress admin panel at **Trade Journal → Settings**.

## Database Schema

The plugin automatically creates the following table structure:

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

## Admin Interface

The plugin provides a complete admin interface:

- **All Trades**: View, edit, and delete trades with performance overview
- **Add New Trade**: Admin form for adding trades (also available via shortcode)
- **Settings**: Configure database connection, markets, sessions, and timeframes
- **Export**: CSV export functionality for data analysis

## Security Features

- **Nonce Verification**: All forms protected with WordPress nonces
- **Input Sanitization**: All user inputs properly sanitized
- **Output Escaping**: All outputs properly escaped to prevent XSS
- **Permission Checks**: Admin functions restricted to appropriate user roles
- **Prepared Statements**: All database queries use prepared statements

## Customization

### Adding Markets/Sessions

1. Go to **Trade Journal → Settings**
2. Update the "Available Markets" or "Trading Sessions" fields
3. Save settings
4. New options will appear in all forms automatically

### Styling

The plugin enqueues its own CSS file (`assets/css/frontend.css`) which can be customized. The plugin uses:

- Bootstrap 5 framework
- Phoenix theme components
- CSS custom properties for easy theming
- Dark mode support via CSS media queries

### Custom Hooks

The plugin provides WordPress hooks for customization:

```php
// Filter trade data before saving
add_filter( 'trade_journal_wp_before_save_trade', 'my_custom_trade_handler' );

// Action after trade is saved
add_action( 'trade_journal_wp_after_save_trade', 'my_post_save_handler' );
```

## JavaScript API

The frontend JavaScript provides events for custom integrations:

```javascript
// Listen for trade save events
$(document).on('tradeJournalTradesSaved', function(event, trade) {
    console.log('Trade saved:', trade);
});

// Listen for trade deleted events  
$(document).on('tradeJournalTradeDeleted', function(event, tradeId) {
    console.log('Trade deleted:', tradeId);
});
```

## Performance Features

- **Auto-save**: Form drafts saved every 10 seconds to localStorage
- **Statistics Caching**: Performance stats cached for faster loading
- **AJAX Operations**: All operations use AJAX for better user experience
- **Pagination**: Large trade lists automatically paginated
- **Lazy Loading**: Statistics updated only when visible

## Browser Compatibility

- Chrome/Edge 88+
- Firefox 85+  
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Migration from PHP Version

This plugin is 100% compatible with the original Trade Journal PHP application:

- **Same Database Schema**: No data migration needed
- **Identical Functionality**: All features from PHP version included
- **Same Color Coding**: UI matches original design patterns
- **API Compatibility**: Same endpoint responses

You can run both versions simultaneously using the same database.

## Troubleshooting

### Connection Issues
- Verify database credentials in settings
- Check if MySQL port is correct (usually 3306)
- Ensure database server allows external connections
- Test connection using the admin settings page

### Shortcode Not Working
- Verify shortcode spelling and attributes
- Check if plugin is activated
- Ensure page/post is published
- Check browser console for JavaScript errors

### Permission Errors
- Verify user has appropriate WordPress capabilities
- Check if AJAX requests include proper nonces
- Review WordPress user roles and permissions

### Performance Issues  
- Check external database connection speed
- Verify MySQL server performance
- Consider caching plugins for better performance
- Monitor trade history size (pagination helps with large datasets)

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher  
- **MySQL**: 5.7 or higher (external database)
- **Apache/Nginx**: mod_rewrite enabled for WordPress

## Support

For technical support or questions:

1. Check the troubleshooting section above
2. Verify all requirements are met
3. Test database connection via admin settings
4. Check WordPress and PHP error logs

## License

GPL v2 or later - same as WordPress core.

## Changelog

### Version 1.0.0
- Initial release
- Complete WordPress plugin conversion
- All original PHP application features included
- External database support
- Multiple shortcode options
- Comprehensive admin interface
- Export functionality
- Security enhancements