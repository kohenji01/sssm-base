<?php
/** @noinspection PhpUnused */
/**
 * =============================================================================================
 *  Project: sssm-core
 *  File: sssm_helper.php
 *  Date: 2020/05/21 17:22
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */


namespace sssm\functions{

    use Exception;
    use Locale;
    use Normalizer;

    /**
     * strtolower のエイリアス
     * @param string $string
     * @return string
     */
    function s2l( $string ){
        return strtolower( $string );
    }

    /**
     * 現在URLのデフォルトのsmartyテンプレートパスを得る
     * @param string $class_method __METHOD__の値
     * @param string $routes ディレクトリ
     * @param string $ext 拡張子 （省略時 .tpl）
     * @return string smartyテンプレートパス
     */
    function tpl( $class_method , $routes = "" , $ext = ".tpl" ){
        list( $class , $method ) = explode( "::" , $class_method );
        return $routes . s2l( $class ) . "/" . s2l( $method ) . $ext;
    }

    /**
     * IPアドレスが範囲内に含まれるかチェック
     * @param string $target_ip ターゲットIP（/でマスク可能）
     * @param string $remote_ip リモートIP（省略時 $_SERVER['REMOTE_ADDR']）
     * @return bool 結果
     */
    function check_ip( $target_ip , $remote_ip = '' ){
        if( $remote_ip == '' ){
            $remote_ip = $_SERVER['REMOTE_ADDR'];
        }
        if( strstr( $target_ip , '/' ) ){
            list( $accept_ip , $mask ) = explode( '/' , $target_ip );
        }else{
            $accept_ip = $target_ip;
            $mask = 32;
        }
        $accept_long = ip2long( $accept_ip ) >> ( 32 - $mask );
        $remote_long = ip2long( $remote_ip ) >> ( 32 - $mask );
        if( $accept_long == $remote_long ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 文字列よりBOMを除去する
     * @param string $data
     * @return null|string|string[]
     */
    function remove_bom( $data = "" ){
        return preg_replace('/^[\x00-\x1F\x80-\xFF]/', '', $data );
    }

    /**
     * Byteの単位を付ける
     * @param $bytes
     * @return string
     */
    function getSymbolByQuantity( $bytes ){
        $symbols = array( 'B' , 'KiB' , 'MiB' , 'GiB' , 'TiB' , 'PiB' , 'EiB' , 'ZiB' , 'YiB' );
        $exp     = floor( log( $bytes ) / log( 1024 ) );

        return intval( pow( 1024 , floor( $exp ) ) ) != 0 ? sprintf( '%.2f ' . $symbols["{$exp}"] , ( $bytes / pow( 1024 , floor( $exp ) ) ) ) : "0B";
    }

    /**
     * SI接頭辞付サイズを数値に変更
     * @param $byteString
     * @return int
     */
    function int_from_bytestring( $byteString ){
        preg_match('/^\s*([0-9.]+)\s*([KMGTPE])B?\s*$/i', $byteString, $matches);
        $num = (float)$matches[1];
        switch( strtoupper( $matches[2] ) ){
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'E':
                $num = $num * 1024;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'P':
                $num = $num * 1024;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'T':
                $num = $num * 1024;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'G':
                $num = $num * 1024;
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'M':
                $num = $num * 1024;
            case 'K':
                $num = $num * 1024;
        }

        return intval($num);
    }

    function get_locale(){
        return short_locale_to_lang_code( Locale::acceptFromHttp( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) );
    }

    function get_locale_short(){
        return short_locale_to_lang_code( substr( get_locale() , 0, 2 ) );
    }

    function short_locale_to_lang_code( $str ){
        switch( $str ){
            case 'ja':
                $str = "ja_JP";
                break;
            case 'zh':
                $str = "zh_TW";
                break;
            case 'ko':
                $str = "ko_KR";
                break;
            case 'de':
                $str = "de_DE";
                break;
            case 'en':
                $str = "en_US";
                break;
        }

        return $str;
    }

    function change_locale( $locale , $domain = '' , $charset = 'utf8' ){
        try{
            $ret =  true;
            if( $domain == '' ){
                $domain = $_ENV['sssm.sysname'];
            }

            $result = putenv( "LANG={$locale}" );
            if( !$result ){
                throw new Exception( 'putenv failed' );
            }
            $result = setlocale( LC_ALL , "{$locale}.{$charset}" );
            if( !$result ){
                throw new Exception( "setlocale LC_ALL failed (locale={$locale}.{$charset})" );
            }
            $result = setlocale( LC_CTYPE , "{$locale}.{$charset}" );
            if( !$result ){
                throw new Exception( "setlocale LC_CTYPE failed (locale={$locale}.{$charset})" );
            }
            bind_textdomain_codeset( $domain , $charset );
            bindtextdomain( $domain , APPPATH . 'Language' . DIRECTORY_SEPARATOR . 'Locale' .DIRECTORY_SEPARATOR );
            textdomain( $domain );

        }catch( Exception $e ){
            $ret = false;
        }
        return $ret;
    }

    /**
     * オブジェクトを配列にキャストする
     * @param mixed $obj
     * @return mixed オブジェクトは配列にそれ以外はそのまま出力
     */
    function obj2arr( $obj ){
        if( !is_object($obj) && !is_array($obj) ){
            return $obj;
        }

        $arr = (array)$obj;

        foreach( $arr as &$a ){
            $a = obj2arr( $a );
        }

        return $arr;
    }

    /**
     * UAがタブレット場合は真
     * @return bool
     */
    function check_tablet(){
        $ua = new UserAgent();
        return ( $ua->set() === "tablet" );
    }

    /**
     * UAがモバイル場合は真
     * @return bool
     */
    function check_mobile(){
        $ua = new UserAgent();
        return ( $ua->set() === "mobile" );
    }

    /**
     * UAのPC/タブレット/モバイルを判別するクラス
     * Class UserAgent
     */
    class UserAgent{
        private $ua;
        private $device;

        public function set(){
            $this->ua = mb_strtolower( $_SERVER['HTTP_USER_AGENT'] );
            if( strpos( $this->ua , 'iphone' ) !== false ){
                $this->device = 'mobile';
            }elseif( strpos( $this->ua , 'ipod' ) !== false ){
                $this->device = 'mobile';
            }elseif( ( strpos( $this->ua , 'android' ) !== false ) && ( strpos( $this->ua , 'mobile' ) !== false ) ){
                $this->device = 'mobile';
            }elseif( ( strpos( $this->ua , 'windows' ) !== false ) && ( strpos( $this->ua , 'phone' ) !== false ) ){
                $this->device = 'mobile';
            }elseif( ( strpos( $this->ua , 'firefox' ) !== false ) && ( strpos( $this->ua , 'mobile' ) !== false ) ){
                $this->device = 'mobile';
            }elseif( strpos( $this->ua , 'blackberry' ) !== false ){
                $this->device = 'mobile';
            }elseif( strpos( $this->ua , 'ipad' ) !== false ){
                $this->device = 'tablet';
            }elseif( ( strpos( $this->ua , 'windows' ) !== false ) && ( strpos( $this->ua , 'touch' ) !== false && ( strpos( $this->ua , 'tablet pc' ) == false ) ) ){
                $this->device = 'tablet';
            }elseif( ( strpos( $this->ua , 'android' ) !== false ) && ( strpos( $this->ua , 'mobile' ) === false ) ){
                $this->device = 'tablet';
            }elseif( ( strpos( $this->ua , 'firefox' ) !== false ) && ( strpos( $this->ua , 'tablet' ) !== false ) ){
                $this->device = 'tablet';
            }elseif( ( strpos( $this->ua , 'kindle' ) !== false ) || ( strpos( $this->ua , 'silk' ) !== false ) ){
                $this->device = 'tablet';
            }elseif( ( strpos( $this->ua , 'playbook' ) !== false ) ){
                $this->device = 'tablet';
            }else{
                $this->device = 'others';
            }

            return $this->device;
        }
    }

    /**
     * reCAPTCHAの照合
     * @param string $g_recaptcha_response レスポンスデータ
     * @param string $secret_key シークレットキー
     * @param string $endpoint 接続先URL
     * @return mixed 成功の場合 $ret['success'] = 1
     */
    function check_reCAPTCHA( $g_recaptcha_response , $secret_key , $endpoint ){
        $endpoint = sprintf( $endpoint , $secret_key , $g_recaptcha_response );
        $curl     = curl_init();
        curl_setopt( $curl , CURLOPT_URL , $endpoint );
        curl_setopt( $curl , CURLOPT_SSL_VERIFYPEER , false );
        curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true );
        curl_setopt( $curl , CURLOPT_TIMEOUT , 5 );
        $json = curl_exec( $curl );
        curl_close( $curl );

        return json_decode( $json , true );
    }

    /**
     * tail コマンドのPHP実装
     * @param string $file ファイル名
     * @param string $lines 行数
     * @return string 内容
     */
    function read_tail( $file , $lines ){
        $handle      = fopen( $file , "r" );
        $line_counter = $lines;
        $pos         = -2;
        $beginning   = false;
        $text        = array();
        while( $line_counter > 0 ){
            $t = " ";
            while( $t != "\n" ){
                if( fseek( $handle , $pos , SEEK_END ) == -1 ){
                    $beginning = true;
                    break;
                }
                $t = fgetc( $handle );
                $pos--;
            }
            $line_counter--;
            if( $beginning ){
                rewind( $handle );
            }
            $text[$lines - $line_counter - 1] = fgets( $handle );
            if( $beginning ){
                break;
            }
        }
        fclose( $handle );

        return implode( "" , array_reverse( $text ) );
    }

    /**
     * NFDの文字列をNFCに変換
     * @param string $str
     * @return string
     */
    function nfd_2_nfc( $str ){
        if( Normalizer::isNormalized( $str , Normalizer::FORM_D ) ){
            return Normalizer::normalize( $str , Normalizer::FORM_C );
        }
        return $str;
    }

    /**
     * nfd_2_nfc のエイリアス
     * @param string $str
     * @return string
     */
    function _nfc( $str ){
        return nfd_2_nfc( $str );
    }

    /**
     * DBのコメントのフォーマット
     * @param string $comment
     * @param string $separator
     * @return bool|string
     */
    function db_comment_format( $comment , $separator ){
        $pos = strpos( $comment , $separator );
        if( $pos !== false ){
            if( $pos == 0 ){
                $ret = substr( $comment , mb_strlen( $separator ) );
            }else{
                list( $ret ) = explode( $separator, $comment );
            }
        }else{
            $ret = $comment;
        }

        return $ret;
    }

    /**
     * 配列の絞り込み($dataに無い$key_listは空文字列が入る)
     * @param array $data
     * @param array $key_list
     * @return array
     */
    function array_filter( $data , $key_list ){
        $ret = [];
        foreach( $key_list as $key ){
            $ret[$key] = $data[$key] ?? '';
        }
        return $ret;
    }
}