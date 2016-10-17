<?php

define(S_HOME, 'ホーム');
define(S_ADMIN, '管理用');
define(S_RETURN, '掲示板に戻る');
define(S_POSTING, 'レス送信モード');
define(S_NOTAGS, 'タグがつかえます');
define(S_NAME, 'おなまえ');
define(S_EMAIL, 'E-mail');
define(S_SUBJECT, '題　　名');
define(S_SUBMIT, '送信する');
define(S_COMMENT, 'コメント');
define(S_UPLOADFILE, '添付File');
define(S_NOFILE, '画像なし');
define(S_DELPASS, '削除キー');
define(S_DELEXPL, '(記事の削除用。英数字で8文字以内)');
define(S_RULES, '<LI>添付可能ファイル：GIF, JPG, PNG ブラウザによっては正常に添付できないことがあります。
<LI>最大投稿データ量は '.MAX_KB.' KB までです。sage機能付き。
<LI>画像は横 '.MAX_W.'ピクセル、縦 '.MAX_H.'ピクセルを超えると縮小表示されます。');
define(S_REPORTERR, '該当記事がみつかりません');
define(S_THUMB, 'サムネイルを表示しています.クリックすると元のサイズを表示します.');
define(S_PICNAME, '画像タイトル：');
define(S_REPLY, '返信');
define(S_OLD, 'このスレは古いので、もうすぐ消えます。');
define(S_RESU, 'レス');
define(S_ABBR, '件省略。全て読むには返信ボタンを押してください。');
define(S_REPDEL, '【記事削除】');
define(S_DELPICONLY, '画像だけ消す');
define(S_DELKEY, '削除キー');
define(S_DELETE, '削除');
define(S_PREV, '前のページ');
define(S_FIRSTPG, '最初のページ');
define(S_NEXT, '次のページ');
define(S_LASTPG, '最後のページ');
define(S_FOOT, '- <a href="http://php.s3.to" target=_blank>GazouBBS</a> + <a href="http://www.2chan.net/" target=_blank>futaba</a> + <a href="http://www.mapored.com/futallaby/" target=_blank>futallaby</a> -'); 
define(S_RELOAD, 'リロード');
define(S_UPFAIL, 'アップロードに失敗しました<br>サーバがサポートしていない可能性があります');
define(S_NOREC, 'アップロードに失敗しました<br>画像ファイル以外は受け付けません');
define(S_SAMEPIC, 'アップロードに失敗しました<br>同じ画像がありました');
define(S_TOOBIG, 'アップロードに失敗しました<br>サイズが大きすぎます<br>'.MAX_KB.'Kバイトまで');
define(S_TOOBIGORNONE,'アップロードに失敗しました<br>画像サイズが大きすぎるか、<br>または画像がありません。');
define(S_UPGOOD, '画像 $upfile_name のアップロードが成功しました<br><br>');
define(S_STRREF, '拒絶されました(str)');
define(S_UNJUST, '不正な投稿をしないで下さい(post)');
define(S_NOPIC, '画像がありません');
define(S_NOTEXT, '何か書いて下さい');
define(S_MANAGEMENT, '削除');
define(S_DELETION, '削除');
define(S_TOOLONG, '本文が長すぎますっ！');
define(S_UNUSUAL, '異常です');
define(S_BADHOST, '拒絶されました(host)');
define(S_PROXY80, 'ＥＲＲＯＲ！　公開ＰＲＯＸＹ規制中！！(80)');
define(S_PROXY8080, 'ＥＲＲＯＲ！　公開ＰＲＯＸＹ規制中！！(8080)');
define(S_SUN, '日');
define(S_MON, '月');
define(S_TUE, '火');
define(S_WED, '水');
define(S_THU, '木');
define(S_FRI, '金');
define(S_SAT, '土');
define(S_ANONAME, '名無し');
define(S_ANOTEXT, '本文なし');
define(S_ANOTITLE, '無題');
define(S_RENZOKU, '連続投稿はもうしばらく時間を置いてからお願い致します');
define(S_RENZOKU2, '画像連続投稿はもうしばらく時間を置いてからお願い致します');
define(S_RENZOKU3, '連続投稿はもうしばらく時間を置いてからお願い致します');
define(S_DUPE, 'アップロードに失敗しました<br>同じ画像があります');
define(S_NOTHREADERR, 'スレッドがありません');
define(S_SCRCHANGE, '画面を切り替えます');
define(S_BADDELPASS, '該当記事が見つからないかパスワードが間違っています');
define(S_WRONGPASS, 'パスワードが違います');
define(S_RETURNS, '掲示板に戻る');
define(S_LOGUPD, 'ログを更新する');
define(S_MANAMODE, '管理モード');
define(S_MANAREPDEL, '記事削除');
define(S_MANAPOST, '管理人投稿');
define(S_MANASUB, ' 認証');
define(S_DELLIST, '削除したい記事のチェックボックスにチェックを入れ、削除ボタンを押して下さい。');
define(S_ITDELETES, '削除する');
define(S_MDRESET, 'リセット');
define(S_MDONLYPIC, '画像だけ消す');
define(S_MDTABLE1, '<th>削除</th><th>記事No</th><th>投稿日</th><th>題名</th>');
define(S_MDTABLE2, '<th>投稿者</th><th>コメント</th><th>ホスト名</th><th>添付<br>(Bytes)</th><th>md5</th>');
define(S_RESET, 'リセット');
define(S_IMGSPACEUSAGE, '【 画像データ合計 : <b>$all</b> KB 】');
define(S_CANNOTWRITE, 'カレントディレクトリに書けません<br>');
define(S_NOTWRITE, 'を書けません<br>');
define(S_NOTREAD, 'を読めません<br>');
define(S_NOTDIR, 'がありません<br>');

/* begin MySQL specific section */
define(S_SQLCONF, '接続失敗');		//MySQL connection failure
define(S_SQLDBSF, 'mysql_select_db失敗<br>');	//database select failure
define(S_TCREATE, 'テーブルを作成します<br>\n');	//creating table
define(S_TCREATEF, 'テーブル作成失敗<br>');		//table creation failed
define(S_SQLFAIL, 'sql失敗<br>');		//SQL Failure
?>

