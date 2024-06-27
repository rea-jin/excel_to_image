<?php


/**
 * ログ出力クラス
 */
class LOG{
    /**
     * ログ出力 関数名ごとなので、APIでは呼び出さないこと。
     * @param String or Array or Object $log
     * @return void
     */
    public static function log($log)
    {
        $fnc_name = debug_backtrace()[1]['function'];
        $dir = dirname(__FILE__);
        $dir .= '/logs/'.$fnc_name;
        if(file_exists($dir)){
            //存在したときの処理
        }else{
            //存在しないときの処理
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        setlocale(LC_TIME, 'ja_JP.UTF-8');
        $fileyymd = str_replace("/","",date('Y/m/d'));
        $datetime = date('Y-m-d H:i:s');
        $fileName = $fileyymd . '.txt';
        $logPath = $dir;    
        $fileName = "$logPath\\$fileName";
        if (!file_exists($fileName)) {
            // ファイルが存在しない場合、作成する
            file_put_contents($fileName, '');
            chmod($fileName, 0666); // ファイルのパーミッションを適切に設定する
        }
        // 連想配列ならjson形式に変換
        if(is_array($log)){
            $log = json_encode($log, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        // objectならjson形式に変換
        if(is_object($log)){
            $log = json_encode($log, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        $log .= PHP_EOL;
        file_put_contents($fileName,$datetime . " " . $log , FILE_APPEND);
    }

    /**
     * ログ出力 API用
     * @param String or Array or Object $log
     * @return void
     */
    public static function debug($dir,$log)
    {
        if(file_exists($dir)){
            //存在したときの処理
        }else{
            //存在しないときの処理
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        setlocale(LC_TIME, 'ja_JP.UTF-8');
        $fileyymd = str_replace("/","",date('Y/m/d'));
        $datetime = date('Y-m-d H:i:s');
        $fileName = $fileyymd . '.txt';
        $logPath = $dir;    
        $fileName = "$logPath\\$fileName";
        if (!file_exists($fileName)) {
            // ファイルが存在しない場合、作成する
            file_put_contents($fileName, '');
            chmod($fileName, 0666); // ファイルのパーミッションを適切に設定する
        }
        // 連想配列ならjson形式に変換
        if(is_array($log)){
            $log = json_encode($log, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        // objectならjson形式に変換
        if(is_object($log)){
            $log = json_encode($log, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        $log .= PHP_EOL;
        file_put_contents($fileName,$datetime . " " . $log , FILE_APPEND);
    }

    /**
     * エラー時のレスポンス
     * 
     */
    public static function error($dir,$status,$code,$msg){
        $response = [
            "error"=>[
                "status" => $status,
                "code" => $code,
                "message" => $msg
            ]
        ];
        LOG::debug($dir,$response);
        return json_encode($response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}