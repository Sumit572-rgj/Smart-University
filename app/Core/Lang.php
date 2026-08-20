<?php
// app/Core/Lang.php
class Lang {
    private static $translations = [
        'en' => [
            'welcome' => 'Welcome',
            'dashboard' => 'Dashboard',
            'logout' => 'Logout'
        ],
        'ta' => [
            'welcome' => 'வரவேற்கிறோம்',
            'dashboard' => 'கட்டுப்பாட்டுப் பலகம்',
            'logout' => 'வெளியேறு'
        ]
    ];

    public static function get($key) {
        $lang = $_COOKIE['lang'] ?? 'en';
        return self::$translations[$lang][$key] ?? $key;
    }
}
