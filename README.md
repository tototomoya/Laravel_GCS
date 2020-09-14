LaravelとGoogleCloudStorageを用いてファイル登録サイトを作成しました。</br> ユーザ一覧ページ</br> http://34.72.82.112:10500 
ユーザーページ</br>ファイルの登録と、そのユーザが登録したファイルの内容が確認できます。</br> ファイルの登録先は「test/UserName.txt」になります。</br> 
http://34.72.82.112:10500/{name} </br> 
ユーザー登録を行えます、ファイル登録にユーザ名を用いているので、同名ユーザの登録はできず、ユーザ名の末尾に数字を付けたものを提示します。</br> 
http://34.72.82.112:10500/{name}/{password}/{email} </br> 
今回の制作を通して、ロギングの大切さを学びました。特にMySQLの機能を用いて、クエリ履歴を出せるようになってからは、製作がはかどりました。</br> 
MySQLのクエリ保存機能を用いるとパフォーマンスが落ちるので、今後はLaravelに保存されるクエリ履歴なども見れるようになりたいです。</br>
また、正規表現などを書く際には、LaravelのOnlinePlaygroundやtinkerなども使用しました。