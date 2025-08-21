# Trade Journal WP - Login Shortcode Documentation

## Overview
The Trade Journal WP plugin now includes a custom WordPress login page using the Phoenix framework's Card style design.

## Shortcode Usage

### Basic Usage
```
[trade_journal_login]
```

### With Attributes
```
[trade_journal_login redirect_to="/dashboard" class="my-custom-class" show_title="false"]
```

## Shortcode Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `redirect_to` | string | `home_url()` | URL to redirect after successful login |
| `class` | string | `""` | Additional CSS classes for the container |
| `show_title` | boolean | `true` | Show/hide the site title and description |

## Features

- ✅ **Phoenix Card Style Design** - Professional authentication interface with authentic Phoenix background images
- ✅ **WordPress Integration** - Uses WordPress's built-in authentication system
- ✅ **Security** - Proper nonces, sanitization, and escaping
- ✅ **AJAX Login** - Smooth user experience with no page reload
- ✅ **Responsive Design** - Works on all devices
- ✅ **Dark Mode Support** - Adapts to user's theme preference
- ✅ **Remember Me** - Standard WordPress remember functionality
- ✅ **Forgot Password** - Links to WordPress password reset
- ✅ **Registration Link** - Shows if user registration is enabled
- ✅ **Error Handling** - User-friendly error messages with Phoenix Alert Subtle styling

## Usage Examples

### 1. Basic Login Page
Create a page with the shortcode:
```
[trade_journal_login]
```

### 2. Login with Custom Redirect
Redirect users to a specific page after login:
```
[trade_journal_login redirect_to="/trading-dashboard"]
```

### 3. Minimal Login Form
Hide the title and branding:
```
[trade_journal_login show_title="false" class="minimal-login"]
```

### 4. Login Modal/Widget
Use in a widget or modal with custom styling:
```
[trade_journal_login class="compact-form" show_title="false"]
```

## Styling

The login form uses authentic Phoenix framework classes loaded by the child theme. Minimal custom styles are in:
- `assets/css/login.css` (only essential custom functionality)

### Phoenix Framework Classes Used
- `.auth-card` - Main login card (Phoenix)
- `.auth-title-box` - Left side panel with background images (Phoenix)
- `.auth-form-box` - Right side form area (Phoenix)  
- `.form-icon-container`, `.form-icon-input`, `.form-icon` - Form with icons (Phoenix)
- `.bg-body-highlight`, `.bg-body-tertiary` - Background utilities (Phoenix)
- `.border-translucent` - Translucent borders (Phoenix)
- `.btn-subtle-secondary` - Subtle password toggle button (Phoenix)
- `.btn-link` - Subtle link buttons for secondary actions (Phoenix)
- `.bg-holder` - Background image containers (Phoenix)

### Background Images
The login form uses authentic Phoenix background images:
- `assets/img/bg/37.png` - Main container background overlay
- `assets/img/bg/38.png` - Left panel background pattern  
- `assets/img/spot-illustrations/auth.png` - Authentication illustration (light mode)
- `assets/img/spot-illustrations/auth-dark.png` - Authentication illustration (dark mode)

### Alert Subtle Styling
The login form uses Phoenix's "Alert Subtle" styling for notifications:
- Success messages: `alert-subtle-success` (green, subtle background)
- Error messages: `alert-subtle-danger` (red, subtle background)
- Info messages: `alert-subtle-info` (blue, subtle background)
- All alerts are dismissible with close buttons

## Security Features

1. **WordPress Nonces** - CSRF protection
2. **Input Sanitization** - All inputs properly sanitized
3. **Output Escaping** - XSS prevention
4. **Rate Limiting** - Uses WordPress's built-in login protection
5. **Secure Password Handling** - No plain text storage

## Browser Support

- Chrome/Edge 88+
- Firefox 85+
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Troubleshooting

### Common Issues

1. **Login form not appearing**
   - Check if user is already logged in
   - Verify shortcode spelling: `[trade_journal_login]`

2. **Styles not loading**
   - Ensure Phoenix framework is loaded by child theme
   - Check browser console for CSS errors

3. **AJAX errors**
   - Check browser console for JavaScript errors
   - Verify WordPress AJAX is working

4. **Redirect not working**
   - Check if `redirect_to` URL is valid
   - Ensure user has permission to access redirect page

### Debug Mode

For debugging, check:
- WordPress debug.log
- Browser console (F12)
- Network tab for AJAX requests

## Integration with Other Plugins

The login shortcode works with:
- Most caching plugins
- Security plugins (may need whitelist for AJAX)
- Translation plugins
- Page builders (Elementor, Gutenberg, etc.)

## Performance

- CSS and JS only load on pages with the shortcode
- AJAX requests are lightweight
- Compatible with caching plugins
- Optimized for mobile devices