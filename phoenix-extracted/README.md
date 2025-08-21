# Phoenix Theme - Complete Index for LLM Integration

⚠️ **CRITICAL NOTICE**: All styles and scripts are provided by the parent Phoenix theme. **NEVER include CSS or JS dependencies directly in components** - they are already loaded in the parent theme.

## Overview
This directory contains 141 extracted HTML templates from the Phoenix Admin Dashboard Theme:
- **31 Modules** - Standalone UI components 
- **110 Pages** - Complete page templates
- **Phoenix v1.23.0** - Bootstrap 5.3+ implementation
- **100% Coverage** - All theme components extracted

---

## 🚀 Common UI Patterns (Quick Lookup)

**User Management**
- User Profile Section → `component-avatar.html` + `component-card.html` + `component-list-group.html`
- User Settings Panel → `component-card.html` + `form-basic-controls.html` + `component-button.html`

**Product & Commerce**
- Product Card → `component-card.html` + `component-badge.html` + `component-button.html`
- Product Listings → `component-card.html` + `utility-grid.html` + `component-pagination.html`
- Shopping Cart → `table-basic.html` + `component-button.html` + `component-badge.html`

**Data Display**
- Dashboard Stats → `component-card.html` + `chart-line.html` + `component-badge.html`
- Analytics Section → `chart-bar-charts.html` + `component-card.html` + `utility-grid.html`
- Data Tables → `table-advanced.html` + `component-pagination.html` + `form-select.html`

**Navigation & Layout**
- Main Navigation → `component-breadcrumb.html` + `component-dropdown.html`
- Sidebar Menu → `component-collapse.html` + `component-list-group.html`
- Tab Interface → `component-modal.html` + `component-button.html`

**Forms & Input**
- Contact Forms → `form-basic-controls.html` + `form-validation.html` + `component-button.html`
- Search Interface → `form-input-group.html` + `component-button.html` + `component-dropdown.html`
- Settings Forms → `form-checks.html` + `form-select.html` + `form-floating-labels.html`

**Notifications & Feedback**
- Alert Systems → `component-alerts.html` + `component-toast.html`
- Status Indicators → `component-badge.html` + `component-spinners.html` + `component-progress-bar.html`

---

## 🎨 Phoenix-Specific CSS Classes always prefered over Bootstrap classes

### Button Variants
- `btn-phoenix-primary`, `btn-phoenix-secondary`, `btn-phoenix-success`, `btn-phoenix-danger`, `btn-phoenix-warning`, `btn-phoenix-info`
- `btn-subtle-primary`, `btn-subtle-secondary`, `btn-subtle-success`, `btn-subtle-danger`, `btn-subtle-warning`, `btn-subtle-info`
- `btn-outline-primary`, `btn-outline-secondary`, `btn-outline-success`, `btn-outline-danger`, `btn-outline-warning`, `btn-outline-info`

### Text Colors
- `text-body-emphasis` - High contrast primary text
- `text-body-secondary` - Secondary text color
- `text-body-tertiary` - Tertiary text color  
- `text-body-quaternary` - Muted text color
- `text-body-highlight` - Highlighted text

### Background Colors
- `bg-body-emphasis` - Emphasized background
- `bg-body-secondary` - Secondary background
- `bg-body-tertiary` - Tertiary background
- `bg-subtle-primary`, `bg-subtle-secondary`, `bg-subtle-success`, `bg-subtle-danger`, `bg-subtle-warning`, `bg-subtle-info`

### Alert Variants
- `alert-subtle-primary`, `alert-subtle-secondary`, `alert-subtle-success`, `alert-subtle-danger`, `alert-subtle-warning`, `alert-subtle-info`
- `alert-outline-primary`, `alert-outline-secondary`, `alert-outline-success`, `alert-outline-danger`, `alert-outline-warning`, `alert-outline-info`

### Card Variants
- `card-body-emphasis` - Emphasized card background
- `border-translucent` - Translucent borders
- `border-subtle` - Subtle border styling

---

## 📦 Module Index (31 Components)

### Components (24 modules)

**Accordion Component**
- Path: `modules/components/component-accordion.html`
- Purpose: Collapsible content sections with expand/collapse functionality
- Keywords: accordion, collapse, expand, content, sections, faq
- Common Combinations: Used with cards, forms, FAQ layouts

**Alert Component** 
- Path: `modules/components/component-alerts.html`
- Purpose: Contextual feedback messages and notifications
- Keywords: alert, notification, message, warning, error, success
- Common Combinations: Used with forms, modals, page headers

