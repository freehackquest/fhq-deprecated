#!/usr/bin/env python
import os
import re
from operator import attrgetter

print 'Generate code error'

PATH="./www/api/"
listcpp = [os.path.join(dp, f) for dp, dn, filenames in os.walk(PATH) for f in filenames if os.path.splitext(f)[1] == '.php']

errorcodes = []
errorcodes2 = []
for cpp in listcpp:
	text_file = open(cpp, "r")
	lines = text_file.readlines()
	nLine = 0
	for line in lines:
		nLine = nLine + 1
		# print line
		# APIHelpers::showerror(912, 'only for admin');
		# matchObj = re.match( r'.*setErrorResponse[ ]*\(\w{1,},(\d{1,}),.*', line, re.M|re.I)
		matchObj = re.match( r'.*APIHelpers::showerror[ ]*\([ ]*(\d{1,})[ ]*,.*', line, re.M|re.I)
		if matchObj:
			# print matchObj.group()
			nCode = int(matchObj.group(1))
			objCode = {"code": nCode, "file": cpp, "line": nLine, "text": line}
			errorcodes.append(nCode)
			errorcodes2.append(objCode)
			#if nCode < 1000:
			#	print "Code must be more then 999: ", objCode
			# print nLine
	text_file.close()




# len(dict)
sort = False
while sort == False:
	sort = True
	for index in range(len(errorcodes2)-1):
		objCode1 = errorcodes2[index]
		objCode2 = errorcodes2[index+1]
		if objCode1['code'] > objCode2['code']:
			errorcodes2[index] = objCode2
			errorcodes2[index+1] = objCode1
			sort = False

tmpcode=0
for codeObj in errorcodes2:
	code = codeObj['code']
	if code < 1000 or code > 9999:
		print "Code", code, "must be 999 < code < 10000: ", objCode['file'], ":", objCode['line']
	if code == tmpcode:
		print "Code", code ,"used twice: ", objCode['file'], ":", objCode['line']
	tmpfreecode=tmpcode+1
	if code != tmpcode and code != tmpfreecode and tmpfreecode >= 1000:
		print "Code", tmpfreecode, "are not used: ", tmpfreecode
	tmpcode = code

#for objCode in errorcodes2:
#	print objCode['code']



# sorted(errorcodes2, key='code')


	
# print errorcodes
