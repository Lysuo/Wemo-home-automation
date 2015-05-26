import os, time, sys
from random import randint
import subprocess

agentuser = ''

#ip = '192.168.0.100'
#ip = '192.168.0.102'
#ip = '192.168.0.120'

#port = '49153'

# headers
headers = ['\'Accept: \'', '\'Content-type: text/xml; charset=\"utf-8\"\'']

# data constant enveloppe
dataHead = '\'<?xml version=\"1.0\" encoding=\"utf-8\"?><s:Envelope xmlns:s=\"http://schemas.xmlsoap.org/soap/envelope/\" s:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\"><s:Body>'
dataEnd = '</s:Body></s:Envelope>\''

# start building the request
req = "curl --connect-timeout 1 -0"
req += " -A " + agentuser
for e in headers:
  req += " -H " + e

def getResponse(inCmd):
  proc = subprocess.Popen([inCmd], stdout=subprocess.PIPE, shell=True)
  (out, err) = proc.communicate()
  return out

def getInfos(service, action, name, value, output):

  data = dataHead + '<u:' +action+ ' xmlns:u=\"urn:Belkin:service:' +service+ ':1\">'
  for i, e in enumerate(name):
    data += '<' +name[i]+ '>' +value[i]+ '</' +name[i]+ '>'
  data += '</u:' +action+ '>' + dataEnd

  URL = 'http://'+ip+':'+port+'/upnp/control/' +service+ '1'
  request = req + " -H " +  '\'SOAPACTION: \"urn:Belkin:service:' +service+ ':1#' +action+ '\"\''
  request += " --data " + data
  request += " -s " + URL 

  if len(output) > 0:
    strOut = ""
    for e in output:
      grepCmd = ' | grep "<' +e+ '"  | cut -d">" -f2 | cut -d "<" -f1'
      strOut += e + " : " + getResponse(request + grepCmd)
    strOut += ""
    print "\n################## " +action + " ############## \n" + strOut

  else :
    out = getResponse(request)
    print out


def on():
  print "\n TURN ON"
  getInfos('basicevent', 'SetBinaryState', ['BinaryState'], ['1'], ['BinaryState'])

def off():
  print "\n TURN OFF"
  getInfos('basicevent', 'SetBinaryState', ['BinaryState'], ['0'], ['BinaryState'])

def getState():
  getInfos('basicevent', 'GetBinaryState', ['BinaryState'], ['1'], ['BinaryState'])

def getFriendlyName():
  getInfos('basicevent', 'GetFriendlyName', ['FriendlyName'], ['1'], ['FriendlyName'])


if __name__ == '__main__':

  if len(sys.argv) == 4:
    ip = sys.argv[1]
    port = sys.argv[2]
    getInfos('basicevent', 'ChangeFriendlyName', ['FriendlyName'], [sys.argv[3]], ['FriendlyName'])
    getState()
    getFriendlyName()

  if len(sys.argv) == 3:
    ip = sys.argv[1]
    port = sys.argv[2]
    getState()
    getFriendlyName()

    getInfos('timesync', 'GetTime', [], [], [])
    getInfos('deviceinfo', 'GetDeviceInformation', ['DeviceInformation'], ['0'], [])

#    getInfos('rules', 'GetRulesDBPath', ['RulesDBPath'], ['0'], [])


  if len(sys.argv) == 1:
    tab = ['GetMacAddr', 'GetDeviceId', 'GetSmartDevInfo']
    out = [['MacAddr', 'SerialNo', 'PluginUDN'], ['DeviceId'], ['SmartDevInfo']]

    for i, e in enumerate(tab):
      getInfos('basicevent', e, [], [], out[i])

 
    print "##################"
    getFriendlyName()
    getState()
    on()
    getState()
    off()
    getState()

#  print "\n################"
#  getInfos('deviceinfo', 'GetDeviceInformation', ['DeviceInformation'], ['0'])

#  print "\n################"
#  getInfos('basicevent', 'GetHomeInfo', ['HomeInfo'], ['0'])

#  print "\n################"
#  getInfos('deviceinfo', 'GetRouterInformation', ['mac'], ['0'])

#  print "\n################"
#  getInfos('metainfo', 'GetMetaInfo', ['MetaInfo'], ['0'])

#  print "\n################"
#  getInfos('metainfo', 'GetExtMetaInfo', ['ExtMetaInfo'], ['0'])