**Avatar Component**
- Path: `modules/components/component-avatar.html` 
- Purpose: User profile images, placeholders, and status indicators
- Keywords: avatar, profile, user, image, photo, status
- Common Combinations: Used with cards, lists, headers, navigation

**Badge Component**
- Path: `modules/components/component-badge.html`
- Purpose: Small status indicators, labels, and counters
- Keywords: badge, label, counter, status, indicator, tag
- Common Combinations: Used with buttons, cards, lists, navigation

**Breadcrumb Component**
- Path: `modules/components/component-breadcrumb.html`
- Purpose: Navigation breadcrumbs showing page hierarchy
- Keywords: breadcrumb, navigation, path, hierarchy, location
- Common Combinations: Used with page headers, navigation systems

**Button Component**
- Path: `modules/components/component-button.html`
- Purpose: All button variations, states, and interactive elements
- Keywords: button, action, click, submit, cta, interactive
- Common Combinations: Used with forms, cards, modals, toolbars

**Calendar Component**
- Path: `modules/components/component-calendar.html`
- Purpose: Date picker and calendar widget interfaces
- Keywords: calendar, date, picker, schedule, event, time
- Common Combinations: Used with forms, modals, dashboards

**Card Component**
- Path: `modules/components/component-card.html`
- Purpose: Content containers with headers, bodies, and footers
- Keywords: card, container, content, panel, section, wrapper
- Common Combinations: Base for most layouts, used with all components

**Chat Widget Component**
- Path: `modules/components/component-chat-widget.html`
- Purpose: Chat interface elements and messaging components
- Keywords: chat, message, conversation, widget, communication
- Common Combinations: Used with avatars, forms, modals

**Collapse Component**
- Path: `modules/components/component-collapse.html`
- Purpose: Collapsible content panels and hide/show functionality
- Keywords: collapse, toggle, show, hide, expand, panel
- Common Combinations: Used with navigation, accordions, sidebars

**Dropdown Component**
- Path: `modules/components/component-dropdown.html`
- Purpose: Dropdown menus, selects, and contextual actions
- Keywords: dropdown, menu, select, options, contextual, actions
- Common Combinations: Used with buttons, navigation, forms

**List Group Component**
- Path: `modules/components/component-list-group.html`
- Purpose: Grouped list items with various styling options
- Keywords: list, group, items, menu, navigation, content
- Common Combinations: Used with cards, navigation, content areas

**Modal Component**
- Path: `modules/components/component-modal.html`
- Purpose: Modal dialogs, popups, and overlay interfaces
- Keywords: modal, dialog, popup, overlay, window, lightbox
- Common Combinations: Used with buttons, forms, confirmations

**Offcanvas Component**
- Path: `modules/components/component-offcanvas.html`
- Purpose: Slide-out panels and off-screen content
- Keywords: offcanvas, sidebar, panel, slide, drawer, menu
- Common Combinations: Used with navigation, settings, filters

**Pagination Component**
- Path: `modules/components/component-pagination.html`
- Purpose: Page navigation and content pagination controls
- Keywords: pagination, pages, navigation, next, previous, numbers
- Common Combinations: Used with tables, lists, search results

**Placeholder Component**
- Path: `modules/components/component-placeholder.html`
- Purpose: Loading placeholders and skeleton screens
- Keywords: placeholder, loading, skeleton, shimmer, waiting
- Common Combinations: Used while loading content, forms, cards

**Popover Component**
- Path: `modules/components/component-popovers.html`
- Purpose: Contextual popover tooltips and information bubbles
- Keywords: popover, tooltip, info, bubble, contextual, help
- Common Combinations: Used with buttons, forms, help systems

**Progress Bar Component**
- Path: `modules/components/component-progress-bar.html`
- Purpose: Progress indicators and loading bars
- Keywords: progress, bar, loading, completion, status, percent
- Common Combinations: Used with forms, uploads, processes

**Scrollspy Component**
- Path: `modules/components/component-scrollspy.html`
- Purpose: Navigation that updates based on scroll position
- Keywords: scrollspy, navigation, scroll, position, highlight
- Common Combinations: Used with navigation, documentation, long content

**Spinner Component**
- Path: `modules/components/component-spinners.html`
- Purpose: Loading spinners and activity indicators
- Keywords: spinner, loading, activity, wait, processing
- Common Combinations: Used with buttons, forms, content loading

