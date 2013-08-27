<?php
$sudo_cmd = "/usr/bin/sudo";
$keyedit_cmd = "~git/keyedit.sh";
$keyedit_cmd = "$sudo_cmd $keyedit_cmd";

if (!array_key_exists('name',$_POST))
	$_POST['name'] = '';
if (!array_key_exists('key',$_POST))
	$_POST['key'] = '';
$msg = '';

if (!$_POST['name'] && !$_POST['key']) {
} else if ($_POST['name'] && !$_POST['key']) {
	$msg = '鍵を入力してください';
} else if (!$_POST['name'] && $_POST['key']) {
	$msg = '名前を入力してください';
} else if (!preg_match('/^[[:alnum:]]+$/', $_POST['name'])) {
	$msg = '名前は半角英数を指定してください';
} else {
	exec("$keyedit_cmd -s " . escapeshellarg($_POST['name']), $keys);
	if (count($keys)) {
		$msg = '既にその名前は登録されています';
	} else {
		if (preg_match('/^ssh-rsa /', $_POST['key'])) {
			$ret = 1;
			$reta[0] = $_POST['key'];
		} else {
			$tmpname = tempnam("/tmp", "pem");
			if ($fp = fopen($tmpname, "w")) {
				fwrite($fp, $_POST['key']);
				fclose($fp);
				$ret = exec("ssh-keygen -i -f $tmpname", $reta);
				unlink($tmpname);
			}
		}
		if ($ret && $reta[0]) {
			$cmd = "$keyedit_cmd -w " . escapeshellarg($_POST['name']) . ' ' . escapeshellarg($reta[0]);
			$ret = exec($cmd, $keys);
			$msg = htmlspecialchars($_POST['name']) . 'の鍵を追加しました';
		} else {
			$msg = '鍵が正しく無い様です';
		}
	}
}
$keys = array();
exec($keyedit_cmd, $keys);
?>
<html lang="ja">
<head>
<title>鍵登録</title>
<style type="text/css">
div#msg {
	border:1px solid black;
	background-color:#eee;
	margin:1em;
	padding:1em;
	color:black;
}
</style>
</head>
<body>
<h1>git用個人鍵登録</h1>
<?php if ($msg) { ?>
<div id="msg"><?php echo $msg; ?></div>
<?php } ?>
<form method="post" action="<?php echo getenv('SCRIPT_NAME'); ?>">
<dl>
<dt>アカウント名(半角英数)</dt>
<dd><input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name']); ?>" size="20" /></dd>
</dl>
<dl>
<dt>鍵</dt>
<dd><textarea name="key" cols="50" rows="5"><?php echo htmlspecialchars($_POST['key']); ?></textarea></dd>
</dl>
<input type="submit" value="鍵登録" />
</form>
<table border=1>
<?php foreach($keys as $key) {
	list($w,$name,$date,$time) = explode(' ', $key);
?>
	<tr>
		<th><?php echo $name ?></th>
		<td><?php echo "$date $time"; ?></td>
		<td><a href="#">削除</a></td>
	</tr>
<?php } ?>
</table>
<p><a href="/">戻る</a></p>
</body>
</html>
