# Feature 01 - Booking Workflow with Check-in/Check-out

## 1) How this feature works (end-to-end)

This feature is the core reservation pipeline for a property listing: user picks dates (and hours for per-hour listings), the theme validates availability and date rules, computes costs, and then creates a booking request (or instant booking) plus invoice/message records.

High-level flow:
1. Listing page loads booking UI and availability calendar.
2. User selects dates/hours and guest count.
3. Frontend asks server for live cost breakdown.
4. Frontend validates selected period via AJAX.
5. If valid, frontend submits booking creation AJAX.
6. Backend creates `wpestate_booking` post/meta, updates reservation map, computes prices, and (for owner/dashboard actions) creates invoice records.
7. Optional contact message flow sends booking inquiry to owner.

## 2) Templates involved (commented)

- `templates/booking_form_template.php:58`
Comment: Main booking form entry point; delegates UI rendering to `wpestate_show_booking_form(...)`.

- `templates/mobile_booking.php:1`
Comment: Mobile booking shell and trigger (`#mobile_booking_triger`) that opens booking actions on small screens.

- `templates/book_per_hour_form.php:1`
Comment: Per-hour booking modal wrapper (`#book_per_hour_calendar`) with cancel/ok controls.

- `templates/show_avalability.php:17`
Comment: Renders availability block title and calendar container. Uses per-hour container (`#all-front-calendars_per_hour`) for hourly listings.

- `templates/property_page_templates/listing_page_1.php:80`
Comment: Includes booking form in mobile area.

- `templates/property_page_templates/listing_page_1.php:149`
Comment: Includes booking form in desktop sidebar.

- `templates/property_page_templates/listing_page_2.php:60`
Comment: Includes booking form for mobile rendering.

- `templates/property_page_templates/listing_page_3.php:65`
Comment: Includes booking form in listing page type 3 layout.

- `templates/property_page_templates/listing_page_4.php:70`
Comment: Includes booking form in listing page type 4 layout.

- `templates/property_page_templates/listing_page_5.php:83`
Comment: Includes booking form in listing page type 5 layout.

## 3) PHP functions involved (commented + algorithm)

### Booking form rendering and inputs

- `libs/help_functions.php:1362` `wpestate_show_booking_form(...)`
Comment: Builds the booking form HTML (check-in/out, hour selectors for per-hour mode, guests, extra options, hidden listing id, messaging container).
Algorithm/logic:
1. Reads rental type, booking type, affiliate link, and sidebar layout.
2. If contact-form mode is enabled, renders contact form instead of booking controls.
3. Else prints check-in/check-out fields; in per-hour mode prints start/end hour dropdowns.
4. Prints guest selector and extra options.
5. Appends booking button block via `wpestate_show_booking_button(...)`.

- `libs/help_functions.php:1557` `wpestate_show_booking_button(...)`
Comment: Renders `Book Now` or `Instant Booking` submit button with data attributes used by JS (`data-maxguest`, `data-overload`, etc.).
Algorithm/logic:
1. Reads listing meta (instant booking, max guests, overflow rules, affiliate url).
2. Optionally prints Terms checkbox if enabled in options.
3. If affiliate listing, prints outbound link; otherwise prints booking submit input.
4. Outputs booking nonce field for AJAX security.

### Availability validation and reservation map

- `libs/help_functions.php:6041` `wpestate_check_booking_valability(...)`
Comment: Server-side availability validator for date ranges.
Algorithm/logic:
1. Converts incoming dates to internal format and resolves booking type.
2. Loads reservation map via `wpestate_get_booking_dates_advanced_search(...)`.
3. Iterates day by day (or boundary day) and rejects if any reserved key exists.
4. Applies custom min-stay constraints from `mega_details` and listing-level `min_days_booking`.
5. Returns boolean availability.

- `libs/help_functions.php:6110` `wpestate_get_booking_dates_advanced_search(...)`
Comment: Builds reservation timestamp map from confirmed bookings when cached map is missing.
Algorithm/logic:
1. Queries `wpestate_booking` posts for listing id + confirmed status.
2. Converts booking start/end dates to timestamps.
3. Fills every day in each booked interval into a lookup array.
4. Returns timestamp-keyed reservation array.