**Toast Component**
- Path: `modules/components/component-toast.html`
- Purpose: Toast notifications and temporary messages
- Keywords: toast, notification, message, temporary, alert
- Common Combinations: Used for feedback, confirmations, alerts

**Tooltip Component**
- Path: `modules/components/component-tooltips.html`
- Purpose: Tooltip help text and hover information
- Keywords: tooltip, help, hover, info, hint, guidance
- Common Combinations: Used with buttons, forms, icons

**Carousel Component**
- Path: `modules/components/component-carousel-bootstrap.html`
- Purpose: Image carousels and content sliders
- Keywords: carousel, slider, gallery, images, slideshow
- Common Combinations: Used with images, testimonials, featured content

**Gantt Chart Component**
- Path: `modules/components/component-dhtmlx-gantt.html`
- Purpose: Project timeline and Gantt chart visualization
- Keywords: gantt, timeline, project, schedule, chart, tasks
- Common Combinations: Used with project management, calendars

### Forms (8 modules)

**Basic Controls**
- Path: `modules/forms/form-basic-controls.html`
- Purpose: Input fields, textareas, and basic form elements
- Keywords: input, form, text, email, password, textarea
- Common Combinations: Used with validation, buttons, cards

**Form Checks**
- Path: `modules/forms/form-checks.html`
- Purpose: Checkboxes, radio buttons, and switches
- Keywords: checkbox, radio, switch, toggle, selection
- Common Combinations: Used with forms, settings, filters

**Floating Labels**
- Path: `modules/forms/form-floating-labels.html`
- Purpose: Modern floating label input styling
- Keywords: floating, labels, modern, input, animation
- Common Combinations: Used with forms, modals, clean designs

**Input Groups**
- Path: `modules/forms/form-input-group.html`
- Purpose: Combined input elements with addons and buttons
- Keywords: input, group, addon, button, combined, search
- Common Combinations: Used with search, forms, toolbars

**Form Layout**
- Path: `modules/forms/form-layout.html`
- Purpose: Form layout patterns and grid systems
- Keywords: layout, grid, columns, arrangement, structure
- Common Combinations: Used with all form components

**Range Controls**
- Path: `modules/forms/form-range.html`
- Purpose: Range sliders and numeric input controls
- Keywords: range, slider, numeric, control, input
- Common Combinations: Used with settings, filters, forms

**Select Controls**
- Path: `modules/forms/form-select.html`
- Purpose: Select dropdowns and option lists
- Keywords: select, dropdown, options, choice, list
- Common Combinations: Used with forms, filters, settings

**Form Validation**
- Path: `modules/forms/form-validation.html`
- Purpose: Form validation states and error messaging
- Keywords: validation, error, success, feedback, required
- Common Combinations: Used with all form components

### Tables (2 modules)

**Basic Tables**
- Path: `modules/tables/table-basic.html`
- Purpose: Standard table layouts and styling options
- Keywords: table, data, rows, columns, basic, list
- Common Combinations: Used with pagination, search, buttons

**Advanced Tables**
- Path: `modules/tables/table-advanced.html`
- Purpose: Feature-rich tables with sorting and interactions
- Keywords: table, advanced, sorting, filtering, interactive
- Common Combinations: Used with pagination, search, modals

### Charts (11 modules)

**Bar Charts**
- Path: `modules/charts/chart-bar-charts.html`
- Purpose: Vertical and horizontal bar chart visualizations
- Keywords: bar, chart, data, visualization, analytics, graph
- Common Combinations: Used with cards, dashboards, reports

**Line Charts**
- Path: `modules/charts/chart-line-charts.html`
- Purpose: Line graph visualizations for trends and time series
- Keywords: line, chart, trend, time, series, graph
- Common Combinations: Used with cards, dashboards, analytics

**Pie Charts**
- Path: `modules/charts/chart-pie-charts.html`
- Purpose: Pie and donut chart visualizations
- Keywords: pie, donut, chart, percentage, distribution, parts
- Common Combinations: Used with dashboards, reports, statistics

**Candlestick Charts**
- Path: `modules/charts/chart-candlestick-charts.html`
- Purpose: Financial candlestick chart visualizations
- Keywords: candlestick, financial, stock, trading, ohlc
- Common Combinations: Used with stock dashboards, financial data

**Gauge Charts**
- Path: `modules/charts/chart-gauge-chart.html`
- Purpose: Gauge and meter chart visualizations
- Keywords: gauge, meter, progress, circular, indicator
- Common Combinations: Used with dashboards, KPI displays

