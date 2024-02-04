# tec21: I haven't tested this script, I couldn't get
# pandas working on my local dev system -> Windows :-(
# as an alternative, I just prepared the csv myself
# and created the NewsFederalElectoralDistrictsAddMoreInfoCANSeeder

import pandas as pd

# Load the existing CSV data
original_csv_path = '../storage/app/csv/news_federal_electoral_districts_CAN.csv'
original_df = pd.read_csv(original_csv_path)

# Load the new CSV data
# new_csv_data = '''
# ED_CODE,ED_NAMEE,ED_NAMEF,POPULATION
# 10001,Avalon,Avalon,87191
# ''' 
 # Replace this string with the path to the new CSV file
# For demonstration, we're using CSV data as a string here
# In practice, you'd use: 
new_df = pd.read_csv('/home/tcross/files/ED-Canada_2021.csv')
# new_df = pd.read_csv(pd.compat.StringIO(new_csv_data))

# Merge the dataframes on the riding name
merged_df = original_df.merge(new_df[['ED_CODE', 'ED_NAMEE', 'POPULATION']], left_on='riding_name_en', right_on='ED_NAMEE', how='left')

# Drop the ED_NAMEE column as it's a duplicate of riding_name_en
merged_df.drop('ED_NAMEE', axis=1, inplace=True)

# Save the merged data back to CSV
merged_csv_path = '../storage/app/csv/news_federal_electoral_districts_more_info_CAN.csv'
merged_df.to_csv(merged_csv_path, index=False)

merged_csv_path
