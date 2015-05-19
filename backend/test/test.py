#!/usr/bin/python

import requests

class FHQFrontEndLib:
	def __init__(self, url):
		self.token = ""
		self.url = url
		self.gameid = 0

	def sendrequest(self, path, params):
		# token
		if self.token != '':
			params['token'] = '81C45C52-FC4E-504B-D0DB-3BE65E3487A3' # self.token

		# todo: try catch	
		resp = {'result' : 'fail'}
		r = requests.post(self.url + path, params)
		if r.headers['content-type'] == 'application/json':
			resp = r.json()

		if resp['result'] == 'fail':
			print(resp['error']['code'])
			print(resp['error']['message'])
			return resp
		return resp

	def login(self, email, password):
		resp = self.sendrequest('/api/security/login.php', {"email": email, "password": password})
		if resp['result'] == 'fail':
			return False
		self.token = resp['data']['token']
		return True

	def games_list(self):
		resp = self.sendrequest('/api/games/list.php', {})
		return resp
		
	def games_choose(self, gameid):
		resp = self.sendrequest('/api/games/choose.php', {'id' : gameid})
		return resp
	def quests_list(self):
		resp = self.sendrequest('/api/quests/list.php', {})
		return resp

email = 'admin@fhq.keva.su'
password = 'admin'

api = FHQFrontEndLib('http://localhost/fhq')
if not api.login(email, password):
	exit(1)

print(api.token)

glist = api.games_list()
gameid = glist['current_game']

for key, value in glist['data'].iteritems():
	print(value['id'] + ': ' + value['title'])
	if gameid == 0:
		gameid = value['id']

game = api.games_choose(gameid)

print('Choosed game ' + game['data']['title'])


print(api.quests_list())
