from flask import Flask, jsonify, request
import os, glob

app = Flask(__name__)

events = {
    "200": {
        "eventId":8,
        "year":28,
        "eventYear":4,
        "begin":"1970-01-04T00:00:00+01:00",
        "end":"1970-01-04T00:00:00+01:00",
        "registrationBegin":"1970-09-30T20:00:00+02:00",
        "registrationEnd":"1970-12-03T12:00:00+01:00",
        "report": None,
        "name":"Fyziklání online",
        "eventTypeId":9,
        "game": {'availablePoints': 150,'tasksOnBoard' : 6,'hardVisible' : False,"begin":"1970-01-04T20:00:00+01:00","end":"1970-01-04T22:00:00+01:00",'resultsVisible': True}
    },
    "201": {
        "eventId":94,
        "year":29,
        "eventYear":5,
        "begin":"2999-12-30T00:00:00+01:00",
        "end":"2999-12-31T00:00:00+01:00",
        "registrationBegin":"2099-12-30T00:00:00+01:00",
        "registrationEnd":"2099-12-31T00:00:00+01:00",
        "report": None,
        "name":"Fyziklání online",
        "eventTypeId":9,
        "game": {'availablePoints': 150,'tasksOnBoard' : 6,'hardVisible' : False,"begin":"2999-12-30T20:00:00+01:00","end":"2999-12-31T22:00:00+01:00",'resultsVisible': True}
    },    
    "202": {
        "eventId":8,
        "year":28,
        "eventYear":4,
        "begin":"1970-01-04T00:00:00+01:00",
        "end":"1970-01-04T00:00:00+01:00",
        "registrationBegin":"1970-09-30T20:00:00+02:00",
        "registrationEnd":"1970-12-03T12:00:00+01:00",
        "report": None,
        "name":"Fyziklání online",
        "eventTypeId":1,
        "game": {'availablePoints': 150,'tasksOnBoard' : 6,'hardVisible' : False,"begin":"1970-01-04T20:00:00+01:00","end":"1970-01-04T22:00:00+01:00",'resultsVisible': True}
    },
}

def getDsefEvents():
    dsefEvents = {}
    index = 0
    for filename in glob.glob(os.path.dirname(os.path.realpath(__file__)) + '/../../app/Modules/Dsef/ArchiveModule/templates/Default/simple*'):
        year, month = os.path.basename(filename).rstrip('.latte').lstrip('simple.').split('-')
        # generate event for every existing file
        dsefEvents[str(index)] = {
            "eventId":index,
            "year":index,
            "eventYear":index,
            "begin":f"{year}-{month}-04T00:00:00+00:00",
            "end":f"{year}-{month}-04T00:01:00+00:00",
            "registrationBegin":"1970-01-01T00:00:00+00:00",
            "registrationEnd":"1970-01-01T00:00:00+00:00",
            "report": None,
            "name":"Dsef",
            "eventTypeId":2
        }
        index += 1
    # add current event with ready registration
    dsefEvents[str(index)] = {
        "eventId":index,
        "year":index,
        "eventYear":index,
        "begin":"2099-12-04T00:00:00+00:00",
        "end":"2099-12-04T00:01:00+00:00",
        "registrationBegin":"2099-12-04T00:01:00+00:00",
        "registrationEnd":"2099-12-04T00:01:00+00:00",
        "report": None,
        "name":"Dsef",
        "eventTypeId":2
    }
    return dsefEvents

events.update(getDsefEvents())

def getTeams():
    states = ["participated", "disqualified", "applied", "pending", "approved", "canceled"]
    memberCountries = ["cs", "sk", "zz", None]
    teams = []
    teamIndex = 0
    for state in states:
        team = {
            "teamId":7047,
            "name":f"Team {teamIndex}",
            "status":state,
            "category":"A",
            "created":"1970-01-01T00:00:00+00:00",
            "phone":"+42012345679",
            "password":None,
            "points":24,
            "rankCategory":70,
            "rankTotal":224,
            "forceA":0,
            "gameLang":"cs",
            "members":[],
            "teachers":[]
        }
        teamIndex += 1
        memberIndex = 0
        for country in memberCountries:
            team["members"].append({
                "name":f"Test {memberIndex}",
                "personId":teamIndex * 5 + memberIndex,
                "email":"test@example.com",
                "schoolId":1,
                "schoolName":"MFF UK",
                "studyYear":1,
                "countryIso":country
            })
            memberIndex += 1
        teams.append(team)
    return teams

@app.route("/events/")
def getEventList():
    print(request.json)
    eventTypes = request.json["eventTypes"]
    if (eventTypes is None):
        return "Event type id not specified", 400
    print({key:events[key] for key in events if events[key].get("eventTypeId") in eventTypes})
    return {key:events[key] for key in events if events[key].get("eventTypeId") in eventTypes}

@app.route("/events/<id>/schedule")
def getSchedule(id):
    return '{}'

@app.route("/events/<id>/teams")
def getTeams(id):
    return '{}'


@app.route("/GetEvent")
def getEvent():
    eventId = request.args.get('event_id', default=None)
    if (eventId is None):
        return "Event id not specified", 400
    dsefEvents = getDsefEvents()
    if (eventId in dsefEvents):
        event = dsefEvents[eventId]
        event["teams"] = getTeams()
        return dsefEvents[eventId]
    if (eventId in events):
        event = events[eventId]
        event["teams"] = getTeams()
        return events[eventId]
    return "Invalid event id", 400