- `libs/ajax_functions_booking.php:244` and `libs/ajax_functions_booking.php:247` `wpestate_ajax_check_booking_valability(...)`
Comment: Main frontend AJAX gatekeeper that returns `run`/`stop*` status strings.
Algorithm/logic:
1. Reads selected dates/hours and listing id from POST.
2. Applies min-stay checks (global and per-period overrides).
3. Applies weekday changeover constraints (`checkin_change_over`, `checkin_checkout_change_over`).
4. Checks direct start conflict and interval overlap.
5. Returns `run` if valid, otherwise returns stop token (`stop`, `stopdays`, `stopcheckin`, `stopcheckinout`, etc.).

- `libs/ajax_functions_booking.php:404` `wprentals_check_hour_booking_overlap_reservations(...)`
Comment: Overlap detector for per-hour ranges.
Algorithm: loops all reservation intervals and delegates interval math to `wprentals_check_dates_overlap(...)`.

- `libs/ajax_functions_booking.php:425` `wprentals_check_booking_overlap_reservations(...)`
Comment: Overlap detector for day-based bookings.
Algorithm: increments from start date through end date and fails if any reserved day key is found.

- `libs/ajax_functions_booking.php:449` `wprentals_check_dates_overlap(...)`
Comment: Generic interval overlap predicate.
Algorithm: compares `[booking_start, booking_end]` vs `[reservation_start, reservation_end]` and returns true on intersection.

### Price and booking creation

- `libs/help_functions.php:3505` `wpestate_booking_price(...)`
Comment: Core pricing engine.
Algorithm/logic:
1. Resolves booking mode (per-day/per-hour/per-guest), base prices, custom prices, weekend prices, taxes/fees/deposit.
2. Iterates booking interval and accumulates day/hour price using custom overrides (`mega_details`, custom price array).
3. Adds guest-overload costs, extra paid options, and manual expenses.
4. Applies early-bird discount and refundable deposit.
5. Returns a detailed price structure used by cost preview and invoices.

- `libs/ajax_functions_booking.php:2532` and `libs/ajax_functions_booking.php:2536` `wpestate_ajax_show_booking_costs(...)`
Comment: AJAX endpoint that calls `wpestate_booking_price(...)` and returns HTML rows with total, fees, discount, deposit, etc.

- `libs/ajax_functions_edit.php:597` and `libs/ajax_functions_edit.php:599` `wpestate_ajax_add_booking(...)`
Comment: Creates a normal booking request.
Algorithm/logic:
1. Validates nonce/user.
2. Creates `wpestate_booking` post and writes booking meta (dates, guests, status, extras).
3. Rebuilds and saves listing `booking_dates` map.
4. Sends inbox/email notifications.
5. Computes and stores custom price array.

- `libs/ajax_functions_edit.php:797` and `libs/ajax_functions_edit.php:800` `wpestate_ajax_add_booking_instant(...)`
Comment: Creates instant booking path when listing enables it.
Algorithm/logic: same core as normal flow, but guarded by `instant_booking == 1`, then proceeds with instant-payment/confirmation path.

- `libs/ajax_functions_booking.php:1310` and `libs/ajax_functions_booking.php:1312` `wpestate_add_booking_invoice(...)`
Comment: Owner/dashboard endpoint that generates invoice for booking.
Algorithm/logic:
1. Validates owner permission and nonce.
2. Re-checks booked period safety.
3. Recomputes booking price.
4. Inserts invoice and syncs booking/invoice meta (deposit, balance, taxes, service fee, statuses).
5. Sends notifications; refreshes reservation map when confirming.

### Availability UI generation

- `libs/listing_functions.php:577` `wpestate_property_show_avalability(...)`
Comment: Generates listing availability section markup and legend.

- `libs/listing_functions.php:2331` `wpestate_get_calendar_custom_avalability(...)`
Comment: Month-loop driver that prints multiple front calendar months.
Algorithm: iterates configured month count and calls `wpestate_draw_month_front(...)`.

- `libs/listing_functions.php:2371` `wpestate_draw_month_front(...)`
Comment: Day-cell renderer with classes for past/today/reserved/free/start/end reservation boundaries.
Algorithm/logic:
1. Builds month grid with week padding.
2. For each day, computes unix key and chooses CSS class by reservation state.
3. Injects per-day price label via `wprentals_front_calendar_price(...)`.
4. Optionally prints min-nights badge from period settings.

- `libs/listing_functions.php:2544` `wprentals_front_calendar_price(...)`
Comment: Price label composer for each calendar day.
Algorithm: selects weekend override if applicable, falls back to custom/default price, then formats currency.

### Booking inquiry messaging

