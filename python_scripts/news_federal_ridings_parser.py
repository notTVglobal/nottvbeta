import json
import csv

# Path to the JSON file
json_file_path = '../storage/app/json/georef-canada-federal-electoral-district@public.json'  # Replace with your JSON file path
csv_file_path = '../storage/app/csv/news_federal_electoral_districts_CAN.csv'  # Output CSV file

# Read the JSON file
with open(json_file_path, 'r', encoding='utf-8') as json_file:
    data = json.load(json_file)

# Open a CSV file for writing
with open(csv_file_path, 'w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)

    # Write the header with separate columns for latitude and longitude
    writer.writerow(['riding_name_en', 'latitude', 'longitude', 'year', 'prov_name_en', 'geo_shape_json'])

    # Write the data
    for item in data:
        # Extract data and ensure it's not an empty list
        riding_name_en = item.get('fed_name_en', [''])[0] if isinstance(item.get('fed_name_en'), list) else item.get('fed_name_en', '')
        
        # Extract latitude and longitude separately
        latitude = item.get('geo_point_2d', {}).get('lat', '')
        longitude = item.get('geo_point_2d', {}).get('lon', '')
        
        year = item.get('year', '')
        prov_name_en = item.get('prov_name_en', [''])[0] if isinstance(item.get('prov_name_en'), list) else item.get('prov_name_en', '')
        
        # Assuming geo_shape is within the 'geometry' key and is a dictionary
        geo_shape = item.get('geo_shape', {}).get('geometry', {})
        geo_shape_json = json.dumps(geo_shape)  # Convert the geo_shape dictionary to JSON

        writer.writerow([riding_name_en, latitude, longitude, year, prov_name_en, geo_shape_json])

# Return the path of the created CSV file
csv_file_path
