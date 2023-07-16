from flask import Flask, jsonify, request
import os, glob

app = Flask(__name__)

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
        "begin":f"2099-12-04T00:00:00+00:00",
        "end":f"2099-12-04T00:01:00+00:00",
        "registrationBegin":"2099-12-04T00:01:00+00:00",
        "registrationEnd":"2099-12-04T00:01:00+00:00",
        "report": None,
        "name":"Dsef",
        "eventTypeId":2
    }
    return dsefEvents


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
        "eventTypeId":9
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
        "eventTypeId":9
    }
}

@app.get("/GetEventList")
def getEventList():
    eventTypeId = request.args.get('event_type_ids[0]', default=None)
    if (eventTypeId is None):
        return "Event type id not specified", 400
    if (eventTypeId == "2"):
        return getDsefEvents()
    return jsonify(events)

@app.get("/GetEvent")
def getEvent():
    eventId = request.args.get('event_id', default=None)
    if (eventId is None):
        return "Event id not specified", 400
    dsefEvents = getDsefEvents()
    if (eventId in dsefEvents):
        return dsefEvents[eventId]
    if (eventId in events):
        return events[eventId]
    return "Invalid event id", 400

getDsefEvents()
