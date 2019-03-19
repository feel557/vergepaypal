<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your e-Guide: <? if(isset($data["subject"])){ echo $data["subject"]; } ?></title>
</head>
<body style="background-color:#f8fbfd; padding:5% 10% 5%;">
<div style="padding:20px;background-color:#fff; border-bottom:1px solid #f8fbfd;"><center><img src="https://ironhome.org/_ironhome/images/logo.png" style="max-width:250px; width:100%;"/></center></div>
<div style="padding:10px 0px 50px;text-align:center; background-color:#fff;">
<? if(isset($data["text"])){ echo $data["text"]; } ?>
</div>
</body>
</html>