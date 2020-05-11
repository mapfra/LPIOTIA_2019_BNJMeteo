# -*-coding:utf-8 -*-
import socket

client_socket=socket.socket(socket.AF_BLUETOOTH, socket.SOCK_STREAM, socket.BTPROTO_RFCOMM)

client_socket.connect(("2C:F7:F1:81:6A:8A",13))

size = 1024
data = client_socket.recv(size)
f = open('Traitement_Temp_Humi.csv', 'a')
while data != '30':
    data = client_socket.recv(size)
    data = data.decode('utf-8')
    print(data)
    f.write(data)
    f.write('\r\n')
client_socket.close()
f.close()