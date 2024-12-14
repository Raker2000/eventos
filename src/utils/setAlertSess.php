<?php

function setAlertSess(string $sessionName, string $title, string $text, string $icon)
{
    //la session debe estar ya iniciada
    $errorData = ["title" => $title, "text" => $text, "icon" => $icon];
    $_SESSION[$sessionName] = $errorData;
}
