<?php
	include('../functions.php');
	connectdb();
	if(isset($_POST['action'])){
		if($_POST['action']=='email') {
			mysql_query("UPDATE users SET email='".$_POST['email']."' WHERE username='".$_SESSION['username']."'");
			header("Location: index.php?changed=1");
		} else if($_POST['action']=='password') {
			$query = "SELECT salt,hash FROM users WHERE username='admin'";
			$result = mysql_query($query);
			$fields = mysql_fetch_array($result);
			$currhash = crypt($_POST['oldpass'], $fields['salt']);
			if($currhash == $fields['hash']) {
				$salt = randomAlphaNum(5);
				$newhash = crypt($_POST['newpass'], $salt);
				mysql_query("UPDATE users SET hash='$newhash', salt='$salt' WHERE username='".$_SESSION['username']."'");
				header("Location: index.php?changed=1");
			} else
				header("Location: index.php?passerror=1");
		} else if($_POST['action']=='settings') {
			if($_POST['accept']=='on') $accept=1; else $accept=0;
			if($_POST['c']=='on') $c=1; else $c=0;
			if($_POST['cpp']=='on') $cpp=1; else $cpp=0;
			if($_POST['java']=='on') $java=1; else $java=0;
			if($_POST['python']=='on') $python=1; else $python=0;
			mysql_query("UPDATE prefs SET name='".$_POST['name']."', accept=$accept, c=$c, cpp=$cpp, java=$java, python=$python");
			header("Location: index.php?changed=1");
		} else if($_POST['action']=='addproblem') {
			$query="INSERT INTO `problems` ( `name` , `text`, `input`, `output`) VALUES ('".$_POST['title']."', '".$_POST['problem']."', '".$_POST['input']."', '".$_POST['output']."')";
			mysql_query($query);
			header("Location: problems.php?added=1");
		} else if($_POST['action']=='editproblem') {
			mysql_query("UPDATE problems SET input='".$_POST['input']."', output='".$_POST['output']."', name='".$_POST['title']."', text='".$_POST['problem']."'  WHERE sl='".$_POST['id']."'");
			mysql_query($query);
			header("Location: problems.php?updated=1&action=edit&id=".$_POST['id']);
		}
	}
	else if(isset($_GET['action'])){
		if($_GET['action']=='delete') {
			$query="DELETE FROM problems WHERE sl=".$_GET['id'];
			mysql_query($query);
			header("Location: problems.php?deleted=1");
		} else if($_GET['action']=='ban') {
			$query="UPDATE users SET status=0 WHERE username='".$_GET['username']."'";
			mysql_query($query);
			header("Location: users.php?banned=1");
		} else if($_GET['action']=='unban') {
			$query="UPDATE users SET status=1 WHERE username='".$_GET['username']."'";
			mysql_query($query);
			header("Location: users.php?unbanned=1");
		}
	}	
?>