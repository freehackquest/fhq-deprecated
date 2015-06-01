#!/usr/bin/python

import requests
import uuid 

class FHQFrontEndLib:
	def __init__(self, url):
		self.token = ""
		self.url = url
		self.gameid = 0

	def sendrequest(self, path, params):
		# token
		if self.token != '':
			params['token'] = self.token

		# print params
		# todo: try catch	
		resp = {'result' : 'fail'}
		r = requests.post(self.url + path, params)
		if r.headers['content-type'] == 'application/json':
			try:
				resp = r.json()
			except ValueError:
				print r.text
				raise Exception('invalid json')

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
		
	def quests_insert(self, params):
		resp = self.sendrequest('/api/quests/insert.php', params)
		return resp

	def quests_get(self, questid):
		resp = self.sendrequest('/api/quests/get.php', { 'taskid' : questid })
		return resp

	def quests_take(self, questid):
		resp = self.sendrequest('/api/quests/take.php', { 'questid' : questid})
		return resp
		
	def quests_pass(self, questid, answer):
		resp = self.sendrequest('/api/quests/pass.php', { 'questid' : questid, 'answer' : answer })
		return resp
		
	def quests_delete(self, questid):
		resp = self.sendrequest('/api/quests/delete.php', { 'questid' : questid })
		return resp

email = 'admin@fhq.keva.su'
password = 'admin'

# login
api = FHQFrontEndLib('http://localhost/fhq')
if not api.login(email, password):
	exit(1)
print(api.token)

# game list
glist = api.games_list()
gameid = glist['current_game']

for key, value in glist['data'].iteritems():
	print(value['id'] + ': ' + value['title'])
	if gameid == 0:
		gameid = value['id']

# game choose
game = api.games_choose(gameid)
print('Choosed game ' + game['data']['title'])

# quest list
quests = api.quests_list()

for key, value in enumerate(quests['data']):
	print(value['questid'] + ': ' + value['name'])

# create new quest
new_quest = api.quests_insert({ 'quest_uuid' : str(uuid.uuid4()),
	'name' : 'test',
	'text' : 'test',
	'score' : 100,
	'min_score' : 0,
	'subject' : 'trivia',
	'idauthor' : 0,
	'author' : 'admin',
	'answer' : 'test',
	'state' : 'open',
	'description_state' : 'ddd'
});

# print new_quest
questid = new_quest['data']['quest']['id']

# quest get
print api.quests_get(questid)

# quests_update todo

# quest take
print api.quests_take(questid)

# quest pass
api.quests_pass(questid, 'test1')
api.quests_pass(questid, 'test')

# quest delete
print api.quests_delete(questid)

# scoreboard
