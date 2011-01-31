SimpleMath
==========

A quick and easy math worksheet.

---

Copyright 2011 Alex King  
http://alexking.org

Released under the FreeBSD license.

## Features

- math is done by JavaScript, anything more than basic algebra is an accident
- strips non-numeric garbage when evaluating (you can leave $ and , when pasting in your numbers)
- the ENTER key from expression side takes you to result side and selects result for easy copying
- hit ENTER again (while in the result field) to get a new row
- CTRL+N at any time will give you a new row
- enter the result of one of the previous 10 rows (numbered) at the cursor position by using CTRL+(1-9)
- numbers reset on every new row so that referencing the previous row is always 1, the second back is always 2, etc.
- if you get so many rows that they extend off the screen, the window automatically scrolls up like an old-school calculator tape
- delete the current row with CMD+DELETE
- single HTTP request with gzipped response (CSS and JS are embedded)

## Notes

The CTRL key isn't a good choice for windows users, however I am not one. Please fork as needed.

## TODO

- figure out why I was having trouble triggering an event to delete a row, remove the duplicate code
- see how using option/alt feels instead of ctrl
- add notes field for each row?
- explicitly set cache headers
- don't allow any data entry on result side (can currently paste in)
- clear/reset button?
- allow re-ordering of rows?
