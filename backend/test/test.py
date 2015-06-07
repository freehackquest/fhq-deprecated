#!/usr/bin/python

import requests
import uuid 
from FHQFrontEndLib import FHQFrontEndLib

email = 'admin@fhq.keva.su'
password = 'admin'

# login
api = FHQFrontEndLib('http://localhost/fhq/api/')
if not api.security.login(email, password):
	exit(1)
print(api.token)


# insert new game
new_game = api.games.insert({
	'uuid' : str(uuid.uuid4()),
	'title' : 'test',
	'logo' : '',
	'type_game' : 'jeopardy',
	'date_start' : '2015-01-01 00:00:00',
	'date_stop' : '2015-01-02 00:00:00',
	'date_restart' : '2015-01-03 00:00:00',
	'description' : 'test',
	'state' : 'unlicensed-copy',
	'form' : 'online',
	'organizators' : 'test'
});

gameid = new_game['data']['game']['id']

api.games.update({
	'id' : gameid,
	'title' : 'test1',
	'type_game' : 'jeopardy',
	'date_start' : '2015-01-01 00:00:00',
	'date_stop' : '2015-01-02 00:00:00',
	'date_restart' : '2015-01-03 00:00:00',
	'description' : 'test1',
	'state' : 'unlicensed-copy',
	'form' : 'online',
	'organizators' : 'test1',
});


api.games.update_rules(gameid, 'new_rules')

# game list
glist = api.games.list()
for key, value in glist['data'].iteritems():
	print(value['id'] + ': ' + value['title'])

# game choose
game = api.games.choose(gameid)
print('Choosed game ' + game['data']['title'])
api.games.get(gameid)

# quest list
quests = api.quests.list()

for key, value in enumerate(quests['data']):
	print(value['questid'] + ': ' + value['name'])

# create new quest
new_quest = api.quests.insert({
	'quest_uuid' : str(uuid.uuid4()),
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
api.quests.get(questid)

# quests.update todo

# quest take
api.quests.take(questid)

# quest pass
api.quests.trypass(questid, 'test1')
api.quests.trypass(questid, 'test')

# update score
api.games.update_score(gameid)

# quest delete
api.quests.delete(questid)

# scoreboard

# game delete
api.games.delete(gameid)
