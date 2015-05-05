<?php

    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        //form submitted

        //check if other form details are correct

        //verify captcha
        $recaptcha_secret = "CLAVE SECRETA GOOGLE RECAPTCHA";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
        $response = json_decode($response, true);
        if($response["success"] === true)
        {
            $output = '<h1>Tu solicitud sera procesada en un plazo de 24 horas.</h1>';
            $flags = 'style="display:none;"';
            $to = 'hectorchirona@gmail.com';
            $subject = 'Presupuesto: '.$_REQUEST['nombre'];
            $message = strip_tags($_REQUEST['message']);
            $attachment = chunk_split(base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
            $filename = $_FILES['file']['name'];
            $boundary =md5(date('r', time()));
            $headers = "From: ".$_REQUEST['email'];
            $headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"_1_$boundary\"";
            $message="This is a multi-part message in MIME format.

--_1_$boundary
Content-Type: multipart/alternative; boundary=\"_2_$boundary\"

--_2_$boundary
Content-Type: text/plain; charset=\"iso-8859-1\"
Content-Transfer-Encoding: 7bit

$message

--_2_$boundary--
--_1_$boundary
Content-Type: application/octet-stream; name=\"$filename\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

$attachment
--_1_$boundary--";

        mail($to, $subject, $message, $headers);

        }
        else
        {
            echo "¿Eres un robot?";
        }
    }


    if(isset($_REQUEST['submit']))
    {

    }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Presupuesto</title>
<style>
body{ font-family:Arial, Helvetica, sans-serif; font-size:13px;}
th{ background:#999999; text-align:left; vertical-align:top;}
input{ width:180px;}
label{ width:200px;}
</style>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
<script>
function popup(){
window.open("info.html","¿Que es la ficha tecnica?","width=400,height=400,menubar=no")
}

</script>
<?php echo $output; ?>
<center>
<form name="formulario"  enctype="multipart/form-data"  action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" <?php echo $flags;?>>
    <table  border="0" cellpadding="5" cellspacing="5">
               <tr>
        <th><label for="name">*Nombre: </label></th>
        <td><input type="text" name="nombre" id="nombre" style="width:250px;" required></td>
    </tr>
               <tr>
        <th><label for="from">*Email: </label></th>
        <td><input type="text" name="email" id="email" style="width:250px;" required></td>
    </tr>
                   <tr>
        <th><label for="message">Comentarios: </label></th>
        <td><textarea name="message" id="message" cols="35" rows="5"></textarea></td>
    </tr>
                   <tr>
        <th><label for="file">Ficha tecnica: </label></th>
        <td><input type="file" name="file" id="file" style="width:250px;" required></td>
    </tr>
 <tr>
        <td style="text-align:left;" ><input type=button value="¿Que es la ficha tecnica?" onclick="popup()"></td>
        <td colspan="2" style="text-align:left;"><input type="submit" name="submit" id="submit" value="Enviar"></td>
 </tr><tr><td></td><td>
 <div class="g-recaptcha" data-sitekey="CLAVE DE SITIO GOOGLE RECAPTCHA"></div></td></tr>
</form>
</center>
</body>
</html>

