<?include_once('global.php');
if (isset($_POST['s']))
{
    $stamp = $_POST['st'];
    $_SESSION['currentview_'.$stamp]= $_POST['s'];
    $_SESSION['receiver_'.$stamp] = $_POST['r'];
}



if(isset($_POST['token'])){
    $a = $_POST['token'];
    $_SESSION['token'] = $a;
    $sql="INSERT INTO Token(usernumber, token) VALUES   ('$session_usernumber', '$a') ON DUPLICATE KEY UPDATE usernumber='$session_usernumber'";
        if(!mysqli_query($con,$sql))
        {
        echo"can not";
        }
}
//watching for new message 
if ($_SESSION['currentview_'.$stamp] == "home"){$search_usernumber = $session_usernumber;}
else if ($_SESSION['currentview_'.$stamp] == "person"){$search_usernumber = $_SESSION['receiver_'.$stamp];}
else if ($_SESSION['currentview_'.$stamp] == "group"){$search_usernumber = $_SESSION['receiver_group_'.$stamp];}

$sql="SELECT * FROM nMessages Where usernumber='$search_usernumber'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while($row= $result->fetch_assoc())
    {
        $_SESSION['new_messages_n'.$stamp] = $row['public_messages'];
        $last_message = $row['last_message'];
    }
    if ($_SESSION['new_messages_n'.$stamp] > $_SESSION['current_messages_n'.$stamp])
    {
    echo "<script>console.log('-----------new message------------');</script>";
    $_SESSION['current_messages_n'.$stamp] = $_SESSION['new_messages_n'.$stamp];
    $a123 = $_SESSION['current_messages_n'.$stamp];
    $sql_1="UPDATE nMessages SET public_messages = '$a123' WHERE usernumber = '$search_usernumber';";
        if(!mysqli_query($con,$sql_1))
        {
        echo"can not";
        }
	?>
	<script>document.getElementById("my_messages").innerHTML = "<?if ($_SESSION['currentview_'.$stamp]=="home"){include( "get_messages/reloading/my_public.php");}if ($_SESSION['currentview_'.$stamp]=="person"){include( "get_messages/reloading/their_public.php");}if ($_SESSION['currentview_'.$stamp]=="group"){include( "get_messages/reloading/group.php");}?>";
	document.getElementById("<?if ($_SESSION['currentview_'.$stamp]=="home"){echo "public_preview";}else if ($_SESSION['currentview_'.$stamp]=="person"){echo "their_public_preview";}else if ($_SESSION['currentview_'.$stamp]=="group"){echo "their_public_preview";}?>").innerHTML = "<?echo $last_message;?>";
	$('#my_messages').scrollTop($('#my_messages')[0].scrollHeight);
	</script>
	<?
    }
}
//adding token to DB
?>
<script>
currentToken_notf_a = getCookie("currentToken_notf");

var time = getCookie("timelimit");
if((currentToken_notf_a != '')&&(time!='1')){
    console.log("adding_  "+currentToken_notf_a);
    document.getElementById("tokenforn0").innerHTML ='<form id="tokenforn" action=" " method="post"><input type="text" hidden name="token" value=""><button type="submit" hidden></button></form>';
    document.getElementById("tokenforn").elements[0].value = currentToken_notf_a;
document.cookie = "currentToken_notf=; path=/;";
document.cookie = "timelimit=1; path=/;";
document.getElementById('tokenforn').submit();
}

</script>
<?
?>

