### excel -> pdf -> png or jpg
ファイル名を指定するときに拡張子を指定すれば、png, jpgどちらでもできる

まずは
### composer install
必要なのは
"phpoffice/phpspreadsheet": "^2.0",
"tecnickcom/tcpdf": "^6.7",
"spatie/pdf-to-image": "^1.2"

### imageMagicをOSにインストール
spaiteはimagickを必要だが、php8.2のdllファイルがないため、
システムにインストールして直接コマンドを実行するしかない
https://aulta.co.jp/technical/server-build/windows10/php/imagemagick
zipファイルを展開するだけでいい。exeファイルからインストールするとうまくいかない。
passを通す。コマンド自体は絶対パスにしてるので通さなくてもいいが。

imagickにはghostscriptが必要なのでこちらもインストール。
こちらは最新のものをそのままインストールすればいい。
https://www.ghostscript.com/releases/gsdnld.html


### フォルダ構成
template:データを書き込むエクセルファイルを置く　ファイル名はtemplate.xlsx
op_excel:データが書き込まれたエクセルファイル
pdf:エクセルからPDFファイルにしたものが出力
images:PDFから画像化されたもの
