<?php
require_once get_theme_file_path('/libs/resources/class.iCalReader.php');

/**
 * Clear imported iCal events from property booking dates
 *
 * This function removes all non-numeric values from the booking_dates meta array
 * for a specific property. In the context of the application, non-numeric values 
 * typically represent imported iCal events, while numeric values represent regular bookings.
 *
 * @param int $prop_id The property ID to clear iCal imported events from
 * @return void
 */
function wpestate_clear_ical_imported($prop_id){
    // Retrieve the booking dates array from post meta
    $reservation_array = get_post_meta($prop_id, 'booking_dates',true);
    
    // Check if the retrieved data is an array, if not initialize as empty array
    if(!is_array($reservation_array)){
        $reservation_array=array();
    }
    
    // Loop through each reservation in the array
    foreach($reservation_array as $key=>$value){
        // Check if the value is non-numeric (0 means it's not numeric)
        // This identifies imported iCal events which are stored as non-numeric values
        if (is_numeric($value)==0){
            // Remove the non-numeric entry from the array
            unset($reservation_array[$key]);
        }
    }
    
    // Update the post meta with the cleaned reservation array
    // This saves the modified booking dates back to the database
    update_post_meta($prop_id, 'booking_dates',$reservation_array);
}

/**
 * Import multiple iCal calendar feeds for a specific property
 *
 * This function processes all iCalendar feeds associated with a property,
 * collects the calendar data, and then triggers the import process.
 *
 * @param int $prop_id The property ID to import iCal feeds for
 * @return void
 */
function wpestate_import_calendar_feed_listing_global($prop_id){
    error_log(sprintf('WP Rentals iCal: begin feed import for property %d', $prop_id));
    // Retrieve the array of iCalendar feed data for this property
    // This contains multiple feeds with their URLs and names
    $property_icalendar_import_multi = get_post_meta($prop_id, 'property_icalendar_import_multi', true);

    // If there are no feeds stored for the property we should not touch the existing
    // booking dates. Returning early avoids wiping manual reservations during the
    // scheduled sync that runs every few hours.
    if ( empty($property_icalendar_import_multi) || !is_array($property_icalendar_import_multi) ) {
        error_log(sprintf('WP Rentals iCal: no feeds found for property %d; skipping import', $prop_id));
        return;
    }
    
    // Initialize an empty array to store all calendar data to be inserted
    $all_data_to_isert=array();
    
    // Loop through each feed in the multi-feed array
    foreach( $property_icalendar_import_multi as $key=>$feed_data){
        error_log(sprintf('WP Rentals iCal: fetching feed "%s" for property %d', $feed_data['name'], $prop_id));
        // Get calendar data from the current feed URL
        // The function returns an array of events from this specific feed
        $temp = wpestate_get_data_to_isert_ical($prop_id,$feed_data['feed'],$feed_data['name']);

        error_log(sprintf(
            'WP Rentals iCal: feed "%s" returned %d events for property %d',
            $feed_data['name'],
            is_array($temp) ? count($temp) : 0,
            $prop_id
        ));

        // Merge the events from this feed with our collection of all events
        // This combines data from multiple calendars into one array
        $all_data_to_isert = array_merge($all_data_to_isert, $temp);
    }

    // Process all collected calendar data and insert it into the property's booking system
    // This function handles the actual import of events into the property's calendar
    error_log(sprintf('WP Rentals iCal: inserting %d aggregated events for property %d', count($all_data_to_isert), $prop_id));
    wpestate_import_calendar_feed_listing_new($prop_id,$all_data_to_isert);
}





/**
 * Import calendar feed data into a property's booking system
 *
 * This function processes calendar data that has been retrieved from external iCal sources
 * and imports it into the property's booking system. It handles both standard bookings and
 * hourly bookings depending on the property configuration.
 *
 * @param int $prop_id The property ID to import calendar data for
 * @param array $data_to_insert Array of calendar events to be inserted
 * @return void
 */
