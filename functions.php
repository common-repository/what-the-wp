<?php

function wtwp_boot() {
    WTWP_Audit::boot();
    WTWP_Debug::boot();
}
