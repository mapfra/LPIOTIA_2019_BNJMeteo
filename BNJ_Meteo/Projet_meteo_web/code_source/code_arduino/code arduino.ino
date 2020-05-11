/*
  Copyright (c) 2014 MediaTek Inc.  All right reserved.

  This library is free software; you can redistribute it and/or
  modify it under the terms of the GNU Lesser General Public
  License as published by the Free Software Foundation; either
  version 2.1 of the License..

  This library is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
   See the GNU Lesser General Public License for more details.
*/

#include "LDHT.h"
#include <LBT.h>
#include <LBTServer.h>
#define SPP_SVR "ARD_SPP" // it is the server's visible name, customize it yourself.
#define ard_log Serial.printf

# define DHTPIN 9 // what pin we're connected to
# define DHTTYPE DHT11 // using DHT11 sensor

LDHT dht(DHTPIN, DHTTYPE);

float tempC = 0.0, Humi = 0.0;
int lightPin = A0; //define a pin for Photo resistor
int pReading;

int read_size = 0;
void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  dht.begin();
  ard_log("start BTS\n");

  bool success = LBTServer.begin((uint8_t*)SPP_SVR);
  if( !success )
  {
      ard_log("Cannot begin Bluetooth Server successfully\n");
      // don't do anything else
      delay(0xffffffff);
  }
  else
  {
      LBTDeviceInfo info;
      if (LBTServer.getHostDeviceInfo(&info))
      {
          ard_log("LBTServer.getHostDeviceInfo [%02x:%02x:%02x:%02x:%02x:%02x]", 
            info.address.nap[1], info.address.nap[0], info.address.uap, info.address.lap[2], info.address.lap[1], info.address.lap[0]);
      }
      else
      {
          ard_log("LBTServer.getHostDeviceInfo failed\n");
      }
      ard_log("Bluetooth Server begin successfully\n");
  }
 
  // waiting for Spp Client to connect
  bool connected = LBTServer.accept(1800);
 
  if( !connected )
  {
      ard_log("No connection request yet\n");
      // don't do anything else
      delay(0xffffffff);
  }
  else
  {
      ard_log("Connected\n");
  }
  
}

int sent = 0;
int i=0;
void loop() {
  if (!sent)
  {
      char buffer[32] = {0};
      char s[50] = "";
      //int read_size = LBTServer.read((uint8_t*)buffer, 32);
      //vm_log_info("spec read size [%d][%s]", read_size);

      // Reading temperature or humidity takes about 250 milliseconds!
      // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
      if (dht.read()) {
        
        tempC = dht.readTemperature();
        Humi = dht.readHumidity();

        Serial.println("------------------------------");
        Serial.print("Temperature Celcius = ");
        Serial.print(dht.readTemperature());
        Serial.println("C");
        
        sprintf(s, "Temperature = %2.f C", dht.readTemperature());
        char* buffer_temp = s;
        
        int write_size = LBTServer.write((uint8_t*)buffer_temp, strlen(buffer_temp));

        Serial.print("Humidity = ");
        Serial.print(dht.readHumidity());
        Serial.println("%");

        sprintf(s, "Humidite = %2.f %%", dht.readHumidity());
        char* buffer_hum = s;
        
        write_size = LBTServer.write((uint8_t*)buffer_hum, strlen(buffer_hum));

        Serial.print("luminosité = ");
        pReading = analogRead(lightPin);
        Serial.println(pReading);
        
        sprintf(s, "Luminosite = %d", pReading);
        char* buffer_lum = s;
        
        write_size = LBTServer.write((uint8_t*)buffer_lum, strlen(buffer_lum));

        /*Serial.print("Temperature Fahrenheit = ");
        Serial.print(dht.readTemperature(false));
        Serial.println("F");

        Serial.print("HeatIndex = ");
        Serial.print(dht.readHeatIndex(tempC, Humi));
        Serial.println("C");

        Serial.print("DewPoint = ");
        Serial.print(dht.readDewPoint(tempC, Humi));
        Serial.println("C");*/
      }
      //ard_log("write_size [%d]", write_size);

      sprintf(s, "%d", i);
      char* buffer_val = s;
      
      int write_size = LBTServer.write((uint8_t*)buffer_val, strlen(buffer_val));
      
      i++;
      sent = 0;
  }
  //ard_log("loop server\n");
  delay(1000);




-----code du programme dht11 et photoresistance et precipitations------


#include <dht11.h>
#define DHT11PIN 4 // broche DATA -> broche 4
 
int check;
dht11 DHT11;
 
void setup()
{
  Serial.begin(9600);
  while (!Serial) {
    // wait for serial port to connect. Needed for native USB (LEONARDO)
  }
  //Serial.println("DHT11 programme d'essai ");
  //Serial.print("LIBRARY VERSION: ");
  //Serial.println(DHT11LIB_VERSION);
  //Serial.println();
}
 
void loop()
{
  check= DHT11.read(DHT11PIN);
  //Serial.print("lecture capteur: ");
  switch (check)
  {
    case DHTLIB_OK:
      //Serial.println("OK");
      break;
    case DHTLIB_ERROR_CHECKSUM:
      //Serial.println("Checksum error");
      break;
    case DHTLIB_ERROR_TIMEOUT:
      //Serial.println("Time out error");
      break;
    default:
      //Serial.println("Unknown error");
      break;
  }
 
  //Serial.print("Humidité (%): ");
  Serial.print((float)DHT11.humidity, 2);
  Serial.print("|");
  //Serial.print("Température (°C): ");
  Serial.print((float)DHT11.temperature, 2);
  
  int valeur = analogRead(A0);
  Serial.print("|");
  Serial.print(valeur);
  
  int pluie = analogRead(A3);
  Serial.print("|");
  Serial.print(pluie);
  Serial.println("");
 
  delay(2000);
}