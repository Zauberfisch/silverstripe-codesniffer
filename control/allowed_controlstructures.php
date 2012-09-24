<?php

if($foo) {
    bar();
}

if($foo) bar();

if($foo) foreach($foo as $bar) {
    zap($bar);
}