function wpestate_import_calendar_feed_listing_new($prop_id,$data_to_insert){

    // If no data was gathered from the iCal feeds, keep existing booking dates intact
    // (for example when all feeds were removed but the cron sync still runs).
    if ( empty($data_to_insert) || !is_array($data_to_insert) ) {
        error_log(sprintf('WP Rentals iCal: no data to insert for property %d; keeping existing bookings', $prop_id));
        return;
    }

    // Retrieve current booking dates for the property
    // 'on_blank' parameter likely indicates to retrieve a blank/clean array structure
    $reservation_array = wpestate_get_booking_dates($prop_id, 'on_blank');

    // Update the property's booking dates meta with the newly retrieved array
    // This essentially resets the booking dates before adding the new entries
    error_log(sprintf('WP Rentals iCal: resetting booking_dates before inserting %d events for property %d', count($data_to_insert), $prop_id));
    update_post_meta($prop_id, 'booking_dates', $reservation_array);

    // Check if the property is configured for hourly bookings
    // (likely returns 2 for hourly bookings, 1 for daily/nightly bookings)
    $wprentals_is_per_hour = wprentals_return_booking_type($prop_id);

    // Loop through each calendar event to be inserted
    foreach ($data_to_insert as $key=>$to_insert){
        error_log(sprintf(
            'WP Rentals iCal: inserting event %s for property %d (%s to %s)',
            $to_insert['uid'],
            $to_insert['prop_id'],
            $to_insert['unix_time_start'],
            $to_insert['unix_time_end']
        ));
        if($wprentals_is_per_hour==2){
            // For hourly bookings, use the hourly event insertion function
            // This handles events with specific start and end times within a day
            $event_timezone = isset($to_insert['ical_timezone']) ? $to_insert['ical_timezone'] : '';
            wpestate_insert_booking_external_event_per_hour(
                $to_insert['prop_id'],
                $to_insert['unix_time_start'],
                $to_insert['unix_time_end'],
                $to_insert['uid'],
                $event_timezone,
                $to_insert['has_embed_time']
            );
              
        }else{
            // For regular (daily/nightly) bookings, use the standard event insertion function
            wpestate_insert_booking_external_event(
                $to_insert['prop_id'], 
                $to_insert['unix_time_start'], 
                $to_insert['unix_time_end'], 
                $to_insert['uid']
            );
        }    
    }
}

/**
 * Generate a list of events from an iCal feed for potential deletion
 *
 * This function retrieves and processes events from an external iCal feed,
 * converting them into a structured array format that can be used for later operations.
 * The function handles timezone conversions and formats the data for the WP Rentals system.
 *
 * @param int $prop_id The property ID to generate the feed for
 * @param string $property_icalendar_import The URL of the iCal feed to process
 * @param string $name The name/identifier for this feed
 * @return array|void Array of calendar events data, or void if feed is invalid
 */
