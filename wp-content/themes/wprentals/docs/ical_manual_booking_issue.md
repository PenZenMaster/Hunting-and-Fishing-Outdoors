# Confirmation of iCal manual booking clearing issue

## Observed behavior
- When a property previously had an iCal feed configured and the feed is removed, the scheduled `event_wp_estate_sync_ical` cron still runs for that property.
- During sync, the importer refreshes the `booking_dates` meta even if no events are available. With an empty event list, the refreshed meta overwrites existing manual bookings, leaving the calendar empty after the next cron cycle.

## Reproduction summary
1. Add an iCal feed to a listing and let a scheduled sync run (or trigger the cron manually). The property now participates in sync.
2. Delete the iCal feed. No feeds remain, but the property stays enrolled for the scheduled sync.
3. Add a manual booking via the dashboard.
4. Wait for the next scheduled sync (typically within 1–2 days) or run the cron. The importer executes with no events and rewrites `booking_dates`, clearing the manual booking.

## Status
- The issue is **confirmed**: manual bookings are cleared after a sync runs with no feeds configured because the importer resets `booking_dates` before adding events, and with no events to add, the calendar remains empty.
