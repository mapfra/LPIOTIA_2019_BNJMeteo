# -*-coding:utf-8 -*-
import socket

client_socket=socket.socket(socket.AF_BLUETOOTH, socket.SOCK_STREAM, socket.BTPROTO_RFCOMM)

client_socket.connect(("2C:F7:F1:81:6A:8A",13))

size = 1024
data = client_socket.recv(size)
while data != '100':
    data = client_socket.recv(size)
    data = data.decode('utf-8')
    print(data)
client_socket.close()


-----code pour le dht11

import serial
import time
luminosite = ""
pression = ""
precipitations = ""
vent = ""
orientationvent = ""
while 1:
    ser = serial.Serial('/dev/ttyACM0', 9600)
    data = ser.read(size=11)
    humidite = (data[0:2])
    print(humidite)
    temperature = (data[6:8])
    print(temperature)
    entetes = [
         u'temperature',
         u'humidite',
         u'luminosite',
         u'pression',
         u'precipitation',
         u'vitessevent',
         u'orientationvent'
    ]
    
    valeurs = [
         [temperature, humidite, luminosite, pression, precipitations, vent, orientationvent]
    ]
    
    f = open('/var/www/html/meteo.csv', 'w')
    ligneEntete = ";".join(entetes) + "\n"
    f.write(ligneEntete)
    for valeur in valeurs:
         ligne = ";".join(valeur) + "\n"
         f.write(ligne)
    
    f.close()
    time.sleep(20)





------code python pour lumière humidité et température et precipitations

import serial
import time
orientationvent = ""
while 1:
    ser = serial.Serial('/dev/ttyACM0', 9600)
    data = ser.read(size=43)
    humidite = ""
    #print(humidite)
    temperature = ""
    #print(temperature)
    luminosite = ""
    #print luminosite
    precipitations = ""
    #print precipitations
    pression = ""
    #print pression
    vent = ""
    #print vent
    print data
    entetes = [
         u'temperature',
         u'humidite',
         u'luminosite',
         u'pression',
         u'precipitation',
         u'vitessevent',
         u'orientationvent'
    ]
    
    valeurs = [
         [temperature, humidite, luminosite, pression, precipitations, vent, orientationvent]
    ]
    
    f = open('/var/www/html/meteo.csv', 'w')
    ligneEntete = ";".join(entetes) + "\n"
    f.write(ligneEntete)
    for valeur in valeurs:
         ligne = data + "\n"
         f.write(ligne)
    
    f.close()
    #time.sleep(1)

------------code python envoi de mail quand le vent est trop fort -----------------
import serial
import time
import csv
import smtplib
import datetime
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
orientationvent = ""
while 1:
    ser = serial.Serial('/dev/ttyACM0', 9600)
    data = ser.readline()
    humidite = ""
    #print(humidite)
    temperature = ""
    #print(temperature)
    luminosite = ""
    #print luminosite
    precipitations = ""
    #print precipitations
    pression = ""
    #print pression
    vent = (data[31:34])
    #print vent
    #print data
    entetes = [
         u'temperature',
         u'humidite',
         u'luminosite',
         u'pression',
         u'precipitation',
         u'vitessevent',
         u'orientationvent'
    ]
    
    valeurs = [
         [temperature, humidite, luminosite, pression, precipitations, vent, orientationvent]
    ]
    
    f = open('/var/www/html/meteo.csv', 'w')
    ligneEntete = ";".join(entetes) + "\n"
    f.write(ligneEntete)
    for valeur in valeurs:
         ligne = data + "\n"
         f.write(ligne)
    
    f.close()
    donnee = data.split(';')
    vent = donnee[5]          
    if float(vent) >= 20:
        date = str(datetime.date.today())
        fdate = open('/var/www/html/date.txt', 'r')
        date2 = fdate.read()
        fdate.close
        if date != date2:                    
            msg = MIMEMultipart()
            msg['From'] = 'rapeurdu11@hotmail.fr'
            msg['To'] = 'bihane.leport@gmail.com'
            msg['Subject'] = 'Vent fort' 
            message = "Atttention, le vent est relativement fort aujourd'hui !"
            msg.attach(MIMEText(message))
            mailserver = smtplib.SMTP('smtp.live.com', 587)
            mailserver.ehlo()
            mailserver.starttls()
            mailserver.ehlo()
            mailserver.login('rapeurdu11@hotmail.fr', '*********')
            mailserver.sendmail('rapeurdu11@hotmail.fr', 'bihane.leport@gmail.com', msg.as_string())
            mailserver.quit
            fdate = open('/var/www/html/date.txt', 'w')
            fdate.write(str(date))
            fdate.close()
            print date2
            print date
        else:
            print "vent fort mais le mail a deja ete envoye"
#    time.sleep(5)