function wpestate_generate_feed_listing_for_delete($prop_id,$property_icalendar_import,$name){
   
    // Verify that the property ID is a valid integer
    // Exit the function if the ID is not valid
    if(!intval($prop_id)){
        exit();
    }
   
    // Return if the iCal feed URL is empty
    if($property_icalendar_import ==''){
        return;
    }
    
    // Validate that the provided string is a valid URL
    // Return if it's not a valid URL
    if (filter_var($property_icalendar_import, FILTER_VALIDATE_URL) === FALSE) {
       return;
    }
    
    // Initialize a new iCal parser object with the feed URL
    $ical = new ICal($property_icalendar_import);
    
    // Get the timezone from the iCal feed
    $ical_timezone = $ical->timezone();
    
    // Retrieve all events from the iCal feed
    $events = $ical->events();
    
    // Get the start date of the first event (appears unused in this function)
    $date = $events[0]['DTSTART'];
  
    // Initialize an empty array to store processed event data
    $data_to_insert = array();
    
    // Loop through each event in the iCal feed
    // iCal events typically have DTSTART (start time) and DTEND (end time)
    foreach ($events as $event) {
        $unix_time_start = '';
        $unix_time_end = '';
        
        // Get the event's unique identifier (UID)
        // If none exists, set it to 'external'
        if(isset($event['UID'])){
            $uid = $event['UID'];
        }else{
            $uid = esc_html__('external','wprentals');
        }
        
        // Check if the event has embedded timezone information
        // This affects how time conversions are handled later
        $has_emebed_time = 0;
        if(isset($event['DTSTART_array'][0]['TZID']) && $event['DTSTART_array'][0]['TZID'] != ''){
            $has_emebed_time = 1;        
        }
   
        // Get the start time of the event and convert to Unix timestamp
        if(isset($event['DTSTART'])){
            $unix_time_start = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
        }
        
        // Get the end time of the event and convert to Unix timestamp
        if(isset($event['DTEND'])){
            $unix_time_end = $ical->iCalDateToUnixTimestamp($event['DTEND']);
        }
        
        // Override the UID with the feed name (updated in version 1.20 for multiple feeds)
        $uid = $name;
        
        // Initialize a temporary array to hold this event's data
        $temp_array = array();
       
        // Only process events that have valid start time, end time, and UID
        if($unix_time_start != '' && $unix_time_end != '' && $uid != ''){
            // If no timezone is specified in the iCal, use the server's default timezone
            if($ical_timezone == ''){
                $ical_timezone = date_default_timezone_get();
            }
            
            // Convert Unix timestamps to formatted date strings then back to Unix timestamps
            // This ensures consistent datetime handling
            $converted_start_date = gmdate("Y-m-d H:i:s", $unix_time_start);
            $convert_unix_time_start = strtotime($converted_start_date);
            $converted_end_date = gmdate("Y-m-d H:i:s", $unix_time_end);
            $converted_unix_end_date = strtotime($converted_end_date);
            
            // Create a DateTime object for timezone offset calculation
            $date = new DateTime($converted_start_date);
            $tz = timezone_open($ical_timezone);
            $timezone_offset = timezone_offset_get($tz, $date);
    
            // Apply timezone offset adjustment if the event doesn't have embedded time info
            if($has_emebed_time == 0){
                $convert_unix_time_start = $convert_unix_time_start + $timezone_offset;
                $converted_unix_end_date = $converted_unix_end_date + $timezone_offset;
            }
            
            // Build the complete event data array with all necessary information
            $temp_array = array(); 
            $temp_array['prop_id'] = $prop_id;
            $temp_array['unix_time_start'] = $convert_unix_time_start;
            $temp_array['unix_time_end'] = $converted_unix_end_date;
            $temp_array['uid'] = $uid;
            $temp_array['has_emebed_time'] = $has_emebed_time;
            $temp_array['ical_timezone'] = $ical_timezone;
          
            // Add this event's data to the collection of events to be inserted
            $data_to_insert[] = $temp_array;
        }
    }
    
    // Return the complete array of processed event data
    return $data_to_insert;
}






/**
 * Retrieve and format iCal calendar data for insertion into the booking system
 *
 * This function fetches events from an external iCal feed, processes them,
 * and prepares the data in a format suitable for insertion into the WP Rentals
 * booking system. It handles validation of inputs and processes event data.
 *
 * @param int $prop_id The property ID to retrieve calendar data for
 * @param string $property_icalendar_import The URL of the iCal feed
 * @param string $name The name/identifier for this feed
 * @return array|void Array of processed calendar events, or void if feed is invalid
 */
function wpestate_get_data_to_isert_ical($prop_id,$property_icalendar_import,$name){
    // Validate property ID is numeric and exit if not
    if(!intval($prop_id)){
        exit();
    }
   
    // Return early if no iCal URL is provided
    if($property_icalendar_import ==''){
        return;
    }
    
    // Validate that the provided string is a valid URL
    // Return if validation fails
    if (filter_var($property_icalendar_import, FILTER_VALIDATE_URL) === FALSE) {
       return;
    }
    
    // Initialize a new iCal parser object with the feed URL
    $ical   = new ICal($property_icalendar_import);
    
    // Extract timezone information from the iCal feed
    $ical_timezone = $ical->timezone();
    
    // Retrieve all events from the iCal feed
    $events = $ical->events();

    // Initialize date variable and set it to the first event's start date if available
    $date='';
    if(isset($events[0]['DTSTART']) ){
        $date = $events[0]['DTSTART'];
    }
    
    // Initialize array to store all processed event data
    $data_to_insert =   array();

    // Only process if events exist and are in array format
    if(is_array($events)):
        // Loop through each event in the iCal feed
        foreach ($events as $event) {
            // Initialize variables for event time tracking
            $unix_time_start    ='';
            $unix_time_end      ='';
            
            // Get the event's unique identifier (UID) or set default if not present
            if( isset($event['UID']) ){
                $uid                =$event['UID'];
            }else{
                $uid=   esc_html__('external','wprentals');
            }

            // Check if event has embedded timezone information
            $has_emebed_time=0;
            if( isset( $event['DTSTART_array'][0]['TZID'] ) &&  $event['DTSTART_array'][0]['TZID']!='' ){
                $has_emebed_time=1;        
            }

            // Extract and convert start time to Unix timestamp
            if( isset($event['DTSTART']) ){
               $unix_time_start =$ical->iCalDateToUnixTimestamp($event['DTSTART']);
            }

            // Extract and convert end time to Unix timestamp
            if( isset($event['DTEND']) ){
                $unix_time_end =$ical->iCalDateToUnixTimestamp($event['DTEND']);
            }

            // Override UID with feed name (updated in version 1.20 for multiple feeds)
            $uid            =   $name;// update on 1.20 with multuple ical feed
            
            // Initialize temporary array for this event's data
            $temp_array     =   array();
            
            // Only proceed if we have all required data (start time, end time, and UID)
            if( $unix_time_start!='' && $unix_time_end!='' && $uid !=''){
                // Create a structured array with all event information
                $temp_array                     =   array(); 
                $temp_array['prop_id']          =   $prop_id;
                $temp_array['unix_time_start']  =   $unix_time_start;
                $temp_array['unix_time_end']    =   $unix_time_end;
                $temp_array['uid']              =   $uid;
                $temp_array['has_embed_time']   =   $has_emebed_time;
                $temp_array['ical_timezone']    =   $ical_timezone;
                
                // Add this event to the main data array
                $data_to_insert[]               =   $temp_array;
            }    
        }
    endif;
    
    // Return the complete array of processed events
    return $data_to_insert;
    
}


