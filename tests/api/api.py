from flask import Flask, jsonify, request
from random import choice
from datetime import  datetime
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
        "eventId":202,
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
    "290":{
        "eventId":290,
        "year":4,
        "eventYear":4,
        "begin":"2015-08-02T00:00:00+02:00",
        "end":"2015-08-14T00:00:00+02:00",
        "registrationBegin":"2015-08-02T00:00:00+02:00",
        "registrationEnd":"2015-08-14T00:00:00+02:00",
        "registration":{
            "begin":"2015-08-02T00:00:00+02:00",
            "end":"2015-08-14T00:00:00+02:00"
            },
        "report":"<p>Report</p>",
        "reportNew":{
            "cs":"<p>reportcz</p>",
            "en":None
            },
        "description":{
            "cs":"DescriptionCz",
            "en":None
            },
        "name":"name290",
        "nameNew":{
            "cs":"namecz290",
            "en":"nameen290"
            },
        "eventTypeId":10,
        "place":None,
        "contestId":2
    },
    "291":{
        "eventId":291,
        "year":4,
        "eventYear":4,
        "begin":"2015-08-02T00:00:00+02:00",
        "end":"2015-08-14T00:00:00+02:00",
        "registrationBegin":"2015-08-02T00:00:00+02:00",
        "registrationEnd":"2015-08-14T00:00:00+02:00",
        "registration":{
            "begin":"2015-08-02T00:00:00+02:00",
            "end":"2015-08-14T00:00:00+02:00"
            },
        "report":"<p>Report</p>",
        "reportNew":{
            "cs":"<p>reportcz</p>",
            "en":None
            },
        "description":{
            "cs":"DescriptionCz",
            "en":None
            },
        "name":"name291",
        "nameNew":{
            "cs":"namecz290",
            "en":"nameen290"
            },
        "eventTypeId":11,
        "place":None,
        "contestId":2
    },
    "292":{
        "eventId":292,
        "year":4,
        "eventYear":4,
        "begin":"2015-08-02T00:00:00+02:00",
        "end":"2015-08-14T00:00:00+02:00",
        "registrationBegin":"2015-08-02T00:00:00+02:00",
        "registrationEnd":"2015-08-14T00:00:00+02:00",
        "registration":{
            "begin":"2015-08-02T00:00:00+02:00",
            "end":"2015-08-14T00:00:00+02:00"
            },
        "report":"<p>Report</p>",
        "reportNew":{
            "cs":"<p>reportcz</p>",
            "en":None
            },
        "description":{
            "cs":"DescriptionCz",
            "en":None
            },
        "name":"name292",
        "nameNew":{
            "cs":"namecz290",
            "en":"nameen290"
            },
        "eventTypeId":12,
        "place":None,
        "contestId":2
    },
    "293":{
        "eventId":293,
        "year":4,
        "eventYear":4,
        "begin":"2015-08-02T00:00:00+02:00",
        "end":"2015-08-14T00:00:00+02:00",
        "registrationBegin":"2015-08-02T00:00:00+02:00",
        "registrationEnd":"2015-08-14T00:00:00+02:00",
        "registration":{
            "begin":"2015-08-02T00:00:00+02:00",
            "end":"2015-08-14T00:00:00+02:00"
            },
        "report":"<p>Report</p>",
        "reportNew":{
            "cs":"<p>reportcz</p>",
            "en":None
            },
        "description":{
            "cs":"DescriptionCz",
            "en":None
            },
        "name":"name292",
        "nameNew":{
            "cs":"namecz290",
            "en":"nameen290"
            },
        "eventTypeId":15,
        "place":None,
        "contestId":2
    }
}


app.personID=0
def createPerson():
    app.personID+=1
    return {
                "name":f"Test {app.personID}",
                "personId":app.personID,
                "email":f"test{app.personID}@example.com",
                "code":None,
            "school":{"schoolId":1,"nameFull":"A School","name":"Gymnázium","nameAbbrev":"G","countryISO":choice(["CS", "SK", "ZZ"])},
            "studyYear":"H_2"}


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

def generateTeams():
    states = ["participated", "disqualified", "applied", "pending", "approved", "canceled"]
    teams = []
    teamIndex = 0
    for state in states:
        team = {
            "teamId":teamIndex,
            "name":f"Team {teamIndex}",
            "code":None,
            "state":state,
            "category":"A",
            "created":"1970-01-01T00:00:00+00:00",
            "phone":"+42012345679",
            "points":122,
            "rankCategory":8,
            "rankTotal":23,
            "rank":{"category":8,"total":23},
            "forceA":0,
            "gameLang":None,
            "place":None,
            "teachers":[createPerson()],
            "members":[]
            }
        for i in range(5):
            team["members"].append(createPerson())
        teams.append(team)
        teamIndex += 1
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
    return {
        "129": {
            "groupId": 129,
            "type": "teacher_present",
            "registration": {
                "begin": None,
                "end": "2023-02-10T23:59:00+01:00"
            },
            "name": {
                "cs": "Program pro učitele",
                "en": "Program for Teachers"
            },
            "eventId": 173,
            "start": "2023-02-10T10:00:00+01:00",
            "end": "2023-02-10T13:00:00+01:00",
            "items": {
                "347": {
                    "groupId": 129,
                    "itemId": 347,
                    "price": [],
                    "capacity": {
                        "total": 100,
                        "used": 23
                    },
                    "name": {
                        "cs": "Program pro učitele",
                        "en": "Program for Teachers"
                    },
                    "begin": "2023-02-10T10:00:00+01:00",
                    "end": "2023-02-10T13:00:00+01:00",
                    "description": {
                        "cs": "Alternativní program v průběhu soutěže",
                        "en": "Alternative program during the contest"
                    },
                    "longDescription": {
                        "cs": None,
                        "en": None
                    },
                    "available": True
                }
            }
        }
    }

@app.route("/events/<id>/teams")
def getTeams(id):
    return jsonify( generateTeams())


@app.route("/GetEvent")
def getEvent():
    eventId = request.args.get('event_id', default=None)
    if (eventId is None):
        return "Event id not specified", 400
    dsefEvents = getDsefEvents()
    if (eventId in dsefEvents):
        event = dsefEvents[eventId]
        event["teams"] = generateTeams()
        return dsefEvents[eventId]
    if (eventId in events):
        event = events[eventId]
        event["teams"] = generateTeams()
        return events[eventId]
    return "Invalid event id", 400

@app.route("/contests/<id>")
def getContest(id):
    return {
        "contestId":id,
        "contest":("vyfuk" if id==2 else "fykos"),
        "name":("VYFUK" if id==2 else "FYKOS"),
        "years":[
            {"year":1,
            "active":False,
            "begin":str(datetime.now().year-2)+"-09-01T00:00:00+02:00",
            "end":str(datetime.now().year-1)+"-08-31T23:59:59+02:00"},
            {"year":2,
            "active":True,
            "begin":str(datetime.now().year-1)+"-09-01T00:00:00+02:00",
            "end":str(datetime.now().year+1)+"-08-31T23:59:59+02:00"},
        ]}

@app.route("/contests/<id>/organizers")
def orgs(id):
    return {0:{
        "name":"jmeno",
        "personId":1,
        "email":"mail@mail.cz",
        "academicDegreePrefix":None,
        "academicDegreeSuffix":None,
        "career":"Studuje.",
        "contribution":None,
        "order":0,
        "role":None,
        "since":0,"until":None,
        "texSignature":"podpis",
        "domainAlias":"jmeno"
        }}
