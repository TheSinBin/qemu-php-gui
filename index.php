<?php 
$user="";			// This is user value
$passwd="";			// This is password value
$folder="";			// This is falder value (Example: /home/user/imgs/)
if (isset($_GET["cmd"])){
	if($_GET["cmd"] == "logout"){
		setcookie("passwd","");
		setcookie("user","");
	}
	if($_GET["cmd"]=="killall"){
		shell_exec("killall qemu-system-x86_64 2> /dev/null | cat > /dev/null &");
		echo 'All VM is Killed<br>';
	}

}


if (($_COOKIE['passwd'] == $passwd) AND ($_COOKIE['user'] == $user)){
	if ($_COOKIE['user'] != ""){
	echo 'Login from:<b>'.$_COOKIE['user'].'</b>&nbsp&nbsp&nbsp<a href="./index.php?cmd=logout">Logout</a>';
	}

	if($_GET["cmd"]=="create"){
		echo '<form action="/index.php?cmd=create" method="post" autocomplete="on">
 Name:&nbsp<input type="text" name="name" value="" >
 Size:&nbsp<input type="text" name="size" value="" >Gb
 <br><input type="submit" value="Cleate" ></form>';
  if(isset($_POST['name']) AND isset($_POST['size'])){
	 if(is_numeric($_POST['size'])){
		 $size=$_POST["size"];
		 $name=str_replace(";","",$_POST["name"]);
		 $name=str_replace("..",".",$name);
		 $name=str_replace("/","",$name);
		 if(is_file($folder.$name)){
			 shell_exec("rm -f ".$folder.$name);
             echo "Disk image delete and ";
			 }
			shell_exec("qemu-img create ".$folder.$name." ".$size."G");
			echo "New disk image create: ".$name."<br>Size: ".$size."Gb";
  }else{
	  echo "Invalid size";
	  }
	}
	exit;
}
if($_GET["cmd"]=="delete"){
		echo '<form action="/index.php?cmd=delete" method="post" autocomplete="on">
 Name:&nbsp<input type="text" name="name" value="" >
 <br><input type="submit" value="Delete" ></form>';
	if(isset($_POST['name'])){
		$name=str_replace(";","",$_POST["name"]);
		$name=str_replace("..",".",$name);
		$name=str_replace("/","",$name);
		if(is_file($folder.$name)){
			shell_exec("rm -f ".$folder.$name);
            echo "Disk image deleted.";
		}else{
			echo "File not found";
			}
  }
		exit;
}

	echo '
	
 <form action="/index.php" method="post" autocomplete="on">';
  echo '<TABLE border=1  width="%25">
  <TR>
      <TD>Boot & RAM</TD>
      <TD>Storage</TD>
      <TD>VGA & Ports</TD>
      <TD>Display & Devices</TD>
  </TR>
  <TR>
      <TD>Boot from:
          <br><input type="radio" name="chooseone" value="c">Hda
          <br><input type="radio" name="chooseone" value="d" checked="checked" >Cdrom
          <br><input type="radio" name="chooseone" value="n">Network
          <p>RAM:<br><input type="number" name="RAM"  min="0" value="2048" style="width: 7em">Mb<br>  
      </TD><TD>Hda:
          <br><input type="text" name="hda" value="">
          <br>Hdb:
          <br><input type="text" name="hdb" value="">
          <br>Hdc:
          <br><input type="text" name="hdc" value="">
          <br>Hdd:
          <br><input type="text" name="hdd" value="">
          <br>Cdrom:
          <br><input type="text" name="cdrom" value="">
        
      </TD>
      <TD>VGA:
          <br><input type="radio" name="choosetwo" value="null" checked="checked" >Default
          <br><input type="radio" name="choosetwo" value="qxl">QXL
          <br><input type="radio" name="choosetwo" value="cirrus">Cirrus
          <br><input type="radio" name="choosetwo" value="std">Std
          <br><input type="radio" name="choosetwo" value="vmware">VmWare
          <p>Redirect port:(H:G,H:G)<br><input type="text" name="redir">
      </TD>
      <TD>Display:
          <br><input type="radio" name="choosezero" value="sdl" checked="checked" >SDL
          <br><input type="radio" name="choosezero" value="none">No Display
          <br><input type="radio" name="choosezero" value="vnc" " >VNC:
          <input type="number" name="vnc_port" min="0" value="0" style="width: 7em">
          <p>Devices:
          <br><input type="checkbox" name="mouse" value="true" checked>Usb Mouse
          <br><input type="checkbox" name="tablet" value="true" checked>Usb Tablet
          <br><input type="checkbox" name="host_cpu" value="true" checked>Host CPU
          <input type="number" name="cores" min="0" max="4" value="1" style="width: 7em">
          
          
      </TD>
          
  </TR>
  </TABLE>
  <input type="submit" value="Start Qemu" ><a href="./index.php?cmd=killall">Kill All VM</a>&nbsp&nbsp&nbsp<a href="./index.php?cmd=create">Create New Disk Image</a>

</form> 

 ';
	if (isset($_POST["RAM"])){
		$port=random_int(0,9999);
		$a="qemu-system-x86_64 --enable-kvm -net nic -net user,hostfwd=tcp::".$port."-:22";
		if ($_POST["redir"] != ""){
			$redir=$_POST["redir"];
			$redir=",".$redir;
			$redir=str_replace(" ","",$redir);
			$redir=str_replace(":","::",$redir);
			$redir=str_replace(","," -redir tcp:",$redir);
			$a=$a.$redir;
		}
		
	if (is_numeric($_POST["RAM"])){
			$a=$a." -m ".$_POST["RAM"];
		}
		if (is_readable($_POST["hda"]) AND !is_dir($_POST["hda"])){
			$a=$a." -hda ".$_POST["hda"];
		}
		if (is_readable($_POST["hdb"]) AND !is_dir($_POST["hdb"])){
			$a=$a." -hdb ".$_POST["hdb"];
		}
		if (is_readable($_POST["hdc"]) AND !is_dir($_POST["hdc"])){
			$a=$a." -hdb ".$_POST["hdc"];
		}
		if (is_readable($_POST["hdd"]) AND !is_dir($_POST["hdd"])){
			$a=$a." -hdb ".$_POST["hdd"];
		}
		if (is_readable($_POST["cdrom"]) AND !is_dir($_POST["cdrom"])){
			$a=$a." -cdrom ".$_POST["cdrom"];
		}
	
		if (isset($_POST["choosezero"])){
			$a=$a." -display ".$_POST["choosezero"];
			if ($_POST["choosezero"] == "vnc"){
				if(is_numeric($_POST["vnc_port"])){
					$a=$a."=:".$_POST["vnc_port"];
				}
			}
		}
		if (isset($_POST["chooseone"])){
			$a=$a." -boot ".$_POST["chooseone"];
		}
		if (isset($_POST["choosetwo"])){
			if ($_POST["choosetwo"] != "null"){
				$a=$a." -vga ".$_POST["choosetwo"];
			}
		}
		if ($_POST["mouse"] == "true"){
			$a=$a." -usbdevice mouse";
		}
		if ($_POST["tablet"] == "true"){
			$a=$a." -usbdevice tablet";
		}
		if ($_POST["host_cpu"] == "true"){
			$a=$a." -cpu host";
				if(is_numeric($_POST["cores"])){
					$a=$a." -smp cpus=".$_POST["cores"];
				}
		$a=$a." 2> error.txt | cat > /dev/null &";
		}
		//echo $a;
		if ($_COOKIE["passwd"] == $passwd){
			shell_exec($a);
			echo "SSh port:".$port;
		}else{
			echo "<p>Invalid Password:";
			echo $_POST["passwd"]."<br>";
		}
	}

}	
else{
	if(isset($_POST['passwd']) AND isset($_POST['user'])){
		session_start();
		setcookie("passwd",$_POST['passwd'],0);
		setcookie("user",$_POST['user'],0);
		}
		echo '<form action="/index.php" method="post" autocomplete="on">
 User:&nbsp<input type="password" name="user" value="" >
 <br>Password:&nbsp<input type="password" name="passwd" value="" >
 <br><input type="submit" value="Login" ></form>';
}
		?>