/**
 * Import events from an iCal feed directly into a property's booking system
 *
 * This function combines fetching data from an iCal feed and inserting it
 * into the property's booking system in one operation. It handles both hourly
 * and daily booking types and manages all aspects of the import process.
 *
 * @param int $prop_id The property ID to import calendar data for
 * @param string $property_icalendar_import The URL of the iCal feed
 * @param string $name The name/identifier for this feed
 * @return void
 */
function wpestate_import_calendar_feed_listing($prop_id,$property_icalendar_import,$name){
    // Validate property ID is numeric and exit if not
    if(!intval($prop_id)){
        exit();
    }
   
    // Return early if no iCal URL is provided
    if($property_icalendar_import ==''){
        return;
    }
    
    // Validate that the provided string is a valid URL
    // Return if validation fails
    if (filter_var($property_icalendar_import, FILTER_VALIDATE_URL) === FALSE) {
       return;
    }
    
    // Initialize a new iCal parser object with the feed URL
    $ical   = new ICal($property_icalendar_import);
    
    // Extract timezone information from the iCal feed
    $ical_timezone = $ical->timezone();
    
    // Retrieve all events from the iCal feed
    $events = $ical->events();

    // Initialize date variable and set it to the first event's start date if available
    $date='';
    if(isset($events[0]['DTSTART']) ){
        $date = $events[0]['DTSTART'];
    }
    
    // Initialize array to store all processed event data
    $data_to_insert =   array();

    // Only process if events exist and are in array format
    if(is_array($events)):
        // Loop through each event in the iCal feed
        foreach ($events as $event) {
            // Initialize variables for event time tracking
            $unix_time_start    ='';
            $unix_time_end      ='';
            
            // Get the event's unique identifier (UID) or set default if not present
            if( isset($event['UID']) ){
                $uid                =$event['UID'];
            }else{
                $uid=   esc_html__('external','wprentals');
            }

            // Check if event has embedded timezone information
            $has_emebed_time=0;
            if( isset( $event['DTSTART_array'][0]['TZID'] ) &&  $event['DTSTART_array'][0]['TZID']!='' ){
                $has_emebed_time=1;        
            }

            // Extract and convert start time to Unix timestamp
            if( isset($event['DTSTART']) ){
               $unix_time_start =$ical->iCalDateToUnixTimestamp($event['DTSTART']);
            }

            // Extract and convert end time to Unix timestamp
            if( isset($event['DTEND']) ){
                $unix_time_end =$ical->iCalDateToUnixTimestamp($event['DTEND']);
            }

            // Override UID with feed name (updated in version 1.20 for multiple feeds)
            $uid            =   $name;// update on 1.20 with multuple ical feed
            
            // Initialize temporary array for this event's data
            $temp_array     =   array();
            
            // Only proceed if we have all required data (start time, end time, and UID)
            if( $unix_time_start!='' && $unix_time_end!='' && $uid !=''){
                // Create a structured array with all event information
                $temp_array                     =   array(); 
                $temp_array['prop_id']          =   $prop_id;
                $temp_array['unix_time_start']  =   $unix_time_start;
                $temp_array['unix_time_end']    =   $unix_time_end;
                $temp_array['uid']              =   $uid;
                $temp_array['has_embed_time']   =   $has_emebed_time;
                $temp_array['ical_timezone']    =   $ical_timezone;
                
                // Add this event to the main data array
                $data_to_insert[]               =   $temp_array;
            }    
        }
    endif;
    
    // Retrieve current booking dates for the property
    // 'on_blank' parameter likely indicates to retrieve a blank/clean array structure
    $reservation_array=  wpestate_get_booking_dates($prop_id,  'on_blank');
    
    // Update the property's booking dates meta with the newly retrieved array
    // This essentially resets the booking dates before adding the new entries
    update_post_meta($prop_id, 'booking_dates',$reservation_array);

    // Check if the property is configured for hourly bookings
    // (likely returns 2 for hourly bookings, 1 for daily/nightly bookings)
    $wprentals_is_per_hour  =   wprentals_return_booking_type($prop_id);
    
    // Loop through each event in the data array and insert into booking system
    foreach ($data_to_insert as $key=>$to_insert){
        if($wprentals_is_per_hour==2){
            // For hourly bookings, use the hourly event insertion function
            // This handles events with specific start and end times within a day
            $event_timezone = isset($to_insert['ical_timezone']) ? $to_insert['ical_timezone'] : '';
            wpestate_insert_booking_external_event_per_hour($to_insert['prop_id'], $to_insert['unix_time_start'], $to_insert['unix_time_end'], $to_insert['uid'], $event_timezone, $to_insert['has_embed_time']);
              
        }else{
            // For regular (daily/nightly) bookings, use the standard event insertion function
            wpestate_insert_booking_external_event($to_insert['prop_id'], $to_insert['unix_time_start'], $to_insert['unix_time_end'], $to_insert['uid'] );
        }    
    }
}








