<?php

class HtmlMessage {
    private const ERROR_TYPE = 0;
    private const SUCCESS_TYPE = 1;
    private const WARNING_TYPE = 2;


    static function parseGetMessage() {
        if (array_key_exists('message', $_GET) &&
            array_key_exists('status', $_GET) &&
            array_key_exists('link', $_GET)) {
            $message = htmlspecialchars($_GET['message']);
            $status = htmlspecialchars($_GET['status']);
            $link = htmlspecialchars($_GET['link']);
            $type = HtmlMessage::ERROR_TYPE;

            if ($status == 200) {
                $type = HtmlMessage::SUCCESS_TYPE;
            }

            HtmlMessage::__message($type, $message, $link);
        } else {
            return null;
        }
    }

    public static function errorMessage(string $str, string $link): void
    {
        HtmlMessage::__message(HtmlMessage::ERROR_TYPE, $str, $link);
    }

    public static function successMessage(string $str, string $link): void
    {
        HtmlMessage::__message(HtmlMessage::SUCCESS_TYPE, $str, $link);
    }

    public static function warningMessage(string $str, string $link, string $deleteLink): void
    {
        HtmlMessage::__message(HtmlMessage::WARNING_TYPE, $str, $link, $deleteLink);
    }

    private static function __message(int $type, string $str, string $link, $deleteLink = ''): void
    {
        $style = 'error';
        $title = 'Erreur!';

        switch ($type) {
            case self::SUCCESS_TYPE:
                $style ='success';
                $title = 'Succès!';
                break;
            case self::WARNING_TYPE:
                $style = 'warning';
                $title = 'Attention!';
                break;
            default:
                break;
        }

        $deleteRef = '<a href="'.$deleteLink.'" class="button button-alert">Supprimer</a>';

        if ($type != self::WARNING_TYPE || empty($deleteLink)) {
            $deleteRef = '';
        }

        echo '<div class="row justify-center mbottom-5">
                <div class="admin-alert admin-alert-'.$style.'">
                    <div class="admin-alert-title">
                        <h2>'.$title.'</h2>
                    </div>
                    <div class="admin-alert-content">
                        <p>'.$str.'</p>
                    </div>
                    <div class="admin-alert-footer">
                        '.$deleteRef.'
                        <a href="'.$link.'" class="button button-std">Retour</a>
                    </div>
                </div>
            </div>';
    }

}