**Geo Maps**
- Path: `modules/charts/chart-geo-map.html`
- Purpose: Geographic map visualizations and location data
- Keywords: map, geographic, location, geo, region, world
- Common Combinations: Used with dashboards, analytics, location data

**Heatmap Charts**
- Path: `modules/charts/chart-heatmap-charts.html`
- Purpose: Heatmap visualizations for data density
- Keywords: heatmap, density, grid, intensity, correlation
- Common Combinations: Used with analytics, calendars, data analysis

**Radar Charts**
- Path: `modules/charts/chart-radar-charts.html`
- Purpose: Radar/spider chart visualizations for multi-dimensional data
- Keywords: radar, spider, multi, dimensional, comparison, skills
- Common Combinations: Used with profiles, comparisons, assessments

**Scatter Charts**
- Path: `modules/charts/chart-scatter-charts.html`
- Purpose: Scatter plot visualizations for correlation analysis
- Keywords: scatter, plot, correlation, relationship, data, points
- Common Combinations: Used with analytics, research, data analysis

**Simple Line Charts**
- Path: `modules/charts/chart-line.html`
- Purpose: Basic line chart implementation
- Keywords: line, simple, basic, chart, trend
- Common Combinations: Used with small dashboards, widgets

**Chart Usage Guide**
- Path: `modules/charts/chart-how-to-use.html`
- Purpose: Instructions and examples for implementing charts
- Keywords: guide, instructions, examples, implementation, usage
- Common Combinations: Reference for all chart implementations

### Icons (3 modules)

**Feather Icons**
- Path: `modules/icons/icon-feather.html`
- Purpose: Feather icon library implementation and examples
- Keywords: feather, icons, svg, simple, clean, minimal
- Common Combinations: Used throughout interface for actions, navigation

**Font Awesome Icons**
- Path: `modules/icons/icon-font-awesome.html`
- Purpose: Font Awesome icon library integration
- Keywords: fontawesome, icons, font, comprehensive, variety
- Common Combinations: Used for complex interfaces, diverse icon needs

**Unicons**
- Path: `modules/icons/icon-unicons.html`
- Purpose: Unicons icon library implementation
- Keywords: unicons, icons, modern, consistent, line
- Common Combinations: Used for modern interfaces, consistent styling

### Utilities (12 modules)

**Background Utilities**
- Path: `modules/utilities/utility-background.html`
- Purpose: Background color and pattern utility classes
- Keywords: background, color, pattern, utility, bg
- Common Combinations: Used with all components for styling

**Border Utilities**
- Path: `modules/utilities/utility-borders.html`
- Purpose: Border styling and utility classes
- Keywords: border, edge, outline, frame, utility
- Common Combinations: Used with cards, forms, sections

**Color Utilities**
- Path: `modules/utilities/utility-colors.html`
- Purpose: Text and background color utility classes
- Keywords: color, text, background, utility, theme
- Common Combinations: Used throughout for consistent theming

**Display Utilities**
- Path: `modules/utilities/utility-display.html`
- Purpose: Display property utilities (show/hide/block/flex)
- Keywords: display, show, hide, block, flex, responsive
- Common Combinations: Used for responsive layouts, visibility control

**Flex Utilities**
- Path: `modules/utilities/utility-flex.html`
- Purpose: Flexbox layout utility classes
- Keywords: flex, layout, align, justify, direction, wrap
- Common Combinations: Used with all layout components

**Grid Utilities**
- Path: `modules/utilities/utility-grid.html`
- Purpose: CSS Grid layout utility classes
- Keywords: grid, layout, columns, rows, responsive, template
- Common Combinations: Used for complex layouts, dashboards

**Overflow Utilities**
- Path: `modules/utilities/utility-overflow.html`
- Purpose: Overflow control utility classes
- Keywords: overflow, scroll, hidden, auto, content
- Common Combinations: Used with containers, modals, content areas

**Position Utilities**
- Path: `modules/utilities/utility-position.html`
- Purpose: Position property utility classes
- Keywords: position, absolute, relative, fixed, sticky, top
- Common Combinations: Used with overlays, navigation, floating elements

**Shadow Utilities**
- Path: `modules/utilities/utility-shadows.html`
- Purpose: Box shadow utility classes for depth
- Keywords: shadow, depth, elevation, box, drop
- Common Combinations: Used with cards, modals, dropdowns

