#include <EEPROM.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <Wire.h>
// #include <Adafruit_INA219.h>
#include <stdlib.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include "DHT.h"
#define DHTPIN D5
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 16, 2);

// Connect to the WiFi
const char* ssid = "wireless2023";
const char* password = "rahasia2023";
const char* mqtt_server = "riset.revolusi-it.com";
const char* user_mqtt="usm";
const char* pass_mqtt="rahasia234";

// const char* mqtt_server = "riset.revolusi-it.com";
const char* topik = "iot/kendali";

int stat_D0,stat_D1,stat_D2,stat_D3,stat_D4,stat_D5,stat_D6,stat_D7,stat_D8; 
 
WiFiClient espClient;
PubSubClient client(espClient);
String messages;
String messages2;

void callback(char* topic, byte* payload, unsigned int length) {

 Serial.print("Pesan dari MQTT [");
 Serial.print(topic);
 Serial.print("] ");
 messages="";
 for (int i=0;i<length;i++) { // susun jadi string saja...
  char receivedChar = (char)payload[i]; 
  messages=messages+(char)payload[i]; // ambil pesannya masukkan dalam string
  }
  // set isi variabel lampu;
  if(messages=="D0=1") { stat_D0=1; }
  if(messages=="D0=0") { stat_D0=0; }
  if(messages=="D1=1") { stat_D1=1; }
  if(messages=="D1=0") { stat_D1=0; }
  if(messages=="D2=1") { stat_D2=1; }
  if(messages=="D2=0") { stat_D2=0; }
  if(messages=="D3=1") { stat_D3=1; }
  if(messages=="D3=0") { stat_D3=0; }
  if(messages=="D4=1") { stat_D4=1; }
  if(messages=="D4=0") { stat_D4=0; }
  if(messages=="D5=1") { stat_D5=1; }
  if(messages=="D5=0") { stat_D5=0; }
  if(messages=="D6=1") { stat_D6=1; }
  if(messages=="D6=0") { stat_D6=0; }
  if(messages=="D7=1") { stat_D7=1; }
  if(messages=="D7=0") { stat_D7=0; }
  if(messages=="D8=1") { stat_D8=1; }
  if(messages=="D8=0") { stat_D8=0; }
  Serial.println(messages);
}
 
 
void reconnect() {
 // Loop until we're reconnected
 while (!client.connected()) {
 Serial.print("Menghubungkan diri ke MQTT Server : "+(String)mqtt_server);
 // Attempt to connect
 if (client.connect("ESP8266 Client XXX",user_mqtt,pass_mqtt)) {
  Serial.println("connected");
  // ... and subscribe to topic
  client.subscribe(topik);
 } else {
  Serial.print("failed, rc=");
  Serial.print(client.state());
  Serial.println(" coba lagi dalam 5 detik...");
  // Wait 5 seconds before retrying
  delay(5000);
  }
 }
}


void cek_suhu_print_lcd()
{

  float h = dht.readHumidity();
  float t = dht.readTemperature();
  lcd.setCursor(0, 0);
  lcd.print("Suhu : " + String(t) + " C");
  lcd.setCursor(0, 1);
  lcd.print("Kelembaban:" + String(h));
}

String cek_suhu()
{
  float h = dht.readHumidity();
  float t = dht.readTemperature();
  lcd.setCursor(0, 0);
  lcd.print("Suhu : " + String(t) + " C");
  lcd.setCursor(0, 1);
  lcd.print("Kelembaban:" + String(h));
  return String(t)+","+String(h);
}

void eksekusi_pin()
{
// gunakan variabel global stat_D0 s/d stat_D8
digitalWrite(D0,stat_D0);
digitalWrite(D1,stat_D1);
digitalWrite(D2,stat_D2);
digitalWrite(D3,stat_D3);
digitalWrite(D4,stat_D4);
digitalWrite(D5,stat_D5);
digitalWrite(D6,stat_D6);
digitalWrite(D7,stat_D7);
digitalWrite(D8,stat_D8);
}

String jsonStatus()
{
  String messagesStatus;
  messagesStatus=  "{D0:"+(String)stat_D0+
                   ",D1:"+(String)stat_D1+
                   ",D2:"+(String)stat_D2+
                   ",D3:"+(String)stat_D3+
                   ",D4:"+(String)stat_D4+
                   ",D5:"+(String)stat_D5+
                   ",D6:"+(String)stat_D6+
                   ",D7:"+(String)stat_D7+
                   ",D8:"+(String)stat_D8+
                   "}";
                   
  return messagesStatus;
}

void konek_wifi()
{
WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected"); 
}

 // ------------------------------- begin --------------------------------------- //
void setup()
{
 Serial.begin(9600);
 
 client.setServer(mqtt_server, 1883);
 client.setCallback(callback);
 // setting pin nya  
 pinMode(D3, OUTPUT); pinMode(D4, OUTPUT); pinMode(D5, OUTPUT);  pinMode(D6, OUTPUT); pinMode(D7, OUTPUT);  pinMode(D8, OUTPUT);

Wire.begin();  // aktivasi D1, D2 sebagai i2c
  lcd.begin(16, 2);

  lcd.backlight();
  dht.begin();
}
 
void loop()
{

 if(WiFi.status() != WL_CONNECTED) { konek_wifi(); } // jika tidak konek wifi maka di reconnect
 if (!client.connected()) { reconnect(); } // reconnect apabila belum konek 
 
 client.loop();
 eksekusi_pin();
 messages2=cek_suhu();
 cek_suhu_print_lcd();
 // Serial.println(messages2);
 // perhatikan cara ngirim variabel string lewat client.publish ini gak bisa langsung...
 char attributes[100];
 messages2.toCharArray(attributes, 100);
 // cek_suhu_print_lcd();
// if(messages=="kirim_status") { client.publish(topik,attributes,true); }
client.publish(topik,attributes,true);
delay(1000);

}

