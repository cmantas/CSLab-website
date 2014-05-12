news_expire_event

in /opt/lampp/etc/my.cnf
change the socket in /opt/lampp/var/mysql/mysql.sock if using lampp
change the two max_allowed_packet entries to the max allowed file upload size you want to support


#in order for the news to be cleared up when they expire, execute the following SQL queries

#this turns on the event scheduler:
SET GLOBAL event_scheduler = ON;	

#this schedules a deletion every one day for all news that have expired:
CREATE EVENT clear_news  
ON SCHEDULE EVERY 1 DAY    
DO DELETE FROM News WHERE Expiry_Datetime<NOW()

#if, for some reason, you want to delete the periodic event created above:
DROP EVENT IF EXISTS clear_news
