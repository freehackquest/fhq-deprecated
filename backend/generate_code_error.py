#!/usr/bin/env python
import os
import re

print 'Generate code error'

PATH="./src/"
listcpp = [os.path.join(dp, f) for dp, dn, filenames in os.walk(PATH) for f in filenames if os.path.splitext(f)[1] == '.cpp']

errorcodes = []
for cpp in listcpp:
	text_file = open(cpp, "r")
	lines = text_file.readlines()
	
	for line in lines:		
		# print line
	
		# matchObj = re.match( r'.*setErrorResponse[ ]*\(\w{1,},(\d{1,}),.*', line, re.M|re.I)
		matchObj = re.match( r'.*setErrorResponse[ ]*\([\w ]*,[ ]*(\d{1,}).*', line, re.M|re.I)
		if matchObj:
			# print matchObj.group()
			errorcodes.append(int(matchObj.group(1)))
	text_file.close()

errorcodes.sort()
tmpcode=0
for code in errorcodes:
	if code == tmpcode:
		print "code used twice: ", code
	tmpfreecode=tmpcode+1
	if code != tmpcode and code != tmpfreecode and tmpfreecode >= 1000:
		print "code are not used: ", tmpfreecode
	tmpcode = code

# print errorcodes
