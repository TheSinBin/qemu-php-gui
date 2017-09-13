<?php 
$passwd=""; // This is password value
echo '
	
 <form action="/index.php" method="post" autocomplete="on">
 Password:<input type="password" name="passwd" value="" >
  <TABLE border=1  width="%25">
  <TR>
      <TD>Boot & RAM</TD>
      <TD>Storage</TD>
      <TD>VGA </TD>
      <TD>Display & Devices</TD>
  </TR>
  <TR>
      <TD>Boot from:
          <br><input type="radio" name="chooseone" value="c">Hda
          <br><input type="radio" name="chooseone" value="d">Cdrom
          <br><input type="radio" name="chooseone" value="n">Network
          <br>RAM:<br><input type="number" name="RAM"  min="0" value="2048" style="width: 7em">Mb<br>  
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
          <br><input type="radio" name="choosetwo" value="null">Default
          <br><input type="radio" name="choosetwo" value="qxl">QXL
          <br><input type="radio" name="choosetwo" value="cirrus">Cirrus
          <br><input type="radio" name="choosetwo" value="std">Std
          <br><input type="radio" name="choosetwo" value="vmware">VmWare
      </TD>
      <TD>Display:
          <br><input type="radio" name="choosezero" value="sdl">SDL
          <br><input type="radio" name="choosezero" value="none">No Display
          <br><input type="radio" name="choosezero" value="vnc">VNC:
          <input type="number" name="vnc_port" min="0" value="0" style="width: 7em">
          <br>Devices:
          <br><input type="checkbox" name="mouse" value="true" checked>Usb Mouse
          <br><input type="checkbox" name="tablet" value="true" checked>Usb Tablet
          <br><input type="checkbox" name="host_cpu" value="true" checked>Host CPU
          <input type="number" name="cores" min="0" max="4" value="1" style="width: 7em">
          
          
      </TD>
          
  </TR>
  </TABLE>
  <input type="submit" value="Start Qemu" >
</form> 

 ';

if (isset($_POST["RAM"])){
$a="qemu-system-x86_64 --enable-kvm ";
if (is_numeric($_POST["RAM"])){
		$a=$a." -m ".$_POST["RAM"];
	}
	if (is_file($_POST["hda"])){
		$a=$a." -hda ".$_POST["hda"];
	}
	if (is_file($_POST["hdb"])){
		$a=$a." -hdb ".$_POST["hdb"];
	}
	if (is_file($_POST["hdc"])){
		$a=$a." -hdb ".$_POST["hdc"];
	}
	if (is_file($_POST["hdd"])){
		$a=$a." -hdb ".$_POST["hdd"];
	}
	if (is_file($_POST["cdrom"])){
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
	$a=$a." 2> /dev/null | cat > /dev/null &";
	}
	echo $a;
	if ($_POST["passwd"] == $passwd){
		shell_exec($a);
	}else{
		echo "Invalid Password:";
		echo $_POST["passwd"]."<br>";
	}
}
	?>
