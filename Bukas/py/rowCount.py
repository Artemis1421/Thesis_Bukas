import pymysql
import sys

database_name = sys.argv[1]
p_id = sys.argv[2]


with open('../forecast_page/assets/forecast/'+database_name+'/'+p_id+'.csv','r') as file:
    data = file.readlines()
lastRow = data[-1]
month = lastRow[5:7]
print(month)