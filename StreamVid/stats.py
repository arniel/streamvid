import os
import csv
import datetime
import time
import json

times = {}
users = {}
userIPs = {}
uniqueaddresses = []

dir = os.listdir('/var/AB3/backupLogs/')
dir.append('/var/AB3/log.txt')

for file in dir:
	print 'Parsing file: ' + file
	
	if file == '/var/AB3/log.txt':
		ifile = open(file, 'rb')
	else:
		ifile = open('/var/AB3/backupLogs/' + file, 'rb')

        reader = csv.reader(ifile)

	for row in reader:
		timestamp = time.strptime(row[0], "%Y-%m-%d %H:%M:%S")
		date = time.strftime("%Y-%m-%d", timestamp)
		timestampstring = time.strftime("%H:%M:%S", timestamp)
		
		#raw time/visit information
		if date not in times:
			newArr = [timestampstring]
			times[date] = newArr
		else:
			newArr = times[date]
			newArr.append(timestampstring)
			times[date] = newArr
		
		#raw user visit information
		if row[2].lower() not in users:
			newArr = [row[0]]
			users[row[2].lower()] = newArr
		else:
			newArr = users[row[2].lower()]
			newArr.append(row[0])
			users[row[2].lower()] = newArr

		#raw per-user IP information
                if row[2].lower() not in userIPs:
                        newArr = [row[1]]
                        userIPs[row[2].lower()] = newArr
                else:
                        newArr = userIPs[row[2].lower()]
			if row[1] not in newArr:
                        	newArr.append(row[1])
	                        userIPs[row[2].lower()] = newArr

		#raw unique IP addresses
		if row[1] not in uniqueaddresses:
			uniqueaddresses.append(row[1])

	ifile.close()

#initialize totals variable
totals = {'active users': len(users.keys())}

#get date range for totals
sorteddates = sorted(times.keys())
totals['datemin'] = sorteddates[0]
totals['datemax'] = sorteddates[len(sorteddates)-1]

#count visits per day
daytotals = {}
totals['total visits'] = 0
for day in sorted(times.keys()):
	daytotals[day] = len(times[day])
	totals['total visits'] += len(times[day])

totals['date stats'] = daytotals

#calculate visits per day
totaldays = len(times.keys())
visitsperday = totals['total visits'] / totaldays
totals['visits per day'] = visitsperday		

#count user total visits
usertotals = {}
for user in sorted(users.keys()):
	totaluservisits = len(users[user])
	uservisitsperday = totaluservisits / totaldays 
	usertotals[user] = { 'total visits':totaluservisits, 'visits per day':uservisitsperday }
	usertotals[user]['IP addresses'] = userIPs[user] 
	usertotals[user]['last visit'] = users[user][totaluservisits-1]

totals['user stats'] = usertotals

#count unique addresses
totals['unique ip addresses'] = len(uniqueaddresses)

#construct final object
finalObj = {'RAW':{'USERS':users, 'TIMES':times, 'UNIQUE IPS':uniqueaddresses}, 'STATS':totals}

fo = open('/var/AB3/stats.json', 'wb')
fo.write(json.dumps(finalObj))
fo.close
