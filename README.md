# ISP_co

Internet-Service-Provider Checkout

Der Speedtest wird alle 15 Minuten ausgeführt.

Ich habe folgende Zeile bei Crontab eingefügt:

\# m h  dom mon dow   command

*/15 * * * *  /root/speedtest

Zur Änderung des Intervalls einfach die "15" anpassen.

# Anforderungen:
speedtest - Linux Shellscript
    Wird vom Cronjob ausgeführt.
    Startet den Speedtest und logt das Ergebnis.
    
speedtest-cli - Python Script bereitgestellt vom Speedtestanbieter.
    Führt den Speedtest durch.
    siehe:
    https://wiki.ubuntuusers.de/speedtest-cli/
    
webpage
    Stellt die Ergebnisse der Tests grafisch dar.
