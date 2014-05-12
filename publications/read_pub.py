# -*- coding: utf-8 -*-
import re
import MySQLdb as mdb
import sys

con = mdb.connect('localhost', 'root', 'xm666666', 'test1');
cur = con.cursor();



counter=0
title=""
names=""
appeared=""
year=0;

def handle_names(line) :
	line=line.replace("και"," , ")
	for author in re.split(',',line):
		print "-->"+author.strip()+"<--"

cur.execute("CREATE TABLE IF NOT EXISTS Publications(\
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,\
	Title TEXT NOT NULL,\
	Authors VARCHAR(200) NOT NULL,\
	Appeared TEXT,\
	Year INT(4)\
	)")


for line in open('publications.txt','r'):
	check=line.find("file")
	if check!=-1 : continue
	
	#count how many words in a line
	count =len( line.split())
	if count==1 : #year line
		year=int(line)
		continue
	elif count==0: #gap line
		continue
		
	counter+=1;
	
	if counter%2==0 :
		names=line
	elif counter%3==0 :
		appeared=line
		print "title: "+title
		query="INSERT INTO `Publications`(`ID`, `Title`, `Authors`, `Appeared`, `Year`)  VALUES \
		(0,\"%s\",\"%s\",\"%s\",%d)" % (title.strip(),names.strip(),appeared.strip(),year)

		cur.execute(query)

		#handle_names(names)
		print

		
		counter=0
		title=""
		names=""
		appeared=""

	else :
		title=line

print "commiting"
con.commit()
con.close()


