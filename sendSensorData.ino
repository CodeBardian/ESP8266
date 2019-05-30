#include <LowPower.h>
#include <SoftwareSerial.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>

#define DHTPIN 2 
#define DHTTYPE DHT11

SoftwareSerial esp(6, 7);// RX, TX
DHT dht(DHTPIN, DHTTYPE);

String website ="example.net"  // change to your host website
String url ="example.php"      // change to url which processes get-request
int sleepTime = 2 ;  // defines the pause time between sending in minutes

String temperature="";
String humidity="";

void setup() {
  esp.begin(115200);
  Serial.begin(9600);
  dht.begin();
}

void loop () {
  temperature = String(dht.readTemperature());
  humidity = String(dht.readHumidity());
  httppost();
  for(int i = 0; i<(sleepTime*60)/8; i++){     
    LowPower.powerDown(SLEEP_8S, ADC_OFF, BOD_OFF); 
  }
}

void httppost () {
  esp.println("AT+CIPSTART=\"TCP\",\""+website+"\",80");
  delay(1000);
  if( esp.find("OK")) {
    Serial.println("TCP connection ready");
  }
  else Serial.println("TCP connection failed");
  delay(1000);
  String getRequest = "GET /"+url+"?temp="+temperature+"&hum="+humidity+" HTTP/1.1\r\nHost:"+website+"\r\n\r\n";
  String sendCmd = "AT+CIPSEND=";
  esp.print(sendCmd);
  esp.println(getRequest.length() );
  delay(500);
  if(esp.find(">")) { 
    Serial.println("Sending.."); 
    esp.print(getRequest);
    delay(1000);
    if( esp.find("SEND OK")) { 
      Serial.println("Packet sent");
      while (esp.available()) {
        String tmpResp = esp.readString();
        Serial.println(tmpResp);
      }
      esp.println("AT+CIPCLOSE");
      Serial.println("connection closed");
    }
  }
}
