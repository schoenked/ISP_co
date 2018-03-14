<html>
<body>
<?php 

$logPath = "/var/speedlog.txt";
$logopen = fopen($logPath, "r") or die("Die Statistikdaten konnten nicht geladen werden!");
$log = fread($logopen,filesize($logPath));

fclose($logopen);
echo "<pre>" . htmlspecialchars($log);
?>
</body>
</html>
