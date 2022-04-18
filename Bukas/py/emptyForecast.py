#!/usr/bin/env python3

import pandas as pd
from sklearn.feature_selection import RFE
from sklearn.ensemble import RandomForestRegressor
from pandas import DataFrame
import numpy as np
import calendar
from datetime import timedelta
from calendar import monthrange
from sklearn import metrics
import matplotlib.pyplot as plt
import json
import glob
import math
import pymysql
import csv
import sys
import os

database_name = sys.argv[1]
p_id = sys.argv[2]
#database connection
connection = pymysql.connect(host="localhost",
                             user="root",
                             passwd="",
                             database = database_name)
cur = connection.cursor()

#declare dictionaries for each product
totalDic = {}
last_array = {}
mape = {}

csv_file_path = '../forecast_page/assets/forecast/' + database_name + '/' + str(p_id[0]) + '.csv'

try:
    sql = 'SELECT DATE, SUM(sales_qty) AS qty FROM sales WHERE product_id ='+str(p_id[0])+' AND deleted != 1 GROUP BY DATE'
    cur.execute(sql)
    rows = cur.fetchall()
    count = cur.rowcount
    if (count >= 60):
    # Continue only if there are rows returned.
        if rows:
            # New empty list called 'result'. This will be written to a file.
            result = list()

            # The row name is the first entry for each entity in the description tuple.
            column_names = list()
            for i in cur.description:
                column_names.append(i[0])

            result.append(column_names)
            for row in rows:
                result.append(row)

            # Write result to file.
            with open(csv_file_path, 'w+', newline='') as csvfile:
                csvwriter = csv.writer(csvfile, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)
                for row in result:
                    csvwriter.writerow(row)
        else:
            sys.exit("No rows found for query: {}".format(sql))

        dataset = pd.read_csv('../forecast_page/assets/forecast/' + database_name + '/' + str(p_id[0]) + '.csv')
        df = pd.read_csv('../forecast_page/assets/forecast/' + database_name + '/' + str(p_id[0]) + '.csv')
        rc = math.floor((len(df))/30)
        def add_month(df, forecast_length, forecast_period):
            end_point = len(df)
            df1 = pd.DataFrame(index=range(forecast_length), columns=range(2))
            df1.columns = ['qty', 'DATE']
            df = df.append(df1)
            df = df.reset_index(drop=True)
            x = df.at[end_point - 1, 'DATE']
            x = pd.to_datetime(x, format='%Y-%m-%d')
            days_in_month = calendar.monthrange(x.year, x.month)[1]
            if forecast_period == 'Week':
                for i in range(forecast_length):
                    df.at[df.index[end_point + i], 'DATE'] = x + timedelta(days=7 + 7 * i)
                    df.at[df.index[end_point + i], 'qty'] = 0
            elif forecast_period == 'Month':
                for i in range(forecast_length):
                    df.at[df.index[end_point + i], 'DATE'] = x + timedelta(days=days_in_month + days_in_month * i)
                    df.at[df.index[end_point + i], 'qty'] = 0
            df['DATE'] = pd.to_datetime(df['DATE'], format='%Y-%m-%d')
            df['month'] = df['DATE'].dt.month
            df = df.drop(['DATE'], axis=1)
            # print(df, "\n")
            return df


        def create_lag(df3):
            dataframe = DataFrame()
            for i in range(31, 0, -1):
                dataframe['t-' + str(i)] = df3.qty.shift(i)
            df4 = pd.concat([df3, dataframe], axis=1)
            df4.dropna(inplace=True)
            # print(df4, "\n")
            return df4


        # prediction of future months
        def RandomForest(df1, forecast_length, forecast_period):
            df3 = df1[['qty', 'DATE']]
            df3 = add_month(df3, forecast_length, forecast_period)
            finaldf = create_lag(df3)
            finaldf = finaldf.reset_index(drop=True)
            n = forecast_length
            end_point = len(finaldf)
            x = end_point - n
            finaldf_train = finaldf.loc[:x - 1, :]
            finaldf_train_x = finaldf_train.loc[:, finaldf_train.columns != 'qty']
            finaldf_train_y = finaldf_train['qty']
            # print("Starting model train..")
            rfe = RFE(RandomForestRegressor(n_estimators=300,
                                                max_depth=3,
                                                min_samples_split=3))
            fit = rfe.fit(finaldf_train_x, finaldf_train_y)
            # print("Model train completed..")
            # print("Creating forecasted set..")
            yhat = []
            end_point = len(finaldf)
            n = forecast_length
            df3_end = len(df3)
            for i in range(n, 0, -1):
                y = end_point - i
                inputfile = finaldf.loc[y:end_point, :]
                inputfile_x = inputfile.loc[:, inputfile.columns != 'qty']
                pred_set = inputfile_x.head(1)
                pred = fit.predict(pred_set)
                df3.at[df3.index[df3_end - i], 'qty'] = pred[0]
                finaldf = create_lag(df3)
                finaldf = finaldf.reset_index(drop=True)
                yhat.append(pred * 30)
            yhat = np.array(yhat)
            # print("Forecast complete..")
            return yhat

        def date_getter(database_name,p_id ):
            with open('../forecast_page/assets/forecast/'+ database_name +'/'+ p_id +'.csv','r') as file:
                data = file.readlines()
            lastRow = data[-1]
            date = lastRow[:10]
            return date

        def calculate (database_name, pid, toEval, rc):
            df = pd.read_csv('../forecast_page/assets/forecast/' + database_name + '/' + str(p_id[0]) + '.csv')
            i = 0
            j = 30
            totalQty = 0
            holder = []

            for x in range(rc):
                qty_sum = df.iloc[i:j,:].sum(axis=1)
                totalQty = sum(qty_sum)
                if totalQty == 0:
                    break
                dividedQty = math.floor(totalQty)
                if dividedQty == 0:
                    holder.append(1)
                else: 
                    holder.append(dividedQty)
                i = i + 30
                j = j + 30

            diff = abs(np.subtract(holder,toEval))
            AbsError = diff[:-1]
            totlForecast = toEval[:-1]
            percentDiff  = np.divide(AbsError, totlForecast)*100
            total = sum(percentDiff)/rc
            return total


        predicted_value = RandomForest(dataset, rc, 'Month')
        pred = predicted_value.flatten()
        myPred = pred.tolist()
        keeper = []
        intKeeper = []
        for output in myPred:
            keeper.append(str(int(output)))
            intKeeper.append(int(output))
        last_array[str(p_id[0])] = str(keeper)
        totalDic[int(p_id[0])] = intKeeper

        mape[str(p_id[0])] = calculate(database_name, int(p_id[0]), totalDic[int(p_id[0])], rc)

except Exception as e:
    print (e)

for k in last_array.keys():

    print (k, mape[k])

    p_date = date_getter(database_name, k)

    check_p_id = f'SELECT product_id from forecast where product_id  = {k}'

    cur.execute(check_p_id)

    pid = cur.fetchall()

    pid_row = cur.rowcount

    if pid_row > 0:

        for ids in pid:
            # print(ids[0], k)
            if (int(ids[0]) == int(k)):
                sql4 = f'UPDATE forecast SET forecast = "{last_array[k]}", date="{p_date}",mape={mape[k]} WHERE product_id = {k}'
                cur.execute(sql4)
                connection.commit() 
            elif ids[0] != k:
                sql3 = f'INSERT INTO forecast (id, forecast, date, product_id,mape) values (default,"{last_array[k]}","{p_date}",{k},{mape[k]})'
                cur.execute(sql3)
                connection.commit() 
    elif pid_row == 0:
        sql3 = f'INSERT INTO forecast (id, forecast, date, product_id,mape) values (default,"{last_array[k]}","{p_date}",{k},{mape[k]})'
        cur.execute(sql3)
        connection.commit() 


# start of prediction
#  read created csv then calculate average sales per month 
