<?php
if(isset($_SESSION['osCAdminID'])) {
echo 'Session exists';
} else {
echo 'You really ought to login first shouldn\'t you?';
}
?>
