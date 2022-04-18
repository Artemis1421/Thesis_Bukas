#!/usr/bin/env python3

import pandas as pd
import math
import csv
import sys
import os 
database_name = sys.argv[1]

p_id = sys.argv[2]

num_lines = sum(1 for line in open('../forecast_page/assets/forecast/'+database_name+'/'+str(p_id)+'.csv'))
print(math.floor(num_lines/30))
