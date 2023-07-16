from flask import Flask, jsonify

app = Flask(__name__)

events = {
    "2":{"eventId":2,"year":27,"eventYear":19,"begin":"2014-02-13T00:00:00+01:00","end":"2014-02-13T00:00:00+01:00","registrationBegin":"2014-01-27T00:00:00+01:00","registrationEnd":"2014-02-09T23:59:59+01:00","report":"Den s experimentální fyzikou proběhl 13. února 2014 v budovách MFF UK na Karlově a následně na Hvězdárně Ďáblice, resp. v areálu školního reaktoru VR-1. Akce se zúčastnilo 39 středoškoláků, 2 středoškolské učitelky a 1 vědecký pracovník.\n\n[[https://www.facebook.com/events/198994116974504/|Akce na Facebooku]]","name":"DSEF jaro 2014","eventTypeId":2},
    "8": {
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
    "94": {
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
    return jsonify(events)

@app.get("/GetEvent")
def getEvent():
    return events["8"]
