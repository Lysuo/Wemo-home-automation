import os, time
from random import randint
import subprocess

#proc = subprocess.Popen(["cat", "/etc/services"], stdout=subprocess.PIPE, shell=True)
#(out, err) = proc.communicate()
#print "program output:", out

agentuser = ''
ip = '192.168.0.126'
port = '49153'
url = 'http://'+ip+':'+port+'/upnp/control/basicevent1'

grepCmdBS = ' | grep "<BinaryState"  | cut -d">" -f2 | cut -d "<" -f1'
grepCmdFN = ' | grep "<FriendlyName"  | cut -d">" -f2 | cut -d "<" -f1'
grepCmdSS = ' | grep "<SignalStrength"  | cut -d">" -f2 | cut -d "<" -f1'

# headers
headers = ['\'Accept: \'', '\'Content-type: text/xml; charset=\"utf-8\"\'']

# data constant enveloppe
dataHead = '\'<?xml version=\"1.0\" encoding=\"utf-8\"?><s:Envelope xmlns:s=\"http://schemas.xmlsoap.org/soap/envelope/\" s:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\"><s:Body>'
dataEnd = '</s:Body></s:Envelope>\''

# start building the request
req = "curl -0"
req += " -A " + agentuser
for e in headers:
  req += " -H " + e


def getResponse(inCmd):
  proc = subprocess.Popen([inCmd], stdout=subprocess.PIPE, shell=True)
  (out, err) = proc.communicate()
  print "program output:", out
  return out

# getState function, retrieve state ON or OFF
def getstate():
  data = dataHead + '<u:GetBinaryState xmlns:u=\"urn:Belkin:service:basicevent:1\"><BinaryState>1</BinaryState></u:GetBinaryState>' + dataEnd

  request = req + " -H " +  '\'SOAPACTION: \"urn:Belkin:service:basicevent:1#GetBinaryState\"\''
  request += " --data " + data
  request += " -s " + url
  
  out = getResponse(request + grepCmdBS)
  print out


# turn the device ON
def on():
  data = dataHead + '<u:SetBinaryState xmlns:u=\"urn:Belkin:service:basicevent:1\"><BinaryState>1</BinaryState></u:SetBinaryState>' + dataEnd

  request = req + " -H " +  '\'SOAPACTION: \"urn:Belkin:service:basicevent:1#SetBinaryState\"\''
  request += " --data " + data
  request += " -s " + url


# turn the device OFF
def off():
  data = dataHead + '<u:SetBinaryState xmlns:u=\"urn:Belkin:service:basicevent:1\"><BinaryState>0</BinaryState></u:SetBinaryState>' + dataEnd

  request = req + " -H " +  '\'SOAPACTION: \"urn:Belkin:service:basicevent:1#SetBinaryState\"\''
  request += " --data " + data
  request += " -s " + url

  
# get the signal strenght, between 0 and 100 
def signalstrength():
  data = dataHead + '<u:GetSignalStrength xmlns:u=\"urn:Belkin:service:basicevent:1\"><SignalStrength>0</SignalStrength></u:GetSignalStrength>' + dataEnd

  request = req + " -H " +  '\'SOAPACTION: \"urn:Belkin:service:basicevent:1#GetSignalStrength\"\''
  request += " --data " + data
  request += " -s " + url


# turn the device OFF
def friendlyname():
  data = dataHead + '<u:GetFriendlyName xmlns:u=\"urn:Belkin:service:basicevent:1\"><FriendlyName>0</FriendlyName></u:GetFriendlyName>' + dataEnd

  request = req + " -H " +  '\'SOAPACTION: \"urn:Belkin:service:basicevent:1#GetFriendlyName\"\''
  request += " --data " + data
  request += " -s " + url

if __name__ == '__main__':

  print "\nGETSTATE"
  getstate()
  signalstrength()
  friendlyname()

  while True:
#    print "\n\nTURN ON"
    on()
    time.sleep(randint(5,120))

#    print "\nTURN OFF"
    off()
    time.sleep(randint(5,120))
