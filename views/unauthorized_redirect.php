<?php
if (!is_null($new_location))
    header($new_location);
else
    header("Location: ?");
?>