<?php

$banners = array('title1.png', 'title2.png'); // populate with your banners

return($banners[mt_rand(0, count($banners) - 1)]);