**Sizing Utilities**
- Path: `modules/utilities/utility-sizing.html`
- Purpose: Width and height utility classes
- Keywords: size, width, height, dimension, responsive
- Common Combinations: Used with all components for sizing control

**Spacing Utilities**
- Path: `modules/utilities/utility-spacing.html`
- Purpose: Margin and padding utility classes
- Keywords: spacing, margin, padding, gap, whitespace
- Common Combinations: Used with all components for layout spacing

**Typography Utilities**
- Path: `modules/utilities/utility-typography.html`
- Purpose: Text styling and typography utility classes
- Keywords: typography, text, font, size, weight, style
- Common Combinations: Used throughout for consistent text styling

---

## 📄 Page Index (110 Templates)

### E-commerce Application (17 pages)

#### Admin Dashboard (7 pages)
- **Add Product** - `pages/apps/e-commerce/admin/add-product.html`
  - Product creation form with image uploads, categories, pricing
- **Customer Details** - `pages/apps/e-commerce/admin/customer-details.html`  
  - Individual customer profile with order history and stats
- **Customers** - `pages/apps/e-commerce/admin/customers.html`
  - Customer management table with filtering and actions
- **Order Details** - `pages/apps/e-commerce/admin/order-details.html`
  - Detailed order view with items, shipping, payment status
- **Orders** - `pages/apps/e-commerce/admin/orders.html`
  - Order management dashboard with status tracking
- **Products** - `pages/apps/e-commerce/admin/products.html`
  - Product catalog management with bulk actions
- **Refund** - `pages/apps/e-commerce/admin/refund.html`
  - Refund processing interface with order lookup

#### Customer Portal (10 pages)
- **Shopping Cart** - `pages/apps/e-commerce/landing/cart.html`
  - Cart management with quantity updates and totals
- **Checkout** - `pages/apps/e-commerce/landing/checkout.html`
  - Multi-step checkout process with payment options
- **Favourite Stores** - `pages/apps/e-commerce/landing/favourite-stores.html`
  - User's favorite store listings and management
- **Homepage** - `pages/apps/e-commerce/landing/homepage.html`
  - Main e-commerce landing page with products and categories
- **Invoice** - `pages/apps/e-commerce/landing/invoice.html`
  - Order invoice with detailed line items and totals
- **Order Tracking** - `pages/apps/e-commerce/landing/order-tracking.html`
  - Real-time order status and shipping tracking
- **Product Details** - `pages/apps/e-commerce/landing/product-details.html`
  - Individual product page with images, specs, reviews
- **Products Filter** - `pages/apps/e-commerce/landing/products-filter.html`
  - Product browsing with advanced filtering options
- **User Profile** - `pages/apps/e-commerce/landing/profile.html`
  - Customer account management and preferences
- **Shipping Info** - `pages/apps/e-commerce/landing/shipping-info.html`
  - Shipping address management and options
- **Wishlist** - `pages/apps/e-commerce/landing/wishlist.html`
  - User wishlist with save-for-later functionality

### CRM Application (8 pages)
- **Add Contact** - `pages/apps/crm/add-contact.html`
  - Contact creation form with lead assignment
- **Analytics** - `pages/apps/crm/analytics.html`
  - CRM analytics dashboard with charts and KPIs
- **Deal Details** - `pages/apps/crm/deal-details.html`
  - Individual deal tracking with timeline and notes
- **Deals** - `pages/apps/crm/deals.html`
  - Deal pipeline management with drag-and-drop
- **Lead Details** - `pages/apps/crm/lead-details.html`
  - Lead profile with interaction history and scoring
- **Leads** - `pages/apps/crm/leads.html`
  - Lead management dashboard with qualification status
- **Report Details** - `pages/apps/crm/report-details.html`
  - Detailed CRM report with metrics and analysis
- **Reports** - `pages/apps/crm/reports.html`
  - CRM reporting dashboard with multiple report types

### Travel Agency Application (16 pages)

#### Flight Booking (3 pages)
- **Flight Booking** - `pages/apps/travel-agency/flight/booking.html`
  - Flight search and booking interface with seat selection
- **Flight Homepage** - `pages/apps/travel-agency/flight/homepage.html`
  - Flight booking landing page with search form
- **Flight Payment** - `pages/apps/travel-agency/flight/payment.html`
  - Flight payment processing with passenger details

#### Hotel Management (10 pages)

