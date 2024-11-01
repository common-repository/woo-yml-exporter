<?php


/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 19.07.17
 * Time: 14:46
 */
class GenerateStatus
{
    const NEVER_RUN = 0;
    const RUNNING = 1;
    const SUCCESS = 2;
    const ERROR = 3;

    const STATUS_SETTING_NAME = 'yml_exporter_generating_status';
    const EXCEPTION_MESSAGE_OPTION_NAME = 'yml_export_exception_message';
    const STATUS = 'status';
    const DATETIME = 'datetime';
    const SPEND_TIME = 'spend_time';
    const DATETIME_FORMAT = 'Y-m-d H:i';
    const ERROR_MESSAGE = 'error_message';
    const PID = 'pid';


    /**
     * @return string
     */
    static function get(){
        $status = get_site_option( self::STATUS_SETTING_NAME, self::NEVER_RUN );
        $status_array = json_decode($status, true);

        if ( !empty( $status_array[ GenerateStatus::STATUS ] ) ) {
            $status_string = self::getStatusLabel( $status_array[ GenerateStatus::STATUS ] );
            switch ( $status_array[ GenerateStatus::STATUS ] ){
                case self::ERROR:
                    $status_string = self::getErrorStatusString( $status_array, $status_string );
                    break;

                case self::SUCCESS:
                    $status_string = self::getSuccessStatusString( $status_array, $status_string );
                    break;

                case self::RUNNING:
                    $status_string = self::getRunningStatusString( $status_array, $status_string );
                    break;
            }
        }else{
            $status_string = self::getStatusLabel( $status );
        }


        return $status_string;
    }


    /**
     * @param $status
     */
    static function set($status){
        (get_site_option( self::STATUS_SETTING_NAME ) === false )
            ? add_site_option(self::STATUS_SETTING_NAME, $status)
            : update_site_option(self::STATUS_SETTING_NAME, $status);
    }


    /**
     * @return array
     */
    static function getStatusLabelList(){
        return [
            0 => 'Никогда не запускался.',
            1 => 'Генерация выполняется.',
            2 => 'Файл xml-выгрузки успешно сгенерирован.',
            3 => 'При генерации возникла ошибка.',
        ];
    }


    /**
     * @param int $status_code
     * @return mixed|string
     */
    static function getStatusLabel( $status_code ){
        $label_list = self::getStatusLabelList();
        $label = array_key_exists( $status_code, $label_list )
            ? $label_list[ $status_code ]
            : 'Undefined status code';

        return $label;
    }


    /**
     * @return string
     */
    static function getWooPluginStatus()
    {
        return ( is_plugin_active( 'woocommerce/woocommerce.php' ) )
            ? 'Плагин активен.' : 'Плагин не активен. <a href="plugins.php">Включите его.</a>';
    }


    /**
     * @return string
     */
    public static function getTransliteratePluginStatus()
    {
        return ( is_plugin_active( 'cyr3lat/cyr-to-lat.php' ) )
            ? 'Плагин активен.' : 'Плагин `cyr3lat/cyr-to-lat` не активен. <a href="plugins.php">Включите его.</a> ' .
            'Актуально для сайтов на словянских языках.';
    }


    /**
     * A sweet interval formatting, will use the two biggest interval parts.
     * On small intervals, you get minutes and seconds.
     * On big intervals, you get months and days.
     * Only the two biggest parts are used.
     * @param $start
     * @param null $end
     * @return string
     */
    public static function formatDateDiff($start, $end=null) {
        if(!($start instanceof DateTime)) {
            $start = new DateTime($start);
        }

        if($end === null) {
            $end = new DateTime();
        }

        if(!($end instanceof DateTime)) {
            $end = new DateTime($start);
        }

        $interval = $end->diff($start);
        $doPlural = function($nb,$str){return $nb>1?$str.'':$str;}; // adds plurals

        $format = array();
        if($interval->y !== 0) {
            $format[] = "%y ".$doPlural($interval->y, "year");
        }
        if($interval->m !== 0) {
            $format[] = "%m ".$doPlural($interval->m, "мес");
        }
        if($interval->d !== 0) {
            $format[] = "%d ".$doPlural($interval->d, "дн");
        }
        if($interval->h !== 0) {
            $format[] = "%h ".$doPlural($interval->h, "час");
        }
        if($interval->i !== 0) {
            $format[] = "%i ".$doPlural($interval->i, "мин");
        }
        if($interval->s !== 0) {
            if(!count($format)) {
                return "меньше минуты";
            } else {
                $format[] = "%s ".$doPlural($interval->s, "сек");
            }
        }

        // We use the two biggest parts
        if(count($format) > 1) {
            $format = array_shift($format)." ".array_shift($format);
        } else {
            $format = array_pop($format);
        }

        // Prepend 'since ' or whatever you like
        return $interval->format($format);
    }


