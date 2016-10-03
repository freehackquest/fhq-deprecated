#!/usr/bin/python

import requests
import uuid
import getpass
import re
from FHQFrontEndLib import FHQFrontEndLib

print("1 http://fhq.sea-kg.com/api/")
print("2 http://fhq.keva.su/api/")
print("3 http://localhost/fhq/api/")
numsrv = raw_input("Please choose server: ")
url = ''
if numsrv == '1':
	url = 'http://fhq.sea-kg.com/api/'
elif numsrv == '2':
	url = 'http://fhq.keva.su/api/'
elif numsrv == '3':
	url = 'http://localhost/fhq/api/'
else:
	url = 'http://fhq.keva.su/api/'

print "Choosed: ", url

# login
email = raw_input("Email: ")
password = getpass.getpass('Password: ')
api = FHQFrontEndLib(url)
if not api.security.login(email, password):
	exit(1)
print('Your token: ' + api.token)

choosed_game = ''
choosed_quest = ''
choosed_gameid = 0

while True:
	command = raw_input(choosed_game + "/" + choosed_quest + "> ")
	if command == 'exit':
		exit(1)
	elif re.match(r'^games list$', command):
		print ""
		glist = api.games.list()
		for key, value in glist['data'].iteritems():
			print(value['id'] + ': ' + value['title'])
		print ""
	elif re.match(r'^choose game ([0-9]+)$', command):
		match = re.match(r'^choose game ([0-9]+)$', command)
		choosed_gameid = match.group(1)
		game = api.games.choose(choosed_gameid)
		choosed_game = game['data']['title']
		print('Choosed game ' + game['data']['title'])
		print ""
	elif re.match(r'^quests list$', command):
		print ""
		quests = api.quests.list({'filter_completed' : True, 'filter_open' : True, 'filter_current' : True})
		formattablequests = '{:<8}|{:<15}|{:<20}|{:<10}|{:<5}'
		print formattablequests.format('Quest ID', 'Subject + Score', 'Name', 'Status', 'Solved')
		print formattablequests.format('--------', '---------------', '--------------------', '----------', '-----')
		for key, value in enumerate(quests['data']):
			print formattablequests.format(value['questid'], value['subject'] + ' ' + value['score'], value['name'], value['status'], value['solved'])
		print ""
	elif re.match(r'^quest show ([0-9]+)$', command):
		match = re.match(r'^quest show ([0-9]+)$', command)
		questid = match.group(1)
		quest = api.quests.get(questid)
		print '   Subject: ' + quest['data']['subject']
		print '     Score: ' + quest['data']['score']
		print '      Name: ' + quest['data']['name']
		print '    Author: ' + quest['data']['author']
		print '      Text: '
		print quest['data']['text']
		print ""
	elif re.match(r'^quest pass ([0-9]+) (.*)$', command):
		match = re.match(r'^quest pass ([0-9]+) (.*)$', command)
		questid = match.group(1)
		answer = match.group(2)
		result = api.quests.trypass(questid, answer)
		if result['result'] == 'ok':
			print "quest passed"
		else:
			print result['error']['message']

	elif command == 'help':
		print ""
		print "help - this"
		print "exit - exit from command line"
		print "games list - list of games"
		print "choose game <number> - choose game"
		print "quests list - list of quests"
		print "quest show <number> - list of quests"
		print "quest pass <number> <answer> - list of quests"
		print ""
	else:
		print "unknown command"

exit(1)