**Hotel Admin (4 pages)**
- **Add Property** - `pages/apps/travel-agency/hotel/admin/add-property.html`
  - Hotel property registration with amenities and photos
- **Add Room** - `pages/apps/travel-agency/hotel/admin/add-room.html`
  - Room type creation with pricing and availability
- **Room Listing** - `pages/apps/travel-agency/hotel/admin/room-listing.html`
  - Hotel room inventory management dashboard
- **Room Search** - `pages/apps/travel-agency/hotel/admin/room-search.html`
  - Administrative room search and booking management

**Hotel Customer (6 pages)**
- **Hotel Checkout** - `pages/apps/travel-agency/hotel/customer/checkout.html`
  - Hotel booking checkout with guest details
- **Hotel Gallery** - `pages/apps/travel-agency/hotel/customer/gallery.html`
  - Hotel photo gallery with room images
- **Hotel Homepage** - `pages/apps/travel-agency/hotel/customer/homepage.html`
  - Hotel booking search and featured properties
- **Hotel Compare** - `pages/apps/travel-agency/hotel/customer/hotel-compare.html`
  - Hotel comparison tool with features and pricing
- **Hotel Details** - `pages/apps/travel-agency/hotel/customer/hotel-details.html`
  - Individual hotel page with rooms and amenities
- **Hotel Payment** - `pages/apps/travel-agency/hotel/customer/payment.html`
  - Hotel booking payment with confirmation

#### Trip Planning (3 pages)
- **Trip Checkout** - `pages/apps/travel-agency/trip/checkout.html`
  - Trip package checkout with itinerary confirmation
- **Trip Homepage** - `pages/apps/travel-agency/trip/homepage.html`
  - Trip planning interface with destination search
- **Trip Details** - `pages/apps/travel-agency/trip/trip-details.html`
  - Detailed trip itinerary with activities and hotels

**Travel Agency Landing** - `pages/apps/travel-agency/landing.html`
- Main travel agency homepage with all services

### Project Management Application (6 pages)
- **Create New Project** - `pages/apps/project-management/create-new.html`
  - Project creation form with team assignment and goals
- **Project Board View** - `pages/apps/project-management/project-board-view.html`
  - Kanban-style project board with task cards
- **Project Card View** - `pages/apps/project-management/project-card-view.html`
  - Project overview in card layout with progress indicators
- **Project Details** - `pages/apps/project-management/project-details.html`
  - Individual project dashboard with timeline and team
- **Project List View** - `pages/apps/project-management/project-list-view.html`
  - Project listing table with sorting and filtering
- **Todo List** - `pages/apps/project-management/todo-list.html`
  - Task management with priorities and assignments

### Email Application (3 pages)
- **Compose Email** - `pages/apps/email/compose.html`
  - Rich text email composer with attachments
- **Email Detail** - `pages/apps/email/email-detail.html`
  - Individual email view with thread and actions
- **Email Inbox** - `pages/apps/email/inbox.html`
  - Email inbox with folder navigation and search

### Social Media Application (3 pages)
- **Social Feed** - `pages/apps/social/feed.html`
  - Social media timeline with posts and interactions
- **Social Profile** - `pages/apps/social/profile.html`
  - User profile page with posts and follower stats
- **Social Settings** - `pages/apps/social/settings.html`
  - Privacy and account settings for social platform

### Kanban Application (3 pages)
- **Kanban Boards** - `pages/apps/kanban/boards.html`
  - Multiple Kanban board management dashboard
- **Create Kanban Board** - `pages/apps/kanban/create-kanban-board.html`
  - Board creation with column setup and team assignment
- **Kanban Board** - `pages/apps/kanban/kanban.html`
  - Interactive Kanban board with drag-and-drop tasks

### Stock Trading Application (3 pages)
- **Stock Portfolio** - `pages/apps/stock/portfolio.html`
  - Investment portfolio with holdings and performance
- **Stock Details** - `pages/apps/stock/stock-details.html`
  - Individual stock analysis with charts and news
- **Stock Watchlist** - `pages/apps/stock/watchlist.html`
  - Stock watchlist with real-time price monitoring

### File Manager Application (2 pages)
- **Grid View** - `pages/apps/file-manager/grid-view.html`
  - File browser in grid layout with thumbnails
- **List View** - `pages/apps/file-manager/list-view.html`
  - File browser in list format with detailed info

### Events Application (2 pages)
- **Create Event** - `pages/apps/events/create-an-event.html`
  - Event creation form with scheduling and invitations
