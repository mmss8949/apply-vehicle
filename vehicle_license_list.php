<?php
  include_once(dirname(__FILE__)."/access_verify.php");
  $radomCode = mt_rand();
?> <!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="icon" href="/apply-vehicle/favicon.ico"><title>vehicle_license</title><script src="https://code.jquery.com/jquery-3.4.1.min.js"></script><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css"><link href="/apply-vehicle/vehicle_license/tools/css/app.b0967f67.css" rel="preload" as="style"><link href="/apply-vehicle/vehicle_license/tools/css/chunk-vendors.8aea7dc9.css" rel="preload" as="style"><link href="/apply-vehicle/vehicle_license/tools/js/app.4d358f80.js" rel="preload" as="script"><link href="/apply-vehicle/vehicle_license/tools/js/chunk-vendors.43053e6d.js" rel="preload" as="script"><link href="/apply-vehicle/vehicle_license/tools/css/chunk-vendors.8aea7dc9.css" rel="stylesheet"><link href="/apply-vehicle/vehicle_license/tools/css/app.b0967f67.css" rel="stylesheet"></head><body><script>gGroups = '<?php echo $_GET["gGroups_i"];?>';
    gSys_s = '<?php echo $_GET["gSys_s"];?>';
    gFunc_s = '<?php echo $_GET["gFunc_s"];?>';</script><script src="/js/color.js?<?php echo $radomCode; ?>"></script><script src="/js/sys_msg.js?<?php echo $radomCode; ?>"></script> <?php
      include '/template/parent_verify.php';
    ?> <noscript><strong>We're sorry but vehicle_license doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript><div id="app"></div><script src="/apply-vehicle/vehicle_license/tools/js/chunk-vendors.43053e6d.js"></script><script src="/apply-vehicle/vehicle_license/tools/js/app.4d358f80.js"></script></body></html>