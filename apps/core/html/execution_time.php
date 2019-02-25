<style>
    .native-execution-time {
        margin: 50px 20px 10px; 
        padding-top: 10px; 
        border-top: 1px solid rgba(0, 0, 0, .3); 
        color: rgba(0, 0, 0, .7);
    }
</style>

<?php
    $ms = $execution_time * 1000;
	if($ms <= 80) $speed = '<span style="color: green">Fast</span>';
	else if($ms > 80 and $ms <= 250) $speed = '<span style="color: blue">Normal</span>';
	else if($ms > 500 and $ms <= 3000) $speed = '<span style="color: orange">Slow</span>';
	else $speed = '<span style="color: red">Very slow!</span>';
?>

<div class="native-execution-time">
    <span style="color: black">
        Script execution time: <b><?=$speed?></b>
    </span>
    <br>
    <b><?=number_format($execution_time / 60, 7)?> minutes </b
    >&nbsp;|&nbsp;&nbsp;
    <b><?=number_format($execution_time, 5)?> seconds</b>
    &nbsp;|&nbsp;&nbsp;
    <b><?=$ms?> milliseconds</b>
    &nbsp;|&nbsp;&nbsp;
    <b><?=number_format($execution_time * 1000000, 0, '.', ' ')?> microseconds</b>
    &nbsp;|&nbsp;&nbsp;
    <b><?=number_format($execution_time * 1000000000, 0, '.', ' ')?> nanoseconds</b>
</div>