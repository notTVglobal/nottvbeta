import json
import csv
import sys

def validate_json(file_path):
    """
    Validate the JSON file to ensure it is properly formatted.

    Parameters:
    file_path (str): The path to the JSON file to be validated.

    This function reads the JSON file, tries to parse it, and prints a message
    indicating whether the file is valid or not. If the JSON is invalid, it
    will print the error message.
    """
    with open(file_path, 'r') as file:
        try:
            data = json.load(file)
            # Add any specific validation logic here
            print("JSON file is valid.")
            return data
        except json.JSONDecodeError as e:
            print(f"Invalid JSON file: {e}")
            return None

def convert_to_csv(data, output_file):
    """
    Convert the validated JSON data to CSV format.

    Parameters:
    data (dict): The validated JSON data.
    output_file (str): The path to the output CSV file.

    This function writes the JSON data to a CSV file.
    """
    if not data:
        print("No data to convert.")
        return

    with open(output_file, 'w', newline='') as csvfile:
        writer = csv.writer(csvfile)

        # Assuming all records have the same structure
        headers = list(data[0].keys())
        writer.writerow(headers)

        for record in data:
            writer.writerow(record.values())

    print(f"CSV file created: {output_file}")

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python validate_and_convert.py <path_to_json_file> <output_csv_file>")
    else:
        json_data = validate_json(sys.argv[1])
        convert_to_csv(json_data, sys.argv[2])