/**
* Insert an external booking event with hourly precision into a property's booking system
*
* This function processes an hourly booking event from an external iCal source and 
* adds it to the property's booking dates. It handles timezone conversions and ensures
* proper time formatting for hourly bookings.
* 
* @param int $prop_id The property ID to add the booking to
* @param int $unix_time_start Unix timestamp for booking start time
* @param int $unix_time_end Unix timestamp for booking end time
* @param string $uid Unique identifier for this booking
* @param string $ical_timezone Timezone from the iCal feed
* @param int $has_emebed_time Flag indicating if the event has embedded timezone info (1) or not (0)
* @return void
*/
function wpestate_insert_booking_external_event_per_hour($prop_id, $unix_time_start, $unix_time_end, $uid, $ical_timezone, $has_emebed_time){
 
   // If no timezone specified in the iCal, use server's default timezone
   if($ical_timezone==''){
      $ical_timezone= date_default_timezone_get();
   }
   
   // Convert Unix timestamps to formatted date strings, then back to timestamps
   // This ensures consistent date/time handling
   $converted_start_date       =   gmdate("Y-m-d H:i:s", $unix_time_start);
   $convert_unix_time_start    =   strtotime($converted_start_date);

   $converted_end_date         =   gmdate("Y-m-d H:i:s", $unix_time_end);
   $converted_unix_end_date    =   strtotime($converted_end_date);
      
   // Create DateTime object for timezone offset calculation
   $date               =   new DateTime($converted_start_date);
   $tz                 =   timezone_open($ical_timezone);
   $timezone_offset    =   timezone_offset_get($tz,$date);

   // Apply timezone adjustment if the event doesn't have embedded time info
   if($has_emebed_time==0){
       $convert_unix_time_start=$convert_unix_time_start+$timezone_offset;
       $converted_unix_end_date=$converted_unix_end_date+$timezone_offset;
   }
  
   // Get current time and calculate timestamp for 3 days ago
   $now=time();
   $daysago = $now-3*24*60*60;
   
   // Skip bookings that are more than 3 days old
   // This prevents importing historical bookings that are no longer relevant
   if ($convert_unix_time_start<$daysago){
       return;
   }
   
   // Get current booking dates for the property
   $reservation_array  = get_post_meta($prop_id, 'booking_dates',true);
   
   // Initialize as empty array if no bookings exist
   if(!is_array($reservation_array)){
       $reservation_array=array();
   }
   
   // Add the new hourly booking to the reservation array
   // For hourly bookings, store start timestamp as key and end timestamp as value
   $reservation_array[$convert_unix_time_start] =   $converted_unix_end_date;
   
   // Update the property meta with the new booking dates array
   update_post_meta($prop_id, 'booking_dates',$reservation_array);
}

