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
	# def logoff(self):
	# register
	# restore password

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

	def get(self, gameid):
		resp = self.parent.sendrequest('/games/get.php', {'id' : gameid})
		return resp

	def insert(self, params):
		resp = self.parent.sendrequest('/games/insert.php', params)
		return resp

	def update(self, params):
		resp = self.parent.sendrequest('/games/update.php', params)
		return resp

	def delete(self, gameid):
		resp = self.parent.sendrequest('/games/delete.php', {'id' : gameid})
		return resp

	def update_rules(self, gameid, rules):
		resp = self.parent.sendrequest('/games/update_rules.php', {'id' : gameid, 'rules' : rules})
		return resp
		
	def update_score(self, gameid):
		resp = self.parent.sendrequest('/games/update_score.php', {'gameid' : gameid})
		return resp

	# def scoreboard
	# def upload_logo

class FHQQuests:
	def  __init__(self, parent):
		self.parent = parent

	def list(self, params):
		resp = self.parent.sendrequest('/quests/list.php', params)
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
			print ' *** FAIL *** '
			print ' * URL: ' + self.url + path
			print ' * Response:' + r.text
			print(' * Error.Code: ' + str(resp['error']['code']))
			print(' * Error.Message: ' + str(resp['error']['message']))
			print ' *************'
			return resp
		return resp
