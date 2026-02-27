High-level overview: what we changed to support half-day + same-day
1) We introduced a “booking granularity” layer

Before: booking logic assumed full-day blocks (date → date).
After: bookings became time-aware (date + time windows).

This is usually done by adding one of these concepts:

Time slots (AM / PM / custom windows), or

Check-in/out times attached to a booking, or

A normalized “booking window” object (start_datetime, end_datetime)

“Ohhh yeah” trigger

You probably remember a debate like:

“Do we store AM/PM as an enum, or store actual timestamps?”

Most systems start with AM/PM enum, then later migrate to datetimes.

2) We extended the data model to represent partial days

To make half-day possible, the system needs to store more than just dates.

Typical data additions

Bookings table (or post meta, if it’s CPT-based) gains:

start_datetime (or start_date + start_slot)

end_datetime (or end_date + end_slot)

duration_units (e.g., 0.5, 1.0, 1.5, etc.)

pricing_mode (nightly vs daily vs half-day)

Listing/property table (or meta) gains configuration:

allow_same_day_turnover (bool)

allow_half_day (bool)

default_checkin_time, default_checkout_time

min_lead_time_hours (for same-day rules)

turnover_buffer_minutes (cleaning buffer)

“Ohhh yeah” trigger

There’s usually a field like buffer/turnover time that exists purely to prevent back-to-back collisions.

3) We rewired the availability engine (the most important change)

Before: availability checks did “date overlap” logic.
After: availability checks did “interval overlap” logic.

Old overlap logic (conceptually)

Booking A: Feb 10 → Feb 12

Booking B: Feb 12 → Feb 14
Often treated as no overlap if checkout day equals next checkin day.

New overlap logic

Booking A: Feb 12 8:00 AM checkout

Booking B: Feb 12 3:00 PM checkin
This becomes valid only if:

listing allows same-day turnover

buffer time is satisfied

cutoff/lead time rules are satisfied

Core rule becomes:

Two bookings conflict if their time windows overlap (including buffer).

“Ohhh yeah” trigger

You probably ended up with a helper like:

windows_overlap($startA, $endA, $startB, $endB, $bufferMinutes)

…and that function became the gatekeeper.

4) We updated pricing to handle half-day math cleanly

Half-day creates pricing edge cases fast.

What likely changed

A “base price per night/day” was split into:

nightly (standard stays)

half-day rate (AM/PM)

optional “same-day turnover fee” or “cleaning fee rules”

Pricing engine probably got one of these strategies:

Strategy A: fractional duration

Total = nightly_rate * duration_units
where duration_units can be 0.5 increments

Strategy B: slot-based pricing

AM slot price

PM slot price

Full day price
This is usually easiest for rentals.

“Ohhh yeah” trigger

There’s always a moment where taxes/fees break because the system assumes integer nights.
Somebody adds max(1, nights) hacks, and then you have to undo that.

5) We modified the booking UI and admin flow

If users can pick half-day, they need controls. That means changes in:

Datepicker / booking widget

Checkout/checkin time selectors OR AM/PM toggle

Display logic in calendars (admin + front-end)

UI changes typically include

Toggle: Full Day / Half Day

If half-day: choose AM or PM

If same-day turnover is allowed: show “Same-day available” messaging

Validation errors that mention:

lead time cutoff

buffer/turnover rule

disallowed same-day on certain listings

“Ohhh yeah” trigger

You probably had to adjust the calendar display so it didn’t “block” an entire day when only AM was booked.

6) We added rules & policies (the “business logic” layer)

Same-day availability is rarely unconditional.

Common rules that get added:

Cutoff time (e.g., “same-day booking only before 10 AM”)

Minimum lead time (e.g., “must book 3 hours in advance”)

Turnover buffer (e.g., 120 minutes)

Operational days (no same-day turnover on Sundays)

Manual approval flag for tight windows

“Ohhh yeah” trigger

There’s almost always a boolean like:

requires_manual_approval_if_same_day = true