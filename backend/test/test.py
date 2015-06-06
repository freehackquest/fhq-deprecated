#!/usr/bin/python

import requests
import uuid 

# api for work security
class FHQSecurity:
	def  __init__(self, parent):
		self.parent = parent
	def login(self, email, password):
		resp = self.parent.sendrequest('/security/login.php', {"email": email, "password": password})
		if resp['result'] == 'fail':
			return False
		self.parent.token = resp['data']['token']
		return True
	#def logoff(self):

# api for work with games
class FHQGames:
	def  __init__(self, parent):
		self.parent = parent
	
	def list(self):
		resp = self.parent.sendrequest('/games/list.php', {})
		return resp
		
	def choose(self, gameid):
		resp = self.parent.sendrequest('/games/choose.php', {'id' : gameid})
		return resp

	# def get
	# def insert
	# def update
	# def scoreboard
	# def updaterules
	# def update_score
	# def upload_logo

class FHQQuests:
	def  __init__(self, parent):
		self.parent = parent

	def list(self):
		resp = self.parent.sendrequest('/quests/list.php', {})
		return resp
		
	def insert(self, params):
		resp = self.parent.sendrequest('/quests/insert.php', params)
		return resp

	def get(self, questid):
		resp = self.parent.sendrequest('/quests/get.php', { 'taskid' : questid })
		return resp

	def take(self, questid):
		resp = self.parent.sendrequest('/quests/take.php', { 'questid' : questid})
		return resp
		
	def trypass(self, questid, answer):
		resp = self.parent.sendrequest('/quests/pass.php', { 'questid' : questid, 'answer' : answer })
		return resp
		
	def delete(self, questid):
		resp = self.parent.sendrequest('/quests/delete.php', { 'questid' : questid })
		return resp

class FHQFrontEndLib:
	def __init__(self, url):
		self.token = ""
		self.url = url
		self.gameid = 0
		self.security = FHQSecurity(self)
		self.games = FHQGames(self)
		self.quests = FHQQuests(self)
		
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

email = 'admin@fhq.keva.su'
password = 'admin'

# login
api = FHQFrontEndLib('http://localhost/fhq/api/')
if not api.security.login(email, password):
	exit(1)
print(api.token)

# game list
glist = api.games.list()
gameid = glist['current_game']

for key, value in glist['data'].iteritems():
	print(value['id'] + ': ' + value['title'])
	if gameid == 0:
		gameid = value['id']

# game choose
game = api.games.choose(gameid)
print('Choosed game ' + game['data']['title'])

# quest list
quests = api.quests.list()

for key, value in enumerate(quests['data']):
	print(value['questid'] + ': ' + value['name'])

# create new quest
new_quest = api.quests.insert({ 'quest_uuid' : str(uuid.uuid4()),
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
print api.quests.get(questid)

# quests.update todo

# quest take
print api.quests.take(questid)

# quest pass
api.quests.trypass(questid, 'test1')
api.quests.trypass(questid, 'test')

# quest delete
print api.quests.delete(questid)

# scoreboard
