# db_analytics.php

``db_analytics.php`` contains a bunch of database functions that are mainly
used by [postAnalytics.js][postAnalytics.js] when ``Analytics.interfaceDB()``
is called.

``Analytics.interfaceDB()`` sends a POST request to ``db_analytics.php`` with
JSON object containing an action, and data, which is an object that may be sent
by the caller of ``Analytics.interfaceDB()``.

[postAnalytics.js]: postAnalytics.js.md

---

## Analytics.interfaceDB actions

The actions that are exposed to interfaceDB are:

- **upload**
- **getParticipantGroup**

### upload

Expects participantID and postsStats to be set in the data object, however this
is entirely handled in interfaceDB without any intervention from the caller.

**participantID** 
- an integer representing the participant's id 

**postsStats**
- an object containing post analytics, see ``Analytics.getStatistics`` in
  [postAnalytics.js][postAnalytics.js].

### getParticipantGroup

Expects participantID.

**participantID** 
- an integer representing the participant's id 

## Note

There are more internal functions that are yet to be documented, but
should be fairly straightforward.
