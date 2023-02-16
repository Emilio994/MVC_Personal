<?php

/**
 * @param string
 * 
 * @return string
 * 
 * Convert dots(.) into forward-slash(/)
 */

function sanitizeViewPath(string $path) :string {
    return str_replace('.','/',$path);
}