/**
* Insert an external booking event with daily precision into a property's booking system
*
* This function processes a daily booking event from an external iCal source and 
* adds it to the property's booking dates. It converts times to midnight-to-midnight format
* and creates an entry for each day in the booking period.
* 
* @param int $prop_id The property ID to add the booking to
* @param int $unix_time_start Unix timestamp for booking start time
* @param int $unix_time_end Unix timestamp for booking end time
* @param string $uid Unique identifier for this booking
* @return void
*/
function wpestate_insert_booking_external_event($prop_id, $unix_time_start, $unix_time_end, $uid){ 
   // Convert timestamps to midnight-to-midnight format (ignoring time component)
   // This ensures bookings align with calendar days
   $converted_start_date       =   gmdate("Y-m-d 0:0:0", $unix_time_start);
   $convert_unix_time_start    =   strtotime($converted_start_date);
   $converted_end_date         =   gmdate("Y-m-d 0:0:0", $unix_time_end);
   $convert_unix_time_end      =   strtotime($converted_end_date);
   
   // Update variables with converted values
   $unix_time_start=$convert_unix_time_start;
   $unix_time_end=$convert_unix_time_end;
    
   // Get current time and calculate timestamp for 3 days ago
   $now=time();
   $daysago = $now-3*24*60*60;
   
   // Skip bookings that are more than 3 days old
   // This prevents importing historical bookings that are no longer relevant
   if ($unix_time_end<$daysago){
       return;
   }
   
   // Get current booking dates for the property
   $reservation_array  = get_post_meta($prop_id, 'booking_dates',true);
 
   // Initialize as empty array if no bookings exist
   if(!is_array($reservation_array)){
       $reservation_array=array();
   }
   
   // Format dates as ISO8601 strings for DateTime object creation
   $unix_time_start    = gmdate("Y-m-d\TH:i:s\Z", $unix_time_start);
   $unix_time_end      = gmdate("Y-m-d\TH:i:s\Z", $unix_time_end);
   
   // Create DateTime objects for start and end dates
   $from_date      =   new DateTime($unix_time_start);
   $from_date_unix =   $from_date->getTimestamp();
   $to_date        =   new DateTime($unix_time_end);
   $to_date_unix   =   $to_date->getTimestamp();
           
   // Ensure UID is a string (with space for numeric UIDs)
   // This prevents issues with numeric keys in arrays
   if(is_numeric($uid)){
       $uid=(string)$uid.' ';
   } 
   
   // Add the initial booking date to the reservation array
   $reservation_array[$from_date_unix] =   $uid;
   $from_date_unix                     =   $from_date->getTimestamp();
   
   // Loop through each day in the booking period and add to reservation array
   while ($from_date_unix < $to_date_unix){
       // Ensure UID is a string (with space for numeric UIDs)
       if(is_numeric($uid)){
           $uid=(string)$uid.' ';
       }
       
       // Add this day to the reservation array with the booking's UID
       $reservation_array[$from_date_unix] = $uid;
       
       // Move to the next day
       $from_date->modify('tomorrow');
       $from_date_unix = $from_date->getTimestamp();
   }
   
   // Update the property meta with the new booking dates array
   update_post_meta($prop_id, 'booking_dates',$reservation_array);
}

/**
* Find dates marked as "air" in the booking reservation array
*
* This function appears to extract all dates that are marked with the "air" identifier
* from a property's booking dates array. This likely represents dates reserved via Airbnb
* or another specific external service.
* 
* @param array $reservation_array The property's booking dates array
* @param array $to_compare_array Comparison array (appears unused)
* @return array Array of timestamps for dates marked as "air"
*/
function wpestate_update_calendar_missing_dates($reservation_array,$to_compare_array){
   // Find all dates in the reservation array with the value "air"
   // Returns an array of timestamps (keys) that have the value "air"
   $result = array_keys($reservation_array, "air");
}