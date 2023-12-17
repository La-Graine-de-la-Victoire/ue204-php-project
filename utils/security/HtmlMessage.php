<?php

/**
 * @class HtmlMessage
 * Used to print HTML messages with style written in CSS assets
 */
class HtmlMessage {
    private const ERROR_TYPE = 0;
    private const SUCCESS_TYPE = 1;
    private const WARNING_TYPE = 2;


    /**
     * Only for this website : detect if a message and status and redirect link
     * are present in the URL and generate message from this information
     * @return void|null
     */
    static function parseGetMessage() {
        // Check array keys : message and status and link
        if (array_key_exists('message', $_GET) &&
            array_key_exists('status', $_GET) &&
            array_key_exists('link', $_GET)) {
            // Secure data
            $message = htmlspecialchars($_GET['message']);
            $status = htmlspecialchars($_GET['status']);
            $link = htmlspecialchars($_GET['link']);
            $type = HtmlMessage::ERROR_TYPE;

            // Code 200 : success
            if ($status == 200) {
                $type = HtmlMessage::SUCCESS_TYPE;
            }

            // Generate HTML message
            HtmlMessage::__message($type, $message, $link);
        } else {
            return null;
        }
    }

    /**
     * Short function to generate error HTML message
     *
     * @param string $str
     * @param string $link
     * @return void
     */
    public static function errorMessage(string $str, string $link): void
    {
        HtmlMessage::__message(HtmlMessage::ERROR_TYPE, $str, $link);
    }

    /**
     * Short function to generate success HTML message
     *
     * @param string $str
     * @param string $link
     */
    public static function successMessage(string $str, string $link): void
    {
        HtmlMessage::__message(HtmlMessage::SUCCESS_TYPE, $str, $link);
    }

    /**
     * Short function to generate warning HTML message
     *
     * @param string $str
     * @param string $link
     * @param string $deleteLink
     * @return void
     */
    public static function warningMessage(string $str, string $link, string $deleteLink): void
    {
        HtmlMessage::__message(HtmlMessage::WARNING_TYPE, $str, $link, $deleteLink);
    }

    /**
     * Private function to generate HTML message
     *
     * @param int $type
     * @param string $str
     * @param string $link
     * @param $deleteLink
     * @return void
     */
    private static function __message(int $type, string $str, string $link, $deleteLink = ''): void
    {
        // select default css style
        $style = 'error';
        // set popup title
        $title = 'Erreur!';

        // Apply settings from specified message type
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

        // Link
        $deleteRef = '<a href="'.$deleteLink.'" class="button button-alert">Supprimer</a>';

        // Remove link if link parameter is empty
        if ($type != self::WARNING_TYPE || empty($deleteLink)) {
            $deleteRef = '';
        }

        $showedLink = '<a href="'.$link.'" class="button button-std">Retour</a>';
        if (empty($link)) {
            $showedLink = '';
        }

        // generate HTML message
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
                        '.$showedLink.'
                    </div>
                </div>
            </div>';
    }

}