- **Event Detail** - `pages/apps/events/event-detail.html`
  - Event information page with RSVP and details

### Gallery Application (6 pages)
- **Photo Album** - `pages/apps/gallery/album.html`
  - Organized photo album with categories
- **Gallery Column** - `pages/apps/gallery/gallery-column.html`
  - Multi-column photo gallery layout
- **Gallery Grid** - `pages/apps/gallery/gallery-grid.html`
  - Grid-based photo gallery with hover effects
- **Gallery Masonry** - `pages/apps/gallery/gallery-masonry.html`
  - Masonry-style photo layout with varying sizes
- **Gallery Slider** - `pages/apps/gallery/gallery-slider.html`
  - Slideshow gallery with navigation controls
- **Grid with Titles** - `pages/apps/gallery/grid-with-title.html`
  - Photo grid with titles and descriptions

### Standalone Applications (3 pages)
- **Calendar** - `pages/apps/calendar.html`
  - Full-featured calendar with event management
- **Chat Application** - `pages/apps/chat.html`
  - Real-time chat interface with contact list
- **Gantt Chart** - `pages/apps/gantt-chart.html`
  - Project timeline visualization with dependencies

### Authentication Pages (21 pages)

#### Card Style (7 pages)
- **Two-Factor Auth** - `pages/pages/authentication/card/2FA.html`
- **Forgot Password** - `pages/pages/authentication/card/forgot-password.html`
- **Lock Screen** - `pages/pages/authentication/card/lock-screen.html`
- **Reset Password** - `pages/pages/authentication/card/reset-password.html`
- **Sign In** - `pages/pages/authentication/card/sign-in.html`
- **Sign Out** - `pages/pages/authentication/card/sign-out.html`
- **Sign Up** - `pages/pages/authentication/card/sign-up.html`

#### Simple Style (7 pages)
- **Two-Factor Auth** - `pages/pages/authentication/simple/2FA.html`
- **Forgot Password** - `pages/pages/authentication/simple/forgot-password.html`
- **Lock Screen** - `pages/pages/authentication/simple/lock-screen.html`
- **Reset Password** - `pages/pages/authentication/simple/reset-password.html`
- **Sign In** - `pages/pages/authentication/simple/sign-in.html`
- **Sign Out** - `pages/pages/authentication/simple/sign-out.html`
- **Sign Up** - `pages/pages/authentication/simple/sign-up.html`

#### Split Style (7 pages)
- **Two-Factor Auth** - `pages/pages/authentication/split/2FA.html`
- **Forgot Password** - `pages/pages/authentication/split/forgot-password.html`
- **Lock Screen** - `pages/pages/authentication/split/lock-screen.html`
- **Reset Password** - `pages/pages/authentication/split/reset-password.html`
- **Sign In** - `pages/pages/authentication/split/sign-in.html`
- **Sign Out** - `pages/pages/authentication/split/sign-out.html`
- **Sign Up** - `pages/pages/authentication/split/sign-up.html`

### Error Pages (3 pages)
- **403 Forbidden** - `pages/pages/errors/403.html`
- **404 Not Found** - `pages/pages/errors/404.html`
- **500 Server Error** - `pages/pages/errors/500.html`

### Landing Pages (2 pages)
- **Default Landing** - `pages/pages/landing/default.html`
- **Alternate Landing** - `pages/pages/landing/alternate.html`

### Pricing Pages (2 pages)
- **Pricing Column** - `pages/pages/pricing/pricing-column.html`
- **Pricing Grid** - `pages/pages/pricing/pricing-grid.html`

### FAQ Pages (2 pages)
- **FAQ Accordion** - `pages/pages/faq/faq-accordion.html`
- **FAQ Tabs** - `pages/pages/faq/faq-tab.html`

### Utility Pages (4 pages)
- **Members** - `pages/pages/members.html`
- **Notifications** - `pages/pages/notifications.html`
- **Starter Template** - `pages/pages/starter.html`
- **Timeline** - `pages/pages/timeline.html`

### Navigation Index
- **All Pages Index** - `pages/index.html`
  - Complete navigation interface for all 110 pages

---

## 🧩 Component Composition Guide

### Basic Building Blocks
**Foundation Components**: Used in almost every layout
- `component-card.html` - Primary container for content sections
- `component-button.html` - All interactive elements and actions  
- `utility-grid.html` - Layout structure and responsive design
- `utility-spacing.html` - Consistent spacing between elements

### Layout Patterns

