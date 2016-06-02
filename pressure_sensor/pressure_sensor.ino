/*
  ThingSpeak Client

  Example code to read a sensor value and log it to ThingSpeak. 

  You need to make 2 modifications to this code -
  
  1) Fill in your API key value, which you'll find on thingspeak.com, into APIKey.
  2) Change the code in readSensorValues() to read the type of sensor you are using.
  
*/

#include <Bridge.h>
#include <HttpClient.h>
#include <ThingSpeak.h>

int P;          // temperature readings are returned in int format
int FSR_Pin0 = A1; //analog pin 1
int FSR_Pin1 = A2; //analog pin 2
int FSR_Pin2 = A3; //analog pin 3
int FSR_Pin3 = A4; //analog pin 4
int FSR_Pin4 = A5; //analog pin 5

const unsigned long postingInterval = 8*1000;  //delay between updates to ThingSpeak
String APIkey ="BXQ26U0482V1UTP4";              // Enter YOUR key here
int data = 0;

//
// setup
//
void setup() 
{
  // Initialize the Bridge and the Serial
  Bridge.begin();
  Serial.begin(9600);

  // wait a while for the Serial port to connect.
  int cnt = 0;
  while (!Serial && cnt++ < 10)
  {
    delay(1000);
  }; 
}

//
// Main loop
//
void loop () 
{
  // Read data values
  int dataNow = readSensorValues();

 // check wifi
  checkWifi();

  
   String dataString = " currentWeight = ";
   dataString += String(dataNow);
   Serial.println(dataString);
  // Log to MyWebsite
  //if current weight < former, check which clothes was selected
  if(dataNow < data){
    // send the change weight to server
    int weight = data-dataNow;
    // make a string of data to log:
    String dataString;

    dataString = "weight=";
    dataString += String(weight);
  
    sendDataToMyWebsite(dataString);
  }
  //no matter current weight is heavier or lighter, update the data
  data = dataNow;
  
  // Pause
  delay(postingInterval);
}


//check wifi
void checkWifi()
{
   // Check wifi
  Process wifiCheck;  // initialize a new process

  wifiCheck.runShellCommand("/usr/bin/pretty-wifi-info.lua");  // command you want to run

  // while there's any characters coming back from the
  // process, print them to the serial monitor:
  while (wifiCheck.available() > 0) {
    char c = wifiCheck.read();
    Serial.print(c);
  }

  Serial.println();
}
//
// readSensorValues
//
// This function must return a string in the format "field1=123&field2=456&field3=789"
//
int readSensorValues()
{
  // REPLACE THIS CODE WITH YOUR OWN
  int P0 = analogRead(FSR_Pin0); 
  int P1 = analogRead(FSR_Pin1); 
  int P2 = analogRead(FSR_Pin2); 
  int P3 = analogRead(FSR_Pin3); 
  int P4 = analogRead(FSR_Pin4); 

  int P = P0+P1+P2+P3+P4;
  Serial.println(P0);
  Serial.println(P1);
  Serial.println(P2);
  Serial.println(P3);
  Serial.println(P4);
  delay(250); //just here to slow down the output for easier reading
  
  // print the results to the serial monitor:
  Serial.print("P = " );                       
  Serial.println(P);   

  return P;  
}

//
// sendDataToThingSpeak
//
void sendDataToMyWebsite(String thisData)
{
    HttpClient client;
    String clientStr;

    Serial.println("connecting...");
    
    // Sometimes the DNS lookup doesn't work so it's best to use ThingSpeak's
    // static IP address.
//    clientStr = "https://184.106.153.149/update?key=" + APIkey + "&";
    clientStr = "http://playground.eca.ed.ac.uk/~s1520365/DIP/update.php?user=1&" + thisData;
//    clientStr = "https://api.thingspeak.com/update?key=" + APIkey + "&";
    
    Serial.println(clientStr);
    
    // The next line sends the data to ThingSpeak
    client.get(clientStr);
    Serial.println("...done");

    // Read the entry number returned by ThingSpeak
    String result;
    while (client.available()) 
    {
      char c = client.read();
      result += String(c);
    }
    
    Serial.println("---");
}

