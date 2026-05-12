<?php

define('BASE_URL', 'http://localhost/ambon_oceanic/');
define('APP_NAME', 'Ambon Oceanic Tourism');

// LLM API Configuration - Google Gemini
define('LLM_API_KEY', 'AIzaSyALPYRWP-uwnfQzjPjnkpL2qb0QL_3XftA');
define('LLM_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent');
define('LLM_MODEL', 'gemini-3-flash-preview');

// Gemini Live API Configuration (for Voice Chat)
// Use the same API key as LLM_API_KEY or set a different one
define('GEMINI_API_KEY', LLM_API_KEY);

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1); // Set to 1 if using HTTPS

session_start();