<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class TelegramController extends Controller
{
    public static function send($chatId, $message)
    {
        $botToken = "437640267:AAGvw92t3b02Ai2PnSAmmm8tdMKIUTG-Sn0"; // Токен телеграмовского бота "StekoLeedBot"
        $website = "https://api.telegram.org/bot" . $botToken;
        $url = $website . "/sendMessage?chat_id=" . $chatId . "&parse_mode=html&text=" . urlencode($message);
        file_get_contents($url);
    }
}