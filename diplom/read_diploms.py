# -*- coding: utf-8 -*-
import re
import MySQLdb as mdb
import sys

#con = mdb.connect('localhost', 'root', '', 'cslab_new');
#cur = con.cursor();
#from wxPython._wx import true

course = content= title= year = filepath ="";





for line in open('titles.txt', 'r'):
    	if line.startswith("<h2>"):
		year=line[3:]
		continue
	if line.startswith("<h3>"):
		course=line[3:]
		continue
	
    	query="INSERT INTO `Diplom`(`Title`, `Year`, `Sticky`, `Course`, `Content`,  `Filepath`)  VALUES \
		(\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\")" % (title,year,0,course, content, filepath)
    	#print query

    	#cur.execute(query)

print "commiting"
con.commit()
con.close()


