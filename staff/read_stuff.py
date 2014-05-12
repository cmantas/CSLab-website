# -*- coding: utf-8 -*-
import re
import MySQLdb as mdb
import sys

con = mdb.connect('localhost', 'root', 'xm666666', 'test1');
cur = con.cursor();
from wxPython._wx import true

counter = 0
title = ""
names = ""
appeared = ""
year = 0;



cur.execute("CREATE TABLE IF NOT EXISTS Staff(\
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,\
	Name VARCHAR(50) NOT NULL,\
	Webpage TEXT,\
    Email TEXT, \
    Category VARCHAR(20),\
	Rank VARCHAR(20),\
	Bio TEXT,\
	Context TEXT\
    )")

for line in open('staff.txt', 'r'):
    page=""
    mail=""
    rank=""
    #count how many words in a line
    count = len(line.split(" "))
    if count == 1: #class line
        classname = line.strip()
        print classname
        continue
    words = line.split(" ")
    if words[0] == "mailto": #case of mail address
        print "mail to:" + words[1]
        mail=words[1].split(":")[1]
        name=words[2]+" "+words[3].strip()
    else :
        page=words[0]
        name=""
        rank_flag=False
        rank="";
        for word in words[1:] :
            if word=="rank":
                rank_flag=True
                continue
            elif  rank_flag==False:
                name=name+" "+word.strip()

            if rank_flag:
                rank=rank+" "+word.strip()

    print "name: "+name
    print "class: "+classname
    print "page: "+page
    if rank:
            print "rank: "+rank
    query="INSERT INTO `Staff`(`ID`, `Name`, `Webpage`, `Email`, `Category`, `Rank`, `Bio`, `Context`)  VALUES \
		(0,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\")" % (name,page,mail,classname, rank, "", "")
    print query

    cur.execute(query)

print "commiting"
con.commit()
con.close()