**Dashboard Layout**: 
1. `component-breadcrumb.html` (navigation)
2. `component-card.html` (main containers)
3. `chart-*.html` (data visualization)
4. `utility-grid.html` (responsive layout)

**Form Layout**:
1. `component-card.html` (form container)
2. `form-basic-controls.html` (input fields)
3. `form-validation.html` (error states)
4. `component-button.html` (actions)

**List/Table Layout**:
1. `table-advanced.html` or `component-list-group.html` (data display)
2. `component-pagination.html` (navigation)
3. `form-select.html` (filtering options)
4. `component-button.html` (row actions)

**Navigation Layout**:
1. `component-breadcrumb.html` (page hierarchy)
2. `component-dropdown.html` (context menus)
3. `component-offcanvas.html` (mobile navigation)
4. `component-collapse.html` (expandable sections)

### Advanced Compositions

**E-commerce Product Card**:
```
component-card.html (container)
├── component-avatar.html (product image)
├── component-badge.html (status/price labels)  
├── utility-typography.html (product title/description)
└── component-button.html (add to cart action)
```

**User Profile Section**:
```
component-card.html (profile container)
├── component-avatar.html (profile picture)
├── component-list-group.html (user details)
├── component-badge.html (status indicators)
└── component-button.html (edit/contact actions)
```

**Analytics Dashboard Widget**:
```
component-card.html (widget container)
├── chart-line.html or chart-bar-charts.html (visualization)
├── component-badge.html (KPI indicators)
└── component-dropdown.html (time period selector)
```

**Data Management Interface**:
```
component-card.html (main container)
├── form-input-group.html (search functionality)
├── form-select.html (filtering options)
├── table-advanced.html (data display)
├── component-pagination.html (navigation)
└── component-modal.html (detailed actions)
```

### Integration Patterns

**Modal-driven Workflows**:
- List View (`table-advanced.html`) + Detail Modal (`component-modal.html`)
- Action Buttons (`component-button.html`) trigger Form Modals (`form-*`)

**Progressive Disclosure**:
- Summary Cards (`component-card.html`) + Detail Pages (full page templates)
- Accordion Sections (`component-accordion.html`) for information hierarchy

**Status Communication**:
- Loading States (`component-placeholder.html` + `component-spinners.html`)
- Success/Error Feedback (`component-alerts.html` + `component-toast.html`)
- Progress Indication (`component-progress-bar.html`)

---

## 🤖 LLM Usage Instructions

### For Code Extraction
1. **Locate Component**: Use the file paths provided in this index
2. **Read HTML File**: Each file contains complete, standalone HTML
3. **Extract Relevant Sections**: Copy the specific markup you need
4. **Apply Phoenix Classes**: Use the Phoenix-specific CSS classes listed above
5. **Combine Components**: Follow the composition patterns for complex layouts

### For Building Applications
1. **Start with Page Template**: Choose from the 110 page templates that match your use case
2. **Identify Required Modules**: Review which components are used in the page
3. **Extract Component Code**: Get HTML from the individual module files
4. **Customize Content**: Replace placeholder text and images with your content
5. **Apply Business Logic**: Add your JavaScript functionality to the Phoenix structure

### Best Practices
- **Always use Phoenix CSS classes** instead of creating custom styles
- **Combine modules systematically** following the composition guide
- **Maintain Bootstrap 5 structure** for responsive behavior
- **Reference complete page templates** for complex layout patterns
- **Use utility classes** for spacing, colors, and layout adjustments

### File Organization
```
phoenix-extracted/
├── modules/          # Individual reusable components
│   ├── components/   # UI building blocks
│   ├── forms/        # Form elements  
│   ├── charts/       # Data visualizations
│   └── utilities/    # Helper classes
└── pages/           # Complete page templates
    └── apps/        # Full application examples
```

### Component Dependencies
- **All components** require Bootstrap 5.3+ framework
- **Chart components** require ECharts or Chart.js libraries
- **Icon components** require respective icon font libraries
- **Interactive components** may require Bootstrap JavaScript

---

## 📊 Summary Statistics

- **Total Files**: 141 HTML templates
- **Modules**: 31 reusable components
- **Pages**: 110 complete page templates  
- **Categories**: 15 major application types
- **Bootstrap Version**: 5.3+ compatible
- **Phoenix Version**: v1.23.0
- **Coverage**: 100% of theme components extracted

**Last Updated**: August 2024  
**Extraction Status**: Complete ✅