    /**
     * @param $status_array
     * @param $status_string
     * @return string
     */
    private static function getErrorStatusString( $status_array, $status_string )
    {
        if ( !empty( $status_array[ GenerateStatus::DATETIME ] ) ) {
            $status_string = "[{$status_array[GenerateStatus::DATETIME]}] " .
                "$status_string";
        }

        if ( !empty( $status_array[ GenerateStatus::ERROR_MESSAGE ] ) ) {
            $status_string .= " ({$status_array[GenerateStatus::ERROR_MESSAGE]})";
        }

        if ( !empty( $status_array[ GenerateStatus::SPEND_TIME ] ) ) {
            $status_string .= " Выполнялся в течение {$status_array[GenerateStatus::SPEND_TIME]}.";
        }


        return $status_string;
    }


    /**
     * @param $status_array
     * @param $status_string
     * @return string
     */
    private static function getSuccessStatusString( $status_array, $status_string )
    {
        if ( !empty( $status_array[ GenerateStatus::DATETIME ] ) ) {
            $status_string = "[{$status_array[GenerateStatus::DATETIME]}] " .
                "$status_string";
        }

        if ( !empty( $status_array[ GenerateStatus::SPEND_TIME ] ) ) {
            $status_string .= " Выполнялся в течение {$status_array[GenerateStatus::SPEND_TIME]}.";
        }

        $file_href = home_url() . Xml::getFileRelativeName();
        $status_string .= " Файл доступен здесь: <a href=\"{$file_href}\" target='_blank'>$file_href</a>";



        return $status_string;
    }


    /**
     * @param $status_array
     * @param $status_string
     * @return string
     */
    private static function getRunningStatusString( $status_array, $status_string )
    {
        if ( !empty( $status_array[ GenerateStatus::DATETIME ] ) ) {
            $status_string = "[{$status_array[GenerateStatus::DATETIME]}] " .
                "$status_string";

            if ( !empty( $status_array[ GenerateStatus::SPEND_TIME ] ) ) {
                $spend_time = GenerateStatus::formatDateDiff(new DateTime( $status_array[ GenerateStatus::DATETIME ] ));
                $status_string .= " выполняется в течение {$spend_time}.";
            }
        }

        if( !empty( $status_array[ GenerateStatus::PID ] ) ){
            $pid_status = self::getPidStatusString( $status_array[ GenerateStatus::PID ] );
            $status_string .= " Статус процесса: {$pid_status}";
        }


        return $status_string;
    }


    /**
     * @param $pid
     * @return string
     */
    private static function getPidStatusString( $pid )
    {
        if( is_null( $status = self::pidStatus($pid) ) ){
            $status_string = 'Нет доступа для определения статуса выполнения процесса';
        }else{
            $status_string = $status ? "Работает в данный момент" : "Процесс не работает";
        }

        return $status_string;
    }


    /**
     * Определить статус указанного процесса
     * Жив ли процесс?
     *
     * @param int $pid
     * @return bool|null
     */
    public static function pidStatus ( $pid )
    {
        $command = "ps -p $pid";

        try{
            exec ( $command, $op );
            $pid_status =  ( isset( $op[ 1 ] ) ) ? true : false;
        }catch (Exception $e){
            $pid_status = null;
        }


        return $pid_status;
    }
}