- `libs/ajax_functions_booking.php:2333` and `libs/ajax_functions_booking.php:2336` `wpestate_mess_front_end(...)`
Comment: Handles contact-owner message with selected booking dates and guest mix; stores inbox message and sends email.

## 4) JavaScript involved (what it does)

- `js/property.js:1232`
Comment: Submit handler for `#submit_booking_front` and `#submit_booking_front_instant`; validates form, guest limits, and terms.

- `js/property.js:1324`
Comment: Per-hour mode handling; combines selected date + hour slots before validation.

- `js/property.js:1352`
Comment: Calls `wprentals_check_booking_valability(button)` (from `control.js`) before creating booking.

- `js/control.js:433`
Comment: AJAX validator client for availability (`action: wpestate_ajax_check_booking_valability`).

- `js/control.js:462`
Comment: Sends date range + listing id to validation endpoint.

- `js/control.js:358`
Comment: Chooses booking create action dynamically (`wpestate_ajax_add_booking` vs `wpestate_ajax_add_booking_instant`).

- `js/property.js:622`
Comment: Calls `wpestate_ajax_show_booking_costs` for live booking cost preview.

- `js/property.js:262` `wpestate_booking_invalid_Date_new(...)`
Comment: Datepicker custom invalid-date logic (check-in/check-out weekday restrictions).

- `js/property.js:330` `wpestate_booking_show_booked(...)`
Comment: Datepicker custom day-class logic (reserved/free/start/end/check-in block).

- `js/property.js:1486`
Comment: Contact modal logic enforces `booking_to_date >= booking_from_date + 1 day`.

- `js/property.js:1576`
Comment: Sends inquiry form via AJAX action `wpestate_mess_front_end`.

- `js/ajaxcalls_add.js:84`
Comment: Internal all-in-one availability check endpoint usage (`wpestate_ajax_check_booking_valability_internal`).

- `js/dashboard-control.js:1452`
Comment: Direct payment booking action (`wpestate_direct_pay_booking`).

- `js/dashboard-control.js:1486`
Comment: PayPal booking payment action (`wpestate_ajax_booking_pay`).

## 5) CSS involved (what it styles)

- `style.css:5162`
Comment: Main booking form container `.booking_form_request`.

- `style.css:8240`
Comment: Availability section title anchor `#listing_calendar`.

- `style.css:18188`
Comment: Start of calendar-booking styling block.

- `style.css:18408`
Comment: Reserved day styling (`.calendar-reserved`, disabled pointer by default).

- `style.css:20087`
Comment: Instant booking modal styles (`#instant_booking_modal`).

- `style.css:21750`
Comment: Per-hour calendar container styles (`#book_per_hour_calendar`) and fullcalendar controls.

- `libs/customcss.php:787`
Comment: Dynamic width/layout rules for booking form based on admin settings.

- `libs/customcss.php:1876`
Comment: Dynamic calendar reserved/background palette generation (admin-configurable colors).

## 6) Script/style loading points

- `libs/css_js_include.php:82`
Comment: Enqueues `daterangepicker.js` used by date range inputs.

- `libs/css_js_include.php:233`
Comment: Registers FullCalendar (`fullcalendar56.js`).

- `libs/css_js_include.php:267`
Comment: Per-hour listings enqueue FullCalendar assets.

- `libs/css_js_include.php:593`
Comment: Enqueues global booking/control logic (`js/control.js`).

- `libs/css_js_include.php:855`
Comment: Registers `js/property.js` with booking-localized strings and options.

- `libs/css_js_include.php:896`
Comment: Enqueues `property.js` on property and reservation-related pages.

- `libs/css_js_include.php:980`
Comment: Additional enqueue path for singular property pages.

## 7) Practical algorithm summary

For this feature, the effective algorithm is:
1. Render booking inputs + availability calendar from property meta and settings.
2. On user input, derive normalized start/end timestamps (plus hours if per-hour).
3. Run AJAX availability validation enforcing:
   - reservation overlap
   - min-stay
   - check-in weekday constraints
4. If valid, compute full booking pricing from base + custom + extras + fees + discounts + deposit.
5. Persist booking record and update `booking_dates` map.
6. Optionally issue invoice/payment flow (instant or owner-confirmed path).
7. Keep owner/guest informed via inbox + email messages.

## 8) Notes

- The workflow is implemented as a mix of PHP-rendered markup, AJAX endpoints, and JS orchestration.
- Day-based and per-hour booking are both supported, with separate overlap logic paths.
