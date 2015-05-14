import time
import os

path = "/home/git/wemo-home-automation/"
command = ["ON", "OFF", "GETSTATE"]
ip = "192.168.0.126"

while True:
  print command[0]
  os.system(path+"wemo "+ip+" "+command[0])
  print command[2]
  os.system(path+"wemo "+ip+" "+command[2])

  # sleep
  time.sleep(5)
  print command[1]
  os.system(path+"wemo "+ip+" "+command[1])
  print command[2]
  os.system(path+"wemo "+ip+" "+command[2])

  # sleep
  time.sleep(5)
